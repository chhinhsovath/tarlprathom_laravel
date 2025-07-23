<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use App\Traits\Sortable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SchoolTemplateExport;

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

        $school->load(['users' => function ($query) {
            $query->where('role', 'teacher')->select('users.id', 'name', 'email');
        }]);

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
            // Assessment date validations
            'baseline_start_date' => ['nullable', 'date'],
            'baseline_end_date' => ['nullable', 'date', 'after_or_equal:baseline_start_date'],
            'midline_start_date' => ['nullable', 'date', 'after_or_equal:baseline_end_date'],
            'midline_end_date' => ['nullable', 'date', 'after_or_equal:midline_start_date'],
            'endline_start_date' => ['nullable', 'date', 'after_or_equal:midline_end_date'],
            'endline_end_date' => ['nullable', 'date', 'after_or_equal:endline_start_date'],
        ]);

        $school->update($validated);

        // Handle teachers update if provided
        if ($request->has('teachers')) {
            $teachersData = json_decode($request->input('teachers'), true);

            foreach ($teachersData as $teacherData) {
                if ($teacherData['action'] === 'remove') {
                    // Remove teacher from school (set school_id to null)
                    \App\Models\User::where('id', $teacherData['id'])
                        ->where('school_id', $school->id)
                        ->update(['school_id' => null]);
                } elseif ($teacherData['action'] === 'new') {
                    // Check if teacher exists or create new
                    $teacher = \App\Models\User::where('email', $teacherData['email'])->first();

                    if (! $teacher) {
                        // Create new teacher
                        $teacher = \App\Models\User::create([
                            'name' => $teacherData['name'],
                            'email' => $teacherData['email'],
                            'password' => bcrypt('password123'), // Default password
                            'role' => 'teacher',
                            'school_id' => $school->id,
                        ]);
                    } else {
                        // Assign existing teacher to this school
                        $teacher->update(['school_id' => $school->id]);
                    }
                } elseif ($teacherData['action'] === 'existing' && isset($teacherData['id'])) {
                    // Update existing teacher if needed
                    $teacher = \App\Models\User::find($teacherData['id']);
                    if ($teacher && $teacher->school_id == $school->id) {
                        $teacher->update([
                            'name' => $teacherData['name'],
                            'email' => $teacherData['email'],
                        ]);
                    }
                }
            }
        }

        return redirect()->route('schools.show', $school)
            ->with('success', __('School and teachers updated successfully.'));
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

    /**
     * Add teachers to the school.
     */
    public function addTeacher(Request $request, School $school)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }

        $request->validate([
            'teacher_ids' => 'required|array',
            'teacher_ids.*' => 'exists:users,id',
        ]);

        // Update the school_id for each selected teacher
        User::whereIn('id', $request->teacher_ids)
            ->where('role', 'teacher')
            ->update(['school_id' => $school->id]);

        return redirect()->route('schools.show', $school)
            ->with('success', __('Teachers added successfully.'));
    }

    /**
     * Remove a teacher from the school.
     */
    public function removeTeacher(Request $request, School $school)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }

        $request->validate([
            'teacher_id' => 'required|exists:users,id',
        ]);

        User::where('id', $request->teacher_id)
            ->where('school_id', $school->id)
            ->update(['school_id' => null]);

        return redirect()->route('schools.show', $school)
            ->with('success', __('Teacher removed successfully.'));
    }

    /**
     * Search for available teachers (not assigned to any school).
     */
    public function searchTeachers(Request $request, School $school)
    {
        $query = $request->get('q', '');

        $teachers = User::where('role', 'teacher')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->where(function ($q) use ($school) {
                $q->whereNull('school_id')
                    ->orWhere('school_id', '!=', $school->id);
            })
            ->limit(20)
            ->get(['id', 'name', 'email', 'school_id'])
            ->map(function ($teacher) use ($school) {
                $teacher->is_current = $teacher->school_id == $school->id;

                return $teacher;
            });

        return response()->json($teachers);
    }

    /**
     * Show the bulk import form.
     */
    public function bulkImportForm()
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }

        return view('schools.bulk-import');
    }

    /**
     * Download bulk import template for schools.
     */
    public function downloadTemplate()
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }

        return Excel::download(new SchoolTemplateExport, 'schools_import_template.xlsx');
    }

    /**
     * Process bulk import of schools.
     */
    public function bulkImport(Request $request)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }

        $request->validate([
            'schools' => 'required|array',
            'schools.*.school_name' => 'required|string|max:255',
            'schools.*.school_code' => 'required|string|max:255',
            'schools.*.province' => 'required|string|max:255',
            'schools.*.district' => 'required|string|max:255',
            'schools.*.cluster' => 'nullable|string|max:255',
        ]);

        $imported = 0;
        $failed = 0;
        $errors = [];

        foreach ($request->schools as $index => $schoolData) {
            try {
                // Check if school code already exists
                if (School::where('school_code', $schoolData['school_code'])->exists()) {
                    $failed++;
                    $errors[] = 'Row '.($index + 1).": School code {$schoolData['school_code']} already exists";

                    continue;
                }

                School::create([
                    'school_name' => $schoolData['school_name'],
                    'school_code' => $schoolData['school_code'],
                    'province' => $schoolData['province'],
                    'district' => $schoolData['district'],
                    'cluster' => $schoolData['cluster'] ?? null,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = 'Row '.($index + 1).': '.$e->getMessage();
            }
        }

        $message = "Successfully imported {$imported} schools.";
        if ($failed > 0) {
            $message .= " {$failed} schools failed to import.";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'imported' => $imported,
            'failed' => $failed,
            'errors' => $errors,
        ]);
    }

    /**
     * Get teachers for a specific school (API endpoint)
     */
    public function getTeachers(School $school)
    {
        $teachers = $school->users()
            ->where('role', 'teacher')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($teachers);
    }

    /**
     * Show assessment dates management page
     */
    public function assessmentDates()
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }

        $schools = School::orderBy('school_name')->get();

        return view('schools.assessment-dates', compact('schools'));
    }

    /**
     * Update assessment dates for multiple schools
     */
    public function updateAssessmentDates(Request $request)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }

        $validated = $request->validate([
            'school_ids' => 'required|array',
            'school_ids.*' => 'exists:schools,id',
            'baseline_start_date' => 'nullable|date',
            'baseline_end_date' => 'nullable|date|after_or_equal:baseline_start_date',
            'midline_start_date' => 'nullable|date',
            'midline_end_date' => 'nullable|date|after_or_equal:midline_start_date',
            'endline_start_date' => 'nullable|date',
            'endline_end_date' => 'nullable|date|after_or_equal:endline_start_date',
        ]);

        // Prepare the update data
        $updateData = [];
        $dateFields = [
            'baseline_start_date', 'baseline_end_date',
            'midline_start_date', 'midline_end_date',
            'endline_start_date', 'endline_end_date'
        ];

        foreach ($dateFields as $field) {
            if (isset($validated[$field])) {
                $updateData[$field] = $validated[$field];
            }
        }

        // Update selected schools
        School::whereIn('id', $validated['school_ids'])
            ->update($updateData);

        return redirect()->route('schools.assessment-dates')
            ->with('success', __('Assessment dates updated successfully for :count schools.', [
                'count' => count($validated['school_ids'])
            ]));
    }
}
