<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\School;

class StudentTemplateExport implements FromArray, WithHeadings, WithStyles, WithTitle
{
    protected $school;

    public function __construct(School $school)
    {
        $this->school = $school;
    }

    public function headings(): array
    {
        return [
            'Student Name *',
            'Date of Birth (YYYY-MM-DD) *',
            'Gender (male/female) *',
            'Grade (1-6) *',
            'Parent/Guardian Name',
            'Parent Phone',
            'Address',
            'Village',
            'Commune',
            'District',
            'Province',
        ];
    }

    public function array(): array
    {
        // Return sample data with school's location pre-filled
        return [
            ['សិស្សទី១', '2015-05-15', 'male', '4', 'មាតាបិតា១', '012345678', 'ភូមិ១', 'Village 1', 'Commune 1', $this->school->district, $this->school->province],
            ['សិស្សទី២', '2014-08-20', 'female', '5', 'មាតាបិតា២', '012345679', 'ភូមិ២', 'Village 2', 'Commune 2', $this->school->district, $this->school->province],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '059669'],
            ],
        ]);

        // Auto-size columns
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Add comments to headers
        $sheet->getComment('A1')->getText()->createTextRun('Required: Full name of the student');
        $sheet->getComment('B1')->getText()->createTextRun('Required: Format YYYY-MM-DD (e.g., 2015-05-15)');
        $sheet->getComment('C1')->getText()->createTextRun('Required: Enter "male" or "female"');
        $sheet->getComment('D1')->getText()->createTextRun('Required: Enter grade 1-6');

        return [];
    }

    public function title(): string
    {
        return 'Student Import - ' . $this->school->school_name;
    }
}