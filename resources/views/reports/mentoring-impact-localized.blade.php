@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ trans_db('Mentoring Impact Report') }}</h3>
                    <a href="{{ route('reports.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        ← {{ trans_db('Back to Reports') }}
                    </a>
                </div>
                
                <!-- Date Range Filter -->
                <form method="GET" action="{{ route('reports.mentoring-impact') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">{{ trans_db('Start Date') }}</label>
                        <input type="date" name="start_date" id="start_date" value="{{ $startDate->format('Y-m-d') }}" 
                               class="w-full h-11 px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">{{ trans_db('End Date') }}</label>
                        <input type="date" name="end_date" id="end_date" value="{{ $endDate->format('Y-m-d') }}" 
                               class="w-full h-11 px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full h-11 inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            {{ trans_db('Apply Filters') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- How Calculations Work -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">{{ trans_db('How We Calculate Impact') }}</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>{{ trans_db('Baseline Percentage') }}:</strong> {{ trans_db('Percentage of students at advanced levels (above Beginner/Letter/1-Digit) during baseline assessment') }}</li>
                            <li><strong>{{ trans_db('Latest Percentage') }}:</strong> {{ trans_db('Percentage of students at advanced levels from the most recent assessment cycle (midline or endline)') }}</li>
                            <li><strong>{{ trans_db('Improvement') }}:</strong> {{ trans_db('Latest Percentage minus Baseline Percentage. Positive values indicate improvement') }}</li>
                        </ul>
                        <p class="mt-2">{{ trans_db('The scatter plot shows the relationship between the number of mentoring visits and the performance improvement for each school.') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Impact Correlation Chart -->
        @if(count($schoolsWithImprovements) > 0)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <h4 class="text-md font-medium text-gray-900 mb-4">{{ trans_db('Mentoring Visits vs Performance Improvement') }}</h4>
                
                <div class="relative" style="height: 400px;">
                    <canvas id="impactChart"></canvas>
                </div>
                
                <div class="mt-4 text-sm text-gray-600">
                    <p>{{ trans_db('Each point represents a school. The X-axis shows the number of mentoring visits received, and the Y-axis shows the improvement in percentage of students at advanced levels.') }}</p>
                </div>
            </div>
        </div>
        @endif
        
        <!-- School Impact Summary -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <h4 class="text-md font-medium text-gray-900 mb-4">{{ trans_db('School Impact Summary') }}</h4>
                
                <!-- Column Explanations -->
                <div class="mb-4 p-3 bg-gray-50 rounded-lg text-sm text-gray-600">
                    <p class="font-medium mb-2">{{ trans_db('Understanding the Data:') }}</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div>
                            <span class="font-semibold">{{ trans_db('Total Visits') }}:</span> {{ trans_db('Number of mentoring visits during the selected period') }}
                        </div>
                        <div>
                            <span class="font-semibold">{{ trans_db('Teachers Mentored') }}:</span> {{ trans_db('Number of unique teachers who received mentoring') }}
                        </div>
                        <div>
                            <span class="font-semibold">{{ trans_db('Avg Mentoring Score') }}:</span> {{ trans_db('Average score given by mentors (quality of teaching observed)') }}
                        </div>
                        <div>
                            <span class="font-semibold">{{ trans_db('Baseline/Latest %') }}:</span> {{ trans_db('Percentage of students at advanced levels') }}
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans_db('School') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans_db('Total Visits') }}
                                    <span class="normal-case text-gray-400" title="{{ trans_db('Number of mentoring visits') }}">ⓘ</span>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans_db('Teachers Mentored') }}
                                    <span class="normal-case text-gray-400" title="{{ trans_db('Unique teachers who received mentoring') }}">ⓘ</span>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans_db('Classes In Session') }}
                                    <span class="normal-case text-gray-400" title="{{ trans_db('Percentage of visits where class was in session') }}">ⓘ</span>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans_db('Session Plans') }}
                                    <span class="normal-case text-gray-400" title="{{ trans_db('Percentage of teachers with session plans') }}">ⓘ</span>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans_db('Baseline %') }}
                                    <span class="normal-case text-gray-400" title="{{ trans_db('Percentage of students at advanced levels at baseline') }}">ⓘ</span>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans_db('Latest %') }}
                                    <span class="normal-case text-gray-400" title="{{ trans_db('Percentage of students at advanced levels at most recent assessment') }}">ⓘ</span>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans_db('Improvement') }}
                                    <span class="normal-case text-gray-400" title="{{ trans_db('Change from baseline to latest (Latest - Baseline)') }}">ⓘ</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($schoolsWithImprovements as $data)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $data['school']->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($data['total_visits']) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($visitsBySchool[$data['school']->id]['unique_teachers'] ?? 0) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($visitsBySchool[$data['school']->id]['classes_in_session_rate'] ?? 0, 0) }}%
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($visitsBySchool[$data['school']->id]['has_session_plan_rate'] ?? 0, 0) }}%
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($data['baseline_percentage'], 1) }}%
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($data['latest_percentage'], 1) }}%
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($data['improvement'] > 0) bg-green-100 text-green-800
                                        @elseif($data['improvement'] < 0) bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        @if($data['improvement'] > 0)+@endif{{ number_format($data['improvement'], 1) }}%
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    {{ trans_db('No data available for the selected period') }}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if(count($schoolsWithImprovements) > 0)
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
                    <p><strong>{{ trans_db('Note') }}:</strong> {{ trans_db('This analysis compares the percentage of students at advanced levels between baseline and the most recent assessment (midline or endline). Schools without both baseline and follow-up assessments are not included in this table.') }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Visit Details by School -->
        @if($visitsBySchool->count() > 0)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h4 class="text-md font-medium text-gray-900 mb-4">{{ trans_db('Visit Details by School') }}</h4>
                
                <div class="space-y-4">
                    @foreach($visitsBySchool as $schoolId => $data)
                    <div class="border rounded-lg p-4">
                        <h5 class="font-medium text-gray-900 mb-2">{{ $data['school']->name }}</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">
                                    {{ trans_db('Total Visits') }}: {{ $data['total_visits'] }} | 
                                    {{ trans_db('Teachers') }}: {{ $data['unique_teachers'] }} |
                                    {{ trans_db('Follow-up Required') }}: {{ $data['follow_up_required'] }}
                                </p>
                            </div>
                            <div class="text-right">
                                <button onclick="toggleVisitDetails('school-{{ $schoolId }}')" 
                                        class="text-sm text-blue-600 hover:text-blue-800">
                                    {{ trans_db('Show Details') }} ↓
                                </button>
                            </div>
                        </div>
                        
                        <div id="school-{{ $schoolId }}" class="hidden mt-4">
                            <div class="mb-2 text-xs text-gray-500">
                                <p>{{ trans_db('Individual visit records during the selected period:') }}</p>
                            </div>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ trans_db('Date') }}</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ trans_db('Teacher') }}</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ trans_db('Subject') }}</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ trans_db('Session') }}</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ trans_db('Follow-up') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($data['visits'] as $visit)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-500">{{ $visit->visit_date->format('Y-m-d') }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $visit->teacher->name }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500">{{ $visit->subject_observed ?? '-' }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500">
                                            @if($visit->class_in_session)
                                                <span class="text-green-600">✓</span>
                                            @else
                                                <span class="text-red-600">✗</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-500">{{ $visit->follow_up_required ? trans_db('Yes') : trans_db('No') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script>
// Translation strings for JavaScript charts
const chartTranslations = {
    'Schools': @json(trans_db('Schools')),
    'Correlation between Mentoring Visits and Performance Improvement': @json(trans_db('Correlation between Mentoring Visits and Performance Improvement')),
    'visits': @json(trans_db('visits')),
    'improvement': @json(trans_db('improvement')),
    'Number of Mentoring Visits': @json(trans_db('Number of Mentoring Visits')),
    'Performance Improvement (Latest - Baseline)': @json(trans_db('Performance Improvement (Latest - Baseline)')),
    'Show Details': @json(trans_db('Show Details')),
    'Hide Details': @json(trans_db('Hide Details'))
};

function toggleVisitDetails(id) {
    const element = document.getElementById(id);
    element.classList.toggle('hidden');
    const button = event.target;
    if (element.classList.contains('hidden')) {
        button.textContent = chartTranslations['Show Details'] + ' ↓';
    } else {
        button.textContent = chartTranslations['Hide Details'] + ' ↑';
    }
}

@if(count($schoolsWithImprovements) > 0)
$(document).ready(function() {
    const impactData = @json($schoolsWithImprovements);
    
    // Create scatter plot
    const ctx = document.getElementById('impactChart').getContext('2d');
    new Chart(ctx, {
        type: 'scatter',
        data: {
            datasets: [{
                label: chartTranslations['Schools'],
                data: impactData.map(d => ({
                    x: d.total_visits,
                    y: d.improvement,
                    label: d.school.school_name
                })),
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgba(59, 130, 246, 1)',
                pointRadius: 8,
                pointHoverRadius: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: chartTranslations['Correlation between Mentoring Visits and Performance Improvement']
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw.label + ': ' + 
                                   context.parsed.x + ' ' + chartTranslations['visits'] + ', ' +
                                   (context.parsed.y > 0 ? '+' : '') + context.parsed.y.toFixed(1) + ' ' + chartTranslations['improvement'];
                        }
                    }
                },
                datalabels: {
                    display: false
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: chartTranslations['Number of Mentoring Visits']
                    },
                    beginAtZero: true
                },
                y: {
                    title: {
                        display: true,
                        text: chartTranslations['Performance Improvement (Latest - Baseline)']
                    },
                    grid: {
                        drawBorder: false,
                        color: function(context) {
                            if (context.tick.value === 0) {
                                return '#000';
                            }
                            return 'rgba(0, 0, 0, 0.1)';
                        }
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
});
@endif
</script>
@endpush
@endsection