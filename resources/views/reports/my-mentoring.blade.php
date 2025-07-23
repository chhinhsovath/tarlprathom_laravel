<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Mentoring Summary') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Date Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('reports.my-mentoring') }}" class="flex gap-4 items-end">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">{{ __('Start Date') }}</label>
                            <input type="date" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">{{ __('End Date') }}</label>
                            <input type="date" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Filter') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Total Visits') }}</dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['total_visits'] }}</dd>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Schools Visited') }}</dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['schools_visited'] }}</dd>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Teachers Mentored') }}</dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['teachers_mentored'] }}</dd>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Average Score') }}</dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($stats['average_score'], 1) }}%</dd>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Follow-up Required') }}</dt>
                        <dd class="mt-1 text-3xl font-semibold text-red-600">{{ $stats['follow_up_required'] }}</dd>
                    </div>
                </div>
            </div>

            <!-- Visits by Month Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Visits by Month') }}</h3>
                    <div style="position: relative; height:300px;">
                        <canvas id="visitsByMonthChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Visits by School -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Visits by School') }}</h3>
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
                                        {{ __('Teachers') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Average Score') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($visitsBySchool as $schoolData)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $schoolData['school']->school_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $schoolData['total_visits'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $schoolData['teachers']->count() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($schoolData['average_score'], 1) }}%
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('reports.school-visits', ['school_id' => $schoolData['school']->id]) }}" 
                                           class="text-indigo-600 hover:text-indigo-900">
                                            {{ __('View Details') }}
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Visits -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Recent Visits') }}</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Date') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('School') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Teacher') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Score') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Follow-up') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($visits->take(10) as $visit)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $visit->visit_date->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $visit->school->school_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $visit->teacher->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($visit->score >= 80) bg-green-100 text-green-800
                                            @elseif($visit->score >= 60) bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $visit->score }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($visit->follow_up_required)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ __('Yes') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ __('No') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('mentoring.show', $visit) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Visits by Month Chart
            const ctx = document.getElementById('visitsByMonthChart');
            if (!ctx) return;
            
            const visitsByMonthData = @json($visitsByMonth);
            
            // Convert the data to arrays
            const monthData = [];
            Object.keys(visitsByMonthData).forEach(month => {
                monthData.push({
                    month: month,
                    count: visitsByMonthData[month].count,
                    average: visitsByMonthData[month].average_score
                });
            });
            
            // Sort by month
            monthData.sort((a, b) => a.month.localeCompare(b.month));
            
            const labels = monthData.map(item => {
                const [year, month] = item.month.split('-');
                const date = new Date(year, month - 1);
                return date.toLocaleDateString('{{ app()->getLocale() }}', { year: 'numeric', month: 'short' });
            });
            
            const data = monthData.map(item => item.count);
            
            // Only create chart if we have data
            if (labels.length > 0) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: '{{ __("Number of Visits") }}',
                            data: data,
                            borderColor: 'rgb(79, 70, 229)',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                            }
                        },
                        scales: {
                            x: {
                                display: true,
                                title: {
                                    display: true,
                                    text: '{{ __("Month") }}'
                                }
                            },
                            y: {
                                display: true,
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: '{{ __("Number of Visits") }}'
                                },
                                ticks: {
                                    stepSize: 1,
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            } else {
                // Display no data message
                ctx.parentElement.innerHTML = '<p class="text-gray-500 text-center py-8">{{ __("No visit data available for the selected period") }}</p>';
            }
        });
    </script>
    @endpush
</x-app-layout>