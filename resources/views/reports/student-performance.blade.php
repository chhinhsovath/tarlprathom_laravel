<x-app-layout>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Student Performance Report') }}</h3>
                        <a href="{{ route('reports.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            ← {{ __('Back to Reports') }}
                        </a>
                    </div>
                    
                    <!-- Filters -->
                    <form method="GET" action="{{ route('reports.student-performance') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="school_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('School') }}</label>
                            <select name="school_id" id="school_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('All Schools') }}</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ $schoolId == $school->id ? 'selected' : '' }}>
                                        {{ $school->school_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Subject') }}</label>
                            <select name="subject" id="subject" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="all" {{ $subject == 'all' ? 'selected' : '' }}>{{ __('All Subjects') }}</option>
                                <option value="khmer" {{ $subject == 'khmer' ? 'selected' : '' }}>{{ __('Khmer') }}</option>
                                <option value="math" {{ $subject == 'math' ? 'selected' : '' }}>{{ __('Math') }}</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="cycle" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Test Cycle') }}</label>
                            <select name="cycle" id="cycle" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="all" {{ $cycle == 'all' ? 'selected' : '' }}>{{ __('All Cycles') }}</option>
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
            
            <!-- Performance by Level -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h4 class="text-md font-medium text-gray-900 mb-4">{{ __('Performance by Level') }}</h4>
                    
                    <div class="relative" style="height: 300px;">
                        <canvas id="performanceChart"></canvas>
                    </div>
                    
                    <!-- Summary Table -->
                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Level') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Number of Students') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Percentage') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $total = $performanceByLevel->sum('count');
                                @endphp
                                @foreach($performanceByLevel as $level)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ __($level->level) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($level->count) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $total > 0 ? number_format(($level->count / $total) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Export Options -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h4 class="text-md font-medium text-gray-900 mb-4">{{ __('Export Options') }}</h4>
                    
                    <div class="flex gap-4">
                        <button onclick="exportChart()" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ __('Export Chart as Image') }}
                        </button>
                        
                        <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            {{ __('Print Report') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        $(document).ready(function() {
            // Prepare data
            const performanceData = @json($performanceByLevel);
            const labels = performanceData.map(item => item.level);
            const data = performanceData.map(item => item.count);
            
            // Create chart
            const ctx = document.getElementById('performanceChart').getContext('2d');
            const performanceChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '{{ __("Number of Students") }}',
                        data: data,
                        backgroundColor: [
                            '#ef4444', // red for beginner
                            '#f59e0b', // amber for letter
                            '#eab308', // yellow for word
                            '#84cc16', // lime for paragraph
                            '#22c55e', // green for story
                            '#10b981', // emerald for comp 1
                            '#14b8a6'  // teal for comp 2
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: '{{ __("Student Distribution by Level") }}'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: '{{ __("Number of Students") }}'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '{{ __("Level") }}'
                            }
                        }
                    }
                }
            });
            
            // Export chart function
            window.exportChart = function() {
                const link = document.createElement('a');
                link.download = 'performance-chart-' + new Date().toISOString().split('T')[0] + '.png';
                link.href = performanceChart.toBase64Image();
                link.click();
            };
        });
    </script>
    @endpush
</x-app-layout>