<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\MentoringVisit;
use App\Models\PilotSchool;
use App\Models\Province;
use App\Models\School;
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

        // Get schools for mentors/admins (optimized with PilotSchool)
        $schools = [];
        $provinces = [];
        $districts = [];
        $clusters = [];

        if ($user->role === 'mentor' || $user->role === 'admin') {
            // For pilot phase, use the optimized pilot_schools table
            // This table only has 30 schools instead of thousands
            $schools = PilotSchool::orderBy('school_name')->get();
            
            // Get unique provinces from pilot schools (only 2 provinces)
            $provinces = PilotSchool::getProvinces();
            
            // Get unique districts from pilot schools (only 2 districts)
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
        $stats = [];

        // Base query
        $studentQuery = Student::query();
        $assessmentQuery = Assessment::query();
        $mentoringQuery = MentoringVisit::query();
        $schoolQuery = School::query();

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
                $studentQuery->whereIn('school_id', $accessibleSchoolIds);
                // Use join instead of whereHas for better performance
                $assessmentQuery->join('students', 'assessments.student_id', '=', 'students.id')
                    ->whereIn('students.school_id', $accessibleSchoolIds)
                    ->select('assessments.*');
                $mentoringQuery->whereIn('school_id', $accessibleSchoolIds);
                $schoolQuery->whereIn('sclAutoID', $accessibleSchoolIds);
            }
        }

        // Apply filters for mentors/admins
        if ($user->role === 'mentor' || $user->role === 'admin') {
            // Filter by province (optimized to use Province model)
            if ($request->has('province') && $request->province) {
                $province = Province::where('name_en', $request->province)->first();
                if ($province) {
                    $filterQuery = School::where('sclProvince', $province->province_code);
                    if (! $user->isAdmin()) {
                        $filterQuery->whereIn('sclAutoID', $accessibleSchoolIds);
                    }
                    $schoolIds = $filterQuery->pluck('sclAutoID');
                    $studentQuery->whereIn('school_id', $schoolIds);
                    // Optimize assessment query with join
                    $assessmentQuery->join('students as s2', 'assessments.student_id', '=', 's2.id')
                        ->whereIn('s2.school_id', $schoolIds)
                        ->select('assessments.*');
                    $mentoringQuery->whereIn('school_id', $schoolIds);
                    $schoolQuery->where('sclProvince', $province->province_code);
                }
            }

            // Filter by district
            if ($request->has('district') && $request->district) {
                $filterQuery = School::where('sclDistrictName', $request->district);
                if (! $user->isAdmin()) {
                    $filterQuery->whereIn('sclAutoID', $accessibleSchoolIds);
                }
                $schoolIds = $filterQuery->pluck('sclAutoID');
                $studentQuery->whereIn('school_id', $schoolIds);
                $assessmentQuery->whereHas('student', function ($q) use ($schoolIds) {
                    $q->whereIn('school_id', $schoolIds);
                });
                $mentoringQuery->whereIn('school_id', $schoolIds);
                $schoolQuery->where('sclDistrictName', $request->district);
            }

            // Filter by cluster (commented out - cluster column doesn't exist yet)
            // if ($request->has('cluster') && $request->cluster) {
            //     $filterQuery = School::where('cluster', $request->cluster);
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
                $studentQuery->where('school_id', $request->school_id);
                $assessmentQuery->whereHas('student', function ($q) use ($request) {
                    $q->where('school_id', $request->school_id);
                });
                $mentoringQuery->where('school_id', $request->school_id);
                $schoolQuery->where('sclAutoID', $request->school_id);
            }
        }

        $stats['total_students'] = $studentQuery->count();
        $stats['total_assessments'] = $assessmentQuery->count();
        $stats['total_schools'] = $schoolQuery->count();
        $stats['total_mentoring_visits'] = $mentoringQuery->count();

        return response()->json($stats);
    }

    /**
     * Get overall assessment results data
     */
    public function getOverallResults(Request $request)
    {
        $user = auth()->user();
        $subject = $request->get('subject', 'khmer');

        // Define levels based on subject with Comp. 1 and Comp. 2 for Khmer
        if ($subject === 'khmer') {
            $levels = ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2'];
        } else {
            $levels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'];
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
                $query->whereHas('student', function ($q) use ($accessibleSchoolIds) {
                    $q->whereIn('school_id', $accessibleSchoolIds);
                });
            }
        }

        // Apply filters for mentors/admins
        if ($user->role === 'mentor' || $user->role === 'admin') {
            // Filter by province (optimized)
            if ($request->has('province') && $request->province) {
                $province = Province::where('name_en', $request->province)->first();
                if ($province) {
                    $filterQuery = School::where('sclProvince', $province->province_code);
                    if (! $user->isAdmin()) {
                        $filterQuery->whereIn('sclAutoID', $accessibleSchoolIds);
                    }
                    $schoolIds = $filterQuery->pluck('sclAutoID');
                    $query->whereHas('student', function ($q) use ($schoolIds) {
                        $q->whereIn('school_id', $schoolIds);
                    });
                }
            }

            // Filter by district
            if ($request->has('district') && $request->district) {
                $filterQuery = School::where('sclDistrictName', $request->district);
                if (! $user->isAdmin()) {
                    $filterQuery->whereIn('sclAutoID', $accessibleSchoolIds);
                }
                $schoolIds = $filterQuery->pluck('sclAutoID');
                $query->whereHas('student', function ($q) use ($schoolIds) {
                    $q->whereIn('school_id', $schoolIds);
                });
            }

            // Filter by cluster (commented out - cluster column doesn't exist yet)
            // if ($request->has('cluster') && $request->cluster) {
            //     $filterQuery = School::where('cluster', $request->cluster);
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
                $query->whereHas('student', function ($q) use ($request) {
                    $q->where('school_id', $request->school_id);
                });
            }
        }

        // Get data for each cycle
        $datasets = [];
        $colors = [
            'Beginner' => '#d32f2f',
            'Letter' => '#ff6b35',
            'Word' => '#fbc02d',
            'Paragraph' => '#66bb6a',
            'Story' => '#42a5f5',
            'Comp. 1' => '#ab47bc',
            'Comp. 2' => '#7e57c2',
            '1-Digit' => '#ff6b35',
            '2-Digit' => '#fbc02d',
            'Subtraction' => '#66bb6a',
            'Division' => '#42a5f5',
            'Word Problem' => '#ab47bc',
        ];

        foreach ($levels as $level) {
            $data = [];
            foreach ($cycles as $cycle) {
                $count = (clone $query)
                    ->where('cycle', $cycle)
                    ->where('level', $level)
                    ->count();
                $data[] = $count;
            }

            $datasets[] = [
                'label' => __($level),
                'data' => $data,
                'backgroundColor' => $colors[$level],
                'stack' => 'Stack 0',
            ];
        }

        // Get totals for table
        $totals = [];
        foreach ($cycles as $cycle) {
            $totals[$cycle] = (clone $query)->where('cycle', $cycle)->count();
        }

        return response()->json([
            'chartData' => [
                'labels' => [__('Baseline'), __('Midline'), __('Endline')],
                'datasets' => $datasets,
            ],
            'totals' => $totals,
        ]);
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

        $subject = $request->get('subject', 'khmer');
        $cycle = $request->get('cycle', 'baseline');

        // Define levels based on subject with Comp. 1 and Comp. 2 for Khmer
        if ($subject === 'khmer') {
            $levels = ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2'];
        } else {
            $levels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'];
        }

        // Get accessible schools based on user role
        $accessibleSchoolIds = $user->getAccessibleSchoolIds();

        // Get schools with filters
        $schoolQuery = School::orderBy('sclName');

        // Apply access restrictions for mentors
        if (! $user->isAdmin()) {
            if (empty($accessibleSchoolIds)) {
                // If no schools are accessible, return no results
                $schoolQuery->whereRaw('1 = 0');
            } else {
                $schoolQuery->whereIn('sclAutoID', $accessibleSchoolIds);
            }
        }

        // Apply filters (optimized)
        if ($request->has('province') && $request->province) {
            $province = Province::where('name_en', $request->province)->first();
            if ($province) {
                $schoolQuery->where('sclProvince', $province->province_code);
            }
        }

        if ($request->has('district') && $request->district) {
            $schoolQuery->where('sclDistrictName', $request->district);
        }

        // Filter by cluster (commented out - cluster column doesn't exist yet)
        // if ($request->has('cluster') && $request->cluster) {
        //     $schoolQuery->where('cluster', $request->cluster);
        // }

        if ($request->has('school_id') && $request->school_id) {
            $schoolQuery->where('sclAutoID', $request->school_id);
        }

        $schools = $schoolQuery->get();
        $schoolNames = $schools->pluck('name')->toArray();

        // Prepare datasets
        $datasets = [];
        $colors = [
            'Beginner' => '#d32f2f',
            'Letter' => '#ff6b35',
            'Word' => '#fbc02d',
            'Paragraph' => '#66bb6a',
            'Story' => '#42a5f5',
            'Comp. 1' => '#ab47bc',
            'Comp. 2' => '#7e57c2',
            '1-Digit' => '#ff6b35',
            '2-Digit' => '#fbc02d',
            'Subtraction' => '#66bb6a',
            'Division' => '#42a5f5',
            'Word Problem' => '#ab47bc',
        ];

        // First, get total students per school for the specific cycle
        $schoolTotals = [];
        foreach ($schools as $school) {
            $total = Assessment::where('subject', $subject)
                ->where('cycle', $cycle)
                ->whereHas('student', function ($q) use ($school) {
                    $q->where('school_id', $school->id);
                })
                ->count();
            $schoolTotals[$school->id] = $total;
        }

        foreach ($levels as $level) {
            $data = [];
            foreach ($schools as $school) {
                $count = Assessment::where('subject', $subject)
                    ->where('cycle', $cycle)
                    ->where('level', $level)
                    ->whereHas('student', function ($q) use ($school) {
                        $q->where('school_id', $school->id);
                    })
                    ->count();

                // Calculate percentage
                $total = $schoolTotals[$school->id];
                $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                $data[] = $percentage;
            }

            $datasets[] = [
                'label' => __($level),
                'data' => $data,
                'backgroundColor' => $colors[$level],
                'stack' => 'Stack 0',
            ];
        }

        return response()->json([
            'chartData' => [
                'labels' => $schoolNames,
                'datasets' => $datasets,
            ],
        ]);
    }
}
