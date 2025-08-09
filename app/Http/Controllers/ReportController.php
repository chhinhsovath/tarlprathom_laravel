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

            // Assessment levels by subject (TaRL uses levels, not scores)
            $stats['levels_by_subject'] = Assessment::select('subject', 'level', DB::raw('COUNT(*) as count'))
                ->groupBy('subject', 'level')
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

            // Assessment levels by subject for their school (TaRL uses levels, not scores)
            $stats['levels_by_subject'] = Assessment::select('subject', 'level', DB::raw('COUNT(*) as count'))
                ->whereHas('student', function ($q) use ($user) {
                    $q->where('school_id', $user->school_id);
                })
                ->groupBy('subject', 'level')
                ->get();

            // Recent assessments for their school
            $stats['recent_assessments'] = Assessment::with(['student'])
                ->whereHas('student', function ($q) use ($user) {
                    $q->where('school_id', $user->school_id);
                })
                ->orderBy('assessed_at', 'desc')
                ->limit(10)
                ->get();

            // Mentoring visits at the teacher's school
            $stats['my_mentoring_visits'] = MentoringVisit::where('school_id', $user->school_id)
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
                $stats['mentoring_visits'] = MentoringVisit::whereIn('school_id', $accessibleSchoolIds)
                    ->count();

                // Assessment levels by subject for their schools (TaRL uses levels, not scores)
                $stats['levels_by_subject'] = Assessment::select('subject', 'level', DB::raw('COUNT(*) as count'))
                    ->whereHas('student', function ($q) use ($accessibleSchoolIds) {
                        $q->whereIn('school_id', $accessibleSchoolIds);
                    })
                    ->groupBy('subject', 'level')
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
                $stats['avg_mentoring_score'] = 0; // Score column not available
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
                    'description' => 'Comprehensive analysis using Assessment Dataset (student_id, cycle, subject, level, score). Formula: Performance Distribution = COUNT(level) GROUP BY subject, cycle. Expected Result: Visual breakdown of student performance levels across Khmer (Beginner→Comp.2) and Math (Beginner→Word Problem) subjects by assessment cycles.',
                    'route' => 'reports.student-performance',
                ],
                [
                    'name' => 'School Comparison Report',
                    'description' => 'Cross-school performance analysis using aggregated Assessment data. Formula: School Performance = AVG(score) + COUNT(level) WHERE school_id GROUP BY school, subject, cycle. Expected Result: Comparative charts showing average scores and level distribution across schools, enabling identification of high-performing institutions.',
                    'route' => 'reports.school-comparison',
                ],
                [
                    'name' => 'Mentoring Impact Report',
                    'description' => 'Correlation analysis between Mentoring Visits and Student Performance datasets. Formula: Impact Score = ΔAVG(assessment_score) / COUNT(mentoring_visits) WHERE visit_date BETWEEN baseline_date AND endline_date. Expected Result: Statistical evidence of mentoring effectiveness on student learning outcomes.',
                    'route' => 'reports.mentoring-impact',
                ],
                [
                    'name' => 'Progress Tracking Report',
                    'description' => 'Longitudinal student progress analysis across assessment cycles. Formula: Progress = (Latest_Level_Index - Baseline_Level_Index) + (Latest_Score - Baseline_Score). Expected Result: Individual student progression trajectories showing improvements from baseline through midline to endline assessments.',
                    'route' => 'reports.progress-tracking',
                ],
                [
                    'name' => 'Performance Calculation Report',
                    'description' => 'TaRL methodology performance indicators using level aggregation. Formula: Language_Letters_% = (Para+Story+Comp1+Comp2)/Total_Students×100, Math_Operations_% = (Subtraction+Division+WordProblem)/Total_Students×100. Expected Result: Standardized performance percentages enabling comparison with national TaRL benchmarks.',
                    'route' => 'reports.performance-calculation',
                ],
            ];
        } elseif ($user->isTeacher()) {
            $reports = [
                [
                    'name' => 'My Students Performance',
                    'description' => 'School-specific assessment analysis filtered by teacher\'s school_id. Formula: School Performance = COUNT(level) WHERE school_id = teacher.school_id GROUP BY subject, cycle, class. Expected Result: Detailed breakdown of student performance levels within your school, showing distribution across classes and subjects for targeted teaching interventions.',
                    'route' => 'reports.my-students',
                ],
                [
                    'name' => 'Class Progress Report',
                    'description' => 'Class-level longitudinal analysis using Student-Assessment relationships. Formula: Class Progress = Σ(student_progress) / COUNT(students) WHERE class_id GROUP BY assessment_cycle. Expected Result: Class-wise progress trajectories showing average improvements, helping identify which classes need additional support.',
                    'route' => 'reports.class-progress',
                ],
            ];
        } elseif ($user->isMentor()) {
            $reports = [
                [
                    'name' => 'Student Performance Report',
                    'description' => 'Assessment analysis filtered by mentor\'s assigned schools. Formula: Mentor Performance = COUNT(level) WHERE school_id IN (mentor.assigned_schools) GROUP BY school, subject, cycle. Expected Result: Performance overview across your assigned schools, enabling targeted mentoring focus on schools with lower performance levels.',
                    'route' => 'reports.student-performance',
                ],
                [
                    'name' => 'School Comparison Report',
                    'description' => 'Comparative analysis within mentor\'s school portfolio. Formula: School Ranking = RANK() OVER (ORDER BY AVG(score) DESC) WHERE school_id IN (mentor.assigned_schools). Expected Result: Ranked comparison of assigned schools showing which institutions are excelling and which need intensive mentoring support.',
                    'route' => 'reports.school-comparison',
                ],
                [
                    'name' => 'My Mentoring Summary',
                    'description' => 'Personal mentoring activity analysis using MentoringVisit dataset. Formula: Mentoring Effectiveness = COUNT(visits) × AVG(mentoring_score) × COUNT(teachers_mentored) WHERE mentor_id = current_user. Expected Result: Comprehensive dashboard showing visit frequency, quality scores, and coverage metrics for performance evaluation.',
                    'route' => 'reports.my-mentoring',
                ],
                [
                    'name' => 'Progress Tracking Report',
                    'description' => 'Student progress analysis within mentor\'s assigned schools portfolio. Formula: Mentored Schools Progress = Σ(school_progress) WHERE school_id IN (mentor.assigned_schools) GROUP BY school. Expected Result: Progress trajectories showing impact of mentoring interventions on student learning outcomes across assigned schools.',
                    'route' => 'reports.progress-tracking',
                ],
                [
                    'name' => 'Performance Calculation Report',
                    'description' => 'TaRL performance indicators for mentor\'s school portfolio. Formula: Portfolio Performance = Σ(Language_Letters_% + Math_Operations_%) / COUNT(assigned_schools). Expected Result: Aggregated TaRL performance metrics across assigned schools for strategic mentoring planning and resource allocation.',
                    'route' => 'reports.performance-calculation',
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
            $schools = School::orderBy('sclName')->get();
        } else {
            $schools = School::whereIn('sclAutoID', $accessibleSchoolIds)->orderBy('sclName')->get();
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

        // Get performance by level and subject
        $performanceByLevelAndSubject = [];

        if ($subject === 'all') {
            // Get data for both subjects
            $subjects = ['khmer', 'math'];
            foreach ($subjects as $subj) {
                $subjectQuery = clone $query;
                $performanceByLevelAndSubject[$subj] = $subjectQuery
                    ->where('subject', $subj)
                    ->select('level', DB::raw('count(*) as count'))
                    ->groupBy('level')
                    ->get();
            }
        } else {
            // Get data for selected subject only
            $performanceByLevelAndSubject[$subject] = $performanceByLevel;
        }

        // Get performance trends
        $performanceTrends = Assessment::select(
            'cycle',
            'subject',
            'level',
            DB::raw('count(*) as count')
        )
            ->when($schoolId, function ($q) use ($schoolId, $user) {
                // Verify user has access to this school
                if ($user->isAdmin() || $user->canAccessSchool($schoolId)) {
                    $q->whereHas('student', function ($sq) use ($schoolId) {
                        $sq->where('school_id', $schoolId);
                    });
                }
            })
            ->when($subject !== 'all', function ($q) use ($subject) {
                $q->where('subject', $subject);
            })
            ->when($cycle !== 'all', function ($q) use ($cycle) {
                $q->where('cycle', $cycle);
            })
            ->groupBy('cycle', 'subject', 'level')
            ->get();

        // Get level ordering for proper chart display
        $khmerLevels = ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2'];
        $mathLevels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'];

        return view('reports.student-performance-localized', compact(
            'schools',
            'schoolId',
            'subject',
            'cycle',
            'performanceByLevel',
            'performanceByLevelAndSubject',
            'performanceTrends',
            'khmerLevels',
            'mathLevels'
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
                    'school' => $school->name,
                    'total_assessed' => $total,
                    'levels' => $levelCounts,
                ];
            }
        }

        return view('reports.school-comparison-localized', compact('comparisonData', 'subject', 'cycle'));
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

        if ($user->isTeacher() && $user->school_id) {
            $query->where('school_id', $user->school_id);
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
                'unique_mentors' => $schoolVisits->pluck('mentor_id')->unique()->count(),
                'follow_up_required' => $schoolVisits->where('follow_up_required', true)->count(),
                'visits' => $schoolVisits,
                'avg_students_enrolled' => $schoolVisits->avg('total_students_enrolled') ?? 0,
                'avg_students_present' => $schoolVisits->avg('students_present') ?? 0,
                'classes_in_session_rate' => $schoolVisits->count() > 0 ? ($schoolVisits->where('class_in_session', true)->count() / $schoolVisits->count()) * 100 : 0,
                'has_session_plan_rate' => $schoolVisits->count() > 0 ? ($schoolVisits->where('has_session_plan', true)->count() / $schoolVisits->count()) * 100 : 0,
            ];
        });

        // Get assessment improvement data
        $schoolsWithImprovements = [];
        foreach ($visitsBySchool as $schoolId => $data) {
            $school = $data['school'];

            // Get baseline and latest assessment data (TaRL level-based)
            $baselineTotal = Assessment::whereHas('student', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
                ->where('cycle', 'baseline')
                ->count();

            $baselineAdvanced = Assessment::whereHas('student', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
                ->where('cycle', 'baseline')
                ->whereNotIn('level', ['Beginner', 'Letter', '1-Digit'])
                ->count();

            $latestCycle = Assessment::whereHas('student', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
                ->whereIn('cycle', ['midline', 'endline'])
                ->orderBy('assessed_at', 'desc')
                ->first();

            if ($latestCycle) {
                $latestTotal = Assessment::whereHas('student', function ($q) use ($schoolId) {
                    $q->where('school_id', $schoolId);
                })
                    ->where('cycle', $latestCycle->cycle)
                    ->count();

                $latestAdvanced = Assessment::whereHas('student', function ($q) use ($schoolId) {
                    $q->where('school_id', $schoolId);
                })
                    ->where('cycle', $latestCycle->cycle)
                    ->whereNotIn('level', ['Beginner', 'Letter', '1-Digit'])
                    ->count();

                $baselinePercentage = $baselineTotal > 0 ? ($baselineAdvanced / $baselineTotal) * 100 : 0;
                $latestPercentage = $latestTotal > 0 ? ($latestAdvanced / $latestTotal) * 100 : 0;

                $schoolsWithImprovements[] = [
                    'school' => $school,
                    'baseline_percentage' => round($baselinePercentage, 1),
                    'latest_percentage' => round($latestPercentage, 1),
                    'improvement' => round($latestPercentage - $baselinePercentage, 1),
                    'total_visits' => $data['total_visits'],
                    'avg_mentoring_score' => 0, // Score column not available
                ];
            }
        }

        // Sort by improvement
        usort($schoolsWithImprovements, function ($a, $b) {
            return $b['improvement'] <=> $a['improvement'];
        });

        return view('reports.mentoring-impact-localized', compact(
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
            $schools = School::orderBy('sclName')->get();
        } else {
            $schools = School::whereIn('sclAutoID', $accessibleSchoolIds)->orderBy('sclName')->get();
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
                        'latest_cycle' => $latest->cycle,
                        'latest_level' => $latest->level,
                        'level_improved' => $this->calculateLevelImprovement($baseline->level, $latest->level, $subject),
                    ];
                }
            }
        }

        // Sort by level improvement
        usort($progressData, function ($a, $b) {
            return $b['level_improved'] <=> $a['level_improved'];
        });

        // Calculate average score change (using level improvement as score change for now)
        $avgScoreChange = ! empty($progressData) ? collect($progressData)->avg('level_improved') : 0;

        return view('reports.progress-tracking-localized', compact(
            'schools',
            'schoolId',
            'subject',
            'progressData',
            'avgScoreChange'
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
            'average_score' => $visits->avg('score') ?? 0,
            'follow_up_required' => $visits->where('follow_up_required', true)->count(),
            'classes_in_session' => $visits->where('class_in_session', true)->count(),
            'full_sessions_observed' => $visits->where('full_session_observed', true)->count(),
        ];

        // Group visits by school
        $visitsBySchool = $visits->groupBy('school_id')->map(function ($schoolVisits) {
            return [
                'school' => $schoolVisits->first()->school,
                'visits' => $schoolVisits,
                'total_visits' => $schoolVisits->count(),
                'teachers' => $schoolVisits->pluck('teacher')->unique(),
                'average_score' => $schoolVisits->avg('score') ?? 0,
                'follow_up_required' => $schoolVisits->where('follow_up_required', true)->count(),
                'avg_students_present' => $schoolVisits->avg('students_present') ?? 0,
                'avg_students_improved' => $schoolVisits->avg('students_improved_from_last_week') ?? 0,
            ];
        });

        // Group visits by month
        $visitsByMonth = $visits->groupBy(function ($visit) {
            return $visit->visit_date->format('Y-m');
        })->map(function ($monthVisits, $month) {
            return [
                'month' => $month,
                'count' => $monthVisits->count(),
                'average_score' => $monthVisits->avg('score') ?? 0,
                'follow_up_required' => $monthVisits->where('follow_up_required', true)->count(),
                'classes_in_session' => $monthVisits->where('class_in_session', true)->count(),
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
                'levels' => $subjectAssessments->groupBy('level')->map->count(),
            ];
        });

        // Get classes for filter
        $classes = \App\Models\SchoolClass::where('school_id', $user->school_id)
            ->orderBy('grade')
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
            ->orderBy('grade')
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

        $schools = School::whereIn('sclAutoID', $schoolIds)
            ->orderBy('name')
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
            $mentors = $visits->pluck('mentor')->unique();

            // Calculate trends
            $visitTrends = $visits->groupBy(function ($visit) {
                return $visit->visit_date->format('Y-m');
            })->map(function ($monthVisits) {
                return [
                    'count' => $monthVisits->count(),
                    'follow_up_required' => $monthVisits->where('follow_up_required', true)->count(),
                ];
            });

            $visitData = [
                'school' => School::find($schoolId),
                'visits' => $visits,
                'mentors' => $mentors,
                'total_visits' => $visits->count(),
                'follow_up_required' => $visits->where('follow_up_required', true)->count(),
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
     * Performance Calculation Report
     */
    public function performanceCalculation(Request $request)
    {
        $user = $request->user();
        if (! in_array($user->role, ['admin', 'viewer', 'mentor'])) {
            abort(403);
        }

        // Get filters
        $schoolId = $request->get('school_id');
        $cycle = $request->get('cycle', 'baseline');

        // Get schools for filter based on user access
        $accessibleSchoolIds = $user->getAccessibleSchoolIds();
        if ($user->isAdmin()) {
            $schools = School::orderBy('sclName')->get();
        } else {
            $schools = School::whereIn('sclAutoID', $accessibleSchoolIds)->orderBy('sclName')->get();
        }

        // Build base query for assessments
        $assessmentsQuery = Assessment::with(['student', 'student.school'])
            ->where('cycle', $cycle);

        // Apply access restrictions for mentors
        if (! $user->isAdmin()) {
            if (empty($accessibleSchoolIds)) {
                $assessmentsQuery->whereRaw('1 = 0');
            } else {
                $assessmentsQuery->whereHas('student', function ($q) use ($accessibleSchoolIds) {
                    $q->whereIn('school_id', $accessibleSchoolIds);
                });
            }
        }

        if ($schoolId) {
            if ($user->isAdmin() || $user->canAccessSchool($schoolId)) {
                $assessmentsQuery->whereHas('student', function ($q) use ($schoolId) {
                    $q->where('school_id', $schoolId);
                });
            }
        }

        // Get performance data by school
        $schoolPerformanceData = [];

        $targetSchools = $schoolId ? School::where('sclAutoID', $schoolId)->get() : $schools;

        foreach ($targetSchools as $school) {
            if (! $user->isAdmin() && ! $user->canAccessSchool($school->id)) {
                continue;
            }

            $schoolAssessments = Assessment::with(['student'])
                ->where('cycle', $cycle)
                ->whereHas('student', function ($q) use ($school) {
                    $q->where('school_id', $school->id);
                })
                ->get();

            // Language Performance Calculation
            $khmerAssessments = $schoolAssessments->where('subject', 'khmer');
            $totalKhmerStudents = $khmerAssessments->count();

            $languageLetters = $khmerAssessments->whereIn('level', ['Paragraph', 'Story', 'Comp. 1', 'Comp. 2'])->count();
            $languageBeginners = $khmerAssessments->whereIn('level', ['Beginner', 'Letter'])->count();

            // Math Performance Calculation
            $mathAssessments = $schoolAssessments->where('subject', 'math');
            $totalMathStudents = $mathAssessments->count();

            $mathOperations = $mathAssessments->whereIn('level', ['Subtraction', 'Division', 'Word Problem'])->count();
            $mathBeginners = $mathAssessments->whereIn('level', ['Beginner', '1-Digit'])->count();

            $schoolPerformanceData[] = [
                'school' => $school,
                'language' => [
                    'total_students' => $totalKhmerStudents,
                    'Letters' => $languageLetters,
                    'beginners' => $languageBeginners,
                    'Letters_percentage' => $totalKhmerStudents > 0 ? round(($languageLetters / $totalKhmerStudents) * 100, 1) : 0,
                    'beginners_percentage' => $totalKhmerStudents > 0 ? round(($languageBeginners / $totalKhmerStudents) * 100, 1) : 0,
                ],
                'math' => [
                    'total_students' => $totalMathStudents,
                    'operations' => $mathOperations,
                    'beginners' => $mathBeginners,
                    'operations_percentage' => $totalMathStudents > 0 ? round(($mathOperations / $totalMathStudents) * 100, 1) : 0,
                    'beginners_percentage' => $totalMathStudents > 0 ? round(($mathBeginners / $totalMathStudents) * 100, 1) : 0,
                ],
            ];
        }

        return view('reports.performance-calculation-localized', compact(
            'schools',
            'schoolId',
            'cycle',
            'schoolPerformanceData'
        ));
    }

    /**
     * Calculate level improvement
     */
    private function calculateLevelImprovement($baselineLevel, $currentLevel, $subject)
    {
        $levels = $subject === 'khmer'
            ? ['Beginner' => 0, 'Letter' => 1, 'Word' => 2, 'Paragraph' => 3, 'Story' => 4, 'Comp. 1' => 5, 'Comp. 2' => 6]
            : ['Beginner' => 0, '1-Digit' => 1, '2-Digit' => 2, 'Subtraction' => 3, 'Division' => 4, 'Word Problem' => 5];

        $baselineIndex = $levels[$baselineLevel] ?? 0;
        $currentIndex = $levels[$currentLevel] ?? 0;

        return $currentIndex - $baselineIndex;
    }
}
