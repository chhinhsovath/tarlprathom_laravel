<?php

namespace App\Imports;

use App\Models\PilotSchool;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;

class PilotSchoolsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Log the row data for debugging
        Log::info('Importing pilot school row:', $row);

        // Map the Excel columns to our database fields
        // Adjust these column names based on the actual Excel headers
        return new PilotSchool([
            'province' => $row['province'] ?? $row['ខេត្ត'] ?? '',
            'district' => $row['district'] ?? $row['ស្រុក'] ?? '',
            'cluster' => $row['cluster'] ?? $row['កម្រង'] ?? '',
            'school_name' => $row['school_name'] ?? $row['សាលារៀន'] ?? $row['ឈ្មោះសាលារៀន'] ?? '',
            'school_code' => $row['school_code'] ?? $row['លេខកូដសាលារៀន'] ?? $row['កូដ'] ?? '',
        ]);
    }

    /**
     * Validation rules for each row
     */
    public function rules(): array
    {
        return [
            'province' => 'required|string|max:100',
            'district' => 'required|string|max:100', 
            'cluster' => 'required|string|max:100',
            'school_name' => 'required|string|max:255',
            'school_code' => 'required|string|max:20|unique:pilot_schools,school_code',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'school_code.unique' => 'School code :input already exists in the database.',
            'required' => 'The :attribute field is required.',
        ];
    }
}