<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\MentoringVisit;
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

        // Get schools for mentors/admins
        $schools = [];
        $provinces = [];
        $districts = [];
        $clusters = [];

        if ($user->role === 'mentor' || $user->role === 'admin') {
            // Get accessible schools based on user role
            $schoolIds = $user->getAccessibleSchoolIds();

            if (! $user->isAdmin() && empty($schoolIds)) {
                // If mentor has no assigned schools, show empty lists
                $schools = collect([]);
                $provinces = collect([]);
                $districts = collect([]);
                $clusters = collect([]);
            } else {
                $schools = School::whereIn('id', $schoolIds)->orderBy('school_name')->get();

                // Get unique provinces, districts, and clusters from accessible schools
                $provinces = School::whereIn('id', $schoolIds)->distinct()->orderBy('province')->pluck('province')->filter()->values();
                $districts = School::whereIn('id', $schoolIds)->distinct()->orderBy('district')->pluck('district')->filter()->values();
                $clusters = School::whereIn('id', $schoolIds)->distinct()->orderBy('cluster')->pluck('cluster')->filter()->values();
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
                $assessmentQuery->whereHas('student', function ($q) use ($accessibleSchoolIds) {
                    $q->whereIn('school_id', $accessibleSchoolIds);
                });
                $mentoringQuery->whereIn('school_id', $accessibleSchoolIds);
                $schoolQuery->whereIn('id', $accessibleSchoolIds);
            }
        }

        // Apply filters for mentors/admins
        if ($user->role === 'mentor' || $user->role === 'admin') {
            // Filter by province
            if ($request->has('province') && $request->province) {
                $filterQuery = School::where('province', $request->province);
                if (! $user->isAdmin()) {
                    $filterQuery->whereIn('id', $accessibleSchoolIds);
                }
                $schoolIds = $filterQuery->pluck('id');
                $studentQuery->whereIn('school_id', $schoolIds);
                $assessmentQuery->whereHas('student', function ($q) use ($schoolIds) {
                    $q->whereIn('school_id', $schoolIds);
                });
                $mentoringQuery->whereIn('school_id', $schoolIds);
                $schoolQuery->where('province', $request->province);
            }

            // Filter by district
            if ($request->has('district') && $request->district) {
                $filterQuery = School::where('district', $request->district);
                if (! $user->isAdmin()) {
                    $filterQuery->whereIn('id', $accessibleSchoolIds);
                }
                $schoolIds = $filterQuery->pluck('id');
                $studentQuery->whereIn('school_id', $schoolIds);
                $assessmentQuery->whereHas('student', function ($q) use ($schoolIds) {
                    $q->whereIn('school_id', $schoolIds);
                });
                $mentoringQuery->whereIn('school_id', $schoolIds);
                $schoolQuery->where('district', $request->district);
            }

            // Filter by cluster
            if ($request->has('cluster') && $request->cluster) {
                $filterQuery = School::where('cluster', $request->cluster);
                if (! $user->isAdmin()) {
                    $filterQuery->whereIn('id', $accessibleSchoolIds);
                }
                $schoolIds = $filterQuery->pluck('id');
                $studentQuery->whereIn('school_id', $schoolIds);
                $assessmentQuery->whereHas('student', function ($q) use ($schoolIds) {
                    $q->whereIn('school_id', $schoolIds);
                });
                $mentoringQuery->whereIn('school_id', $schoolIds);
                $schoolQuery->where('cluster', $request->cluster);
            }

            // Filter by selected school
            if ($request->has('school_id') && $request->school_id) {
                $studentQuery->where('school_id', $request->school_id);
                $assessmentQuery->whereHas('student', function ($q) use ($request) {
                    $q->where('school_id', $request->school_id);
                });
                $mentoringQuery->where('school_id', $request->school_id);
                $schoolQuery->where('id', $request->school_id);
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

        // Define levels based on subject
        if ($subject === 'khmer') {
            $levels = ['Beginner', 'Reader', 'Word', 'Paragraph', 'Story'];
        } else {
            $levels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division'];
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
            // Filter by province
            if ($request->has('province') && $request->province) {
                $filterQuery = School::where('province', $request->province);
                if (! $user->isAdmin()) {
                    $filterQuery->whereIn('id', $accessibleSchoolIds);
                }
                $schoolIds = $filterQuery->pluck('id');
                $query->whereHas('student', function ($q) use ($schoolIds) {
                    $q->whereIn('school_id', $schoolIds);
                });
            }

            // Filter by district
            if ($request->has('district') && $request->district) {
                $filterQuery = School::where('district', $request->district);
                if (! $user->isAdmin()) {
                    $filterQuery->whereIn('id', $accessibleSchoolIds);
                }
                $schoolIds = $filterQuery->pluck('id');
                $query->whereHas('student', function ($q) use ($schoolIds) {
                    $q->whereIn('school_id', $schoolIds);
                });
            }

            // Filter by cluster
            if ($request->has('cluster') && $request->cluster) {
                $filterQuery = School::where('cluster', $request->cluster);
                if (! $user->isAdmin()) {
                    $filterQuery->whereIn('id', $accessibleSchoolIds);
                }
                $schoolIds = $filterQuery->pluck('id');
                $query->whereHas('student', function ($q) use ($schoolIds) {
                    $q->whereIn('school_id', $schoolIds);
                });
            }

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
            'Reader' => '#f57c00',
            'Word' => '#fbc02d',
            'Paragraph' => '#388e3c',
            'Story' => '#2e7d32',
            '1-Digit' => '#f57c00',
            '2-Digit' => '#fbc02d',
            'Subtraction' => '#388e3c',
            'Division' => '#2e7d32',
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

        // Define levels based on subject
        if ($subject === 'khmer') {
            $levels = ['Beginner', 'Reader', 'Word', 'Paragraph', 'Story'];
        } else {
            $levels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division'];
        }

        // Get accessible schools based on user role
        $accessibleSchoolIds = $user->getAccessibleSchoolIds();

        // Get schools with filters
        $schoolQuery = School::orderBy('school_name');

        // Apply access restrictions for mentors
        if (! $user->isAdmin()) {
            if (empty($accessibleSchoolIds)) {
                // If no schools are accessible, return no results
                $schoolQuery->whereRaw('1 = 0');
            } else {
                $schoolQuery->whereIn('id', $accessibleSchoolIds);
            }
        }

        // Apply filters
        if ($request->has('province') && $request->province) {
            $schoolQuery->where('province', $request->province);
        }

        if ($request->has('district') && $request->district) {
            $schoolQuery->where('district', $request->district);
        }

        if ($request->has('cluster') && $request->cluster) {
            $schoolQuery->where('cluster', $request->cluster);
        }

        if ($request->has('school_id') && $request->school_id) {
            $schoolQuery->where('id', $request->school_id);
        }

        $schools = $schoolQuery->get();
        $schoolNames = $schools->pluck('school_name')->toArray();

        // Prepare datasets
        $datasets = [];
        $colors = [
            'Beginner' => '#d32f2f',
            'Reader' => '#f57c00',
            'Word' => '#fbc02d',
            'Paragraph' => '#388e3c',
            'Story' => '#2e7d32',
            '1-Digit' => '#f57c00',
            '2-Digit' => '#fbc02d',
            'Subtraction' => '#388e3c',
            'Division' => '#2e7d32',
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
