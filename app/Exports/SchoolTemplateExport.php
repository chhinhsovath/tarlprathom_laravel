<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SchoolTemplateExport implements FromArray, WithColumnWidths, WithEvents, WithHeadings, WithStyles
{
    public function array(): array
    {
        // Provide sample data to help users understand the format
        return [
            [
                'សាលា​បឋម​សិក្សា​ភូមិ​ថ្មី', // School Name
                'SCH001',                      // School Code
                'ខេត្ត​កំពង់ចាម',              // Province
                'ស្រុក​កំពង់សៀម',             // District
                'ឃុំ​ជាំក្រវៀន',               // Cluster (optional)
            ],
            [
                'សាលា​បឋម​សិក្សា​ចំការ​លើ',
                'SCH002',
                'ខេត្ត​កំពង់ចាម',
                'ស្រុក​កំពង់សៀម',
                'ឃុំ​ដូនផែន',
            ],
            [
                'Example Primary School',
                'SCH003',
                'Kampong Cham',
                'Kampong Siem',
                'Chamkar Andoung',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'School Name',
            'School Code',
            'Province',
            'District',
            'Cluster',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,  // School Name
            'B' => 15,  // School Code
            'C' => 20,  // Province
            'D' => 20,  // District
            'E' => 20,  // Cluster
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
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Add borders to all cells with data
        $sheet->getStyle('A1:E'.($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Add data validation for required fields
                // Add comments to help users
                $sheet->getComment('A1')->getText()->createTextRun('Required: Enter the full name of the school');
                $sheet->getComment('B1')->getText()->createTextRun('Required: Enter a unique school code');
                $sheet->getComment('C1')->getText()->createTextRun('Required: Enter the province name');
                $sheet->getComment('D1')->getText()->createTextRun('Required: Enter the district name');
                $sheet->getComment('E1')->getText()->createTextRun('Optional: Enter the cluster/commune name if applicable');

                // Auto-filter
                $sheet->setAutoFilter('A1:E1');
            },
        ];
    }
}
