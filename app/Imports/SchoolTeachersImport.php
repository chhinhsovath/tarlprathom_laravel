<?php

namespace App\Imports;

use App\Models\User;
use App\Models\School;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;

class SchoolTeachersImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;
    
    protected $school;
    protected $rowCount = 0;
    protected $errors = [];
    protected $updatedCount = 0;

    public function __construct(School $school)
    {
        $this->school = $school;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Skip empty rows
            if (empty($row['name']) || empty($row['email'])) {
                continue;
            }

            // Check if user already exists
            $user = User::where('email', $row['email'])->first();

            if (!$user) {
                // Create new teacher
                $user = User::create([
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'password' => Hash::make($row['password'] ?? 'admin123'),
                    'role' => 'teacher',
                    'phone' => $row['phone'] ?? null,
                    'address' => $row['address'] ?? null,
                    'school_id' => $this->school->id,
                    'is_active' => true,
                ]);
            } else {
                // Update existing user to be a teacher at this school
                $user->update([
                    'school_id' => $this->school->id,
                    'role' => 'teacher',
                    'is_active' => true,
                ]);
                $this->updatedCount++;
            }

            $this->rowCount++;
        }
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:255',
            '*.email' => 'required|email|max:255',
            '*.phone' => 'nullable|string|max:20',
            '*.address' => 'nullable|string|max:500',
            '*.password' => 'nullable|string|min:6|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.name.required' => 'Teacher name is required.',
            '*.email.required' => 'Teacher email is required.',
            '*.email.email' => 'Please provide a valid email address.',
        ];
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }
    
    public function getUpdatedCount(): int
    {
        return $this->updatedCount;
    }
    
    public function getErrors(): array
    {
        return $this->errors;
    }
    
    public function onError(\Throwable $e)
    {
        // Handle errors gracefully
        $this->errors[] = 'Error on row: ' . $e->getMessage();
    }
    
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->errors[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
        }
    }
}