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

        $query = Student::with(['pilotSchool.assignedMentors', 'teacher'])
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

        // Add pilot school filter (for admins and mentors with access)
        if ($request->filled('pilot_school_id')) {
            $schoolId = $request->get('pilot_school_id');
            if ($user->isAdmin() || $user->canAccessSchool($schoolId)) {
                $query->where('pilot_school_id', $schoolId);
            }
        }

        // Add grade/class filter (database column is 'class')
        if ($request->filled('grade')) {
            $query->where('class', $request->get('grade'));  // 'grade' param maps to 'class' column
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

        // Use mobile-optimized view
        return view('students.index-mobile', compact('students', 'schools', 'sortField', 'sortOrder'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Student::class);
        $user = auth()->user();

        // For teachers, only show their assigned school
        if ($user->isTeacher() && $user->school_id) {
            $schools = PilotSchool::where('id', $user->school_id)->get();
        } else {
            // For admins and mentors, get schools based on access
            $accessibleSchoolIds = $user->getAccessibleSchoolIds();

            if (! empty($accessibleSchoolIds)) {
                $schools = PilotSchool::whereIn('id', $accessibleSchoolIds)->orderBy('school_name')->get();
            } else {
                $schools = collect();
            }
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

        // For teachers, auto-assign their school and themselves
        if ($user->isTeacher()) {
            $validated['school_id'] = $user->school_id;
            $validated['pilot_school_id'] = $user->pilot_school_id;
            $validated['teacher_id'] = $user->id;
        } else {
            // For non-teachers, ensure they can only add students to accessible pilot schools
            if (! $user->isAdmin() && ! $user->canAccessSchool($validated['pilot_school_id'])) {
                return back()->withErrors(['pilot_school_id' => 'You do not have access to this pilot school.']);
            }

            // Set school_id to match pilot_school_id if not provided
            if (!isset($validated['school_id']) && isset($validated['pilot_school_id'])) {
                $validated['school_id'] = $validated['pilot_school_id'];
            }

            // Verify teacher belongs to the selected school if provided
            if (isset($validated['teacher_id']) && $validated['teacher_id']) {
                $teacher = \App\Models\User::find($validated['teacher_id']);
                if (! $teacher || $teacher->pilot_school_id != $validated['pilot_school_id']) {
                    return back()->withErrors(['teacher_id' => 'The selected teacher does not belong to the selected pilot school.']);
                }
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
        if ($user->isTeacher() && $student->school_id !== $user->school_id) {
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
     * Show bulk add form for manual entry
     */
    public function bulkAddForm()
    {
        $this->authorize('create', Student::class);
        
        // Use mobile-optimized view
        return view('students.bulk-add-mobile');
    }
    
    /**
     * Process bulk add of students from manual form
     */
    public function bulkAdd(Request $request)
    {
        $this->authorize('create', Student::class);
        
        $user = auth()->user();
        $successCount = 0;
        $errors = [];
        
        // Filter out empty entries
        $students = collect($request->students)->filter(function ($student) {
            return !empty($student['name']);
        });
        
        if ($students->isEmpty()) {
            return back()->withErrors(['students' => 'Please add at least one student.']);
        }
        
        foreach ($students as $index => $studentData) {
            try {
                // Validate individual student data
                $validator = \Validator::make($studentData, [
                    'name' => 'required|string|max:255',
                    'grade' => 'required|in:4,5',
                    'gender' => 'required|in:male,female',
                    'age' => 'required|integer|min:3|max:18',
                ]);
                
                if ($validator->fails()) {
                    $errors[] = "Row " . ($index + 1) . ": " . implode(', ', $validator->errors()->all());
                    continue;
                }
                
                // For teachers, auto-assign their school
                if ($user->isTeacher()) {
                    $studentData['school_id'] = $user->school_id;
                    $studentData['pilot_school_id'] = $user->pilot_school_id;
                    $studentData['teacher_id'] = $user->id;
                } else {
                    // For non-teachers, use the selected school
                    $studentData['school_id'] = $request->school_id;
                    $studentData['pilot_school_id'] = $request->school_id;
                }
                
                // Map grade to class column
                $studentData['class'] = $studentData['grade'];
                unset($studentData['grade']);
                
                Student::create($studentData);
                $successCount++;
                
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 1) . ": Failed to save student";
            }
        }
        
        if ($successCount > 0) {
            $message = "Successfully added {$successCount} student(s).";
            if (!empty($errors)) {
                return redirect()->route('students.index')
                    ->with('success', $message)
                    ->withErrors($errors);
            }
            return redirect()->route('students.index')->with('success', $message);
        }
        
        return back()->withErrors($errors)->withInput();
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
