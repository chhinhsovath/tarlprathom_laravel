<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Assessment;
use App\Models\School;
use App\Models\MentoringVisit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Base query for statistics based on user role
        $studentQuery = Student::query();
        $assessmentQuery = Assessment::query();
        $schoolQuery = School::query();
        $mentoringQuery = MentoringVisit::query();
        
        // Apply role-based filters
        if ($user->role === 'teacher') {
            $studentQuery->where('teacher_id', $user->id);
            $assessmentQuery->whereHas('student', function($q) use ($user) {
                $q->where('teacher_id', $user->id);
            });
            $schoolQuery->where('id', $user->school_id);
            $mentoringQuery->where('school_id', $user->school_id);
        } elseif ($user->role === 'mentor') {
            $schoolIds = $user->schools->pluck('id');
            $studentQuery->whereIn('school_id', $schoolIds);
            $assessmentQuery->whereIn('school_id', $schoolIds);
            $schoolQuery->whereIn('id', $schoolIds);
            $mentoringQuery->whereIn('school_id', $schoolIds);
        }
        
        // Get summary statistics
        $totalStudents = $studentQuery->count();
        $totalSchools = $schoolQuery->count();
        $totalAssessments = $assessmentQuery->count();
        $totalMentoringVisits = $mentoringQuery->count();

        // Get assessment statistics by cycle
        $assessmentStats = $assessmentQuery->select('cycle', DB::raw('count(*) as count'))
            ->groupBy('cycle')
            ->get();

        // Get recent assessments
        $recentAssessments = $assessmentQuery->with(['student', 'school'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Define available reports based on user role
        $availableReports = $this->getAvailableReports($user);

        // Prepare stats array for the view
        $stats = [
            'total_students' => $totalStudents,
            'total_schools' => $totalSchools,
            'total_assessments' => $totalAssessments,
            'total_mentoring_visits' => $totalMentoringVisits,
        ];

        return view('reports.index', compact(
            'stats',
            'assessmentStats',
            'recentAssessments',
            'availableReports'
        ));
    }

    public function studentPerformance(Request $request)
    {
        $user = Auth::user();
        $query = Student::with(['assessments', 'school', 'schoolClass']);

        // Apply role-based filters
        if ($user->role === 'teacher') {
            $query->where('teacher_id', $user->id);
        } elseif ($user->role === 'mentor') {
            $query->whereIn('school_id', $user->schools->pluck('id'));
        }

        // Apply filters
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        $students = $query->paginate(20);
        $schools = School::all();
        
        // Get filter values for the view
        $schoolId = $request->get('school_id');
        $classId = $request->get('class_id');
        $grade = $request->get('class');
        $subject = $request->get('subject', 'all');
        $cycle = $request->get('cycle', 'all');
        
        // Get classes if needed
        $classes = [];
        if ($user->role === 'teacher') {
            $classes = \App\Models\SchoolClass::where('teacher_id', $user->id)->get();
        } else {
            $classes = \App\Models\SchoolClass::all();
        }
        
        // Define assessment levels
        $khmerLevels = ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2'];
        $mathLevels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'];
        
        // Calculate performance by level
        $performanceByLevel = collect();
        $performanceByLevelAndSubject = [
            'khmer' => collect(),
            'math' => collect()
        ];
        
        // Get assessments for performance calculation
        $assessmentQuery = Assessment::query();
        
        // Apply same filters as students
        if ($user->role === 'teacher') {
            $assessmentQuery->whereHas('student', function($q) use ($user) {
                $q->where('teacher_id', $user->id);
            });
        } elseif ($user->role === 'mentor') {
            $assessmentQuery->whereIn('school_id', $user->schools->pluck('id'));
        }
        
        if ($request->filled('school_id')) {
            $assessmentQuery->where('school_id', $request->school_id);
        }
        
        if ($subject !== 'all') {
            $assessmentQuery->where('subject', $subject);
            $performanceByLevel = $assessmentQuery->select('level', DB::raw('count(*) as count'))
                ->groupBy('level')
                ->get();
        } else {
            // Get performance for both subjects
            $performanceByLevelAndSubject['khmer'] = Assessment::where('subject', 'khmer')
                ->when($user->role === 'teacher', function($q) use ($user) {
                    $q->whereHas('student', function($q) use ($user) {
                        $q->where('teacher_id', $user->id);
                    });
                })
                ->select('level', DB::raw('count(*) as count'))
                ->groupBy('level')
                ->get();
                
            $performanceByLevelAndSubject['math'] = Assessment::where('subject', 'math')
                ->when($user->role === 'teacher', function($q) use ($user) {
                    $q->whereHas('student', function($q) use ($user) {
                        $q->where('teacher_id', $user->id);
                    });
                })
                ->select('level', DB::raw('count(*) as count'))
                ->groupBy('level')
                ->get();
        }
        
        // Total students by subject
        $totalBySubject = [
            'khmer' => 0,
            'math' => 0
        ];

        return view('reports.student-performance-localized', compact(
            'students', 
            'schools', 
            'schoolId', 
            'classId', 
            'grade',
            'subject',
            'cycle',
            'classes',
            'khmerLevels',
            'mathLevels',
            'performanceByLevel',
            'performanceByLevelAndSubject',
            'totalBySubject'
        ));
    }

    public function schoolComparison(Request $request)
    {
        $schools = School::with(['students', 'assessments'])
            ->withCount('students')
            ->get();

        return view('reports.school-comparison-localized', compact('schools'));
    }

    public function mentoringImpact(Request $request)
    {
        $query = MentoringVisit::with(['school', 'user']);

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        if ($request->filled('mentor_id')) {
            $query->where('user_id', $request->mentor_id);
        }

        $visits = $query->orderBy('visit_date', 'desc')->paginate(20);
        $schools = School::all();
        $mentors = User::where('role', 'mentor')->get();

        return view('reports.mentoring-impact-localized', compact('visits', 'schools', 'mentors'));
    }

    public function progressTracking(Request $request)
    {
        $user = Auth::user();
        $query = Assessment::with(['student', 'school']);

        // Apply role-based filters
        if ($user->role === 'teacher') {
            $query->whereHas('student', function($q) use ($user) {
                $q->where('teacher_id', $user->id);
            });
        } elseif ($user->role === 'mentor') {
            $query->whereIn('school_id', $user->schools->pluck('id'));
        }

        if ($request->filled('cycle')) {
            $query->where('cycle', $request->cycle);
        }
        
        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }
        
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        $assessments = $query->paginate(20);
        
        // Get schools for filter dropdown
        $schools = School::all();
        
        // Get filter values for the view
        $cycle = $request->get('cycle', 'all');
        $subject = $request->get('subject', 'all');
        $schoolId = $request->get('school_id');
        
        // Calculate progress data
        $progressData = [];
        
        // Get students with assessments for progress tracking
        $studentsWithAssessments = Student::whereHas('assessments')
            ->when($user->role === 'teacher', function($q) use ($user) {
                $q->where('teacher_id', $user->id);
            })
            ->when($request->filled('school_id'), function($q) use ($request) {
                $q->where('school_id', $request->school_id);
            })
            ->with(['assessments' => function($q) use ($subject, $cycle) {
                if ($subject !== 'all') {
                    $q->where('subject', $subject);
                }
                if ($cycle !== 'all') {
                    $q->where('cycle', $cycle);
                }
                $q->orderBy('assessed_at', 'desc');
            }])
            ->get();
        
        // Calculate progress for each student
        foreach ($studentsWithAssessments as $student) {
            if ($student->assessments->count() >= 2) {
                $latest = $student->assessments->first();
                $baseline = $student->assessments->last(); // Get the earliest assessment as baseline
                
                // Map levels to numeric values for comparison
                $levelValues = [
                    'Beginner' => 1,
                    'Letter' => 2, '1-Digit' => 2,
                    'Word' => 3, '2-Digit' => 3,
                    'Paragraph' => 4, 'Subtraction' => 4,
                    'Story' => 5, 'Division' => 5,
                    'Comp. 1' => 6, 'Word Problem' => 6,
                    'Comp. 2' => 7
                ];
                
                $latestValue = $levelValues[$latest->level] ?? 0;
                $baselineValue = $levelValues[$baseline->level] ?? 0;
                
                // Calculate score change (assuming scores exist, otherwise use level values)
                $scoreChange = ($latest->score ?? 0) - ($baseline->score ?? 0);
                
                $progressData[] = [
                    'student' => $student, // Pass the entire student object
                    'baseline_level' => $baseline->level,
                    'latest_level' => $latest->level,
                    'latest_cycle' => $latest->cycle,
                    'level_improved' => $latestValue - $baselineValue,
                    'score_change' => $scoreChange,
                    'baseline_date' => $baseline->assessed_at,
                    'latest_date' => $latest->assessed_at
                ];
            }
        }
        
        // Calculate average score change
        $avgScoreChange = 0;
        if (count($progressData) > 0) {
            $avgScoreChange = collect($progressData)->avg('level_improved');
        }

        return view('reports.progress-tracking-localized', compact(
            'assessments', 
            'schools', 
            'cycle', 
            'subject', 
            'schoolId',
            'progressData',
            'avgScoreChange'
        ));
    }

    public function performanceCalculation(Request $request)
    {
        // Performance calculation logic
        $data = [];
        
        return view('reports.performance-calculation-localized', compact('data'));
    }

    public function myStudents(Request $request)
    {
        $user = Auth::user();
        
        // Build query
        $query = Student::where('teacher_id', $user->id)
            ->with(['assessments', 'school', 'schoolClass']);
        
        // Apply filters
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        
        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }
        
        $students = $query->paginate(20);
        
        // Get classes for this teacher
        $classes = \App\Models\SchoolClass::where('teacher_id', $user->id)->get();
        
        // Get filter values for the view
        $classId = $request->get('class_id');
        $grade = $request->get('class');
        $subject = $request->get('subject', 'all');
        $cycle = $request->get('cycle', 'all');
        
        // Get unique class/grade values from students
        $grades = Student::where('teacher_id', $user->id)
            ->distinct()
            ->pluck('class')
            ->filter()
            ->sort();
        
        // Get assessments for the teacher's students
        $assessments = Assessment::whereHas('student', function($q) use ($user) {
            $q->where('teacher_id', $user->id);
        })
        ->when($subject !== 'all', function($q) use ($subject) {
            $q->where('subject', $subject);
        })
        ->when($cycle !== 'all', function($q) use ($cycle) {
            $q->where('cycle', $cycle);
        })
        ->when($request->filled('class_id'), function($q) use ($request) {
            $q->whereHas('student', function($sq) use ($request) {
                $sq->where('class_id', $request->class_id);
            });
        })
        ->get();

        // Calculate performance by level
        $performanceByLevel = collect();
        if ($subject !== 'all') {
            $performanceByLevel = Assessment::whereHas('student', function($q) use ($user) {
                $q->where('teacher_id', $user->id);
            })
            ->where('subject', $subject)
            ->when($cycle !== 'all', function($q) use ($cycle) {
                $q->where('cycle', $cycle);
            })
            ->when($request->filled('class_id'), function($q) use ($request) {
                $q->whereHas('student', function($sq) use ($request) {
                    $sq->where('class_id', $request->class_id);
                });
            })
            ->select('level', DB::raw('count(*) as count'))
            ->groupBy('level')
            ->get();
        }

        return view('reports.my-students', compact(
            'students',
            'classes',
            'classId',
            'grade',
            'grades',
            'subject',
            'cycle',
            'assessments',
            'performanceByLevel'
        ));
    }

    public function classProgress(Request $request)
    {
        $user = Auth::user();
        $classes = [];
        $classId = $request->get('class_id');
        
        if ($user->role === 'teacher') {
            // Get classes for this teacher
            $classes = DB::table('classes')
                ->where('teacher_id', $user->id)
                ->get();
        }

        return view('reports.class-progress', compact('classes', 'classId'));
    }

    public function myMentoring(Request $request)
    {
        $user = Auth::user();
        $visits = MentoringVisit::where('user_id', $user->id)
            ->with(['school'])
            ->orderBy('visit_date', 'desc')
            ->paginate(20);

        return view('reports.my-mentoring', compact('visits'));
    }

    public function schoolVisits(Request $request)
    {
        $query = MentoringVisit::with(['school', 'user']);

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        $visits = $query->orderBy('visit_date', 'desc')->paginate(20);
        $schools = School::all();

        return view('reports.school-visits', compact('visits', 'schools'));
    }

    public function export(Request $request, $type)
    {
        // Export functionality based on report type
        return back()->with('info', 'Export functionality will be implemented soon.');
    }

    /**
     * Get available reports based on user role
     */
    private function getAvailableReports($user)
    {
        $reports = [];

        // Common reports for all roles
        $commonReports = [
            [
                'name' => 'Student Performance',
                'description' => 'Track individual student progress and performance',
                'route' => 'reports.student-performance',
                'icon' => 'chart-bar',
                'color' => 'blue',
            ],
            [
                'name' => 'Progress Tracking',
                'description' => 'Monitor assessment progress over time',
                'route' => 'reports.progress-tracking',
                'icon' => 'trending-up',
                'color' => 'green',
            ],
        ];

        // Add common reports
        $reports = array_merge($reports, $commonReports);

        // Role-specific reports
        if ($user->role === 'admin' || $user->role === 'coordinator') {
            $adminReports = [
                [
                    'name' => 'School Comparison',
                    'description' => 'Compare performance across schools',
                    'route' => 'reports.school-comparison',
                    'icon' => 'office-building',
                    'color' => 'purple',
                ],
                [
                    'name' => 'Mentoring Impact',
                    'description' => 'Analyze the impact of mentoring visits',
                    'route' => 'reports.mentoring-impact',
                    'icon' => 'user-group',
                    'color' => 'indigo',
                ],
                [
                    'name' => 'Performance Calculation',
                    'description' => 'Calculate overall performance metrics',
                    'route' => 'reports.performance-calculation',
                    'icon' => 'calculator',
                    'color' => 'yellow',
                ],
            ];
            $reports = array_merge($reports, $adminReports);
        }

        if ($user->role === 'teacher') {
            $teacherReports = [
                [
                    'name' => 'My Students',
                    'description' => 'View your students and their progress',
                    'route' => 'reports.my-students',
                    'icon' => 'users',
                    'color' => 'pink',
                ],
                [
                    'name' => 'Class Progress',
                    'description' => 'Track progress by class',
                    'route' => 'reports.class-progress',
                    'icon' => 'academic-cap',
                    'color' => 'teal',
                ],
            ];
            $reports = array_merge($reports, $teacherReports);
        }

        if ($user->role === 'mentor') {
            $mentorReports = [
                [
                    'name' => 'My Mentoring',
                    'description' => 'View your mentoring visits and feedback',
                    'route' => 'reports.my-mentoring',
                    'icon' => 'clipboard-list',
                    'color' => 'orange',
                ],
                [
                    'name' => 'School Visits',
                    'description' => 'Track visits to assigned schools',
                    'route' => 'reports.school-visits',
                    'icon' => 'location-marker',
                    'color' => 'red',
                ],
            ];
            $reports = array_merge($reports, $mentorReports);
        }

        return $reports;
    }
}