<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\School;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use Carbon\Carbon;

class SchoolStudentsImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;
    
    protected $school;
    protected $rowCount = 0;
    protected $errors = [];

    public function __construct(School $school)
    {
        $this->school = $school;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Skip empty rows
            if (empty($row['student_name']) || empty($row['date_of_birth'])) {
                continue;
            }

            // Parse date of birth
            try {
                $dob = Carbon::parse($row['date_of_birth']);
            } catch (\Exception $e) {
                continue; // Skip invalid dates
            }

            // Generate unique student ID
            $studentId = 'STU' . $this->school->id . '_' . time() . '_' . $this->rowCount;
            
            // Check for duplicate student (same name, DOB, and school)
            $existingStudent = Student::where('name', $row['student_name'])
                ->where('date_of_birth', $dob)
                ->where('school_id', $this->school->id)
                ->first();
                
            if ($existingStudent) {
                $this->errors[] = "Student '{$row['student_name']}' with DOB {$dob->format('Y-m-d')} already exists in this school.";
                continue;
            }

            // Create new student
            Student::create([
                'student_id' => $studentId,
                'name' => $row['student_name'],
                'date_of_birth' => $dob,
                'age' => $dob->age,
                'gender' => strtolower($row['gender']) === 'male' ? 'male' : 'female',
                'grade' => intval($row['grade']),
                'school_id' => $this->school->id,
                'parent_name' => $row['parentguardian_name'] ?? null,
                'parent_phone' => $row['parent_phone'] ?? null,
                'address' => $row['address'] ?? null,
                'village' => $row['village'] ?? null,
                'commune' => $row['commune'] ?? null,
                'district' => $row['district'] ?? $this->school->district,
                'province' => $row['province'] ?? $this->school->province,
                'enrollment_date' => now(),
                'status' => 'active',
            ]);

            $this->rowCount++;
        }
    }

    public function rules(): array
    {
        return [
            '*.student_name' => 'required|string|max:255',
            '*.date_of_birth' => 'required|date_format:Y-m-d',
            '*.gender' => 'required|in:male,female,Male,Female',
            '*.grade' => 'required|numeric|between:1,6',
            '*.parentguardian_name' => 'nullable|string|max:255',
            '*.parent_phone' => 'nullable|string|max:20',
            '*.address' => 'nullable|string|max:500',
            '*.village' => 'nullable|string|max:255',
            '*.commune' => 'nullable|string|max:255',
            '*.district' => 'nullable|string|max:255',
            '*.province' => 'nullable|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.student_name.required' => 'Student name is required.',
            '*.date_of_birth.required' => 'Date of birth is required.',
            '*.date_of_birth.date_format' => 'Date of birth must be in YYYY-MM-DD format.',
            '*.gender.required' => 'Gender is required.',
            '*.gender.in' => 'Gender must be either male or female.',
            '*.grade.required' => 'Grade is required.',
            '*.grade.between' => 'Grade must be between 1 and 6.',
        ];
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
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