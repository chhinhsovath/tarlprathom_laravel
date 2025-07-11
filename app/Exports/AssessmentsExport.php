<?php

namespace App\Exports;

use App\Models\Assessment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Http\Request;

class AssessmentsExport implements FromQuery, WithHeadings, WithMapping
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
            'Updated At'
        ];
    }

    public function map($assessment): array
    {
        return [
            $assessment->id,
            $assessment->student->name,
            $assessment->student->grade,
            ucfirst($assessment->student->gender),
            $assessment->student->school->school_name ?? 'N/A',
            ucfirst($assessment->subject),
            ucfirst($assessment->cycle),
            $assessment->level,
            $assessment->score,
            $assessment->assessed_at->format('Y-m-d'),
            $assessment->created_at->format('Y-m-d H:i:s'),
            $assessment->updated_at->format('Y-m-d H:i:s')
        ];
    }
}