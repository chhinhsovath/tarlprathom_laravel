<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use App\Models\School;
use App\Exports\StudentsExport;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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

        $query = Student::with('school');

        // Filter by school for teachers
        if ($request->user()->isTeacher()) {
            $query->where('school_id', $request->user()->school_id);
        }

        // Add search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Add school filter
        if ($request->has('school_id')) {
            $query->where('school_id', $request->get('school_id'));
        }

        // Add grade filter
        if ($request->has('grade') && $request->get('grade') !== '') {
            $query->where('grade', $request->get('grade'));
        }

        // Add gender filter
        if ($request->has('gender') && $request->get('gender') !== '') {
            $query->where('gender', $request->get('gender'));
        }

        $students = $query->orderBy('name')->paginate(20);

        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Student::class);

        // Get schools for dropdown
        $schools = School::orderBy('school_name')->get();

        // If teacher, only show their school
        if (auth()->user()->isTeacher()) {
            $schools = School::where('id', auth()->user()->school_id)->get();
        }

        return view('students.create', compact('schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        $validated = $request->validated();

        // If teacher, ensure they can only add students to their own school
        if ($request->user()->isTeacher()) {
            $validated['school_id'] = $request->user()->school_id;
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

        // Get schools for dropdown
        $schools = School::orderBy('school_name')->get();

        // If teacher, only show their school
        if (auth()->user()->isTeacher()) {
            $schools = School::where('id', auth()->user()->school_id)->get();
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
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $this->authorize('delete', $student);

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

        return Excel::download(new StudentsExport($request), 'students_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}