<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreUserRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user() && Auth::user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;
        
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\p{N}\s\-\'\.]+$/u', // Allow letters, numbers, spaces, hyphens, apostrophes, dots
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            ],
            'role' => [
                'required',
                'string',
                Rule::in(['admin', 'coordinator', 'mentor', 'teacher', 'viewer']),
            ],
            'school_id' => [
                'nullable',
                'exists:pilot_schools,id',
                'required_if:role,teacher',
            ],
            'province' => [
                'nullable',
                'string',
                'max:255',
            ],
            'district' => [
                'nullable',
                'string',
                'max:255',
            ],
            'commune' => [
                'nullable',
                'string',
                'max:255',
            ],
            'village' => [
                'nullable',
                'string',
                'max:255',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[\+]?[0-9\s\-\(\)]+$/',
            ],
            'sex' => [
                'nullable',
                'string',
                Rule::in(['M', 'F', 'Male', 'Female']),
            ],
            'holding_classes' => [
                'nullable',
                'string',
                'max:500',
            ],
            'assigned_subject' => [
                'nullable',
                'string',
                'max:255',
            ],
            'is_active' => [
                'boolean',
            ],
            'assigned_schools' => [
                'nullable',
                'array',
                'required_if:role,mentor',
            ],
            'assigned_schools.*' => [
                'exists:pilot_schools,id',
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => __('rbac.Name'),
            'email' => __('rbac.Email'),
            'password' => __('rbac.Password'),
            'role' => __('rbac.Role'),
            'school_id' => __('rbac.School'),
            'province' => __('rbac.Province'),
            'district' => __('rbac.District'),
            'commune' => __('rbac.Commune'),
            'village' => __('rbac.Village'),
            'phone' => __('rbac.Phone'),
            'sex' => __('rbac.Gender'),
            'holding_classes' => __('rbac.Holding Classes'),
            'assigned_subject' => __('rbac.Assigned Subject'),
            'is_active' => __('rbac.Status'),
            'assigned_schools' => __('rbac.Assigned Schools'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => __('rbac.The name field is required.'),
            'name.regex' => __('rbac.The name may only contain letters, numbers, spaces, hyphens, apostrophes, and dots.'),
            'email.required' => __('rbac.The email field is required.'),
            'email.email' => __('rbac.The email must be a valid email address.'),
            'email.unique' => __('rbac.The email has already been taken.'),
            'password.required' => __('rbac.The password field is required.'),
            'password.min' => __('rbac.The password must be at least 8 characters.'),
            'password.regex' => __('rbac.The password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.'),
            'role.required' => __('rbac.The role field is required.'),
            'role.in' => __('rbac.The selected role is invalid.'),
            'school_id.required_if' => __('rbac.The school field is required for teachers.'),
            'school_id.exists' => __('rbac.The selected school is invalid.'),
            'phone.regex' => __('rbac.The phone number format is invalid.'),
            'sex.in' => __('rbac.The selected gender is invalid.'),
            'assigned_schools.required_if' => __('rbac.At least one school must be assigned to mentors.'),
            'assigned_schools.*.exists' => __('rbac.One or more selected schools are invalid.'),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default values
        if (!$this->has('is_active')) {
            $this->merge(['is_active' => true]);
        }

        // Clean phone number
        if ($this->has('phone')) {
            $this->merge([
                'phone' => preg_replace('/[^\+0-9]/', '', $this->phone)
            ]);
        }

        // Normalize gender values
        if ($this->has('sex')) {
            $sex = strtolower($this->sex);
            if (in_array($sex, ['m', 'male'])) {
                $this->merge(['sex' => 'M']);
            } elseif (in_array($sex, ['f', 'female'])) {
                $this->merge(['sex' => 'F']);
            }
        }
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Custom business logic validation
            
            // Ensure admin cannot remove their own admin role if they're the last admin
            if ($this->route('user') && $this->route('user')->id === Auth::id()) {
                if ($this->role !== 'admin' && \App\Models\User::where('role', 'admin')->where('is_active', true)->count() <= 1) {
                    $validator->errors()->add('role', __('rbac.Cannot change role: You are the last active admin.'));
                }
            }

            // Validate mentor school assignments
            if ($this->role === 'mentor' && $this->has('assigned_schools')) {
                if (empty($this->assigned_schools)) {
                    $validator->errors()->add('assigned_schools', __('rbac.Mentors must be assigned to at least one school.'));
                }
            }

            // Validate teacher school assignment
            if ($this->role === 'teacher' && !$this->school_id) {
                $validator->errors()->add('school_id', __('rbac.Teachers must be assigned to a school.'));
            }
        });
    }
}