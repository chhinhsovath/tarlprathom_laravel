<x-app-layout>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('School Comparison Report') }}</h3>
                        <a href="{{ route('reports.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            ← {{ __('Back to Reports') }}
                        </a>
                    </div>
                    
                    <!-- Filters -->
                    <form method="GET" action="{{ route('reports.school-comparison') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Subject') }}</label>
                            <select name="subject" id="subject" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="khmer" {{ $subject == 'khmer' ? 'selected' : '' }}>{{ __('Khmer') }}</option>
                                <option value="math" {{ $subject == 'math' ? 'selected' : '' }}>{{ __('Math') }}</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="cycle" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Test Cycle') }}</label>
                            <select name="cycle" id="cycle" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="baseline" {{ $cycle == 'baseline' ? 'selected' : '' }}>{{ __('Baseline') }}</option>
                                <option value="midline" {{ $cycle == 'midline' ? 'selected' : '' }}>{{ __('Midline') }}</option>
                                <option value="endline" {{ $cycle == 'endline' ? 'selected' : '' }}>{{ __('Endline') }}</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                {{ __('Apply Filters') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Comparison Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h4 class="text-md font-medium text-gray-900 mb-4">{{ __('Performance Comparison') }}</h4>
                    
                    <div class="relative" style="height: 400px;">
                        <canvas id="comparisonChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Detailed Comparison Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h4 class="text-md font-medium text-gray-900 mb-4">{{ __('Detailed Comparison') }}</h4>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('School') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Total Assessed') }}
                                    </th>
                                    @php
                                        $allLevels = $subject === 'khmer' 
                                            ? ['Beginner', 'Letter Reader', 'Word Level', 'Paragraph Reader', 'Story Reader', 'Comp. 1', 'Comp. 2']
                                            : ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'];
                                    @endphp
                                    @foreach($allLevels as $level)
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __($level) }}
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($comparisonData as $data)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $data['school'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($data['total_assessed']) }}
                                    </td>
                                    @foreach($allLevels as $level)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        @if(isset($data['levels'][$level]))
                                            {{ $data['levels'][$level] }}
                                            <br>
                                            <span class="text-xs text-gray-400">
                                                ({{ number_format(($data['levels'][$level] / $data['total_assessed']) * 100, 1) }}%)
                                            </span>
                                        @else
                                            0
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ count($allLevels) + 2 }}" class="px-6 py-4 text-center text-sm text-gray-500">
                                        {{ __('No data available for the selected filters') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        $(document).ready(function() {
            const comparisonData = @json($comparisonData);
            const subject = @json($subject);
            
            if (comparisonData.length > 0) {
                // Prepare data for chart
                const schools = comparisonData.map(d => d.school);
                const levels = subject === 'khmer' 
                    ? ['Beginner', 'Letter Reader', 'Word Level', 'Paragraph Reader', 'Story Reader', 'Comp. 1', 'Comp. 2']
                    : ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'];
                
                const datasets = levels.map((level, index) => {
                    const colors = [
                        '#ef4444', // red
                        '#f59e0b', // amber
                        '#eab308', // yellow
                        '#84cc16', // lime
                        '#22c55e', // green
                        '#10b981', // emerald
                        '#14b8a6'  // teal
                    ];
                    
                    return {
                        label: level,
                        data: comparisonData.map(d => d.levels[level] || 0),
                        backgroundColor: colors[index],
                        stack: 'stack0'
                    };
                });
                
                // Create stacked bar chart
                const ctx = document.getElementById('comparisonChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: schools,
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: '{{ __("Student Distribution by School and Level") }}'
                            },
                            tooltip: {
                                callbacks: {
                                    afterLabel: function(context) {
                                        const total = comparisonData[context.dataIndex].total_assessed;
                                        const percentage = ((context.raw / total) * 100).toFixed(1);
                                        return percentage + '%';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                stacked: true,
                                title: {
                                    display: true,
                                    text: '{{ __("School") }}'
                                }
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: '{{ __("Number of Students") }}'
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>