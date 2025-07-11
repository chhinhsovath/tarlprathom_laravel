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
            'school_id' => 'required|exists:schools,id',
            'teacher_id' => 'required|exists:users,id',
            'visit_date' => 'required|date',
            'observation' => 'required|string',
            'score' => 'required|integer|min:0|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
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
            'photo.image' => 'The photo must be an image.',
            'photo.mimes' => 'The photo must be a file of type: jpeg, png, jpg, gif.',
            'photo.max' => 'The photo may not be greater than 5MB.',
        ];
    }
}