<?php

namespace App\Exports;

use App\Models\School;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentTemplateExport implements FromArray, WithColumnWidths, WithHeadings, WithStyles
{
    public function array(): array
    {
        $schools = School::orderBy('school_name')->pluck('school_name')->toArray();
        $firstSchool = $schools[0] ?? 'School Name';

        // Get teachers for the first school as examples
        $teachers = User::where('role', 'teacher')
            ->whereHas('school', function ($query) use ($firstSchool) {
                $query->where('school_name', $firstSchool);
            })
            ->pluck('name')
            ->toArray();
        $firstTeacher = $teachers[0] ?? 'Teacher Name';

        // Return sample data
        return [
            ['John Doe', 10, 'male', 4, $firstSchool, $firstTeacher],
            ['Jane Smith', 11, 'female', 5, $firstSchool, ''],
            ['', '', '', '', '', ''], // Empty row for user to fill
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Age',
            'Gender',
            'Grade',
            'School',
            'Teacher (Optional)',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25, // Name
            'B' => 10, // Age
            'C' => 15, // Gender
            'D' => 10, // Grade
            'E' => 30, // School
            'F' => 30, // Teacher
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the header row
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E5E7EB'],
                ],
            ],
        ];
    }
}
