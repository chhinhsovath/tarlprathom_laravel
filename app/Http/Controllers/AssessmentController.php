<?php

namespace App\Http\Controllers;

use App\Exports\AssessmentsExport;
use App\Http\Requests\StoreAssessmentRequest;
use App\Models\Assessment;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentAssessmentEligibility;
use App\Traits\Sortable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AssessmentController extends Controller
{
    use AuthorizesRequests, Sortable;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if user can view assessments
        if (! in_array($request->user()->role, ['admin', 'mentor', 'teacher', 'viewer'])) {
            abort(403);
        }

        // If request is AJAX, return existing assessments for data entry
        if ($request->ajax()) {
            $query = Assessment::where('subject', $request->get('subject'))
                ->where('cycle', $request->get('cycle'));

            // Filter by school for teachers
            if ($request->user()->isTeacher()) {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->where('school_id', $request->user()->school_id);
                });
            }

            $assessments = $query->get(['student_id', 'level']);

            return response()->json([
                'assessments' => $assessments,
            ]);
        }

        $query = Assessment::with(['student', 'student.school']);

        // Apply access restrictions based on user role
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

        // Search by student name
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by school (only if user has access)
        if ($request->filled('school_id')) {
            $schoolId = $request->get('school_id');
            if ($user->isAdmin() || $user->canAccessSchool($schoolId)) {
                $query->whereHas('student', function ($q) use ($schoolId) {
                    $q->where('school_id', $schoolId);
                });
            }
        }

        // Filter by grade
        if ($request->filled('grade')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('grade', $request->get('grade'));
            });
        }

        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->get('student_id'));
        }

        // Filter by subject
        if ($request->filled('subject')) {
            $query->where('subject', $request->get('subject'));
        }

        // Filter by cycle
        if ($request->filled('cycle')) {
            $query->where('cycle', $request->get('cycle'));
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->where('assessed_at', '>=', $request->get('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->where('assessed_at', '<=', $request->get('to_date'));
        }

        // Apply sorting
        $sortData = $this->applySorting(
            $query,
            $request,
            ['student_id', 'subject', 'cycle', 'level', 'assessed_at'],
            'assessed_at',
            'desc'
        );

        $assessments = $query->paginate(20)->withQueryString();

        // Get schools for filter dropdown (based on access)
        $schools = [];
        if ($request->user()->isAdmin()) {
            $schools = School::orderBy('school_name')->get();
        } elseif ($request->user()->isMentor()) {
            if (empty($accessibleSchoolIds)) {
                $schools = collect([]);  // Empty collection if no schools accessible
            } else {
                $schools = School::whereIn('id', $accessibleSchoolIds)->orderBy('school_name')->get();
            }
        }

        return view('assessments.index', compact('assessments', 'schools') + $sortData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Check authorization
        if (! in_array($request->user()->role, ['admin', 'teacher', 'mentor'])) {
            abort(403);
        }

        // Get students based on access
        $user = $request->user();
        $accessibleSchoolIds = $user->getAccessibleSchoolIds();
        $studentsQuery = Student::with('school');

        // Apply access restrictions
        if (! $user->isAdmin()) {
            if (empty($accessibleSchoolIds)) {
                // If no schools are accessible, return no results
                $studentsQuery->whereRaw('1 = 0');
            } else {
                $studentsQuery->whereIn('school_id', $accessibleSchoolIds);
            }
        }

        // Get the subject and cycle
        $subject = $request->get('subject', 'khmer');
        $cycle = $request->get('cycle', 'baseline');

        // For Midline or Endline cycles, filter students based on eligibility
        if (in_array($cycle, ['midline', 'endline'])) {
            // First check for manually selected students
            $eligibleStudentIds = StudentAssessmentEligibility::where('assessment_type', $cycle)
                ->where('is_eligible', true)
                ->pluck('student_id');

            if ($eligibleStudentIds->isNotEmpty()) {
                // Use manually selected students
                $studentsQuery->whereIn('id', $eligibleStudentIds);
            } else {
                // Fall back to baseline-based filtering
                if ($subject === 'khmer') {
                    // Get student IDs who scored Beginner through Story (not Comp. 1 or Comp. 2) in baseline
                    $eligibleStudentsQuery = Assessment::where('subject', 'khmer')
                        ->where('cycle', 'baseline')
                        ->whereIn('level', ['Beginner', 'Reader', 'Word', 'Paragraph', 'Story']);
                } else {
                    // For Math: Get student IDs who scored Beginner through Subtraction (not Division or Word Problem) in baseline
                    $eligibleStudentsQuery = Assessment::where('subject', 'math')
                        ->where('cycle', 'baseline')
                        ->whereIn('level', ['Beginner', '1-Digit', '2-Digit', 'Subtraction']);
                }

                // If teacher, only get eligible students from their school
                if ($user->isTeacher()) {
                    $eligibleStudentsQuery->whereHas('student', function ($q) use ($user) {
                        $q->where('school_id', $user->school_id);
                    });
                }

                $eligibleStudentIds = $eligibleStudentsQuery->pluck('student_id');
                $studentsQuery->whereIn('id', $eligibleStudentIds);
            }
        }

        $students = $studentsQuery->orderBy('name')->get();

        // Get existing assessments for these students
        $studentIds = $students->pluck('id');
        $existingAssessments = Assessment::whereIn('student_id', $studentIds)
            ->where('subject', $subject)
            ->where('cycle', $cycle)
            ->get()
            ->keyBy('student_id');

        // Add assessment status to each student
        $students->transform(function ($student) use ($existingAssessments) {
            $existingAssessment = $existingAssessments->get($student->id);
            $student->has_assessment = $existingAssessment !== null;
            $student->is_assessment_locked = $existingAssessment && (\Schema::hasColumn('assessments', 'is_locked') ? ($existingAssessment->is_locked ?? false) : false);
            $student->assessment_level = $existingAssessment ? $existingAssessment->level : null;

            return $student;
        });

        return view('assessments.create', compact('students', 'subject', 'cycle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssessmentRequest $request)
    {
        $validated = $request->validated();

        // Verify user can access this student
        $user = $request->user();
        $student = Student::findOrFail($validated['student_id']);

        if (! $user->isAdmin() && ! $user->canAccessStudent($student->id)) {
            abort(403, 'You can only assess students from your accessible schools.');
        }

        // Check if assessment period is active for the school (only for non-admins)
        if (! $request->user()->isAdmin()) {
            $student = Student::findOrFail($validated['student_id']);
            $school = $student->school;
            if (! $school->isAssessmentPeriodActive($validated['cycle'])) {
                $status = $school->getAssessmentPeriodStatus($validated['cycle']);
                $message = match ($status) {
                    'not_set' => 'Assessment dates have not been set for this school.',
                    'upcoming' => 'Assessment period has not started yet for this school.',
                    'expired' => 'Assessment period has ended for this school.',
                    default => 'Assessment is not available at this time.'
                };

                return redirect()->back()->with('error', $message);
            }
        }

        $assessment = Assessment::create($validated);

        return redirect()->route('assessments.index')
            ->with('success', 'Assessment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Assessment $assessment)
    {
        // Check if user can view this assessment
        $user = auth()->user();
        $assessment->load(['student']);

        if ($user->isAdmin() || $user->isViewer()) {
            // These roles can view all assessments
        } elseif ($user->canAccessStudent($assessment->student_id)) {
            // User can view if they have access to the student
        } else {
            abort(403);
        }

        $assessment->load(['student', 'student.school']);

        return view('assessments.show', compact('assessment'));
    }

    /**
     * Public assessment results page
     */
    public function publicResults()
    {
        return view('public.assessment-results');
    }

    /**
     * Get chart data via AJAX
     */
    public function getChartData(Request $request)
    {
        $subject = $request->get('subject', 'khmer');

        if ($subject === 'khmer') {
            $levels = ['Beginner', 'Reader', 'Word', 'Paragraph', 'Story'];
            $labels = [__('Beginner'), __('Letter'), __('Word'), __('Paragraph'), __('Story')];
        } else {
            $levels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division'];
            $labels = [__('Beginner'), __('1-Digit'), __('2-Digit'), __('Subtraction'), __('Division')];
        }

        // Get assessment counts by level (filtered by access)
        $user = auth()->user();
        $query = Assessment::select('level', DB::raw('count(*) as count'))
            ->where('subject', $subject)
            ->whereNotNull('level');

        // Apply access restrictions
        if ($user && ! $user->isAdmin()) {
            // User is authenticated but not admin - apply school restrictions
            $accessibleSchoolIds = $user->getAccessibleSchoolIds();
            if (! empty($accessibleSchoolIds)) {
                $query->whereHas('student', function ($q) use ($accessibleSchoolIds) {
                    $q->whereIn('school_id', $accessibleSchoolIds);
                });
            } else {
                // User has no accessible schools - return no results
                $query->whereRaw('1 = 0');
            }
        } elseif (! $user) {
            // No user authenticated - this is a public view
            // You can optionally restrict public view to certain schools or show all
            // For now, showing all data for public view
        }

        $levelCounts = $query->groupBy('level')
            ->pluck('count', 'level')
            ->toArray();

        // Prepare chart data
        $data = [];
        foreach ($levels as $level) {
            $data[] = $levelCounts[$level] ?? 0;
        }

        $chartData = [
            'labels' => $labels,
            'datasets' => [[
                'label' => __($subject === 'khmer' ? 'Khmer Assessment Results' : 'Math Assessment Results'),
                'data' => $data,
                'backgroundColor' => ['#d32f2f', '#f57c00', '#fbc02d', '#388e3c', '#2e7d32'],
                'borderWidth' => 1,
            ]],
        ];

        // Get cycle data (filtered by access)
        $cycleQuery = Assessment::select('cycle', DB::raw('count(DISTINCT student_id) as count'))
            ->where('subject', $subject);

        // Apply same access restrictions
        if ($user && ! $user->isAdmin()) {
            // User is authenticated but not admin - apply school restrictions
            $accessibleSchoolIds = $user->getAccessibleSchoolIds();
            if (! empty($accessibleSchoolIds)) {
                $cycleQuery->whereHas('student', function ($q) use ($accessibleSchoolIds) {
                    $q->whereIn('school_id', $accessibleSchoolIds);
                });
            } else {
                // User has no accessible schools - return no results
                $cycleQuery->whereRaw('1 = 0');
            }
        } elseif (! $user) {
            // No user authenticated - this is a public view
            // For now, showing all data for public view
        }

        $cycleData = $cycleQuery->groupBy('cycle')
            ->pluck('count', 'cycle')
            ->toArray();

        $totalQuery = Assessment::where('subject', $subject);
        if ($user && ! $user->isAdmin()) {
            // User is authenticated but not admin - apply school restrictions
            $accessibleSchoolIds = $user->getAccessibleSchoolIds();
            if (! empty($accessibleSchoolIds)) {
                $totalQuery->whereHas('student', function ($q) use ($accessibleSchoolIds) {
                    $q->whereIn('school_id', $accessibleSchoolIds);
                });
            } else {
                // User has no accessible schools - return no results
                $totalQuery->whereRaw('1 = 0');
            }
        } elseif (! $user) {
            // No user authenticated - this is a public view
            // For now, showing all data for public view
        }

        $cycleData['total'] = $totalQuery->distinct('student_id')
            ->count('student_id');

        return response()->json([
            'chartData' => $chartData,
            'cycleData' => $cycleData,
            'subject' => $subject,
        ]);
    }

    /**
     * Save individual student assessment via AJAX
     */
    public function saveStudentAssessment(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject' => 'required|in:khmer,math',
            'cycle' => 'required|in:baseline,midline,endline',
            'level' => 'required|string',
            'gender' => 'required|in:male,female',
        ]);

        // Validate level based on subject
        if ($validated['subject'] === 'khmer') {
            if (! in_array($validated['level'], ['Beginner', 'Reader', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2'])) {
                return response()->json(['success' => false, 'message' => 'Invalid level for Khmer'], 422);
            }
        } else {
            if (! in_array($validated['level'], ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'])) {
                return response()->json(['success' => false, 'message' => 'Invalid level for Math'], 422);
            }
        }

        // Update student gender if needed
        $student = Student::find($validated['student_id']);
        if ($student->gender !== $validated['gender']) {
            $student->gender = $validated['gender'];
            $student->save();
        }

        // Check if assessment period is active for the school (only for non-admins)
        if (! auth()->user()->isAdmin()) {
            $school = $student->school;
            if (! $school->isAssessmentPeriodActive($validated['cycle'])) {
                $status = $school->getAssessmentPeriodStatus($validated['cycle']);
                $message = match ($status) {
                    'not_set' => 'Assessment dates have not been set for this school.',
                    'upcoming' => 'Assessment period has not started yet for this school.',
                    'expired' => 'Assessment period has ended for this school.',
                    default => 'Assessment is not available at this time.'
                };

                return response()->json(['success' => false, 'message' => $message], 422);
            }
        }

        // Check if assessment already exists
        $assessment = Assessment::where('student_id', $validated['student_id'])
            ->where('subject', $validated['subject'])
            ->where('cycle', $validated['cycle'])
            ->first();

        if ($assessment) {
            // Update existing
            $assessment->level = $validated['level'];
            $assessment->assessed_at = now();
            $assessment->save();
        } else {
            // Create new
            Assessment::create([
                'student_id' => $validated['student_id'],
                'subject' => $validated['subject'],
                'cycle' => $validated['cycle'],
                'level' => $validated['level'],
                'assessed_at' => now(),
                'score' => 0, // We're not tracking scores in this interface
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Assessment saved successfully',
        ]);
    }

    /**
     * Submit all assessments for a class
     */
    public function submitAllAssessments(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|in:khmer,math',
            'cycle' => 'required|in:baseline,midline,endline',
            'submitted_count' => 'required|integer|min:0',
        ]);

        // Log the submission (you might want to create a submission log table)
        \Log::info('Teacher submitted assessments', [
            'teacher_id' => auth()->id(),
            'subject' => $validated['subject'],
            'cycle' => $validated['cycle'],
            'count' => $validated['submitted_count'],
            'submitted_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'All assessments submitted successfully',
            'redirect' => route('dashboard'),
        ]);
    }

    /**
     * Export assessments to Excel
     */
    public function export(Request $request)
    {
        // Check if user can view assessments
        if (! in_array($request->user()->role, ['admin', 'mentor', 'teacher', 'viewer'])) {
            abort(403);
        }

        return Excel::download(new AssessmentsExport($request), 'assessments_'.date('Y-m-d_H-i-s').'.xlsx');
    }

    /**
     * Show the form for selecting students for midline/endline assessments.
     */
    public function selectStudents(Request $request)
    {
        $user = $request->user();

        // Check if user can select students (admin or teacher)
        if (! in_array($user->role, ['admin', 'teacher'])) {
            abort(403);
        }

        $assessmentType = $request->get('type', 'midline');
        if (! in_array($assessmentType, ['midline', 'endline'])) {
            $assessmentType = 'midline';
        }

        // Get students based on user role
        $query = Student::with(['school', 'assessmentEligibility', 'assessments' => function ($q) {
            $q->where('cycle', 'baseline')->latest('assessed_at');
        }]);

        if ($user->isTeacher()) {
            // Teachers can only select students from their school
            $query->where('school_id', $user->school_id);
        } elseif ($user->isAdmin()) {
            // Admins can see all students, optionally filtered by school
            if ($request->filled('school_id')) {
                $query->where('school_id', $request->get('school_id'));
            }
        }

        // Add search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by grade
        if ($request->filled('grade')) {
            $query->where('grade', $request->get('grade'));
        }

        // Filter by baseline Khmer level
        if ($request->filled('baseline_khmer')) {
            if ($request->get('baseline_khmer') === 'not_assessed') {
                $query->whereDoesntHave('assessments', function ($q) {
                    $q->where('cycle', 'baseline')->where('subject', 'khmer');
                });
            } else {
                $query->whereHas('assessments', function ($q) use ($request) {
                    $q->where('cycle', 'baseline')
                        ->where('subject', 'khmer')
                        ->where('level', $request->get('baseline_khmer'));
                });
            }
        }

        // Filter by baseline Math level
        if ($request->filled('baseline_math')) {
            if ($request->get('baseline_math') === 'not_assessed') {
                $query->whereDoesntHave('assessments', function ($q) {
                    $q->where('cycle', 'baseline')->where('subject', 'math');
                });
            } else {
                $query->whereHas('assessments', function ($q) use ($request) {
                    $q->where('cycle', 'baseline')
                        ->where('subject', 'math')
                        ->where('level', $request->get('baseline_math'));
                });
            }
        }

        $students = $query->orderBy('name')->paginate(50)->withQueryString();

        // Get current eligibility status for each student
        $eligibleStudentIds = StudentAssessmentEligibility::where('assessment_type', $assessmentType)
            ->where('is_eligible', true)
            ->pluck('student_id')
            ->toArray();

        $schools = School::orderBy('school_name')->get();

        return view('assessments.select-students', compact(
            'students',
            'assessmentType',
            'eligibleStudentIds',
            'schools'
        ));
    }

    /**
     * Update the students selected for midline/endline assessments.
     */
    public function updateSelectedStudents(Request $request)
    {
        $user = $request->user();

        // Check if user can select students (admin or teacher)
        if (! in_array($user->role, ['admin', 'teacher'])) {
            abort(403);
        }

        $request->validate([
            'assessment_type' => 'required|in:midline,endline',
            'students' => 'array',
            'students.*' => 'exists:students,id',
        ]);

        $assessmentType = $request->get('assessment_type');
        $selectedStudentIds = $request->get('students', []);

        // Get students the user has access to
        $accessibleStudentIds = Student::when($user->isTeacher(), function ($q) use ($user) {
            $q->where('school_id', $user->school_id);
        })->pluck('id')->toArray();

        // Clear existing eligibility for these students and assessment type
        StudentAssessmentEligibility::whereIn('student_id', $accessibleStudentIds)
            ->where('assessment_type', $assessmentType)
            ->delete();

        // Add new eligibility records
        $eligibilityData = [];
        foreach ($selectedStudentIds as $studentId) {
            // Ensure the user has access to this student
            if (in_array($studentId, $accessibleStudentIds)) {
                $eligibilityData[] = [
                    'student_id' => $studentId,
                    'assessment_type' => $assessmentType,
                    'selected_by' => $user->id,
                    'is_eligible' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (! empty($eligibilityData)) {
            StudentAssessmentEligibility::insert($eligibilityData);
        }

        return redirect()->route('assessments.select-students', ['type' => $assessmentType])
            ->with('success', __('Students selected for :type assessment successfully.', ['type' => ucfirst($assessmentType)]));
    }
}
