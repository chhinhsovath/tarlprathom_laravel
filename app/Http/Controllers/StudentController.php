<?php

namespace App\Http\Controllers;

use App\Exports\StudentsExport;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
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

        return view('students.create', compact('schools', 'classes'));
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

            // Verify the class belongs to this teacher
            if (isset($validated['class_id'])) {
                $class = SchoolClass::find($validated['class_id']);
                if (! $class || $class->teacher_id !== $user->id) {
                    abort(403, 'Invalid class selection.');
                }
            }
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $path = $photo->store('students/photos', 'public');
            $validated['photo'] = $path;
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
}
