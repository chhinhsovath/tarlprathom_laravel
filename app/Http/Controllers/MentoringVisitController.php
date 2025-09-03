<?php

namespace App\Http\Controllers;

use App\Exports\MentoringVisitsExport;
use App\Http\Requests\StoreMentoringVisitRequest;
use App\Models\Geographic;
use App\Models\MentoringVisit;
use App\Models\PilotSchool;
use App\Models\Province;
use App\Models\School;
use App\Models\User;
use App\Traits\Sortable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MentoringVisitController extends Controller
{
    use AuthorizesRequests, Sortable;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Check if user can view mentoring visits
        if (! in_array($user->role, ['admin', 'mentor', 'teacher', 'viewer'])) {
            abort(403);
        }

        $query = MentoringVisit::with(['mentor', 'teacher', 'pilotSchool']);

        // Filter based on user role
        if ($user->isTeacher()) {
            // Teachers can only see visits where they are the teacher
            $query->where('teacher_id', $user->id);
        } elseif ($user->isMentor()) {
            // Mentors can see visits for their assigned pilot schools
            $assignedSchoolIds = $user->assignedPilotSchools()->pluck('pilot_schools.id')->toArray();
            if (! empty($assignedSchoolIds)) {
                $query->whereIn('pilot_school_id', $assignedSchoolIds);
            } else {
                // If no schools assigned, show no visits
                $query->whereRaw('1 = 0');
            }
        }

        // Add search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('observation', 'like', "%{$search}%")
                    ->orWhere('action_plan', 'like', "%{$search}%")
                    ->orWhereHas('teacher', function ($tq) use ($search) {
                        $tq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('mentor', function ($mq) use ($search) {
                        $mq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('pilotSchool', function ($sq) use ($search) {
                        $sq->where('school_name', 'like', "%{$search}%");
                    });
            });
        }

        // Add filters
        if ($request->filled('pilot_school_id')) {
            $query->where('pilot_school_id', $request->get('pilot_school_id'));
        }

        if ($request->filled('mentor_id')) {
            $query->where('mentor_id', $request->get('mentor_id'));
        }

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->get('teacher_id'));
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->where('visit_date', '>=', $request->get('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->where('visit_date', '<=', $request->get('to_date'));
        }

        // Apply sorting
        $sortData = $this->applySorting(
            $query,
            $request,
            ['visit_date', 'pilot_school_id', 'teacher_id', 'mentor_id', 'score'],
            'visit_date',
            'desc'
        );

        $mentoringVisits = $query->paginate(20)->withQueryString();

        return view('mentoring.index', compact('mentoringVisits') + $sortData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Check authorization
        if (! in_array($request->user()->role, ['admin', 'mentor'])) {
            abort(403);
        }

        // Get pilot schools for dropdown
        $user = $request->user();
        if ($user->isMentor()) {
            // Mentors can only see their assigned pilot schools
            $schools = $user->assignedPilotSchools()->orderBy('school_name')->get();
        } else {
            // Admins can see all pilot schools
            $schools = PilotSchool::orderBy('school_name')->get();
        }

        // Get provinces from Geographic table
        $provinces = Province::orderBy('name_kh')->pluck('name_en', 'name_en');

        // Get teachers for dropdown
        $teachers = User::where('role', 'teacher')
            ->where('is_active', true)
            ->with('pilotSchool')
            ->orderBy('name')
            ->get();

        // Get mentors for dropdown (for admin)
        $mentors = User::where('role', 'mentor')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Get students for dropdown
        $students = \App\Models\Student::with('school')
            ->orderBy('name')
            ->get();

        // Pre-select values if passed as parameters
        $selectedSchoolId = $request->get('pilot_school_id');
        $selectedTeacherId = $request->get('teacher_id');

        // Get questionnaire configuration
        $questionnaire = config('mentoring.questionnaire');

        return view('mentoring.create', compact('schools', 'provinces', 'teachers', 'mentors', 'students', 'selectedSchoolId', 'selectedTeacherId', 'questionnaire'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMentoringVisitRequest $request)
    {
        $validated = $request->validated();

        // Set the mentor_id
        if ($request->user()->isMentor()) {
            $validated['mentor_id'] = $request->user()->id;
        } else {
            // Admin can specify mentor_id if needed
            $validated['mentor_id'] = $validated['mentor_id'] ?? $request->user()->id;
        }

        // Process questionnaire data
        $questionnaireData = [];
        foreach (config('mentoring.questionnaire') as $section => $questions) {
            foreach ($questions as $field => $config) {
                if ($request->has("questionnaire.{$field}")) {
                    $questionnaireData[$field] = $request->input("questionnaire.{$field}");
                }
            }
        }
        $validated['questionnaire_data'] = $questionnaireData;

        // Process specific fields
        if ($request->has('class_in_session')) {
            $validated['class_in_session'] = $request->input('class_in_session') === 'Yes';
        }

        if ($request->has('full_session_observed')) {
            $validated['full_session_observed'] = $request->input('full_session_observed') === 'Yes';
        }

        if ($request->has('follow_up_required')) {
            $validated['follow_up_required'] = $request->input('follow_up_required') === 'Yes';
        }

        // Verify the teacher belongs to the selected school
        $teacher = User::findOrFail($validated['teacher_id']);
        if ($teacher->pilot_school_id !== $validated['pilot_school_id']) {
            return back()->withErrors(['teacher_id' => __('The selected teacher does not belong to the selected school.')]);
        }

        // Verify mentor has access to the school
        if ($request->user()->isMentor()) {
            $assignedSchoolIds = $request->user()->assignedPilotSchools()->pluck('pilot_schools.id')->toArray();
            if (! in_array($validated['pilot_school_id'], $assignedSchoolIds)) {
                return back()->withErrors(['pilot_school_id' => __('You are not assigned to this school.')]);
            }
        }

        $mentoringVisit = MentoringVisit::create($validated);

        return redirect()->route('mentoring.index')
            ->with('success', __('Mentoring visit recorded successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(MentoringVisit $mentoringVisit)
    {
        $user = auth()->user();

        // Check if user can view this mentoring visit
        if ($user->isAdmin() || $user->isViewer()) {
            // These roles can view all visits
        } elseif ($user->isMentor() && $user->canAccessSchool($mentoringVisit->pilot_school_id)) {
            // Mentors can view visits from their assigned schools
        } elseif ($user->isTeacher() && $mentoringVisit->teacher_id === $user->id) {
            // Teachers can view visits where they are the teacher
        } else {
            abort(403);
        }

        $mentoringVisit->load(['mentor', 'teacher', 'pilotSchool']);

        return view('mentoring.show', compact('mentoringVisit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MentoringVisit $mentoringVisit)
    {
        $user = auth()->user();

        // Check if the mentoring visit is locked (only if column exists)
        if (\Schema::hasColumn('mentoring_visits', 'is_locked')) {
            if ($mentoringVisit->is_locked && ! $user->isAdmin()) {
                return redirect()->route('mentoring.show', $mentoringVisit)
                    ->with('error', __('This mentoring visit is locked and cannot be edited.'));
            }
        }

        // Check if user can edit this mentoring visit
        if ($user->isAdmin() || $mentoringVisit->mentor_id === $user->id) {
            // Admin and the mentor who created it can edit
        } else {
            abort(403);
        }
        if ($user->isMentor()) {
            // Mentors can only see their assigned pilot schools
            $schools = $user->assignedPilotSchools()->orderBy('school_name')->get();
        } else {
            // Admins can see all pilot schools
            $schools = PilotSchool::orderBy('school_name')->get();
        }

        // Get provinces from Geographic table
        $provinces = Province::orderBy('name_kh')->pluck('name_en', 'name_en');

        // Get teachers for dropdown
        $teachers = User::where('role', 'teacher')
            ->where('is_active', true)
            ->with('pilotSchool')
            ->orderBy('name')
            ->get();

        // Get mentors for dropdown (for admin)
        $mentors = User::where('role', 'mentor')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Get students for dropdown
        $students = \App\Models\Student::with('school')
            ->orderBy('name')
            ->get();

        // Get questionnaire configuration
        $questionnaire = config('mentoring.questionnaire');

        return view('mentoring.edit', compact('mentoringVisit', 'schools', 'provinces', 'teachers', 'mentors', 'students', 'questionnaire'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreMentoringVisitRequest $request, MentoringVisit $mentoringVisit)
    {
        $user = auth()->user();

        // Check if the mentoring visit is locked (only if column exists)
        if (\Schema::hasColumn('mentoring_visits', 'is_locked')) {
            if ($mentoringVisit->is_locked && ! $user->isAdmin()) {
                return redirect()->route('mentoring.show', $mentoringVisit)
                    ->with('error', __('This mentoring visit is locked and cannot be edited.'));
            }
        }

        // Check if user can edit this mentoring visit
        if (! ($user->isAdmin() || $mentoringVisit->mentor_id === $user->id)) {
            abort(403);
        }

        $validated = $request->validated();

        // Set the mentor_id
        if (! $user->isAdmin()) {
            // Non-admin users can't change the mentor
            $validated['mentor_id'] = $mentoringVisit->mentor_id;
        }

        // Process questionnaire data
        $questionnaireData = [];
        foreach (config('mentoring.questionnaire') as $section => $questions) {
            foreach ($questions as $field => $config) {
                if ($request->has("questionnaire.{$field}")) {
                    $questionnaireData[$field] = $request->input("questionnaire.{$field}");
                }
            }
        }
        $validated['questionnaire_data'] = $questionnaireData;

        // Process specific fields
        if ($request->has('class_in_session')) {
            $validated['class_in_session'] = $request->input('class_in_session') === 'Yes';
        }

        if ($request->has('full_session_observed')) {
            $validated['full_session_observed'] = $request->input('full_session_observed') === 'Yes';
        }

        if ($request->has('follow_up_required')) {
            $validated['follow_up_required'] = $request->input('follow_up_required') === 'Yes';
        }

        // Verify the teacher belongs to the selected school
        $teacher = User::findOrFail($validated['teacher_id']);
        if ($teacher->pilot_school_id !== $validated['pilot_school_id']) {
            return back()->withErrors(['teacher_id' => __('The selected teacher does not belong to the selected school.')]);
        }

        $mentoringVisit->update($validated);

        return redirect()->route('mentoring.show', $mentoringVisit)
            ->with('success', __('Mentoring visit updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MentoringVisit $mentoringVisit)
    {
        $user = auth()->user();

        // Check if user can delete this mentoring visit
        if (! ($user->isAdmin() || $mentoringVisit->mentor_id === $user->id)) {
            abort(403);
        }

        $mentoringVisit->delete();

        return redirect()->route('mentoring.index')
            ->with('success', __('Mentoring visit deleted successfully.'));
    }

    /**
     * Export mentoring visits to Excel
     */
    public function export(Request $request)
    {
        $user = $request->user();

        // Check if user can view mentoring visits
        if (! in_array($user->role, ['admin', 'mentor', 'teacher', 'viewer'])) {
            abort(403);
        }

        return Excel::download(new MentoringVisitsExport($request), 'mentoring_visits_'.date('Y-m-d_H-i-s').'.xlsx');
    }
}
