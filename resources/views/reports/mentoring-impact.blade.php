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
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('End Date') }}</label>
                            <input type="date" name="end_date" id="end_date" value="{{ $endDate->format('Y-m-d') }}" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                {{ __('Apply Filters') }}
                            </button>
                        </div>
                    </form>
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
                </div>
            </div>
            @endif
            
            <!-- School Impact Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h4 class="text-md font-medium text-gray-900 mb-4">{{ __('School Impact Summary') }}</h4>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('School') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Total Visits') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Teachers Mentored') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Avg Mentoring Score') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Baseline Avg') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Latest Avg') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Improvement') }}
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
    <script>
        function toggleVisitDetails(id) {
            const element = document.getElementById(id);
            element.classList.toggle('hidden');
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
                                text: '{{ __("Performance Improvement") }}'
                            }
                        }
                    }
                }
            });
        });
        @endif
    </script>
    @endpush
</x-app-layout>