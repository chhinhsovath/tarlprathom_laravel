<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Student::class);
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
            'class' => 'required|string|in:Grade 1,Grade 2,Grade 3,Grade 4,Grade 5,Grade 6',
            'sex' => 'required|in:male,female',
            'gender' => 'nullable|in:male,female',
            'school_id' => 'required|exists:schools,id',
            'pilot_school_id' => 'nullable|exists:pilot_schools,id',
            'teacher_id' => 'nullable|exists:users,id',
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
            'sex.in' => 'The sex field must be either male or female.',
            'gender.in' => 'The gender field must be either male or female.',
            'class.in' => 'The class must be a valid grade level.',
            'school_id.exists' => 'The selected school does not exist.',
            'teacher_id.exists' => 'The selected teacher does not exist.',
        ];
    }
}
