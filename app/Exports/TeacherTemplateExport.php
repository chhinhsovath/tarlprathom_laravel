<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\School;

class TeacherTemplateExport implements FromArray, WithHeadings, WithStyles, WithTitle
{
    protected $school;

    public function __construct(School $school)
    {
        $this->school = $school;
    }

    public function headings(): array
    {
        return [
            'Name *',
            'Email *',
            'Phone',
            'Address',
            'Password (leave blank for default: admin123)',
        ];
    }

    public function array(): array
    {
        // Return sample data
        return [
            ['John Doe', 'john.doe@example.com', '012345678', 'Phnom Penh', ''],
            ['Jane Smith', 'jane.smith@example.com', '012345679', 'Battambang', 'customPassword123'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'],
            ],
        ]);

        // Auto-size columns
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Add comment to header
        $sheet->getComment('A1')->getText()->createTextRun('Required field: Teacher full name');
        $sheet->getComment('B1')->getText()->createTextRun('Required field: Unique email address');
        $sheet->getComment('E1')->getText()->createTextRun('Optional: Leave blank to use default password (admin123)');

        return [];
    }

    public function title(): string
    {
        return 'Teacher Import - ' . $this->school->school_name;
    }
}