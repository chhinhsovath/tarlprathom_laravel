<?php

namespace App\Http\Controllers;

use App\Exports\StudentsExport;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Assessment;
use App\Models\AssessmentHistory;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Student::class);

        $query = Student::with(['school', 'teacher', 'schoolClass']);
        $user = $request->user();

        // Filter based on user role
        if ($user->isTeacher()) {
            // Teachers can only see their own students
            $query->where('teacher_id', $user->id);
        } elseif (! $user->isAdmin()) {
            // Non-admin users can only see students from their school
            $query->where('school_id', $user->school_id);
        }

        // Add search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Add school filter (only for admins)
        if ($request->filled('school_id') && $user->isAdmin()) {
            $query->where('school_id', $request->get('school_id'));
        }

        // Add grade filter
        if ($request->filled('grade')) {
            $query->where('grade', $request->get('grade'));
        }

        // Add gender filter
        if ($request->filled('gender')) {
            $query->where('gender', $request->get('gender'));
        }

        // Add class filter
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->get('class_id'));
        }

        // Add sorting
        $sortField = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');

        // Validate sort field
        $allowedSorts = ['name', 'grade', 'gender', 'created_at'];
        if (! in_array($sortField, $allowedSorts)) {
            $sortField = 'name';
        }

        // Validate sort order
        if (! in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $students = $query->orderBy($sortField, $sortOrder)->paginate(20)->withQueryString();

        // Get schools for filter dropdown (only for admins)
        $schools = $user->isAdmin() ? School::orderBy('school_name')->get() : collect();

        // Get classes for filter dropdown
        $classes = SchoolClass::query()
            ->when($user->isTeacher(), function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            })
            ->when(! $user->isAdmin() && ! $user->isTeacher(), function ($q) use ($user) {
                $q->where('school_id', $user->school_id);
            })
            ->orderBy('grade_level')
            ->orderBy('name')
            ->get();

        return view('students.index', compact('students', 'schools', 'classes', 'sortField', 'sortOrder'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Student::class);
        $user = auth()->user();

        // Get schools for dropdown
        if ($user->isAdmin()) {
            $schools = School::orderBy('school_name')->get();
        } else {
            $schools = School::where('id', $user->school_id)->get();
        }

        return view('students.create', compact('schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();

        // If teacher, ensure they can only add students to their own school and assign to themselves
        if ($user->isTeacher()) {
            $validated['school_id'] = $user->school_id;
            $validated['teacher_id'] = $user->id;
        }

        // Verify teacher belongs to the selected school if provided
        if (isset($validated['teacher_id']) && $validated['teacher_id']) {
            $teacher = \App\Models\User::find($validated['teacher_id']);
            if (! $teacher || $teacher->school_id != $validated['school_id']) {
                return back()->withErrors(['teacher_id' => 'The selected teacher does not belong to the selected school.']);
            }
        }

        $student = Student::create($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $this->authorize('view', $student);

        // Load relationships
        $student->load(['school', 'assessments']);

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $this->authorize('update', $student);
        $user = auth()->user();

        // Get schools for dropdown
        if ($user->isAdmin()) {
            $schools = School::orderBy('school_name')->get();
        } else {
            $schools = School::where('id', $user->school_id)->get();
        }

        // Get classes for dropdown
        $classes = SchoolClass::query()
            ->when($user->isTeacher(), function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            })
            ->when(! $user->isAdmin() && ! $user->isTeacher(), function ($q) use ($user) {
                $q->where('school_id', $user->school_id);
            })
            ->orderBy('grade_level')
            ->orderBy('name')
            ->get();

        return view('students.edit', compact('student', 'schools', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $validated = $request->validated();

        // If teacher, ensure they can only keep students in their own school
        if ($request->user()->isTeacher()) {
            $validated['school_id'] = $request->user()->school_id;
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }

            $photo = $request->file('photo');
            $path = $photo->store('students/photos', 'public');
            $validated['photo'] = $path;
        }

        $student->update($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Show assessment history for a student
     */
    public function assessmentHistory(Student $student)
    {
        // Check authorization
        $user = auth()->user();
        if ($user->isTeacher() && $student->school_id !== $user->school_id) {
            abort(403);
        }

        // Load student with school and class
        $student->load(['school', 'schoolClass']);

        // Get all assessment histories for this student
        $histories = AssessmentHistory::where('student_id', $student->id)
            ->with('updatedBy')
            ->orderBy('assessed_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($history) {
                return $history->subject.'_'.$history->cycle;
            });

        // Get current assessments
        $currentAssessments = Assessment::where('student_id', $student->id)
            ->get()
            ->keyBy(function ($assessment) {
                return $assessment->subject.'_'.$assessment->cycle;
            });

        return view('students.assessment-history', compact('student', 'histories', 'currentAssessments'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $this->authorize('delete', $student);

        // Delete photo if exists
        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }

        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    /**
     * Export students to Excel
     */
    public function export(Request $request)
    {
        $this->authorize('viewAny', Student::class);

        return Excel::download(new StudentsExport($request), 'students_'.date('Y-m-d_H-i-s').'.xlsx');
    }

    /**
     * Show the bulk import form.
     */
    public function bulkImportForm()
    {
        $this->authorize('create', Student::class);

        $schools = School::orderBy('school_name')->pluck('school_name', 'id');
        $teachers = \App\Models\User::where('role', 'teacher')
            ->select('id', 'name', 'school_id')
            ->get();

        return view('students.bulk-import', compact('schools', 'teachers'));
    }

    /**
     * Download the student import template
     */
    public function downloadTemplate()
    {
        $this->authorize('create', Student::class);

        return Excel::download(new \App\Exports\StudentTemplateExport, 'student_import_template.xlsx');
    }

    /**
     * Process bulk import of students.
     */
    public function bulkImport(Request $request)
    {
        $this->authorize('create', Student::class);

        $request->validate([
            'students' => 'required|array',
            'students.*.name' => 'required|string|max:255',
            'students.*.age' => 'required|integer|min:3|max:18',
            'students.*.gender' => 'required|in:male,female',
            'students.*.grade' => 'required|integer|in:4,5',
            'students.*.school_id' => 'required|exists:schools,id',
            'students.*.teacher_id' => 'nullable|exists:users,id',
        ]);

        $imported = 0;
        $failed = 0;
        $user = auth()->user();

        foreach ($request->students as $studentData) {
            try {
                // Verify teacher belongs to the school if provided
                if (isset($studentData['teacher_id']) && $studentData['teacher_id']) {
                    $teacher = \App\Models\User::find($studentData['teacher_id']);
                    if (! $teacher || $teacher->school_id != $studentData['school_id']) {
                        $failed++;

                        continue;
                    }
                }

                // If teacher is importing, assign students to themselves
                if ($user->isTeacher() && ! isset($studentData['teacher_id'])) {
                    $studentData['teacher_id'] = $user->id;
                }

                Student::create([
                    'name' => $studentData['name'],
                    'age' => $studentData['age'],
                    'gender' => $studentData['gender'],
                    'grade' => $studentData['grade'],
                    'school_id' => $studentData['school_id'],
                    'teacher_id' => $studentData['teacher_id'] ?? null,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $failed++;
            }
        }

        $message = "Successfully imported {$imported} students.";
        if ($failed > 0) {
            $message .= " {$failed} students failed to import.";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'imported' => $imported,
            'failed' => $failed,
        ]);
    }
}
