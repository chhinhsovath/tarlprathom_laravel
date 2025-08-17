<?php

namespace App\Http\Controllers;

use App\Models\PilotSchool;
use App\Models\SchoolClass;
use App\Models\User;
use App\Traits\Sortable;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    use Sortable;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = SchoolClass::with(['school', 'teacher'])->withCount('students');

        // Filter based on user role
        if ($user->isTeacher()) {
            $query->where('teacher_id', $user->id);
        } elseif (! $user->isAdmin()) {
            $query->where('pilot_school_id', $user->pilot_school_id);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by school (admin only)
        if ($request->filled('school_id') && $user->isAdmin()) {
            $query->where('pilot_school_id', $request->get('school_id'));
        }

        // Filter by grade level
        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->get('grade_level'));
        }

        // Apply sorting
        $sortData = $this->applySorting(
            $query,
            $request,
            ['name', 'grade_level', 'created_at'],
            'grade_level',
            'asc'
        );

        $classes = $query->paginate(20)->withQueryString();
        $schools = $user->isAdmin() ? PilotSchool::orderBy('school_name')->get() : collect();

        return view('classes.index', compact('classes', 'schools') + $sortData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only admins can create classes
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }

        $schools = PilotSchool::orderBy('school_name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();

        return view('classes.create', compact('schools', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only admins can create classes
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'grade_level' => ['required', 'integer', 'in:4,5'],
            'pilot_school_id' => ['required', 'exists:pilot_schools,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'academic_year' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Map the field name for database
        if (isset($validated['pilot_school_id'])) {
            $validated['pilot_school_id'] = $validated['pilot_school_id'];
        }

        SchoolClass::create($validated);

        return redirect()->route('classes.index')
            ->with('success', __('Class created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolClass $class)
    {
        $class->load(['school', 'teacher', 'students']);

        // Check authorization
        $user = auth()->user();
        if (! $user->isAdmin() && $class->teacher_id !== $user->id && $class->pilot_school_id !== $user->pilot_school_id) {
            abort(403, __('Unauthorized action.'));
        }

        return view('classes.show', compact('class'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolClass $class)
    {
        // Only admins can edit classes
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }

        $schools = PilotSchool::orderBy('school_name')->get();
        $teachers = User::where('role', 'teacher')
            ->where('pilot_school_id', $class->pilot_school_id)
            ->orderBy('name')
            ->get();

        return view('classes.edit', compact('class', 'schools', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolClass $class)
    {
        // Only admins can update classes
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'grade_level' => ['required', 'integer', 'in:4,5'],
            'pilot_school_id' => ['required', 'exists:pilot_schools,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'academic_year' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $class->update($validated);

        return redirect()->route('classes.index')
            ->with('success', __('Class updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolClass $class)
    {
        // Only admins can delete classes
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }

        // Check if class has students
        if ($class->students()->exists()) {
            return redirect()->route('classes.index')
                ->with('error', __('Cannot delete class with associated students.'));
        }

        $class->delete();

        return redirect()->route('classes.index')
            ->with('success', __('Class deleted successfully.'));
    }
}
