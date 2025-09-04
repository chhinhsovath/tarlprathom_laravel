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
            'region' => 'required|string|max:255',
            'program_type' => 'required|string|max:255',
            'class_in_session' => 'required|in:Yes,No',
            'class_not_in_session_reason' => 'required_if:class_in_session,No|nullable|string',
            'full_session_observed' => 'required_if:class_in_session,Yes|nullable|in:Yes,No',
            'grade_group' => 'required_if:class_in_session,Yes|nullable|string',
            'grades_observed' => 'required_if:class_in_session,Yes|nullable|array',
            'grades_observed.*' => 'in:1,2,3,4,5,6',
            'subject_observed' => 'required_if:class_in_session,Yes|nullable|in:Language,Numeracy',
            'language_levels_observed' => 'required_if:subject_observed,Language|nullable|array',
            'language_levels_observed.*' => 'string',
            'numeracy_levels_observed' => 'required_if:subject_observed,Numeracy|nullable|array',
            'numeracy_levels_observed.*' => 'string',

            // Delivery questions
            'class_started_on_time' => 'required_if:class_in_session,Yes|nullable|in:Yes,No',
            'late_start_reason' => 'required_if:class_started_on_time,No|nullable|string',

            // Classroom questions
            'materials_present' => 'nullable|array',
            'materials_present.*' => 'string',
            'children_grouped_appropriately' => 'required_if:class_in_session,Yes|nullable|in:Yes,No',
            'students_fully_involved' => 'required_if:class_in_session,Yes|nullable|in:Yes,No',

            // Teacher questions
            'has_session_plan' => 'required_if:class_in_session,Yes|nullable|in:Yes,No',
            'no_session_plan_reason' => 'nullable|string',
            'followed_session_plan' => 'required_if:has_session_plan,Yes|nullable|in:Yes,No',
            'no_follow_plan_reason' => 'nullable|string',
            'session_plan_appropriate' => 'required_if:has_session_plan,Yes|nullable|in:Yes,No',

            // Activity overview
            'number_of_activities' => 'required_if:class_in_session,Yes|nullable|in:1,2,3',

            // Activity 1
            'activity1_name_language' => 'required_if:subject_observed,Language,number_of_activities,1|required_if:subject_observed,Language,number_of_activities,2|required_if:subject_observed,Language,number_of_activities,3|nullable|string',
            'activity1_name_numeracy' => 'required_if:subject_observed,Numeracy,number_of_activities,1|required_if:subject_observed,Numeracy,number_of_activities,2|required_if:subject_observed,Numeracy,number_of_activities,3|nullable|string',
            'activity1_duration' => 'required_if:number_of_activities,1|required_if:number_of_activities,2|required_if:number_of_activities,3|nullable|integer|min:1',
            'activity1_clear_instructions' => 'required_if:number_of_activities,1|required_if:number_of_activities,2|required_if:number_of_activities,3|nullable|in:Yes,No',
            'activity1_no_clear_instructions_reason' => 'nullable|string',
            'activity1_demonstrated' => 'required_if:number_of_activities,1|required_if:number_of_activities,2|required_if:number_of_activities,3|nullable|in:Yes,No',
            'activity1_students_practice' => 'required_if:number_of_activities,1|required_if:number_of_activities,2|required_if:number_of_activities,3|nullable|string',
            'activity1_small_groups' => 'required_if:number_of_activities,1|required_if:number_of_activities,2|required_if:number_of_activities,3|nullable|string',
            'activity1_individual' => 'required_if:number_of_activities,1|required_if:number_of_activities,2|required_if:number_of_activities,3|nullable|string',

            // Activity 2
            'activity2_name_language' => 'required_if:subject_observed,Language,number_of_activities,2|required_if:subject_observed,Language,number_of_activities,3|nullable|string',
            'activity2_name_numeracy' => 'required_if:subject_observed,Numeracy,number_of_activities,2|required_if:subject_observed,Numeracy,number_of_activities,3|nullable|string',
            'activity2_duration' => 'required_if:number_of_activities,2|required_if:number_of_activities,3|nullable|integer|min:1',
            'activity2_clear_instructions' => 'required_if:number_of_activities,2|required_if:number_of_activities,3|nullable|in:Yes,No',
            'activity2_no_clear_instructions_reason' => 'nullable|string',
            'activity2_demonstrated' => 'required_if:number_of_activities,2|required_if:number_of_activities,3|nullable|in:Yes,No',
            'activity2_students_practice' => 'required_if:number_of_activities,2|required_if:number_of_activities,3|nullable|string',
            'activity2_small_groups' => 'required_if:number_of_activities,2|required_if:number_of_activities,3|nullable|string',
            'activity2_individual' => 'required_if:number_of_activities,2|required_if:number_of_activities,3|nullable|string',

            // Activity 3
            'activity3_name_language' => 'required_if:subject_observed,Language,number_of_activities,3|nullable|string',
            'activity3_name_numeracy' => 'required_if:subject_observed,Numeracy,number_of_activities,3|nullable|string',
            'activity3_duration' => 'required_if:number_of_activities,3|nullable|integer|min:1',
            'activity3_clear_instructions' => 'required_if:number_of_activities,3|nullable|in:Yes,No',
            'activity3_no_clear_instructions_reason' => 'nullable|string',
            'activity3_demonstrated' => 'required_if:number_of_activities,3|nullable|in:Yes,No',
            'activity3_students_practice' => 'required_if:number_of_activities,3|nullable|string',
            'activity3_small_groups' => 'required_if:number_of_activities,3|nullable|string',
            'activity3_individual' => 'required_if:number_of_activities,3|nullable|string',

            // Questionnaire data
            'questionnaire' => 'nullable|array',
            'questionnaire.*' => 'nullable',

            // Optional fields (made nullable)
            'observation' => 'nullable|string',
            'action_plan' => 'nullable|string',
            'score' => 'nullable|integer|min:0|max:100',
            'follow_up_required' => 'nullable|in:Yes,No',
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
