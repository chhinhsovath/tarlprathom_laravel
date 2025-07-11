<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('student'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'grade' => 'required|integer|min:1|max:6',
            'gender' => 'required|in:male,female',
            'school_id' => 'required|exists:schools,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
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
            'gender.in' => 'The gender field must be either male or female.',
            'grade.min' => 'The grade must be at least 1.',
            'grade.max' => 'The grade must be no more than 6.',
            'school_id.exists' => 'The selected school does not exist.',
            'photo.image' => 'The file must be an image.',
            'photo.max' => 'The photo may not be greater than 5MB.',
        ];
    }
}