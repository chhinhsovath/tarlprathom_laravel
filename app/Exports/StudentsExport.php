<?php

namespace App\Exports;

use App\Models\Student;
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

class StudentsExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Student::with('school');

        // Apply filters
        if ($this->request->user()->isTeacher()) {
            $query->where('school_id', $this->request->user()->school_id);
        }

        if ($this->request->has('search')) {
            $search = $this->request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        if ($this->request->has('school_id') && $this->request->get('school_id') !== '') {
            $query->where('school_id', $this->request->get('school_id'));
        }

        if ($this->request->has('grade') && $this->request->get('grade') !== '') {
            $query->where('grade', $this->request->get('grade'));
        }

        if ($this->request->has('gender') && $this->request->get('gender') !== '') {
            $query->where('gender', $this->request->get('gender'));
        }

        return $query->orderBy('name');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Grade',
            'Gender',
            'Date of Birth',
            'School',
            'Address',
            'Parent/Guardian Name',
            'Contact Number',
            'Created At',
            'Updated At',
        ];
    }

    public function map($student): array
    {
        return [
            $student->id,
            $student->name,
            $student->grade,
            ucfirst($student->gender),
            $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '',
            $student->school->name ?? 'N/A',
            $student->address ?? '',
            $student->parent_name ?? '',
            $student->contact_number ?? '',
            $student->created_at->format('Y-m-d H:i:s'),
            $student->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Apply Hanuman font to the entire sheet
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Hanuman');

        // Style the header row
        $sheet->getStyle('A1:K1')->applyFromArray([
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
        $sheet->getStyle('A2:K'.$highestRow)->applyFromArray([
            'font' => [
                'name' => 'Hanuman',
                'size' => 11,
            ],
        ]);

        return $sheet;
    }
}
