<?php

namespace App\Http\Controllers;

use App\Exports\SchoolTemplateExport;
use App\Models\Geographic;
use App\Models\Province;
use App\Models\School;
use App\Models\User;
use App\Traits\Sortable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SchoolController extends Controller
{
    use Sortable;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Check if user can view schools
        if (! in_array($user->role, ['admin', 'mentor'])) {
            abort(403, __('Unauthorized action.'));
        }

        $query = School::withCount(['users', 'students']);

        // Apply access restrictions for mentors
        if ($user->isMentor()) {
            $accessibleSchoolIds = $user->getAccessibleSchoolIds();
            if (! empty($accessibleSchoolIds)) {
                $query->whereIn('sclAutoID', $accessibleSchoolIds);
            } else {
                // No schools assigned, show empty result
                $query->whereRaw('1 = 0');
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('sclName', 'like', "%{$search}%")
                    ->orWhere('sclCode', 'like', "%{$search}%")
                    ->orWhere('sclProvinceName', 'like', "%{$search}%")
                    ->orWhere('sclDistrictName', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortData = $this->applySorting($query, $request, ['sclName', 'sclCode', 'sclProvinceName', 'sclDistrictName', 'created_at'], 'sclName');

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

        $provinces = Province::orderBy('name_kh')->get();

        return view('schools.create', compact('provinces'));
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
            'sclName' => ['required', 'string', 'max:255'],
            'sclCode' => ['required', 'string', 'max:255', 'unique:tbl_tarl_schools,sclCode'],
            'sclProvinceName' => ['required', 'string', 'max:255'],
            'sclDistrictName' => ['required', 'string', 'max:255'],
            'sclClusterName' => ['nullable', 'string', 'max:255'],
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
        $user = auth()->user();

        // Check if user can view this school
        if (! $user->isAdmin() && ! $user->canAccessSchool($school->id)) {
            abort(403, __('Unauthorized action.'));
        }

        $school->loadCount(['users', 'students']);
        $teachers = $school->users()->where('role', 'teacher')->get();
        $mentors = User::where('role', 'mentor')
            ->whereHas('assignedSchools', function ($query) use ($school) {
                $query->where('schools.sclAutoID', $school->sclAutoID);
            })
            ->get();
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

        $provinces = Province::orderBy('name_kh')->get();

        return view('schools.edit', compact('school', 'provinces'));
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
            'sclName' => ['required', 'string', 'max:255'],
            'sclCode' => ['required', 'string', 'max:255', 'unique:tbl_tarl_schools,sclCode,'.$school->sclAutoID.',sclAutoID'],
            'sclProvinceName' => ['required', 'string', 'max:255'],
            'sclDistrictName' => ['required', 'string', 'max:255'],
            'sclClusterName' => ['nullable', 'string', 'max:255'],
            // Assessment date validations - these fields don't exist in tbl_tarl_schools
            // You may want to store these in a separate table
            // 'baseline_start_date' => ['nullable', 'date'],
            // 'baseline_end_date' => ['nullable', 'date', 'after_or_equal:baseline_start_date'],
            // 'midline_start_date' => ['nullable', 'date', 'after_or_equal:baseline_end_date'],
            // 'midline_end_date' => ['nullable', 'date', 'after_or_equal:midline_start_date'],
            // 'endline_start_date' => ['nullable', 'date', 'after_or_equal:midline_end_date'],
            // 'endline_end_date' => ['nullable', 'date', 'after_or_equal:endline_start_date'],
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
     * Add mentors to the school.
     */
    public function addMentor(Request $request, School $school)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }

        $request->validate([
            'mentor_ids' => 'required|array',
            'mentor_ids.*' => 'exists:users,id',
        ]);

        // Attach mentors to the school through the pivot table
        foreach ($request->mentor_ids as $mentorId) {
            // Check if the user is actually a mentor
            $mentor = User::where('id', $mentorId)->where('role', 'mentor')->first();
            if ($mentor) {
                // Attach mentor to school if not already attached
                if (! $mentor->assignedSchools()->where('school_id', $school->id)->exists()) {
                    $mentor->assignedSchools()->attach($school->id);
                }
            }
        }

        return redirect()->route('schools.show', $school)
            ->with('success', __('Mentors added successfully.'));
    }

    /**
     * Remove a mentor from the school.
     */
    public function removeMentor(Request $request, School $school)
    {
        // Check if user is admin
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Unauthorized action.'));
        }

        $request->validate([
            'mentor_id' => 'required|exists:users,id',
        ]);

        $mentor = User::find($request->mentor_id);
        if ($mentor && $mentor->role === 'mentor') {
            $mentor->assignedSchools()->detach($school->id);
        }

        return redirect()->route('schools.show', $school)
            ->with('success', __('Mentor removed successfully.'));
    }

    /**
     * Search for available mentors.
     */
    public function searchMentors(Request $request, School $school)
    {
        $query = $request->get('q', '');

        $mentors = User::where('role', 'mentor')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit(20)
            ->get(['id', 'name', 'email'])
            ->map(function ($mentor) use ($school) {
                $mentor->is_assigned = $mentor->assignedSchools()->where('school_id', $school->id)->exists();

                return $mentor;
            });

        return response()->json($mentors);
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
            'schools.*.sclName' => 'required|string|max:255',
            'schools.*.sclCode' => 'required|string|max:255',
            'schools.*.sclProvinceName' => 'required|string|max:255',
            'schools.*.sclDistrictName' => 'required|string|max:255',
            'schools.*.sclClusterName' => 'nullable|string|max:255',
        ]);

        $imported = 0;
        $failed = 0;
        $errors = [];

        foreach ($request->schools as $index => $schoolData) {
            try {
                // Check if school code already exists
                if (School::where('sclCode', $schoolData['sclCode'])->exists()) {
                    $failed++;
                    $errors[] = 'Row '.($index + 1).": School code {$schoolData['sclCode']} already exists";

                    continue;
                }

                School::create([
                    'sclName' => $schoolData['sclName'],
                    'sclCode' => $schoolData['sclCode'],
                    'sclProvinceName' => $schoolData['sclProvinceName'],
                    'sclDistrictName' => $schoolData['sclDistrictName'],
                    'sclClusterName' => $schoolData['sclClusterName'] ?? null,
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
     * Get all provinces (API endpoint)
     */
    public function getProvinces()
    {
        $provinces = \App\Models\Province::orderBy('name_kh')->get();

        return response()->json($provinces);
    }

    /**
     * Get districts by province (API endpoint)
     */
    public function getDistricts(Request $request)
    {
        $provinceCode = $request->get('province_code');

        $districts = \App\Models\Geographic::getDistrictsByProvince($provinceCode);

        return response()->json($districts);
    }

    /**
     * Get communes by district (API endpoint)
     */
    public function getCommunes(Request $request)
    {
        $districtCode = $request->get('district_code');

        $communes = \App\Models\Geographic::getCommunesByDistrict($districtCode);

        return response()->json($communes);
    }

    /**
     * Get villages by commune (API endpoint)
     */
    public function getVillages(Request $request)
    {
        $communeCode = $request->get('commune_code');

        $villages = \App\Models\Geographic::getVillagesByCommune($communeCode);

        return response()->json($villages);
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

        $schools = School::orderBy('sclName')->get();

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
            'endline_start_date', 'endline_end_date',
        ];

        foreach ($dateFields as $field) {
            if (isset($validated[$field])) {
                $updateData[$field] = $validated[$field];
            }
        }

        // Update selected schools
        School::whereIn('sclAutoID', $validated['school_ids'])
            ->update($updateData);

        return redirect()->route('schools.assessment-dates')
            ->with('success', __('Assessment dates updated successfully for :count schools.', [
                'count' => count($validated['school_ids']),
            ]));
    }
}
