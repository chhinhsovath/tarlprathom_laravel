<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\MentoringVisit;
use App\Models\School;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ChartComponent extends Component
{
    public $chartType = 'studentsBySchool';

    public $filterSchool = null;

    public $filterDateFrom = null;

    public $filterDateTo = null;

    public function mount()
    {
        $this->filterDateFrom = now()->subMonths(6)->format('Y-m-d');
        $this->filterDateTo = now()->format('Y-m-d');
    }

    public function getChartData()
    {
        switch ($this->chartType) {
            case 'studentsBySchool':
                return $this->getStudentsBySchoolData();
            case 'assessmentsByLevel':
                return $this->getAssessmentsByLevelData();
            case 'mentoringTrend':
                return $this->getMentoringTrendData();
            case 'studentProgress':
                return $this->getStudentProgressData();
            default:
                return [];
        }
    }

    private function getStudentsBySchoolData()
    {
        $data = Student::select('schools.school_name as school_name', DB::raw('count(students.id) as count'))
            ->join('schools', 'students.school_id', '=', 'schools.id')
            ->groupBy('schools.id', 'schools.school_name')
            ->get();

        return [
            'labels' => $data->pluck('name')->toArray(),
            'datasets' => [[
                'label' => 'Students',
                'data' => $data->pluck('count')->toArray(),
                'backgroundColor' => ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
            ]],
        ];
    }

    private function getAssessmentsByLevelData()
    {
        $query = Assessment::select('level', DB::raw('count(*) as count'))
            ->groupBy('level');

        if ($this->filterSchool) {
            $query->whereHas('student', function ($q) {
                $q->where('school_id', $this->filterSchool);
            });
        }

        $data = $query->get();

        return [
            'labels' => $data->pluck('level')->toArray(),
            'datasets' => [[
                'label' => 'Assessments',
                'data' => $data->pluck('count')->toArray(),
                'backgroundColor' => ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899'],
            ]],
        ];
    }

    private function getMentoringTrendData()
    {
        $data = MentoringVisit::select(
            DB::raw('DATE_FORMAT(visit_date, "%Y-%m") as month'),
            DB::raw('count(*) as count')
        )
            ->whereBetween('visit_date', [$this->filterDateFrom, $this->filterDateTo])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'labels' => $data->pluck('month')->toArray(),
            'datasets' => [[
                'label' => 'Mentoring Visits',
                'data' => $data->pluck('count')->toArray(),
                'borderColor' => '#4F46E5',
                'tension' => 0.1,
                'fill' => false,
            ]],
        ];
    }

    private function getStudentProgressData()
    {
        $cycles = ['baseline', 'midline', 'endline'];
        $subjects = ['khmer', 'math'];
        $datasets = [];

        foreach ($subjects as $subject) {
            $data = [];
            foreach ($cycles as $cycle) {
                $avg = Assessment::where('cycle', $cycle)
                    ->where('subject', $subject)
                    ->whereNotNull('score')
                    ->avg('score') ?? 0;
                $data[] = round($avg, 2);
            }

            $datasets[] = [
                'label' => ucfirst($subject),
                'data' => $data,
                'borderColor' => $subject === 'khmer' ? '#4F46E5' : '#10B981',
                'backgroundColor' => $subject === 'khmer' ? 'rgba(79, 70, 229, 0.1)' : 'rgba(16, 185, 129, 0.1)',
            ];
        }

        return [
            'labels' => ['Baseline', 'Midline', 'Endline'],
            'datasets' => $datasets,
        ];
    }

    public function updated()
    {
        $this->dispatch('chartDataUpdated');
    }

    public function render()
    {
        return view('livewire.chart-component', [
            'chartData' => $this->getChartData(),
            'schools' => School::all(),
        ]);
    }
}
