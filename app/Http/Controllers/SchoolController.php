<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use App\Traits\Sortable;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    use Sortable;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }
        $query = School::withCount(['users', 'students']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('school_name', 'like', "%{$search}%")
                    ->orWhere('school_code', 'like', "%{$search}%")
                    ->orWhere('province', 'like', "%{$search}%")
                    ->orWhere('district', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortData = $this->applySorting($query, $request, ['school_name', 'school_code', 'province', 'district', 'created_at'], 'school_name');

        $schools = $query->paginate(20)->withQueryString();

        return view('schools.index', array_merge(compact('schools'), $sortData));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }

        return view('schools.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }
        $validated = $request->validate([
            'school_name' => ['required', 'string', 'max:255'],
            'school_code' => ['required', 'string', 'max:255', 'unique:schools'],
            'province' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'cluster' => ['nullable', 'string', 'max:255'],
        ]);

        School::create($validated);

        return redirect()->route('schools.index')
            ->with('success', __('School created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }
        $school->loadCount(['users', 'students']);
        $teachers = $school->users()->where('role', 'teacher')->get();
        $mentors = $school->users()->where('role', 'mentor')->get();
        $recentStudents = $school->students()->latest()->limit(10)->get();

        return view('schools.show', compact('school', 'teachers', 'mentors', 'recentStudents'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }

        return view('schools.edit', compact('school'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }
        $validated = $request->validate([
            'school_name' => ['required', 'string', 'max:255'],
            'school_code' => ['required', 'string', 'max:255', 'unique:schools,school_code,'.$school->id],
            'province' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'cluster' => ['nullable', 'string', 'max:255'],
        ]);

        $school->update($validated);

        return redirect()->route('schools.index')
            ->with('success', __('School updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }
        // Check if school has associated users or students
        if ($school->users()->exists() || $school->students()->exists()) {
            return redirect()->route('schools.index')
                ->with('error', __('Cannot delete school with associated users or students.'));
        }

        $school->delete();

        return redirect()->route('schools.index')
            ->with('success', __('School deleted successfully.'));
    }
}
