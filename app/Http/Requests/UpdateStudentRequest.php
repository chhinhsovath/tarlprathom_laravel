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
            'age' => 'required|integer|min:3|max:18',
            'grade' => 'required|integer|in:4,5',
            'gender' => 'required|in:male,female',
            'school_id' => 'required|exists:schools,id',
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
            'age.min' => 'The age must be at least 3 years.',
            'age.max' => 'The age must not exceed 18 years.',
            'gender.in' => 'The gender field must be either male or female.',
            'grade.in' => 'The grade must be either 4 or 5.',
            'school_id.exists' => 'The selected school does not exist.',
        ];
    }
}
