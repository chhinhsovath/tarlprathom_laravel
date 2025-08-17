<?php

namespace App\Http\Controllers;

use App\Exports\StudentsExport;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Assessment;
use App\Models\AssessmentHistory;
use App\Models\PilotSchool;
use App\Models\School;
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

        $query = Student::with(['pilotSchool', 'teacher'])
            ->withCount('assessments');
        $user = $request->user();

        // Filter based on user role and accessible schools
        $accessibleSchoolIds = $user->getAccessibleSchoolIds();

        if (! $user->isAdmin()) {
            if (empty($accessibleSchoolIds)) {
                // If no schools are accessible, return no results
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('pilot_school_id', $accessibleSchoolIds);
            }
        }

        // Additional filter for teachers to see only their students
        if ($user->isTeacher()) {
            $query->where('teacher_id', $user->id);
        }

        // Add search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Add school filter (for admins and mentors with access)
        if ($request->filled('school_id')) {
            $schoolId = $request->get('school_id');
            if ($user->isAdmin() || $user->canAccessSchool($schoolId)) {
                $query->where('pilot_school_id', $schoolId);
            }
        }

        // Add grade/class filter
        if ($request->filled('grade')) {
            $query->where('grade', $request->get('grade'));
        }
        if ($request->filled('class')) {
            $query->where('class', $request->get('class'));
        }

        // Add gender filter
        if ($request->filled('gender')) {
            $query->where('gender', $request->get('gender'));
        }

        // Add sorting
        $sortField = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');

        // Validate sort field
        $allowedSorts = ['name', 'class', 'gender', 'created_at'];
        if (! in_array($sortField, $allowedSorts)) {
            $sortField = 'name';
        }

        // Validate sort order
        if (! in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $students = $query->orderBy($sortField, $sortOrder)->paginate(20)->withQueryString();

        // Get schools for filter dropdown (for admins and mentors)
        if ($user->isAdmin()) {
            $schools = PilotSchool::orderBy('school_name')->get();
        } elseif ($user->isMentor()) {
            $schools = PilotSchool::whereIn('id', $accessibleSchoolIds)->orderBy('school_name')->get();
        } else {
            $schools = collect();
        }

        return view('students.index', compact('students', 'schools', 'sortField', 'sortOrder'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Student::class);
        $user = auth()->user();

        // Get schools for dropdown based on access
        $accessibleSchoolIds = $user->getAccessibleSchoolIds();

        if (! empty($accessibleSchoolIds)) {
            $schools = PilotSchool::whereIn('id', $accessibleSchoolIds)->orderBy('school_name')->get();
        } else {
            $schools = collect();
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

        // Ensure user can only add students to accessible schools
        if (! $user->isAdmin() && ! $user->canAccessSchool($validated['school_id'])) {
            return back()->withErrors(['school_id' => 'You do not have access to this school.']);
        }

        // If teacher, ensure they assign students to themselves
        if ($user->isTeacher()) {
            $validated['teacher_id'] = $user->id;
        }

        // Verify teacher belongs to the selected school if provided
        if (isset($validated['teacher_id']) && $validated['teacher_id']) {
            $teacher = \App\Models\User::find($validated['teacher_id']);
            if (! $teacher || $teacher->pilot_school_id != $validated['school_id']) {
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

        // Get schools for dropdown based on access
        $accessibleSchoolIds = $user->getAccessibleSchoolIds();

        if (! empty($accessibleSchoolIds)) {
            $schools = PilotSchool::whereIn('id', $accessibleSchoolIds)->orderBy('school_name')->get();
        } else {
            $schools = collect();
        }

        return view('students.edit', compact('student', 'schools'));
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
        if ($user->isTeacher() && $student->pilot_school_id !== $user->pilot_school_id) {
            abort(403);
        }

        // Load student with school and teacher
        $student->load(['school', 'teacher']);

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

        $schools = PilotSchool::orderBy('school_name')->pluck('school_name', 'id');
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
                    if (! $teacher || $teacher->pilot_school_id != $studentData['school_id']) {
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
