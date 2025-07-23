<?php

namespace App\Http\Controllers;

use App\Exports\AssessmentsExport;
use App\Exports\MentoringVisitsExport;
use App\Models\Assessment;
use App\Models\MentoringVisit;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Display a listing of available reports.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Check if user can view reports
        if (! in_array($user->role, ['admin', 'mentor', 'teacher', 'viewer'])) {
            abort(403);
        }

        // Get summary statistics based on user role
        $stats = $this->getStatistics($user);

        // Get available report types based on user role
        $availableReports = $this->getAvailableReports($user);

        return view('reports.index', compact('stats', 'availableReports'));
    }

    /**
     * Get statistics based on user role.
     */
    private function getStatistics($user)
    {
        $stats = [];

        if ($user->isAdmin() || $user->isViewer()) {
            // Admin and Viewer can see all statistics
            $stats['total_students'] = Student::count();
            $stats['total_assessments'] = Assessment::count();
            $stats['total_schools'] = School::count();
            $stats['total_mentoring_visits'] = MentoringVisit::count();
            $stats['total_teachers'] = User::where('role', 'teacher')->count();
            $stats['total_mentors'] = User::where('role', 'mentor')->count();

            // Average assessment scores by subject
            $stats['avg_scores_by_subject'] = Assessment::select('subject', DB::raw('AVG(score) as avg_score'))
                ->groupBy('subject')
                ->get();

            // Recent assessments
            $stats['recent_assessments'] = Assessment::with(['student', 'student.school'])
                ->orderBy('assessed_at', 'desc')
                ->limit(10)
                ->get();

            // Recent mentoring visits
            $stats['recent_visits'] = MentoringVisit::with(['mentor', 'teacher', 'school'])
                ->orderBy('visit_date', 'desc')
                ->limit(10)
                ->get();

        } elseif ($user->isTeacher()) {
            // Teachers can see statistics for their school
            $stats['total_students'] = Student::where('school_id', $user->school_id)->count();
            $stats['total_assessments'] = Assessment::whereHas('student', function ($q) use ($user) {
                $q->where('school_id', $user->school_id);
            })->count();

            // Average assessment scores by subject for their school
            $stats['avg_scores_by_subject'] = Assessment::select('subject', DB::raw('AVG(score) as avg_score'))
                ->whereHas('student', function ($q) use ($user) {
                    $q->where('school_id', $user->school_id);
                })
                ->groupBy('subject')
                ->get();

            // Recent assessments for their school
            $stats['recent_assessments'] = Assessment::with(['student'])
                ->whereHas('student', function ($q) use ($user) {
                    $q->where('school_id', $user->school_id);
                })
                ->orderBy('assessed_at', 'desc')
                ->limit(10)
                ->get();

            // Mentoring visits where they were the teacher
            $stats['my_mentoring_visits'] = MentoringVisit::where('teacher_id', $user->id)
                ->with(['mentor', 'school'])
                ->orderBy('visit_date', 'desc')
                ->limit(5)
                ->get();

        } elseif ($user->isMentor()) {
            // Mentors can see statistics for their assigned schools
            $accessibleSchoolIds = $user->getAccessibleSchoolIds();

            if (! empty($accessibleSchoolIds)) {
                $stats['total_students'] = Student::whereIn('school_id', $accessibleSchoolIds)->count();
                $stats['total_assessments'] = Assessment::whereHas('student', function ($q) use ($accessibleSchoolIds) {
                    $q->whereIn('school_id', $accessibleSchoolIds);
                })->count();
                $stats['total_schools'] = count($accessibleSchoolIds);
                $stats['total_visits'] = MentoringVisit::whereIn('school_id', $accessibleSchoolIds)->count();
                $stats['schools_visited'] = MentoringVisit::whereIn('school_id', $accessibleSchoolIds)
                    ->distinct('school_id')
                    ->count('school_id');
                $stats['teachers_mentored'] = MentoringVisit::whereIn('school_id', $accessibleSchoolIds)
                    ->distinct('teacher_id')
                    ->count('teacher_id');

                // Average assessment scores by subject for their schools
                $stats['avg_scores_by_subject'] = Assessment::select('subject', DB::raw('AVG(score) as avg_score'))
                    ->whereHas('student', function ($q) use ($accessibleSchoolIds) {
                        $q->whereIn('school_id', $accessibleSchoolIds);
                    })
                    ->groupBy('subject')
                    ->get();

                // Recent assessments for their schools
                $stats['recent_assessments'] = Assessment::with(['student', 'student.school'])
                    ->whereHas('student', function ($q) use ($accessibleSchoolIds) {
                        $q->whereIn('school_id', $accessibleSchoolIds);
                    })
                    ->orderBy('assessed_at', 'desc')
                    ->limit(10)
                    ->get();

                // Recent mentoring visits for their schools
                $stats['recent_visits'] = MentoringVisit::whereIn('school_id', $accessibleSchoolIds)
                    ->with(['teacher', 'school', 'mentor'])
                    ->orderBy('visit_date', 'desc')
                    ->limit(10)
                    ->get();

                // Average mentoring scores for their schools
                $stats['avg_mentoring_score'] = MentoringVisit::whereIn('school_id', $accessibleSchoolIds)
                    ->avg('score');
            } else {
                // No schools assigned, show empty stats
                $stats['total_students'] = 0;
                $stats['total_assessments'] = 0;
                $stats['total_schools'] = 0;
                $stats['total_visits'] = 0;
                $stats['schools_visited'] = 0;
                $stats['teachers_mentored'] = 0;
                $stats['avg_scores_by_subject'] = collect();
                $stats['recent_assessments'] = collect();
                $stats['recent_visits'] = collect();
                $stats['avg_mentoring_score'] = 0;
            }
        }

        return $stats;
    }

    /**
     * Get available report types based on user role.
     */
    private function getAvailableReports($user)
    {
        $reports = [];

        if ($user->isAdmin() || $user->isViewer()) {
            $reports = [
                [
                    'name' => 'Student Performance Report',
                    'description' => 'Detailed analysis of student assessment scores',
                    'route' => 'reports.student-performance',
                ],
                [
                    'name' => 'School Comparison Report',
                    'description' => 'Compare performance metrics across schools',
                    'route' => 'reports.school-comparison',
                ],
                [
                    'name' => 'Mentoring Impact Report',
                    'description' => 'Analysis of mentoring visits and their impact',
                    'route' => 'reports.mentoring-impact',
                ],
                [
                    'name' => 'Progress Tracking Report',
                    'description' => 'Track student progress over time',
                    'route' => 'reports.progress-tracking',
                ],
            ];
        } elseif ($user->isTeacher()) {
            $reports = [
                [
                    'name' => 'My Students Performance',
                    'description' => 'Performance analysis of students in your school',
                    'route' => 'reports.my-students',
                ],
                [
                    'name' => 'Class Progress Report',
                    'description' => 'Track progress by class',
                    'route' => 'reports.class-progress',
                ],
            ];
        } elseif ($user->isMentor()) {
            $reports = [
                [
                    'name' => 'Student Performance Report',
                    'description' => 'Performance analysis of students in your assigned schools',
                    'route' => 'reports.student-performance',
                ],
                [
                    'name' => 'School Comparison Report',
                    'description' => 'Compare performance across your assigned schools',
                    'route' => 'reports.school-comparison',
                ],
                [
                    'name' => 'My Mentoring Summary',
                    'description' => 'Summary of your mentoring activities',
                    'route' => 'reports.my-mentoring',
                ],
                [
                    'name' => 'Progress Tracking Report',
                    'description' => 'Track student progress in your assigned schools',
                    'route' => 'reports.progress-tracking',
                ],
            ];
        }

        return $reports;
    }

    /**
     * Student Performance Report
     */
    public function studentPerformance(Request $request)
    {
        $user = $request->user();
        if (! in_array($user->role, ['admin', 'viewer', 'mentor'])) {
            abort(403);
        }

        // Get filters
        $schoolId = $request->get('school_id');
        $subject = $request->get('subject', 'all');
        $cycle = $request->get('cycle', 'all');

        // Get schools for filter based on user access
        $accessibleSchoolIds = $user->getAccessibleSchoolIds();
        if ($user->isAdmin()) {
            $schools = School::orderBy('school_name')->get();
        } else {
            $schools = School::whereIn('id', $accessibleSchoolIds)->orderBy('school_name')->get();
        }

        // Build query
        $query = Assessment::with(['student', 'student.school']);

        // Apply access restrictions for mentors
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

        if ($schoolId) {
            // Verify user has access to this school
            if ($user->isAdmin() || $user->canAccessSchool($schoolId)) {
                $query->whereHas('student', function ($q) use ($schoolId) {
                    $q->where('school_id', $schoolId);
                });
            }
        }

        if ($subject !== 'all') {
            $query->where('subject', $subject);
        }

        if ($cycle !== 'all') {
            $query->where('cycle', $cycle);
        }

        // Get performance by level
        $performanceByLevel = $query->select('level', DB::raw('count(*) as count'))
            ->groupBy('level')
            ->get();

        // Get performance trends
        $performanceTrends = Assessment::select(
            'cycle',
            'subject',
            'level',
            DB::raw('count(*) as count')
        )
            ->when($schoolId, function ($q) use ($schoolId) {
                $q->whereHas('student', function ($sq) use ($schoolId) {
                    $sq->where('school_id', $schoolId);
                });
            })
            ->when($subject !== 'all', function ($q) use ($subject) {
                $q->where('subject', $subject);
            })
            ->groupBy('cycle', 'subject', 'level')
            ->get();

        return view('reports.student-performance', compact(
            'schools',
            'schoolId',
            'subject',
            'cycle',
            'performanceByLevel',
            'performanceTrends'
        ));
    }

    /**
     * School Comparison Report
     */
    public function schoolComparison(Request $request)
    {
        $user = $request->user();
        if (! in_array($user->role, ['admin', 'viewer', 'mentor'])) {
            abort(403);
        }

        $subject = $request->get('subject', 'khmer');
        $cycle = $request->get('cycle', 'baseline');

        // Get schools based on user access
        $accessibleSchoolIds = $user->getAccessibleSchoolIds();
        $schoolsQuery = School::with(['students.assessments' => function ($q) use ($subject, $cycle) {
            $q->where('subject', $subject)->where('cycle', $cycle);
        }]);

        if (! $user->isAdmin()) {
            if (empty($accessibleSchoolIds)) {
                // If no schools are accessible, return no results
                $schoolsQuery->whereRaw('1 = 0');
            } else {
                $schoolsQuery->whereIn('id', $accessibleSchoolIds);
            }
        }

        $schools = $schoolsQuery->get();

        $comparisonData = [];

        foreach ($schools as $school) {
            $assessments = $school->students->pluck('assessments')->flatten();
            $total = $assessments->count();

            if ($total > 0) {
                $levelCounts = $assessments->groupBy('level')->map->count();

                $comparisonData[] = [
                    'school' => $school->school_name,
                    'total_assessed' => $total,
                    'levels' => $levelCounts,
                    'average_score' => $assessments->avg('score') ?? 0,
                ];
            }
        }

        return view('reports.school-comparison', compact('comparisonData', 'subject', 'cycle'));
    }

    /**
     * Export report data
     */
    public function export(Request $request, $type)
    {
        $user = $request->user();

        // Check permissions
        if (! in_array($user->role, ['admin', 'viewer', 'mentor', 'teacher'])) {
            abort(403);
        }

        // Always use XLSX format for user-readable exports
        $format = 'xlsx';

        switch ($type) {
            case 'assessments':
                return $this->exportAssessments($request, $format);
            case 'mentoring':
                return $this->exportMentoringVisits($request, $format);
            default:
                abort(404);
        }
    }

    /**
     * Export assessments data
     */
    private function exportAssessments(Request $request, $format)
    {
        if ($format === 'xlsx') {
            // Use the existing AssessmentsExport class with Hanuman font
            return Excel::download(new AssessmentsExport($request), 'assessments_'.date('Y-m-d_H-i-s').'.xlsx');
        }

        // Fallback to JSON if needed
        $query = Assessment::with(['student', 'student.school']);

        // Apply filters based on user role
        $user = $request->user();
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

        $assessments = $query->get();

        return response()->json($assessments);
    }

    /**
     * Export mentoring visits data
     */
    private function exportMentoringVisits(Request $request, $format)
    {
        if ($format === 'xlsx') {
            // Use the existing MentoringVisitsExport class with Hanuman font
            return Excel::download(new MentoringVisitsExport($request), 'mentoring_visits_'.date('Y-m-d_H-i-s').'.xlsx');
        }

        // Fallback to JSON if needed
        $query = MentoringVisit::with(['mentor', 'teacher', 'school']);

        // Apply filters based on user role
        $user = $request->user();
        $accessibleSchoolIds = $user->getAccessibleSchoolIds();

        if (! $user->isAdmin()) {
            if (empty($accessibleSchoolIds)) {
                // If no schools are accessible, return no results
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('school_id', $accessibleSchoolIds);
            }
        }

        if ($user->isTeacher()) {
            $query->where('teacher_id', $user->id);
        }

        $visits = $query->get();

        return response()->json($visits);
    }

    /**
     * Mentoring Impact Report
     */
    public function mentoringImpact(Request $request)
    {
        $user = $request->user();
        if (! in_array($user->role, ['admin', 'viewer'])) {
            abort(403);
        }

        // Get date range filters
        $startDate = $request->get('start_date', now()->subMonths(3)->startOfDay());
        $endDate = $request->get('end_date', now()->endOfDay());

        // Get mentoring visits with related data
        $visits = MentoringVisit::with(['mentor', 'teacher', 'school'])
            ->whereBetween('visit_date', [$startDate, $endDate])
            ->get();

        // Calculate impact metrics
        $visitsBySchool = $visits->groupBy('school_id')->map(function ($schoolVisits) {
            return [
                'school' => $schoolVisits->first()->school,
                'total_visits' => $schoolVisits->count(),
                'unique_teachers' => $schoolVisits->pluck('teacher_id')->unique()->count(),
                'average_score' => $schoolVisits->avg('score'),
                'visits' => $schoolVisits,
            ];
        });

        // Get assessment improvement data
        $schoolsWithImprovements = [];
        foreach ($visitsBySchool as $schoolId => $data) {
            $school = $data['school'];

            // Get baseline and latest assessment data
            $baselineAvg = Assessment::whereHas('student', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
                ->where('cycle', 'baseline')
                ->avg('score');

            $latestCycle = Assessment::whereHas('student', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
                ->whereIn('cycle', ['midline', 'endline'])
                ->orderBy('assessed_at', 'desc')
                ->first();

            if ($latestCycle) {
                $latestAvg = Assessment::whereHas('student', function ($q) use ($schoolId) {
                    $q->where('school_id', $schoolId);
                })
                    ->where('cycle', $latestCycle->cycle)
                    ->avg('score');

                $schoolsWithImprovements[] = [
                    'school' => $school,
                    'baseline_avg' => $baselineAvg,
                    'latest_avg' => $latestAvg,
                    'improvement' => $latestAvg - $baselineAvg,
                    'total_visits' => $data['total_visits'],
                    'avg_mentoring_score' => $data['average_score'],
                ];
            }
        }

        // Sort by improvement
        usort($schoolsWithImprovements, function ($a, $b) {
            return $b['improvement'] <=> $a['improvement'];
        });

        return view('reports.mentoring-impact', compact(
            'visitsBySchool',
            'schoolsWithImprovements',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Progress Tracking Report
     */
    public function progressTracking(Request $request)
    {
        $user = $request->user();
        if (! in_array($user->role, ['admin', 'viewer', 'mentor'])) {
            abort(403);
        }

        // Get filters
        $schoolId = $request->get('school_id');
        $subject = $request->get('subject', 'khmer');

        // Get schools for filter based on user access
        $accessibleSchoolIds = $user->getAccessibleSchoolIds();
        if ($user->isAdmin()) {
            $schools = School::orderBy('school_name')->get();
        } else {
            $schools = School::whereIn('id', $accessibleSchoolIds)->orderBy('school_name')->get();
        }

        // Build query for students with multiple assessments
        $studentsQuery = Student::with(['assessments' => function ($q) use ($subject) {
            $q->where('subject', $subject)
                ->orderBy('cycle');
        }])
            ->whereHas('assessments', function ($q) use ($subject) {
                $q->where('subject', $subject);
            }, '>', 1); // Only students with more than 1 assessment

        // Apply access restrictions for mentors
        if (! $user->isAdmin()) {
            if (empty($accessibleSchoolIds)) {
                // If no schools are accessible, return no results
                $studentsQuery->whereRaw('1 = 0');
            } else {
                $studentsQuery->whereIn('school_id', $accessibleSchoolIds);
            }
        }

        if ($schoolId) {
            // Verify user has access to this school
            if ($user->isAdmin() || $user->canAccessSchool($schoolId)) {
                $studentsQuery->where('school_id', $schoolId);
            }
        }

        $students = $studentsQuery->get();

        // Process progress data
        $progressData = [];
        foreach ($students as $student) {
            $assessments = $student->assessments;
            if ($assessments->count() > 1) {
                $baseline = $assessments->where('cycle', 'baseline')->first();
                $latest = $assessments->whereIn('cycle', ['midline', 'endline'])->last();

                if ($baseline && $latest) {
                    $progressData[] = [
                        'student' => $student,
                        'baseline_level' => $baseline->level,
                        'baseline_score' => $baseline->score,
                        'latest_cycle' => $latest->cycle,
                        'latest_level' => $latest->level,
                        'latest_score' => $latest->score,
                        'score_change' => $latest->score - $baseline->score,
                        'level_improved' => $this->calculateLevelImprovement($baseline->level, $latest->level, $subject),
                    ];
                }
            }
        }

        // Sort by improvement
        usort($progressData, function ($a, $b) {
            return $b['score_change'] <=> $a['score_change'];
        });

        return view('reports.progress-tracking', compact(
            'schools',
            'schoolId',
            'subject',
            'progressData'
        ));
    }

    /**
     * My Mentoring Summary Report (for mentors)
     */
    public function myMentoring(Request $request)
    {
        $user = $request->user();
        if (! $user->isMentor()) {
            abort(403);
        }

        // Get date range filters
        $startDate = $request->get('start_date', now()->subMonths(6)->startOfDay());
        $endDate = $request->get('end_date', now()->endOfDay());

        // Get mentor's visits from assigned schools
        $accessibleSchoolIds = $user->getAccessibleSchoolIds();
        $visitsQuery = MentoringVisit::with(['teacher', 'school'])
            ->whereBetween('visit_date', [$startDate, $endDate]);

        if (empty($accessibleSchoolIds)) {
            // If no schools are accessible, return no results
            $visitsQuery->whereRaw('1 = 0');
        } else {
            $visitsQuery->whereIn('school_id', $accessibleSchoolIds);
        }

        $visits = $visitsQuery->orderBy('visit_date', 'desc')->get();

        // Calculate summary statistics
        $stats = [
            'total_visits' => $visits->count(),
            'schools_visited' => $visits->pluck('school_id')->unique()->count(),
            'teachers_mentored' => $visits->pluck('teacher_id')->unique()->count(),
            'average_score' => $visits->avg('score'),
            'follow_up_required' => $visits->where('follow_up_required', true)->count(),
        ];

        // Group visits by school
        $visitsBySchool = $visits->groupBy('school_id')->map(function ($schoolVisits) {
            return [
                'school' => $schoolVisits->first()->school,
                'visits' => $schoolVisits,
                'total_visits' => $schoolVisits->count(),
                'average_score' => $schoolVisits->avg('score'),
                'teachers' => $schoolVisits->pluck('teacher')->unique(),
            ];
        });

        // Group visits by month
        $visitsByMonth = $visits->groupBy(function ($visit) {
            return $visit->visit_date->format('Y-m');
        })->map(function ($monthVisits, $month) {
            return [
                'month' => $month,
                'count' => $monthVisits->count(),
                'average_score' => $monthVisits->avg('score'),
            ];
        })->sortKeys();

        return view('reports.my-mentoring', compact(
            'visits',
            'stats',
            'visitsBySchool',
            'visitsByMonth',
            'startDate',
            'endDate'
        ));
    }

    /**
     * My Students Performance Report (for teachers)
     */
    public function myStudents(Request $request)
    {
        $user = $request->user();
        if (! $user->isTeacher()) {
            abort(403);
        }

        // Get filters
        $subject = $request->get('subject', 'all');
        $cycle = $request->get('cycle', 'all');
        $classId = $request->get('class_id');

        // Get teacher's students
        $studentsQuery = Student::where('school_id', $user->school_id)
            ->with(['assessments', 'schoolClass']);

        if ($classId) {
            $studentsQuery->where('class_id', $classId);
        }

        $students = $studentsQuery->get();

        // Get assessment data
        $assessmentsQuery = Assessment::whereIn('student_id', $students->pluck('id'));

        if ($subject !== 'all') {
            $assessmentsQuery->where('subject', $subject);
        }

        if ($cycle !== 'all') {
            $assessmentsQuery->where('cycle', $cycle);
        }

        $assessments = $assessmentsQuery->get();

        // Calculate performance metrics
        $performanceByLevel = $assessments->groupBy('level')->map->count();
        $performanceBySubject = $assessments->groupBy('subject')->map(function ($subjectAssessments) {
            return [
                'count' => $subjectAssessments->count(),
                'average_score' => $subjectAssessments->avg('score'),
                'levels' => $subjectAssessments->groupBy('level')->map->count(),
            ];
        });

        // Get classes for filter
        $classes = \App\Models\SchoolClass::where('school_id', $user->school_id)
            ->orderBy('grade_level')
            ->orderBy('name')
            ->get();

        return view('reports.my-students', compact(
            'students',
            'assessments',
            'performanceByLevel',
            'performanceBySubject',
            'subject',
            'cycle',
            'classId',
            'classes'
        ));
    }

    /**
     * Class Progress Report (for teachers)
     */
    public function classProgress(Request $request)
    {
        $user = $request->user();
        if (! $user->isTeacher()) {
            abort(403);
        }

        // Get class filter
        $classId = $request->get('class_id');
        $subject = $request->get('subject', 'khmer');

        // Get classes
        $classes = \App\Models\SchoolClass::where('school_id', $user->school_id)
            ->orderBy('grade_level')
            ->orderBy('name')
            ->get();

        $progressData = [];

        if ($classId) {
            // Get students in the class with their assessments
            $students = Student::where('class_id', $classId)
                ->with(['assessments' => function ($q) use ($subject) {
                    $q->where('subject', $subject)
                        ->orderBy('cycle');
                }])
                ->get();

            foreach ($students as $student) {
                $assessments = $student->assessments;
                if ($assessments->count() > 0) {
                    $baseline = $assessments->where('cycle', 'baseline')->first();
                    $midline = $assessments->where('cycle', 'midline')->first();
                    $endline = $assessments->where('cycle', 'endline')->first();

                    $progressData[] = [
                        'student' => $student,
                        'baseline' => $baseline,
                        'midline' => $midline,
                        'endline' => $endline,
                        'latest' => $endline ?? $midline ?? $baseline,
                    ];
                }
            }
        }

        return view('reports.class-progress', compact(
            'classes',
            'classId',
            'subject',
            'progressData'
        ));
    }

    /**
     * School Visit Report (for mentors)
     */
    public function schoolVisits(Request $request)
    {
        $user = $request->user();
        if (! $user->isMentor()) {
            abort(403);
        }

        // Get school filter
        $schoolId = $request->get('school_id');

        // Get schools visited by this mentor
        $schoolIds = MentoringVisit::where('mentor_id', $user->id)
            ->distinct()
            ->pluck('school_id');

        $schools = School::whereIn('id', $schoolIds)
            ->orderBy('school_name')
            ->get();

        $visitData = null;

        if ($schoolId) {
            // Get all visits to this school by this mentor
            $visits = MentoringVisit::where('mentor_id', $user->id)
                ->where('school_id', $schoolId)
                ->with(['teacher', 'school'])
                ->orderBy('visit_date', 'desc')
                ->get();

            // Get unique teachers mentored at this school
            $teachers = $visits->pluck('teacher')->unique();

            // Calculate trends
            $visitTrends = $visits->groupBy(function ($visit) {
                return $visit->visit_date->format('Y-m');
            })->map(function ($monthVisits) {
                return [
                    'count' => $monthVisits->count(),
                    'average_score' => $monthVisits->avg('score'),
                ];
            });

            $visitData = [
                'school' => School::find($schoolId),
                'visits' => $visits,
                'teachers' => $teachers,
                'total_visits' => $visits->count(),
                'average_score' => $visits->avg('score'),
                'visit_trends' => $visitTrends,
            ];
        }

        return view('reports.school-visits', compact(
            'schools',
            'schoolId',
            'visitData'
        ));
    }

    /**
     * Calculate level improvement
     */
    private function calculateLevelImprovement($baselineLevel, $currentLevel, $subject)
    {
        $levels = $subject === 'khmer'
            ? ['Beginner' => 0, 'Reader' => 1, 'Word' => 2, 'Paragraph' => 3, 'Story' => 4, 'Comp. 1' => 5, 'Comp. 2' => 6]
            : ['Beginner' => 0, '1-Digit' => 1, '2-Digit' => 2, 'Subtraction' => 3, 'Division' => 4, 'Word Problem' => 5];

        $baselineIndex = $levels[$baselineLevel] ?? 0;
        $currentIndex = $levels[$currentLevel] ?? 0;

        return $currentIndex - $baselineIndex;
    }
}
