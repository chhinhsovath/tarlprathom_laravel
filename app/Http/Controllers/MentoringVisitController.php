<?php

namespace App\Http\Controllers;

use App\Exports\MentoringVisitsExport;
use App\Http\Requests\StoreMentoringVisitRequest;
use App\Http\Requests\UpdateMentoringVisitRequest;
use App\Models\Geographic;
use App\Models\MentoringVisit;
use App\Models\PilotSchool;
use App\Models\School;
use App\Models\User;
use App\Traits\Sortable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use App\Jobs\ExportMentoringVisitsJob;
use App\Services\MentoringVisitService;

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

        // Eager load relationships to prevent N+1 queries
        $query = MentoringVisit::with([
            'mentor:id,name,email',
            'teacher:id,name,email', 
            'pilotSchool:id,school_name,school_code'
        ]);

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


        // Get teachers for dropdown - optimize query
        $teachers = User::select('id', 'name', 'email', 'pilot_school_id')
            ->where('role', 'teacher')
            ->where('is_active', true)
            ->with('pilotSchool:id,school_name')
            ->orderBy('name')
            ->get();

        // Get mentors from cache for better performance
        $mentors = Cache::remember('active_mentors', 300, function () {
            return User::select('id', 'name', 'email')
                ->where('role', 'mentor')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        });

        // Only get students from relevant schools for better performance
        $schoolIds = $schools->pluck('id');
        $students = \App\Models\Student::select('id', 'name', 'student_code', 'pilot_school_id')
            ->whereIn('pilot_school_id', $schoolIds)
            ->orderBy('name')
            ->limit(500) // Limit for performance
            ->get();

        // Pre-select values if passed as parameters
        $selectedSchoolId = $request->get('pilot_school_id');
        $selectedTeacherId = $request->get('teacher_id');

        // Get questionnaire configuration
        $questionnaire = config('mentoring.questionnaire');

        return view('mentoring.create', compact('schools', 'teachers', 'mentors', 'students', 'selectedSchoolId', 'selectedTeacherId', 'questionnaire'));
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
            $validated['class_in_session'] = $request->input('class_in_session') == '1';
        }

        if ($request->has('full_session_observed')) {
            $validated['full_session_observed'] = $request->input('full_session_observed') == '1';
        }

        if ($request->has('follow_up_required')) {
            $validated['follow_up_required'] = $request->input('follow_up_required') == '1';
        }

        // Process new comprehensive fields
        if ($request->has('teaching_materials')) {
            $validated['teaching_materials'] = json_encode($request->input('teaching_materials', []));
        }

        if ($request->has('students_grouped_by_level')) {
            $validated['students_grouped_by_level'] = $request->input('students_grouped_by_level') == '1';
        }

        if ($request->has('students_active_participation')) {
            $validated['students_active_participation'] = $request->input('students_active_participation') == '1';
        }

        if ($request->has('teacher_has_lesson_plan')) {
            $validated['teacher_has_lesson_plan'] = $request->input('teacher_has_lesson_plan') == '1';
        }

        if ($request->has('followed_lesson_plan')) {
            $validated['followed_lesson_plan'] = $request->input('followed_lesson_plan') == '1';
        }

        if ($request->has('plan_appropriate_for_levels')) {
            $validated['plan_appropriate_for_levels'] = $request->input('plan_appropriate_for_levels') == '1';
        }

        // Handle activity data
        $activitiesData = [];
        $numActivities = $request->input('number_of_activities', 0);
        
        for ($i = 1; $i <= 3; $i++) {
            if ($i <= $numActivities) {
                // Determine activity type based on subject
                $activityType = '';
                if ($request->input('subject_observed') === 'ភាសាខ្មែរ') {
                    $activityType = $request->input("activity{$i}_name_language", '');
                } elseif ($request->input('subject_observed') === 'គណិតវិទ្យា') {
                    $activityType = $request->input("activity{$i}_name_numeracy", '');
                }
                
                // Store activity in both individual fields and JSON
                if ($i == 1) {
                    $validated['activity1_type'] = $activityType;
                    $validated['activity1_duration'] = $request->input('activity1_duration');
                    $validated['activity1_clear_instructions'] = $request->input('activity1_clear_instructions') == '1';
                    $validated['activity1_unclear_reason'] = $request->input('activity1_no_clear_instructions_reason');
                    $validated['activity1_followed_process'] = $request->input('activity1_followed_process') == '1';
                    $validated['activity1_not_followed_reason'] = $request->input('activity1_not_followed_reason');
                } elseif ($i == 2) {
                    $validated['activity2_type'] = $activityType;
                    $validated['activity2_duration'] = $request->input('activity2_duration');
                    $validated['activity2_clear_instructions'] = $request->input('activity2_clear_instructions') == '1';
                    $validated['activity2_unclear_reason'] = $request->input('activity2_no_clear_instructions_reason');
                    $validated['activity2_followed_process'] = $request->input('activity2_followed_process') == '1';
                    $validated['activity2_not_followed_reason'] = $request->input('activity2_not_followed_reason');
                }
                
                $activitiesData[] = [
                    'type' => $activityType,
                    'duration' => $request->input("activity{$i}_duration"),
                    'clear_instructions' => $request->input("activity{$i}_clear_instructions") == '1',
                    'unclear_reason' => $request->input("activity{$i}_no_clear_instructions_reason"),
                    'followed_process' => $request->input("activity{$i}_followed_process") == '1',
                    'not_followed_reason' => $request->input("activity{$i}_not_followed_reason"),
                ];
            }
        }
        
        if (!empty($activitiesData)) {
            $validated['activities_data'] = json_encode($activitiesData);
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

        try {
            // Set school_id based on pilot_school_id if not provided
            if (!isset($validated['school_id']) && isset($validated['pilot_school_id'])) {
                $validated['school_id'] = $validated['pilot_school_id'];
            }
            
            $mentoringVisit = MentoringVisit::create($validated);

            return redirect()->route('mentoring.index')
                ->with('success', 'ទស្សនកិច្ចណែនាំត្រូវបានកត់ត្រាដោយជោគជ័យ។');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Mentoring visit creation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'data' => $validated
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'មានបញ្ហាក្នុងការរក្សាទុកទិន្នន័យ។ សូមព្យាយាមម្តងទៀត ឬទាក់ទងអ្នកគ្រប់គ្រង។');
        }
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
        if (!($user->isAdmin() || 
              ($user->isMentor() && $user->canAccessSchool($mentoringVisit->pilot_school_id)))) {
            abort(403);
        }
        if ($user->isMentor()) {
            // Mentors can only see their assigned pilot schools
            $schools = $user->assignedPilotSchools()->orderBy('school_name')->get();
        } else {
            // Admins can see all pilot schools
            $schools = PilotSchool::orderBy('school_name')->get();
        }


        // Get teachers for dropdown - optimize query
        $teachers = User::select('id', 'name', 'email', 'pilot_school_id')
            ->where('role', 'teacher')
            ->where('is_active', true)
            ->with('pilotSchool:id,school_name')
            ->orderBy('name')
            ->get();

        // Get mentors from cache for better performance
        $mentors = Cache::remember('active_mentors', 300, function () {
            return User::select('id', 'name', 'email')
                ->where('role', 'mentor')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        });

        // Only get students from relevant schools for better performance
        $schoolIds = $schools->pluck('id');
        $students = \App\Models\Student::select('id', 'name', 'student_code', 'pilot_school_id')
            ->whereIn('pilot_school_id', $schoolIds)
            ->orderBy('name')
            ->limit(500) // Limit for performance
            ->get();

        // Get questionnaire configuration
        $questionnaire = config('mentoring.questionnaire');

        return view('mentoring.edit', compact('mentoringVisit', 'schools', 'teachers', 'mentors', 'students', 'questionnaire'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMentoringVisitRequest $request, MentoringVisit $mentoringVisit)
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
        if (!($user->isAdmin() || 
              ($user->isMentor() && $user->canAccessSchool($mentoringVisit->pilot_school_id)))) {
            abort(403);
        }

        // Get validated data - this handles all the basic field validation
        $validated = $request->validated();

        // Set the mentor_id
        if (! $user->isAdmin()) {
            // Non-admin users can't change the mentor
            $validated['mentor_id'] = $mentoringVisit->mentor_id;
        }

        // Process boolean fields properly
        if ($request->has('class_in_session')) {
            $validated['class_in_session'] = $request->input('class_in_session') == '1';
        }
        if ($request->has('full_session_observed')) {
            $validated['full_session_observed'] = $request->input('full_session_observed') == '1';
        }
        if ($request->has('children_grouped_appropriately')) {
            $validated['children_grouped_appropriately'] = $request->input('children_grouped_appropriately') == '1';
        }
        if ($request->has('students_fully_involved')) {
            $validated['students_fully_involved'] = $request->input('students_fully_involved') == '1';
        }
        if ($request->has('has_session_plan')) {
            $validated['has_session_plan'] = $request->input('has_session_plan') == '1';
        }
        if ($request->has('followed_session_plan')) {
            $validated['followed_session_plan'] = $request->input('followed_session_plan') == '1';
        }
        if ($request->has('session_plan_appropriate')) {
            $validated['session_plan_appropriate'] = $request->input('session_plan_appropriate') == '1';
        }
        if ($request->has('follow_up_required')) {
            $validated['follow_up_required'] = $request->input('follow_up_required') == '1';
        }

        // Process activity fields for all 3 activities
        for ($i = 1; $i <= 3; $i++) {
            if ($request->has("activity{$i}_clear_instructions")) {
                $validated["activity{$i}_clear_instructions"] = $request->input("activity{$i}_clear_instructions") == '1';
            }
            if ($request->has("activity{$i}_followed_process")) {
                $validated["activity{$i}_followed_process"] = $request->input("activity{$i}_followed_process") == '1';
            }
        }

        // Verify the teacher belongs to the selected school if teacher is provided
        if (!empty($validated['teacher_id'])) {
            $teacher = User::findOrFail($validated['teacher_id']);
            if ($teacher->pilot_school_id !== $validated['pilot_school_id']) {
                return back()->withErrors(['teacher_id' => __('The selected teacher does not belong to the selected school.')]);
            }
        }

        try {
            $mentoringVisit->update($validated);
            
            return redirect()->route('mentoring.show', $mentoringVisit)
                ->with('success', 'ការកែសម្រួលបានជោគជ័យ។ (Mentoring visit updated successfully.)');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'មានបញ្ហាក្នុងការរក្សាទុកទិន្នន័យ។ សូមព្យាយាមម្តងទៀត។ (Error saving data: ' . $e->getMessage() . ')'])->withInput();
        }
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
     * Export mentoring visits to Excel (queued)
     */
    public function export(Request $request)
    {
        $user = $request->user();

        // Check if user can view mentoring visits
        if (! in_array($user->role, ['admin', 'mentor', 'teacher', 'viewer'])) {
            abort(403);
        }

        // Dispatch export job to queue
        ExportMentoringVisitsJob::dispatch($user, $request->all());

        return redirect()->back()->with('success', __('Export has been queued. You will receive the file shortly.'));
    }
}
