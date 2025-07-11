<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssessmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Admin, teacher, and mentor can create assessments
        return in_array($this->user()->role, ['admin', 'teacher', 'mentor']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'cycle' => 'required|string|max:50',
            'subject' => 'required|string|max:100',
            'level' => 'required|string|max:50',
            'score' => 'required|numeric|min:0|max:100',
            'assessed_at' => 'required|date',
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
            'student_id.exists' => 'The selected student does not exist.',
            'score.min' => 'The score must be at least 0.',
            'score.max' => 'The score must not exceed 100.',
            'assessed_at.date' => 'The assessment date must be a valid date.',
        ];
    }
}