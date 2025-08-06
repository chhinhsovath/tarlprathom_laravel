<x-app-layout>
    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Performance Calculation Report') }}</h2>
                    <p class="text-gray-600 mb-4">{{ __('Analysis of student performance based on reading levels and mathematical operations capability') }}</p>
                    
                    <!-- Filters -->
                    <form method="GET" class="bg-gray-50 p-6 rounded-lg mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div>
                                <label for="school_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('School') }}
                                </label>
                                <select name="school_id" id="school_id" class="w-full h-11 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('All Schools') }}</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ $schoolId == $school->id ? 'selected' : '' }}>
                                            {{ $school->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="cycle" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Assessment Cycle') }}
                                </label>
                                <select name="cycle" id="cycle" class="w-full h-11 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="baseline" {{ $cycle === 'baseline' ? 'selected' : '' }}>{{ __('Baseline') }}</option>
                                    <option value="midline" {{ $cycle === 'midline' ? 'selected' : '' }}>{{ __('Midline') }}</option>
                                    <option value="endline" {{ $cycle === 'endline' ? 'selected' : '' }}>{{ __('Endline') }}</option>
                                </select>
                            </div>
                            
                            <div></div>
                            
                            <div class="flex items-end">
                                <button type="submit" class="w-full h-11 inline-flex justify-center items-center px-4 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                    {{ __('Filter Results') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Performance Calculation Explanation -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-8 bg-white border-b border-gray-200">
                    <h3 class="text-xl font-medium text-gray-900 mb-6">{{ __('Calculation of Performance') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Language Performance -->
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h4 class="font-semibold text-blue-900 mb-3 text-lg">{{ __('a. Language -') }}</h4>
                            <div class="text-base text-blue-800 space-y-2">
                                <div class="ml-4">
                                    <span class="font-medium">i.</span> {{ __('Readers = Para + Story + Comp 1 + Comp 2') }}
                                </div>
                                <div class="ml-4">
                                    <span class="font-medium">ii.</span> {{ __('Beginners = Beginner + Letter') }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Math Performance -->
                        <div class="bg-green-50 p-6 rounded-lg">
                            <h4 class="font-semibold text-green-900 mb-3 text-lg">{{ __('b. Math -') }}</h4>
                            <div class="text-base text-green-800 space-y-2">
                                <div class="ml-4">
                                    <span class="font-medium">i.</span> {{ __('Students who can do operations = Subtraction + Division + Word Problem') }}
                                </div>
                                <div class="ml-4">
                                    <span class="font-medium">ii.</span> {{ __('Beginners = Beginner + 1-Digit') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Chart -->
            @if(count($schoolPerformanceData) > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-8 bg-white border-b border-gray-200">
                    <h3 class="text-xl font-medium text-gray-900 mb-6">{{ __('c. Sample graph') }}</h3>
                    
                    <!-- Chart container -->
                    <div class="bg-yellow-50 p-8 rounded-lg">
                        <h4 class="font-semibold text-center mb-6 text-lg text-gray-800">
                            {{ __('Children who can read at least a simple paragraph') }}
                        </h4>
                        
                        <!-- Chart -->
                        <div class="relative" style="height: 500px;">
                            <canvas id="performanceChart"></canvas>
                        </div>
                        
                        <!-- Legend -->
                        <div class="flex justify-center mt-4 space-x-6">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-orange-400 rounded mr-2"></div>
                                <span class="text-sm">{{ __('Baseline') }}</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-orange-600 rounded mr-2"></div>
                                <span class="text-sm">{{ __('Midline') }}</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-blue-600 rounded mr-2"></div>
                                <span class="text-sm">{{ __('Endline') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Performance Data Table -->
            @if(count($schoolPerformanceData) > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 bg-white">
                    <h3 class="text-xl font-medium text-gray-900 mb-6">{{ __('Performance Data - ') . __(ucfirst($cycle)) }}</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-8 py-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('School') }}
                                    </th>
                                    <th class="px-8 py-4 text-center text-sm font-medium text-gray-500 uppercase tracking-wider" colspan="3">
                                        {{ __('Language Performance') }}
                                    </th>
                                    <th class="px-8 py-4 text-center text-sm font-medium text-gray-500 uppercase tracking-wider" colspan="3">
                                        {{ __('Math Performance') }}
                                    </th>
                                </tr>
                                <tr class="bg-gray-100">
                                    <th class="px-8 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Name') }}
                                    </th>
                                    <th class="px-8 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Total Students') }}
                                    </th>
                                    <th class="px-8 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Readers') }}
                                    </th>
                                    <th class="px-8 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Beginners') }}
                                    </th>
                                    <th class="px-8 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Total Students') }}
                                    </th>
                                    <th class="px-8 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Operations') }}
                                    </th>
                                    <th class="px-8 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Beginners') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($schoolPerformanceData as $data)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-8 py-6 whitespace-nowrap text-base font-medium text-gray-900">
                                        {{ $data['school']->name }}
                                    </td>
                                    
                                    <!-- Language Performance -->
                                    <td class="px-8 py-6 whitespace-nowrap text-base text-center text-gray-500">
                                        {{ $data['language']['total_students'] }}
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-base text-center">
                                        <div class="text-gray-900 font-medium">{{ $data['language']['readers'] }}</div>
                                        <div class="text-sm text-gray-500">({{ $data['language']['readers_percentage'] }}%)</div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-base text-center">
                                        <div class="text-gray-900 font-medium">{{ $data['language']['beginners'] }}</div>
                                        <div class="text-sm text-gray-500">({{ $data['language']['beginners_percentage'] }}%)</div>
                                    </td>
                                    
                                    <!-- Math Performance -->
                                    <td class="px-8 py-6 whitespace-nowrap text-base text-center text-gray-500">
                                        {{ $data['math']['total_students'] }}
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-base text-center">
                                        <div class="text-gray-900 font-medium">{{ $data['math']['operations'] }}</div>
                                        <div class="text-sm text-gray-500">({{ $data['math']['operations_percentage'] }}%)</div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-base text-center">
                                        <div class="text-gray-900 font-medium">{{ $data['math']['beginners'] }}</div>
                                        <div class="text-sm text-gray-500">({{ $data['math']['beginners_percentage'] }}%)</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-12 bg-white text-center">  
                    <div class="text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No Performance Data Available') }}</h3>
                        <p class="text-gray-600">{{ __('No assessment data found for the selected criteria. Please try different filters or ensure assessments have been completed.') }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    @if(count($schoolPerformanceData) > 0)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('performanceChart').getContext('2d');
            
            // Sample data for demonstration - in real implementation this would come from multiple cycles
            const schoolNames = @json(array_column($schoolPerformanceData, 'school'));
            const languageReaderPercentages = @json(array_column(array_column($schoolPerformanceData, 'language'), 'readers_percentage'));
            
            // For now, using current cycle data, but in real implementation you'd need data from all three cycles
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: schoolNames.map(school => school.school_name),
                    datasets: [{
                        label: '{{ __(ucfirst($cycle)) }}',
                        data: languageReaderPercentages,
                        backgroundColor: 
                            @if($cycle === 'baseline')
                                'rgba(251, 146, 60, 0.8)'
                            @elseif($cycle === 'midline')
                                'rgba(194, 65, 12, 0.8)'
                            @else
                                'rgba(37, 99, 235, 0.8)'
                            @endif,
                        borderColor: 
                            @if($cycle === 'baseline')
                                'rgba(251, 146, 60, 1)'
                            @elseif($cycle === 'midline')
                                'rgba(194, 65, 12, 1)'
                            @else
                                'rgba(37, 99, 235, 1)'
                            @endif,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + '%';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endif
</x-app-layout>