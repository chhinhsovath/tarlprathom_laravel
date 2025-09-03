<?php

namespace App\Services;

use App\Models\MentoringVisit;
use App\Models\PilotSchool;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MentoringVisitService
{
    /**
     * Validate that a teacher belongs to a specific school
     */
    public function validateTeacherSchoolAssignment(int $teacherId, int $schoolId): bool
    {
        return User::where('id', $teacherId)
            ->where('pilot_school_id', $schoolId)
            ->where('role', 'teacher')
            ->exists();
    }

    /**
     * Validate that a mentor has access to a specific school
     */
    public function validateMentorSchoolAccess(User $mentor, int $schoolId): bool
    {
        if ($mentor->isAdmin()) {
            return true;
        }

        return $mentor->assignedPilotSchools()
            ->where('pilot_schools.id', $schoolId)
            ->exists();
    }

    /**
     * Get form data for creating/editing mentoring visits
     */
    public function getFormData(User $user): array
    {
        $schools = $this->getAvailableSchools($user);
        
        return [
            'schools' => $schools,
            'provinces' => $this->getCachedProvinces(),
            'teachers' => $this->getTeachersForSchools($schools),
            'mentors' => $this->getCachedMentors(),
            'students' => $this->getStudentsForSchools($schools),
        ];
    }

    /**
     * Get available schools based on user role
     */
    private function getAvailableSchools(User $user)
    {
        if ($user->isMentor()) {
            return $user->assignedPilotSchools()
                ->orderBy('school_name')
                ->get();
        }
        
        return PilotSchool::orderBy('school_name')->get();
    }

    /**
     * Get cached provinces list
     */
    private function getCachedProvinces()
    {
        return Cache::remember('provinces_list', 900, function () {
            return \App\Models\Province::orderBy('name_kh')
                ->pluck('name_en', 'name_en');
        });
    }

    /**
     * Get cached mentors list
     */
    private function getCachedMentors()
    {
        return Cache::remember('active_mentors', 300, function () {
            return User::select('id', 'name', 'email')
                ->where('role', 'mentor')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Get teachers for specific schools
     */
    private function getTeachersForSchools($schools)
    {
        $schoolIds = $schools->pluck('id');
        
        return User::select('id', 'name', 'email', 'pilot_school_id')
            ->where('role', 'teacher')
            ->where('is_active', true)
            ->whereIn('pilot_school_id', $schoolIds)
            ->with('pilotSchool:id,school_name')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get students for specific schools with limit
     */
    private function getStudentsForSchools($schools, int $limit = 500)
    {
        $schoolIds = $schools->pluck('id');
        
        return \App\Models\Student::select('id', 'name', 'student_code', 'pilot_school_id')
            ->whereIn('pilot_school_id', $schoolIds)
            ->orderBy('name')
            ->limit($limit)
            ->get();
    }

    /**
     * Process questionnaire data from request
     */
    public function processQuestionnaireData(array $requestData): array
    {
        $questionnaireData = [];
        
        foreach (config('mentoring.questionnaire', []) as $section => $questions) {
            foreach ($questions as $field => $config) {
                if (isset($requestData["questionnaire.{$field}"])) {
                    $questionnaireData[$field] = $requestData["questionnaire.{$field}"];
                }
            }
        }
        
        return $questionnaireData;
    }

    /**
     * Create a new mentoring visit
     */
    public function create(array $data, User $creator): MentoringVisit
    {
        return DB::transaction(function () use ($data, $creator) {
            // Set mentor ID
            if ($creator->isMentor()) {
                $data['mentor_id'] = $creator->id;
            }
            
            // Process boolean fields
            $data = $this->processBooleanFields($data);
            
            // Process questionnaire data
            if (isset($data['questionnaire'])) {
                $data['questionnaire_data'] = $this->processQuestionnaireData($data);
                unset($data['questionnaire']);
            }
            
            return MentoringVisit::create($data);
        });
    }

    /**
     * Update an existing mentoring visit
     */
    public function update(MentoringVisit $visit, array $data, User $updater): MentoringVisit
    {
        return DB::transaction(function () use ($visit, $data, $updater) {
            // Check if locked
            if ($visit->is_locked && !$updater->isAdmin()) {
                throw new \Exception('This mentoring visit is locked and cannot be edited.');
            }
            
            // Don't allow changing mentor unless admin
            if (!$updater->isAdmin()) {
                unset($data['mentor_id']);
            }
            
            // Process boolean fields
            $data = $this->processBooleanFields($data);
            
            // Process questionnaire data
            if (isset($data['questionnaire'])) {
                $data['questionnaire_data'] = $this->processQuestionnaireData($data);
                unset($data['questionnaire']);
            }
            
            $visit->update($data);
            
            return $visit->fresh();
        });
    }

    /**
     * Process boolean fields from Yes/No strings
     */
    private function processBooleanFields(array $data): array
    {
        $booleanFields = [
            'class_in_session',
            'full_session_observed',
            'follow_up_required',
            'class_started_on_time',
            'children_grouped_appropriately',
            'students_fully_involved',
            'has_session_plan',
            'followed_session_plan',
            'session_plan_appropriate'
        ];
        
        foreach ($booleanFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = $data[$field] === 'Yes' || $data[$field] === '1' || $data[$field] === true;
            }
        }
        
        return $data;
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics(User $user): array
    {
        $query = MentoringVisit::query();
        
        // Apply role-based filtering
        if ($user->isMentor()) {
            $schoolIds = $user->assignedPilotSchools()->pluck('pilot_schools.id');
            $query->whereIn('pilot_school_id', $schoolIds);
        } elseif ($user->isTeacher()) {
            $query->where('teacher_id', $user->id);
        }
        
        return Cache::remember("mentoring_stats_{$user->id}", 300, function () use ($query) {
            return [
                'total_visits' => $query->count(),
                'this_month' => $query->clone()->whereMonth('visit_date', now()->month)->count(),
                'follow_up_required' => $query->clone()->where('follow_up_required', true)->count(),
                'recent_visits' => $query->clone()
                    ->with(['mentor:id,name', 'teacher:id,name', 'pilotSchool:id,school_name'])
                    ->latest('visit_date')
                    ->limit(5)
                    ->get()
            ];
        });
    }
}