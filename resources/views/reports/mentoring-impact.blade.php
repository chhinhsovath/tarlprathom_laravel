<x-app-layout>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Mentoring Impact Report') }}</h3>
                        <a href="{{ route('reports.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            ← {{ __('Back to Reports') }}
                        </a>
                    </div>
                    
                    <!-- Date Range Filter -->
                    <form method="GET" action="{{ route('reports.mentoring-impact') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Start Date') }}</label>
                            <input type="date" name="start_date" id="start_date" value="{{ $startDate->format('Y-m-d') }}" 
                                   class="w-full h-11 px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('End Date') }}</label>
                            <input type="date" name="end_date" id="end_date" value="{{ $endDate->format('Y-m-d') }}" 
                                   class="w-full h-11 px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="w-full h-11 inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                {{ __('Apply Filters') }}
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
                        <h3 class="text-sm font-medium text-blue-800">{{ __('How We Calculate Impact') }}</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li><strong>{{ __('Baseline Average') }}:</strong> {{ __('Average assessment score of all students in a school during their baseline (initial) assessment') }}</li>
                                <li><strong>{{ __('Latest Average') }}:</strong> {{ __('Average assessment score from the most recent assessment cycle (midline or endline)') }}</li>
                                <li><strong>{{ __('Improvement') }}:</strong> {{ __('Latest Average minus Baseline Average. Positive values indicate improvement') }}</li>
                                <li><strong>{{ __('Mentoring Score') }}:</strong> {{ __('Average score given by mentors during their visits (0-100%)') }}</li>
                            </ul>
                            <p class="mt-2">{{ __('The scatter plot shows the relationship between the number of mentoring visits and the performance improvement for each school.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Impact Correlation Chart -->
            @if(count($schoolsWithImprovements) > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h4 class="text-md font-medium text-gray-900 mb-4">{{ __('Mentoring Visits vs Performance Improvement') }}</h4>
                    
                    <div class="relative" style="height: 400px;">
                        <canvas id="impactChart"></canvas>
                    </div>
                    
                    <div class="mt-4 text-sm text-gray-600">
                        <p>{{ __('Each point represents a school. The X-axis shows the number of mentoring visits received, and the Y-axis shows the improvement in average student assessment scores.') }}</p>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- School Impact Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h4 class="text-md font-medium text-gray-900 mb-4">{{ __('School Impact Summary') }}</h4>
                    
                    <!-- Column Explanations -->
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg text-sm text-gray-600">
                        <p class="font-medium mb-2">{{ __('Understanding the Data:') }}</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <div>
                                <span class="font-semibold">{{ __('Total Visits') }}:</span> {{ __('Number of mentoring visits during the selected period') }}
                            </div>
                            <div>
                                <span class="font-semibold">{{ __('Teachers Mentored') }}:</span> {{ __('Number of unique teachers who received mentoring') }}
                            </div>
                            <div>
                                <span class="font-semibold">{{ __('Avg Mentoring Score') }}:</span> {{ __('Average score given by mentors (quality of teaching observed)') }}
                            </div>
                            <div>
                                <span class="font-semibold">{{ __('Baseline/Latest Avg') }}:</span> {{ __('Average student assessment scores') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('School') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Total Visits') }}
                                        <span class="normal-case text-gray-400" title="{{ __('Number of mentoring visits') }}">ⓘ</span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Teachers Mentored') }}
                                        <span class="normal-case text-gray-400" title="{{ __('Unique teachers who received mentoring') }}">ⓘ</span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Avg Mentoring Score') }}
                                        <span class="normal-case text-gray-400" title="{{ __('Average score from mentor evaluations') }}">ⓘ</span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Baseline Avg') }}
                                        <span class="normal-case text-gray-400" title="{{ __('Average student score at baseline assessment') }}">ⓘ</span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Latest Avg') }}
                                        <span class="normal-case text-gray-400" title="{{ __('Average student score at most recent assessment') }}">ⓘ</span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Improvement') }}
                                        <span class="normal-case text-gray-400" title="{{ __('Change from baseline to latest (Latest - Baseline)') }}">ⓘ</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($schoolsWithImprovements as $data)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $data['school']->school_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($data['total_visits']) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($visitsBySchool[$data['school']->id]['unique_teachers'] ?? 0) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($data['avg_mentoring_score'], 1) }}%
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($data['baseline_avg'], 1) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($data['latest_avg'], 1) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($data['improvement'] > 0) bg-green-100 text-green-800
                                            @elseif($data['improvement'] < 0) bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            @if($data['improvement'] > 0)+@endif{{ number_format($data['improvement'], 1) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                        {{ __('No data available for the selected period') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(count($schoolsWithImprovements) > 0)
                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
                        <p><strong>{{ __('Note') }}:</strong> {{ __('This analysis compares baseline assessment scores with the most recent assessment scores (midline or endline). Schools without both baseline and follow-up assessments are not included in this table.') }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Visit Details by School -->
            @if($visitsBySchool->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h4 class="text-md font-medium text-gray-900 mb-4">{{ __('Visit Details by School') }}</h4>
                    
                    <div class="space-y-4">
                        @foreach($visitsBySchool as $schoolId => $data)
                        <div class="border rounded-lg p-4">
                            <h5 class="font-medium text-gray-900 mb-2">{{ $data['school']->school_name }}</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">
                                        {{ __('Total Visits') }}: {{ $data['total_visits'] }} | 
                                        {{ __('Teachers') }}: {{ $data['unique_teachers'] }} |
                                        {{ __('Avg Score') }}: {{ number_format($data['average_score'], 1) }}%
                                    </p>
                                </div>
                                <div class="text-right">
                                    <button onclick="toggleVisitDetails('school-{{ $schoolId }}')" 
                                            class="text-sm text-blue-600 hover:text-blue-800">
                                        {{ __('Show Details') }} ↓
                                    </button>
                                </div>
                            </div>
                            
                            <div id="school-{{ $schoolId }}" class="hidden mt-4">
                                <div class="mb-2 text-xs text-gray-500">
                                    <p>{{ __('Individual visit records during the selected period:') }}</p>
                                </div>
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Date') }}</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Teacher') }}</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Mentor') }}</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Score') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($data['visits'] as $visit)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-500">{{ $visit->visit_date->format('Y-m-d') }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900">{{ $visit->teacher->name }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-500">{{ $visit->mentor->name }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-500">{{ $visit->score }}%</td>
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
        function toggleVisitDetails(id) {
            const element = document.getElementById(id);
            element.classList.toggle('hidden');
            const button = event.target;
            if (element.classList.contains('hidden')) {
                button.textContent = '{{ __("Show Details") }} ↓';
            } else {
                button.textContent = '{{ __("Hide Details") }} ↑';
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
                        label: '{{ __("Schools") }}',
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
                            text: '{{ __("Correlation between Mentoring Visits and Performance Improvement") }}'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.raw.label + ': ' + 
                                           context.parsed.x + ' {{ __("visits") }}, ' +
                                           (context.parsed.y > 0 ? '+' : '') + context.parsed.y.toFixed(1) + ' {{ __("improvement") }}';
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
                                text: '{{ __("Number of Mentoring Visits") }}'
                            },
                            beginAtZero: true
                        },
                        y: {
                            title: {
                                display: true,
                                text: '{{ __("Performance Improvement (Latest - Baseline)") }}'
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
</x-app-layout>