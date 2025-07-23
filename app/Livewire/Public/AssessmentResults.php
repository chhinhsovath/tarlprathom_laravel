<?php

namespace App\Livewire\Public;

use App\Models\Assessment;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AssessmentResults extends Component
{
    public $chartData = [];

    public $cycleData = [];

    public $selectedSubject = 'khmer';

    public $isLoading = false;

    public $levels = ['Beginner', 'Reader', 'Word', 'Paragraph', 'Story'];

    public function mount()
    {
        $this->loadAssessmentData();
    }

    public function updatedSelectedSubject()
    {
        $this->loadAssessmentData();
    }

    private function loadAssessmentData()
    {
        // Get assessment counts by level for the selected subject
        $levelCounts = Assessment::select('level', DB::raw('count(*) as count'))
            ->where('subject', $this->selectedSubject)
            ->whereNotNull('level')
            ->groupBy('level')
            ->pluck('count', 'level')
            ->toArray();

        // Log for debugging
        \Log::info('Loading assessment data for '.$this->selectedSubject, $levelCounts);

        // Prepare chart data
        $data = [];
        $colors = ['#d32f2f', '#f57c00', '#fbc02d', '#388e3c', '#2e7d32'];

        foreach ($this->levels as $level) {
            $data[] = $levelCounts[$level] ?? 0;
        }

        $this->chartData = [
            'labels' => [__('Emergent'), __('Letter'), __('Word'), __('Paragraph'), __('Story')],
            'datasets' => [[
                'label' => __($this->selectedSubject === 'khmer' ? 'Khmer Assessment Results' : 'Math Assessment Results'),
                'data' => $data,
                'backgroundColor' => $colors,
                'borderWidth' => 1,
                'indexAxis' => 'y', // Horizontal bar chart
            ]],
        ];

        // Get cycle data
        $this->cycleData = Assessment::select('cycle', DB::raw('count(DISTINCT student_id) as count'))
            ->where('subject', $this->selectedSubject)
            ->groupBy('cycle')
            ->pluck('count', 'cycle')
            ->toArray();

        // Calculate total unique students
        $this->cycleData['total'] = Assessment::where('subject', $this->selectedSubject)
            ->distinct('student_id')
            ->count('student_id');
    }

    public function render()
    {
        return view('livewire.public.assessment-results', [
            'chartData' => $this->chartData,
            'cycleData' => $this->cycleData,
        ]);
    }
}
