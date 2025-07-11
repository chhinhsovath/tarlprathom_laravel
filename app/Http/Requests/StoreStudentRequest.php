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
            'sex' => 'required|in:M,F',
            'age' => 'required|integer|min:5|max:18',
            'class' => 'required|string|max:50',
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
            'sex.in' => 'The sex field must be either M (Male) or F (Female).',
            'age.min' => 'The student must be at least 5 years old.',
            'age.max' => 'The student must be no more than 18 years old.',
            'school_id.exists' => 'The selected school does not exist.',
        ];
    }
}