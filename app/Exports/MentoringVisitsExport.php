<?php

namespace App\Exports;

use App\Models\MentoringVisit;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Http\Request;

class MentoringVisitsExport implements FromQuery, WithHeadings, WithMapping
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
            $query->where(function($q) use ($search) {
                $q->where('observation', 'like', "%{$search}%")
                  ->orWhere('action_plan', 'like', "%{$search}%")
                  ->orWhereHas('teacher', function($tq) use ($search) {
                      $tq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('mentor', function($mq) use ($search) {
                      $mq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('school', function($sq) use ($search) {
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
            'School',
            'Teacher',
            'Mentor',
            'Score',
            'Observation',
            'Action Plan',
            'Follow-up Required',
            'Photo',
            'Created At',
            'Updated At'
        ];
    }

    public function map($visit): array
    {
        return [
            $visit->id,
            $visit->visit_date->format('Y-m-d'),
            $visit->school->school_name ?? 'N/A',
            $visit->teacher->name ?? 'N/A',
            $visit->mentor->name ?? 'N/A',
            $visit->score,
            $visit->observation,
            $visit->action_plan,
            $visit->follow_up_required ? 'Yes' : 'No',
            $visit->photo ? 'Yes' : 'No',
            $visit->created_at->format('Y-m-d H:i:s'),
            $visit->updated_at->format('Y-m-d H:i:s')
        ];
    }
}