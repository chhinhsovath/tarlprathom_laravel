<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMentoringVisitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admin and mentor can create mentoring visits
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
            'teacher_id' => 'required_if:class_in_session,Yes|nullable|exists:users,id',
            'visit_date' => 'required|date',
            'mentor_id' => 'nullable|exists:users,id',

            // New questionnaire fields
            'region' => 'nullable|string|max:255',
            'program_type' => 'nullable|string|max:255',
            'class_in_session' => 'required|in:0,1',
            'class_not_in_session_reason' => 'required_if:class_in_session,0|nullable|string',
            'full_session_observed' => 'required_if:class_in_session,1|nullable|in:0,1',
            'grade_group' => 'nullable|string',
            'grades_observed' => 'required_if:class_in_session,1|nullable|array',
            'grades_observed.*' => 'in:1,2,3,4,5,6,ទី១,ទី២,ទី៣,ទី៤,ទី៥,ទី៦',
            'subject_observed' => 'required_if:class_in_session,1|nullable|in:ភាសាខ្មែរ,គណិតវិទ្យា',
            'language_levels_observed' => 'required_if:subject_observed,ភាសាខ្មែរ|nullable|array',
            'language_levels_observed.*' => 'string',
            'numeracy_levels_observed' => 'required_if:subject_observed,គណិតវិទ្យា|nullable|array',
            'numeracy_levels_observed.*' => 'string',

            // Delivery questions
            'class_started_on_time' => 'required_if:class_in_session,1|nullable|in:0,1',
            'late_start_reason' => 'required_if:class_started_on_time,0|nullable|string',

            // Classroom questions
            'materials_present' => 'nullable|array',
            'materials_present.*' => 'string',
            'children_grouped_appropriately' => 'required_if:class_in_session,1|nullable|in:0,1',
            'students_fully_involved' => 'required_if:class_in_session,1|nullable|in:0,1',

            // Teacher questions
            'has_session_plan' => 'required_if:class_in_session,1|nullable|in:0,1',
            'no_session_plan_reason' => 'nullable|string',
            'followed_session_plan' => 'required_if:has_session_plan,1|nullable|in:0,1',
            'no_follow_plan_reason' => 'nullable|string',
            'session_plan_appropriate' => 'required_if:has_session_plan,1|nullable|in:0,1',

            // Activity overview
            'number_of_activities' => 'required_if:class_in_session,1|nullable|in:0,1,2,3',

            // Activity 1
            'activity1_name_language' => 'required_if:subject_observed,ភាសាខ្មែរ,number_of_activities,1|required_if:subject_observed,ភាសាខ្មែរ,number_of_activities,2|required_if:subject_observed,ភាសាខ្មែរ,number_of_activities,3|nullable|string',
            'activity1_name_numeracy' => 'required_if:subject_observed,គណិតវិទ្យា,number_of_activities,1|required_if:subject_observed,គណិតវិទ្យា,number_of_activities,2|required_if:subject_observed,គណិតវិទ្យា,number_of_activities,3|nullable|string',
            'activity1_duration' => 'required_if:number_of_activities,1|required_if:number_of_activities,2|required_if:number_of_activities,3|nullable|integer|min:1',
            'activity1_clear_instructions' => 'required_if:number_of_activities,1|required_if:number_of_activities,2|required_if:number_of_activities,3|nullable|in:0,1',
            'activity1_no_clear_instructions_reason' => 'nullable|string',
            'activity1_followed_process' => 'required_if:number_of_activities,1|required_if:number_of_activities,2|required_if:number_of_activities,3|nullable|in:0,1',
            'activity1_not_followed_reason' => 'nullable|string',

            // Activity 2
            'activity2_name_language' => 'required_if:subject_observed,ភាសាខ្មែរ,number_of_activities,2|required_if:subject_observed,ភាសាខ្មែរ,number_of_activities,3|nullable|string',
            'activity2_name_numeracy' => 'required_if:subject_observed,គណិតវិទ្យា,number_of_activities,2|required_if:subject_observed,គណិតវិទ្យា,number_of_activities,3|nullable|string',
            'activity2_duration' => 'required_if:number_of_activities,2|required_if:number_of_activities,3|nullable|integer|min:1',
            'activity2_clear_instructions' => 'required_if:number_of_activities,2|required_if:number_of_activities,3|nullable|in:0,1',
            'activity2_no_clear_instructions_reason' => 'nullable|string',
            'activity2_followed_process' => 'required_if:number_of_activities,2|required_if:number_of_activities,3|nullable|in:0,1',
            'activity2_not_followed_reason' => 'nullable|string',

            // Activity 3
            'activity3_name_language' => 'required_if:subject_observed,ភាសាខ្មែរ,number_of_activities,3|nullable|string',
            'activity3_name_numeracy' => 'required_if:subject_observed,គណិតវិទ្យា,number_of_activities,3|nullable|string',
            'activity3_duration' => 'required_if:number_of_activities,3|nullable|integer|min:1',
            'activity3_clear_instructions' => 'required_if:number_of_activities,3|nullable|in:0,1',
            'activity3_no_clear_instructions_reason' => 'nullable|string',
            'activity3_followed_process' => 'required_if:number_of_activities,3|nullable|in:0,1',
            'activity3_not_followed_reason' => 'nullable|string',

            // Questionnaire data
            'questionnaire' => 'nullable|array',
            'questionnaire.*' => 'nullable',

            // Optional fields (made nullable)
            'observation' => 'nullable|string',
            'action_plan' => 'nullable|string',
            'score' => 'nullable|integer|min:0|max:100',
            'follow_up_required' => 'nullable|in:0,1',
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
            'school_id.exists' => 'The selected school does not exist.',
            'teacher_id.exists' => 'The selected teacher does not exist.',
            'visit_date.date' => 'The visit date must be a valid date.',
            'score.min' => 'The score must be at least 0.',
            'score.max' => 'The score must not exceed 100.',
        ];
    }
}
