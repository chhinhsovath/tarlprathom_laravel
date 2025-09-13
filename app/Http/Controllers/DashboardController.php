<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\MentoringVisit;
use App\Models\PilotSchool;
use App\Models\Province;
use App\Models\Student;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     */
    public function index()
    {
        $user = auth()->user();

        // Redirect coordinators to their workspace
        if ($user->isCoordinator()) {
            return redirect()->route('coordinator.workspace');
        }
        
        // Check if teacher needs to complete profile setup
        if ($user->role === 'teacher') {
            if (!$user->school_id || !$user->assigned_subject || !$user->holding_classes) {
                return redirect()->route('teacher.profile.setup')
                    ->with('info', 'Please complete your profile setup to continue.');
            }
        }

        // Get schools for mentors/admins (optimized with PilotSchool)
        $schools = [];
        $provinces = [];
        $districts = [];
        $clusters = [];

        if ($user->role === 'mentor' || $user->role === 'admin') {
            // Get schools from the pilot schools table
            $schools = PilotSchool::orderBy('school_name')->get();

            // Get unique provinces from pilot schools
            $provinces = PilotSchool::getProvinces();

            // Get unique districts from pilot schools
            $districts = PilotSchool::getDistricts();

            // Get unique clusters from pilot schools
            $clusters = PilotSchool::getClusters();

            // If mentor has restricted access, filter the schools
            if (! $user->isAdmin()) {
                $accessibleSchoolCodes = $user->getAccessibleSchoolCodes();
                if (! empty($accessibleSchoolCodes)) {
                    $schools = $schools->filter(function ($school) use ($accessibleSchoolCodes) {
                        return in_array($school->school_code, $accessibleSchoolCodes);
                    });
                }
            }
        }

        return view('dashboard', compact('schools', 'provinces', 'districts', 'clusters'));
    }

    /**
     * Get dashboard statistics
     */
    public function getStats(Request $request)
    {
        $user = auth()->user();

        // Create cache key based on user access and filters
        $cacheKey = 'dashboard_stats_'.$user->id.'_'.md5(serialize([
            $user->role,
            $user->getAccessibleSchoolIds(),
            $request->only(['province', 'district', 'school_id']),
        ]));

        // Cache for 5 minutes - cache the data, not the response
        $stats = \Cache::remember($cacheKey, 300, function () use ($request, $user) {
            $stats = [];

            // Base query
            $studentQuery = Student::query();
            $assessmentQuery = Assessment::query();
            $mentoringQuery = MentoringVisit::query();
            $schoolQuery = PilotSchool::query();

            // Apply access restrictions based on user role
            $accessibleSchoolIds = $user->getAccessibleSchoolIds();

            // For all non-admin users, restrict to accessible schools
            if (! $user->isAdmin()) {
                if (empty($accessibleSchoolIds)) {
                    // If no schools are accessible, return no results
                    $studentQuery->whereRaw('1 = 0');
                    $assessmentQuery->whereRaw('1 = 0');
                    $mentoringQuery->whereRaw('1 = 0');
                    $schoolQuery->whereRaw('1 = 0');
                } else {
                    // Restrict to accessible schools
                    $studentQuery->whereIn('pilot_school_id', $accessibleSchoolIds);
                    // Use pilot_school_id directly on assessments
                    $assessmentQuery->whereIn('pilot_school_id', $accessibleSchoolIds);
                    $mentoringQuery->whereIn('pilot_school_id', $accessibleSchoolIds);
                    $schoolQuery->whereIn('id', $accessibleSchoolIds);
                }
            }

            // Apply filters for mentors/admins
            if ($user->role === 'mentor' || $user->role === 'admin') {
                // Filter by province (optimized to use Province model)
                if ($request->has('province') && $request->province) {
                    $province = Province::where('name_en', $request->province)->first();
                    if ($province) {
                        $filterQuery = PilotSchool::where('province', $province->name_en);
                        if (! $user->isAdmin()) {
                            $filterQuery->whereIn('id', $accessibleSchoolIds);
                        }
                        $schoolIds = $filterQuery->pluck('id');
                        $studentQuery->whereIn('pilot_school_id', $schoolIds);
                        // Use pilot_school_id directly on assessments
                        $assessmentQuery->whereIn('pilot_school_id', $schoolIds);
                        $mentoringQuery->whereIn('pilot_school_id', $schoolIds);
                        $schoolQuery->where('province', $province->name_en);
                    }
                }

                // Filter by district
                if ($request->has('district') && $request->district) {
                    $filterQuery = PilotSchool::where('district', $request->district);
                    if (! $user->isAdmin()) {
                        $filterQuery->whereIn('id', $accessibleSchoolIds);
                    }
                    $schoolIds = $filterQuery->pluck('id');
                    $studentQuery->whereIn('pilot_school_id', $schoolIds);
                    $assessmentQuery->whereIn('pilot_school_id', $schoolIds);
                    $mentoringQuery->whereIn('pilot_school_id', $schoolIds);
                    $schoolQuery->where('district', $request->district);
                }

                // Filter by cluster (commented out - cluster column doesn't exist yet)
                // if ($request->has('cluster') && $request->cluster) {
                //     $filterQuery = PilotSchool::where('cluster', $request->cluster);
                //     if (! $user->isAdmin()) {
                //         $filterQuery->whereIn('id', $accessibleSchoolIds);
                //     }
                //     $schoolIds = $filterQuery->pluck('id');
                //     $studentQuery->whereIn('school_id', $schoolIds);
                //     $assessmentQuery->whereHas('student', function ($q) use ($schoolIds) {
                //         $q->whereIn('school_id', $schoolIds);
                //     });
                //     $mentoringQuery->whereIn('school_id', $schoolIds);
                //     $schoolQuery->where('cluster', $request->cluster);
                // }

                // Filter by selected school
                if ($request->has('school_id') && $request->school_id) {
                    $studentQuery->where('pilot_school_id', $request->school_id);
                    $assessmentQuery->where('pilot_school_id', $request->school_id);
                    $mentoringQuery->where('pilot_school_id', $request->school_id);
                    $schoolQuery->where('id', $request->school_id);
                }
            }

            $stats['total_students'] = $studentQuery->count();
            $stats['total_assessments'] = $assessmentQuery->count();
            $stats['total_schools'] = $schoolQuery->count();
            $stats['total_mentoring_visits'] = $mentoringQuery->count();

            return $stats;
        });

        return response()->json($stats)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Get overall assessment results data
     */
    public function getOverallResults(Request $request)
    {
        $user = auth()->user();
        \Log::info('getOverallResults called', [
            'user_id' => $user->id,
            'params' => $request->all()
        ]);

        // Create cache key based on user access and filters
        $cacheKey = 'overall_results_'.$user->id.'_'.md5(serialize([
            $user->role,
            $user->getAccessibleSchoolIds(),
            $request->only(['subject', 'province', 'district', 'school_id']),
        ]));

        // Cache for 5 minutes - cache the data, not the response
        $data = \Cache::remember($cacheKey, 300, function () use ($request, $user) {
            $subject = $request->get('subject', 'khmer');

            // Get actual levels from database for the subject
            $levels = Assessment::where('subject', $subject)
                ->distinct()
                ->pluck('level')
                ->filter() // Remove null/empty values
                ->sort()
                ->values()
                ->toArray();

            // If no levels found, use default fallback
            if (empty($levels)) {
                if ($subject === 'khmer') {
                    $levels = ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2'];
                } else {
                    $levels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'];
                }
            }
            $cycles = ['baseline', 'midline', 'endline'];

            // Build base query
            $query = Assessment::where('subject', $subject);

            // Apply access restrictions based on user role
            $accessibleSchoolIds = $user->getAccessibleSchoolIds();

            if (! $user->isAdmin()) {
                if (empty($accessibleSchoolIds)) {
                    // If no schools are accessible, return no results
                    $query->whereRaw('1 = 0');
                } else {
                    $query->whereIn('pilot_school_id', $accessibleSchoolIds);
                }
            }

            // Apply filters for mentors/admins
            if ($user->role === 'mentor' || $user->role === 'admin') {
                // Filter by province (optimized)
                if ($request->has('province') && $request->province) {
                    $province = Province::where('name_en', $request->province)->first();
                    if ($province) {
                        $filterQuery = PilotSchool::where('province', $province->name_en);
                        if (! $user->isAdmin()) {
                            $filterQuery->whereIn('id', $accessibleSchoolIds);
                        }
                        $schoolIds = $filterQuery->pluck('id');
                        $query->whereIn('pilot_school_id', $schoolIds);
                    }
                }

                // Filter by district
                if ($request->has('district') && $request->district) {
                    $filterQuery = PilotSchool::where('district', $request->district);
                    if (! $user->isAdmin()) {
                        $filterQuery->whereIn('id', $accessibleSchoolIds);
                    }
                    $schoolIds = $filterQuery->pluck('id');
                    $query->whereIn('pilot_school_id', $schoolIds);
                }

                // Filter by cluster (commented out - cluster column doesn't exist yet)
                // if ($request->has('cluster') && $request->cluster) {
                //     $filterQuery = PilotSchool::where('cluster', $request->cluster);
                //     if (! $user->isAdmin()) {
                //         $filterQuery->whereIn('id', $accessibleSchoolIds);
                //     }
                //     $schoolIds = $filterQuery->pluck('id');
                //     $query->whereHas('student', function ($q) use ($schoolIds) {
                //         $q->whereIn('school_id', $schoolIds);
                //     });
                // }

                // Filter by selected school
                if ($request->has('school_id') && $request->school_id) {
                    $query->where('pilot_school_id', $request->school_id);
                }
            }

            // Optimize: Get all data in single aggregated queries
            $colors = [
                'Beginner' => '#d32f2f',
                'Letter' => '#ff6b35',
                'Letters' => '#ff6b35', // Alias for Letter
                'Word' => '#fbc02d',
                'Words' => '#fbc02d', // Alias for Word
                'Paragraph' => '#66bb6a',
                'Story' => '#42a5f5',
                'Comp. 1' => '#ab47bc',
                'Comp 1' => '#ab47bc', // Alias without period
                'Comp. 2' => '#7e57c2',
                'Comp 2' => '#7e57c2', // Alias without period
                'Nothing' => '#9e9e9e', // Gray for Nothing level
                '1-Digit' => '#ff6b35',
                '2-Digit' => '#fbc02d',
                'Subtraction' => '#66bb6a',
                'Division' => '#42a5f5',
                'Word Problem' => '#ab47bc',
            ];
            
            // Generate colors for any levels not in the predefined map
            $colorPalette = ['#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5', '#2196f3', '#03a9f4', '#00bcd4', '#009688', '#4caf50', '#8bc34a', '#cddc39', '#ffeb3b', '#ffc107', '#ff9800', '#ff5722'];
            $colorIndex = 0;
            foreach ($levels as $level) {
                if (!isset($colors[$level])) {
                    $colors[$level] = $colorPalette[$colorIndex % count($colorPalette)];
                    $colorIndex++;
                }
            }

            // Get all counts grouped by cycle and level in one query
            $assessmentCounts = (clone $query)
                ->select('cycle', 'level', \DB::raw('COUNT(*) as count'))
                ->groupBy('cycle', 'level')
                ->get()
                ->keyBy(function ($item) {
                    return $item->cycle.'_'.$item->level;
                });

            // Build datasets using cached data
            $datasets = [];
            foreach ($levels as $level) {
                $data = [];
                foreach ($cycles as $cycle) {
                    $key = $cycle.'_'.$level;
                    $count = isset($assessmentCounts[$key]) ? $assessmentCounts[$key]->count : 0;
                    // IMPORTANT: Cast to integer to ensure Chart.js can render the bars
                    $data[] = (int)$count;
                }

                // Translate the level label
                $translatedLabel = trans_db(strtolower($level));
                if ($translatedLabel == strtolower($level)) {
                    // If no translation found, try the original case
                    $translatedLabel = trans_db($level);
                }
                
                $datasets[] = [
                    'label' => $translatedLabel,
                    'data' => $data,
                    'backgroundColor' => $colors[$level] ?? '#999999',
                    'stack' => 'Stack 0',
                ];
            }

            // Get totals for table in one query
            $totals = (clone $query)
                ->select('cycle', \DB::raw('COUNT(*) as total'))
                ->groupBy('cycle')
                ->pluck('total', 'cycle')
                ->map(function($value) {
                    return (int)$value;  // Cast to integer
                })
                ->toArray();

            return [
                'chartData' => [
                    'labels' => [trans_db('Baseline'), trans_db('Midline'), trans_db('Endline')],
                    'datasets' => $datasets,
                ],
                'totals' => $totals,
            ];
        });

        \Log::info('getOverallResults response', ['data' => $data]);
        return response()->json($data)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Get results by school (for mentors/admins)
     */
    public function getResultsBySchool(Request $request)
    {
        $user = auth()->user();

        // Only mentors and admins can see this
        if (! in_array($user->role, ['mentor', 'admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Create cache key based on user access and filters
        $cacheKey = 'results_by_school_'.$user->id.'_'.md5(serialize([
            $user->role,
            $user->getAccessibleSchoolIds(),
            $request->only(['subject', 'cycle', 'province', 'district', 'school_id']),
        ]));

        // Cache for 5 minutes - cache the data, not the response
        $data = \Cache::remember($cacheKey, 300, function () use ($request, $user) {

            $subject = $request->get('subject', 'khmer');
            $cycle = $request->get('cycle', 'baseline');

            // Get actual levels from database for the subject
            $levels = Assessment::where('subject', $subject)
                ->distinct()
                ->pluck('level')
                ->filter() // Remove null/empty values
                ->sort()
                ->values()
                ->toArray();

            // If no levels found, use default fallback
            if (empty($levels)) {
                if ($subject === 'khmer') {
                    $levels = ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2'];
                } else {
                    $levels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'];
                }
            }

            // Get accessible schools based on user role
            $accessibleSchoolIds = $user->getAccessibleSchoolIds();

            // Get schools with filters
            $schoolQuery = PilotSchool::orderBy('school_name');

            // Apply access restrictions for mentors
            if (! $user->isAdmin()) {
                if (empty($accessibleSchoolIds)) {
                    // If no schools are accessible, return no results
                    $schoolQuery->whereRaw('1 = 0');
                } else {
                    $schoolQuery->whereIn('id', $accessibleSchoolIds);
                }
            }

            // Apply filters (optimized)
            if ($request->has('province') && $request->province) {
                $province = Province::where('name_en', $request->province)->first();
                if ($province) {
                    $schoolQuery->where('province', $request->province);
                }
            }

            if ($request->has('district') && $request->district) {
                $schoolQuery->where('district', $request->district);
            }

            // Filter by cluster (commented out - cluster column doesn't exist yet)
            // if ($request->has('cluster') && $request->cluster) {
            //     $schoolQuery->where('cluster', $request->cluster);
            // }

            if ($request->has('school_id') && $request->school_id) {
                $schoolQuery->where('id', $request->school_id);
            }

            $schools = $schoolQuery->get();
            $schoolNames = $schools->pluck('school_name')->toArray();

            // Prepare datasets
            $datasets = [];
            $colors = [
                'Beginner' => '#d32f2f',
                'Letter' => '#ff6b35',
                'Letters' => '#ff6b35', // Alias for Letter
                'Word' => '#fbc02d',
                'Words' => '#fbc02d', // Alias for Word
                'Paragraph' => '#66bb6a',
                'Story' => '#42a5f5',
                'Comp. 1' => '#ab47bc',
                'Comp 1' => '#ab47bc', // Alias without period
                'Comp. 2' => '#7e57c2',
                'Comp 2' => '#7e57c2', // Alias without period
                'Nothing' => '#9e9e9e', // Gray for Nothing level
                '1-Digit' => '#ff6b35',
                '2-Digit' => '#fbc02d',
                'Subtraction' => '#66bb6a',
                'Division' => '#42a5f5',
                'Word Problem' => '#ab47bc',
            ];
            
            // Generate colors for any levels not in the predefined map
            $colorPalette = ['#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5', '#2196f3', '#03a9f4', '#00bcd4', '#009688', '#4caf50', '#8bc34a', '#cddc39', '#ffeb3b', '#ffc107', '#ff9800', '#ff5722'];
            $colorIndex = 0;
            foreach ($levels as $level) {
                if (!isset($colors[$level])) {
                    $colors[$level] = $colorPalette[$colorIndex % count($colorPalette)];
                    $colorIndex++;
                }
            }

            // Optimize: Get all assessment data in a single query using proper aggregation
            $schoolIds = $schools->pluck('id');

            // Get counts grouped by school and level in one query
            $assessmentCounts = Assessment::select('pilot_school_id', 'level', \DB::raw('COUNT(*) as count'))
                ->where('subject', $subject)
                ->where('cycle', $cycle)
                ->whereIn('pilot_school_id', $schoolIds)
                ->groupBy('pilot_school_id', 'level')
                ->get()
                ->keyBy(function ($item) {
                    return $item->pilot_school_id.'_'.$item->level;
                });

            // Get total assessments per school in one query
            $schoolTotals = Assessment::select('pilot_school_id', \DB::raw('COUNT(*) as total'))
                ->where('subject', $subject)
                ->where('cycle', $cycle)
                ->whereIn('pilot_school_id', $schoolIds)
                ->groupBy('pilot_school_id')
                ->pluck('total', 'pilot_school_id');

            // Build datasets using cached data
            $counts = []; // Store actual counts for tooltips
            foreach ($levels as $level) {
                $data = [];
                $levelCounts = [];
                foreach ($schools as $school) {
                    $key = $school->id.'_'.$level;
                    $count = isset($assessmentCounts[$key]) ? (int)$assessmentCounts[$key]->count : 0;
                    $levelCounts[] = (int)$count; // Ensure it's an integer

                    // Calculate percentage
                    $total = $schoolTotals[$school->id] ?? 0;
                    $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                    // IMPORTANT: Ensure percentage is a number, not string
                    $data[] = (float)$percentage;
                }
                $counts[$level] = $levelCounts;

                // Translate the level label
                $translatedLabel = trans_db(strtolower($level));
                if ($translatedLabel == strtolower($level)) {
                    // If no translation found, try the original case
                    $translatedLabel = trans_db($level);
                }
                
                $datasets[] = [
                    'label' => $translatedLabel,
                    'data' => $data,
                    'counts' => $levelCounts, // Add actual counts for tooltips
                    'backgroundColor' => $colors[$level] ?? '#999999',
                    'stack' => 'Stack 0',
                ];
            }

            return [
                'chartData' => [
                    'labels' => $schoolNames,
                    'datasets' => $datasets,
                ],
                'schoolTotals' => $schoolTotals->toArray(), // Add school totals for debugging
            ];
        });

        return response()->json($data)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
