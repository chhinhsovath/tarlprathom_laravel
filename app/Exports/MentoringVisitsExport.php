<?php

namespace App\Exports;

use App\Models\MentoringVisit;
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

class MentoringVisitsExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $user = $this->request->user();
        $query = MentoringVisit::with(['mentor', 'teacher', 'school']);

        // Filter based on user role
        if ($user->isTeacher()) {
            // Teachers can only see visits where they are the teacher
            $query->where('teacher_id', $user->id);
        } elseif ($user->isMentor()) {
            // Mentors can see their own visits
            $query->where('mentor_id', $user->id);
        }

        // Add search functionality
        if ($this->request->has('search') && $this->request->get('search') !== '') {
            $search = $this->request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('observation', 'like', "%{$search}%")
                    ->orWhere('action_plan', 'like', "%{$search}%")
                    ->orWhereHas('teacher', function ($tq) use ($search) {
                        $tq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('mentor', function ($mq) use ($search) {
                        $mq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('school', function ($sq) use ($search) {
                        $sq->where('school_name', 'like', "%{$search}%");
                    });
            });
        }

        // Add filters
        if ($this->request->has('school_id') && $this->request->get('school_id') !== '') {
            $query->where('school_id', $this->request->get('school_id'));
        }

        if ($this->request->has('mentor_id') && $this->request->get('mentor_id') !== '') {
            $query->where('mentor_id', $this->request->get('mentor_id'));
        }

        if ($this->request->has('teacher_id') && $this->request->get('teacher_id') !== '') {
            $query->where('teacher_id', $this->request->get('teacher_id'));
        }

        // Filter by date range
        if ($this->request->has('from_date')) {
            $query->where('visit_date', '>=', $this->request->get('from_date'));
        }
        if ($this->request->has('to_date')) {
            $query->where('visit_date', '<=', $this->request->get('to_date'));
        }

        return $query->orderBy('visit_date', 'desc');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Visit Date',
            'Region',
            'Province',
            'School',
            'Teacher',
            'Mentor',
            'Program Type',
            'Class in Session',
            'Class Not in Session Reason',
            'Full Session Observed',
            'Grade Group',
            'Grades Observed',
            'Subject Observed',
            'Language Levels',
            'Numeracy Levels',
            'Score',
            'Observation',
            'Action Plan',
            'Follow-up Required',
            'Photo',
            'Created At',
        ];
    }

    public function map($visit): array
    {
        return [
            $visit->id,
            $visit->visit_date->format('Y-m-d'),
            $visit->region ?? 'N/A',
            $visit->province ?? 'N/A',
            $visit->school->name ?? 'N/A',
            $visit->teacher->name ?? 'N/A',
            $visit->mentor->name ?? 'N/A',
            $visit->program_type ?? 'TaRL',
            $visit->class_in_session ? 'Yes' : 'No',
            $visit->class_not_in_session_reason ?? 'N/A',
            $visit->full_session_observed ? 'Yes' : 'No',
            $visit->grade_group ?? 'N/A',
            $visit->grades_observed ? implode(', ', $visit->grades_observed) : 'N/A',
            $visit->subject_observed ?? 'N/A',
            $visit->language_levels_observed ? implode(', ', $visit->language_levels_observed) : 'N/A',
            $visit->numeracy_levels_observed ? implode(', ', $visit->numeracy_levels_observed) : 'N/A',
            $visit->score ?? 'N/A',
            $visit->observation ?? 'N/A',
            $visit->action_plan ?? 'N/A',
            $visit->follow_up_required ? 'Yes' : 'No',
            $visit->photo ? 'Yes' : 'No',
            $visit->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Apply Hanuman font to the entire sheet
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Hanuman');

        // Style the header row
        $sheet->getStyle('A1:V1')->applyFromArray([
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
        $sheet->getStyle('A2:V'.$highestRow)->applyFromArray([
            'font' => [
                'name' => 'Hanuman',
                'size' => 11,
            ],
        ]);

        return $sheet;
    }
}
