<?php

namespace App\Exports;

use App\Models\Assessment;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssessmentsExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Assessment::with(['student', 'student.school']);

        // Filter by school for teachers
        if ($this->request->user()->isTeacher()) {
            $query->whereHas('student', function ($q) {
                $q->where('school_id', $this->request->user()->school_id);
            });
        }

        // Search by student name
        if ($this->request->has('search') && $this->request->get('search') !== '') {
            $search = $this->request->get('search');
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by student
        if ($this->request->has('student_id')) {
            $query->where('student_id', $this->request->get('student_id'));
        }

        // Filter by subject
        if ($this->request->has('subject') && $this->request->get('subject') !== '') {
            $query->where('subject', $this->request->get('subject'));
        }

        // Filter by cycle
        if ($this->request->has('cycle') && $this->request->get('cycle') !== '') {
            $query->where('cycle', $this->request->get('cycle'));
        }

        // Filter by date range
        if ($this->request->has('from_date')) {
            $query->where('assessed_at', '>=', $this->request->get('from_date'));
        }
        if ($this->request->has('to_date')) {
            $query->where('assessed_at', '<=', $this->request->get('to_date'));
        }

        return $query->orderBy('assessed_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Student Name',
            'Student Grade',
            'Student Gender',
            'School',
            'Subject',
            'Test Cycle',
            'Student Level',
            'Score',
            'Assessment Date',
            'Created At',
            'Updated At',
        ];
    }

    public function map($assessment): array
    {
        return [
            $assessment->id,
            $assessment->student->name,
            $assessment->student->grade,
            ucfirst($assessment->student->gender),
            $assessment->student->school->name ?? 'N/A',
            ucfirst($assessment->subject),
            ucfirst($assessment->cycle),
            $assessment->level,
            $assessment->score,
            $assessment->assessed_at->format('Y-m-d'),
            $assessment->created_at->format('Y-m-d H:i:s'),
            $assessment->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Apply Hanuman font to the entire sheet
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Hanuman');

        // Style the header row
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'name' => 'Hanuman',
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Apply font to all data cells
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A2:L'.$highestRow)->applyFromArray([
            'font' => [
                'name' => 'Hanuman',
                'size' => 11,
            ],
        ]);

        return $sheet;
    }
}
