<?php

namespace App\Http\Controllers;

use App\Exports\AssessmentsExport;
use App\Http\Requests\StoreAssessmentRequest;
use App\Models\Assessment;
use App\Models\Student;
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

        // Filter by school for teachers
        if ($request->user()->isTeacher()) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('school_id', $request->user()->school_id);
            });
        }

        // Search by student name
        if ($request->has('search') && $request->get('search') !== '') {
            $search = $request->get('search');
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by student
        if ($request->has('student_id')) {
            $query->where('student_id', $request->get('student_id'));
        }

        // Filter by subject
        if ($request->has('subject')) {
            $query->where('subject', $request->get('subject'));
        }

        // Filter by cycle
        if ($request->has('cycle')) {
            $query->where('cycle', $request->get('cycle'));
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->where('assessed_at', '>=', $request->get('from_date'));
        }
        if ($request->has('to_date')) {
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

        return view('assessments.index', compact('assessments') + $sortData);
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

        // Get students for the teacher's school/class
        $user = $request->user();
        $studentsQuery = Student::with('school');

        // If teacher, only show students from their school
        if ($user->isTeacher()) {
            $studentsQuery->where('school_id', $user->school_id);
        }

        // Get the subject and cycle
        $subject = $request->get('subject', 'khmer');
        $cycle = $request->get('cycle', 'baseline');

        // For Midline or Endline cycles, filter students based on baseline results
        if (in_array($cycle, ['midline', 'endline'])) {
            if ($subject === 'khmer') {
                // Get student IDs who scored Beginner through Story (not Comp. 1 or Comp. 2) in baseline
                $eligibleStudentsQuery = Assessment::where('subject', 'khmer')
                    ->where('cycle', 'baseline')
                    ->whereIn('level', ['Beginner', 'Letter Reader', 'Word Level', 'Paragraph Reader', 'Story Reader']);
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

        $students = $studentsQuery->orderBy('name')->get();

        return view('assessments.create', compact('students', 'subject', 'cycle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssessmentRequest $request)
    {
        $validated = $request->validated();

        // If teacher, verify the student belongs to their school
        if ($request->user()->isTeacher()) {
            $student = Student::findOrFail($validated['student_id']);
            if ($student->school_id !== $request->user()->school_id) {
                abort(403, 'You can only assess students from your own school.');
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

        if ($user->isAdmin() || $user->isViewer() || $user->isMentor()) {
            // These roles can view all assessments
        } elseif ($user->isTeacher() && $assessment->student->school_id === $user->school_id) {
            // Teachers can view assessments of students from their school
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
            $levels = ['Beginner', 'Letter Reader', 'Word Level', 'Paragraph Reader', 'Story Reader'];
            $labels = [__('Beginner'), __('Letter'), __('Word'), __('Paragraph'), __('Story')];
        } else {
            $levels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division'];
            $labels = [__('Beginner'), __('1-Digit'), __('2-Digit'), __('Subtraction'), __('Division')];
        }

        // Get assessment counts by level
        $levelCounts = Assessment::select('level', DB::raw('count(*) as count'))
            ->where('subject', $subject)
            ->whereNotNull('level')
            ->groupBy('level')
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

        // Get cycle data
        $cycleData = Assessment::select('cycle', DB::raw('count(DISTINCT student_id) as count'))
            ->where('subject', $subject)
            ->groupBy('cycle')
            ->pluck('count', 'cycle')
            ->toArray();

        $cycleData['total'] = Assessment::where('subject', $subject)
            ->distinct('student_id')
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
            if (! in_array($validated['level'], ['Beginner', 'Letter Reader', 'Word Level', 'Paragraph Reader', 'Story Reader', 'Comp. 1', 'Comp. 2'])) {
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
}
