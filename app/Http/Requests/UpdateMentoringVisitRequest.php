<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMentoringVisitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admin and mentor can update mentoring visits
        return in_array($this->user()->role, ['admin', 'mentor']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Basic fields
            'pilot_school_id' => 'required|exists:pilot_schools,id',
            'school_id' => 'nullable|exists:schools,id', // Legacy field, optional
            'teacher_id' => 'required_if:class_in_session,1|nullable|exists:users,id',
            'visit_date' => 'required|date',
            'mentor_id' => 'nullable|exists:users,id',

            // Boolean fields - accept 0/1 values from form
            'class_in_session' => 'required|in:0,1',
            'class_not_in_session_reason' => 'required_if:class_in_session,0|nullable|string',
            'full_session_observed' => 'required_if:class_in_session,1|nullable|in:0,1',
            
            // Grade and subject fields
            'grade_group' => 'nullable|string',
            'grades_observed' => 'nullable|array',
            'grades_observed.*' => 'in:ទី៤,ទី៥,ទី៦,1,2,3,4,5,6', // Accept both Khmer and English
            'subject_observed' => 'required_if:class_in_session,1|nullable|in:ភាសាខ្មែរ,គណិតវិទ្យា,Language,Numeracy',
            
            // Student statistics - new comprehensive fields
            'total_students_enrolled' => 'nullable|integer|min:0|max:20',
            'students_present' => 'nullable|integer|min:0',
            'students_improved' => 'nullable|integer|min:0',
            'classes_conducted_before_visit' => 'nullable|integer|min:0',
            
            // Delivery questions
            'class_started_on_time' => 'required_if:class_in_session,1|nullable|in:1,0,-1,បាទ/ចាស,ទេ,មិនដឹង',
            'late_start_reason' => 'required_if:class_started_on_time,0|nullable|string',

            // Classroom questions
            'materials_present' => 'nullable|array',
            'materials_present.*' => 'string',
            'teaching_materials' => 'nullable|array',
            'teaching_materials.*' => 'string',
            'children_grouped_appropriately' => 'required_if:class_in_session,1|nullable|in:0,1',
            'students_fully_involved' => 'required_if:class_in_session,1|nullable|in:0,1',
            'students_grouped_by_level' => 'nullable|in:0,1',
            'students_active_participation' => 'nullable|in:0,1',

            // Teacher questions
            'has_session_plan' => 'required_if:class_in_session,1|nullable|in:0,1',
            'teacher_has_lesson_plan' => 'nullable|in:0,1',
            'no_session_plan_reason' => 'nullable|string',
            'no_lesson_plan_reason' => 'nullable|string',
            'followed_session_plan' => 'required_if:has_session_plan,1|nullable|in:0,1',
            'followed_lesson_plan' => 'nullable|in:0,1',
            'no_follow_plan_reason' => 'nullable|string',
            'not_followed_reason' => 'nullable|string',
            'session_plan_appropriate' => 'required_if:has_session_plan,1|nullable|in:0,1',
            'plan_appropriate_for_levels' => 'nullable|in:0,1',
            'session_plan_notes' => 'nullable|string',
            'lesson_plan_feedback' => 'nullable|string',

            // Activity overview
            'number_of_activities' => 'nullable|integer|min:0|max:5',
            'num_activities_observed' => 'nullable|integer|min:0|max:3',

            // Activity fields - both legacy and new format
            'activity1_name_language' => 'nullable|string',
            'activity1_name_numeracy' => 'nullable|string',
            'activity1_type' => 'nullable|string',
            'activity1_duration' => 'nullable|integer|min:1',
            'activity1_clear_instructions' => 'nullable|in:0,1',
            'activity1_no_clear_instructions_reason' => 'nullable|string',
            'activity1_unclear_reason' => 'nullable|string',
            'activity1_demonstrated' => 'nullable|in:0,1',
            'activity1_followed_process' => 'nullable|in:0,1',
            'activity1_not_followed_reason' => 'nullable|string',
            'activity1_students_practice' => 'nullable|string',
            'activity1_small_groups' => 'nullable|string',
            'activity1_individual' => 'nullable|string',

            // Activity 2
            'activity2_name_language' => 'nullable|string',
            'activity2_name_numeracy' => 'nullable|string',
            'activity2_type' => 'nullable|string',
            'activity2_duration' => 'nullable|integer|min:1',
            'activity2_clear_instructions' => 'nullable|in:0,1',
            'activity2_no_clear_instructions_reason' => 'nullable|string',
            'activity2_unclear_reason' => 'nullable|string',
            'activity2_demonstrated' => 'nullable|in:0,1',
            'activity2_followed_process' => 'nullable|in:0,1',
            'activity2_not_followed_reason' => 'nullable|string',
            'activity2_students_practice' => 'nullable|string',
            'activity2_small_groups' => 'nullable|string',
            'activity2_individual' => 'nullable|string',

            // Activity 3
            'activity3_name_language' => 'nullable|string',
            'activity3_name_numeracy' => 'nullable|string',
            'activity3_duration' => 'nullable|integer|min:1',
            'activity3_clear_instructions' => 'nullable|in:0,1',
            'activity3_no_clear_instructions_reason' => 'nullable|string',
            'activity3_demonstrated' => 'nullable|in:0,1',
            'activity3_students_practice' => 'nullable|string',
            'activity3_small_groups' => 'nullable|string',
            'activity3_individual' => 'nullable|string',

            // Language levels
            'language_levels_observed' => 'nullable|array',
            'language_levels_observed.*' => 'string',
            'numeracy_levels_observed' => 'nullable|array',
            'numeracy_levels_observed.*' => 'string',

            // Questionnaire data
            'questionnaire' => 'nullable|array',
            'questionnaire.*' => 'nullable',

            // Optional fields
            'observation' => 'nullable|string',
            'action_plan' => 'nullable|string',
            'score' => 'nullable|integer|min:0|max:100',
            'follow_up_required' => 'nullable|in:0,1',
            
            // Additional comprehensive fields
            'feedback_for_teacher' => 'nullable|string',
            'teacher_feedback' => 'nullable|string',
            'activities_data' => 'nullable|string', // JSON string
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'pilot_school_id.exists' => 'The selected school does not exist.',
            'teacher_id.exists' => 'The selected teacher does not exist.',
            'visit_date.date' => 'The visit date must be a valid date.',
            'score.min' => 'The score must be at least 0.',
            'score.max' => 'The score must not exceed 100.',
            'total_students_enrolled.max' => 'The total students enrolled cannot exceed 20 for TaRL classes.',
        ];
    }
}