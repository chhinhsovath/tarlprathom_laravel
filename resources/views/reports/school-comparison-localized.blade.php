@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ trans_db('School Comparison Report') }}</h3>
                    <a href="{{ route('reports.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        ← {{ trans_db('Back to Reports') }}
                    </a>
                </div>
                
                <!-- Filters -->
                <form method="GET" action="{{ route('reports.school-comparison') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">{{ trans_db('Subject') }}</label>
                        <select name="subject" id="subject" class="w-full h-11 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="khmer" {{ $subject == 'khmer' ? 'selected' : '' }}>{{ trans_db('Khmer') }}</option>
                            <option value="math" {{ $subject == 'math' ? 'selected' : '' }}>{{ trans_db('Math') }}</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="cycle" class="block text-sm font-medium text-gray-700 mb-1">{{ trans_db('Test Cycle') }}</label>
                        <select name="cycle" id="cycle" class="w-full h-11 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="baseline" {{ $cycle == 'baseline' ? 'selected' : '' }}>{{ trans_db('baseline') }}</option>
                            <option value="midline" {{ $cycle == 'midline' ? 'selected' : '' }}>{{ trans_db('midline') }}</option>
                            <option value="endline" {{ $cycle == 'endline' ? 'selected' : '' }}>{{ trans_db('endline') }}</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full h-11 inline-flex justify-center items-center px-4 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            {{ trans_db('Apply Filters') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Understanding School Comparisons -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">{{ trans_db('Understanding School Comparisons') }}</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p class="mb-2">{{ trans_db('This report compares student performance across different schools for a specific subject and assessment cycle. It helps identify:') }}</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>{{ trans_db('Schools with higher concentrations of advanced students') }}</li>
                            <li>{{ trans_db('Schools that may need additional support for foundational skills') }}</li>
                            <li>{{ trans_db('Distribution patterns that inform resource allocation') }}</li>
                            <li>{{ trans_db('Performance variations across the school network') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- How to Read the Data -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">{{ trans_db('How to Read the Charts and Tables') }}</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-semibold mb-1">{{ trans_db('Stacked Bar Chart') }}:</h4>
                                <ul class="list-disc list-inside text-xs space-y-0.5">
                                    <li>{{ trans_db('Each bar represents one school') }}</li>
                                    <li>{{ trans_db('Colors show skill levels (red=beginner, green/teal=advanced)') }}</li>
                                    <li>{{ trans_db('Bar height shows total students assessed') }}</li>
                                    <li>{{ trans_db('Color proportions show level distribution') }}</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">{{ trans_db('Comparison Table') }}:</h4>
                                <ul class="list-disc list-inside text-xs space-y-0.5">
                                    <li>{{ trans_db('Numbers show student counts at each level') }}</li>
                                    <li>{{ trans_db('Percentages show proportion of school total') }}</li>
                                    <li>{{ trans_db('Compare across rows to see school differences') }}</li>
                                    <li>{{ trans_db('Compare within columns to see level concentrations') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Selection Info -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-800">{{ trans_db('Current View') }}</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ trans_db('Showing') }} <strong>{{ trans_db(ucfirst($subject)) }}</strong> {{ trans_db('performance during') }} <strong>{{ trans_db(ucfirst($cycle)) }}</strong> {{ trans_db('assessment cycle') }}
                        @if($subject === 'khmer')
                            - {{ trans_db('Reading skill levels from basic letter recognition to advanced comprehension') }}
                        @else
                            - {{ trans_db('Math skill levels from basic number recognition to complex problem solving') }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Comparison Chart -->
        @if(count($comparisonData) > 0)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <h4 class="text-md font-medium text-gray-900 mb-4">{{ trans_db('Performance Comparison') }} - {{ trans_db(ucfirst($subject)) }} ({{ trans_db(ucfirst($cycle)) }})</h4>
                
                <div class="mb-4 p-3 bg-gray-50 rounded-lg text-sm text-gray-600">
                    <p><strong>{{ trans_db('Chart interpretation') }}:</strong> {{ trans_db('Each school is represented by a stacked bar. Taller bars indicate more students assessed. The color distribution within each bar shows how students are distributed across skill levels.') }}</p>
                </div>
                
                <div class="relative" style="height: 400px;">
                    <canvas id="comparisonChart"></canvas>
                </div>
                
                <div class="mt-4 text-sm text-gray-600">
                    <p><strong>{{ trans_db('Look for') }}:</strong> {{ trans_db('Schools with more green/teal (advanced levels) vs red/yellow (beginner levels), and schools with similar total heights but different color distributions.') }}</p>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Detailed Comparison Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <h4 class="text-md font-medium text-gray-900 mb-4">{{ trans_db('Detailed Comparison') }} - {{ trans_db(ucfirst($subject)) }} ({{ trans_db(ucfirst($cycle)) }})</h4>
                
                <!-- Level Explanations -->
                <div class="mb-4 p-3 bg-gray-50 rounded-lg text-sm text-gray-600">
                    <p class="font-medium mb-2">{{ trans_db('Skill Level Definitions') }}:</p>
                    @if($subject === 'khmer')
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs">
                        <div><strong>{{ trans_db('Beginner') }}:</strong> {{ trans_db('Pre-reading') }}</div>
                        <div><strong>{{ trans_db('Letter') }}:</strong> {{ trans_db('Letter recognition') }}</div>
                        <div><strong>{{ trans_db('Word') }}:</strong> {{ trans_db('Simple words') }}</div>
                        <div><strong>{{ trans_db('Paragraph') }}:</strong> {{ trans_db('Basic reading') }}</div>
                        <div><strong>{{ trans_db('Story') }}:</strong> {{ trans_db('Fluent reading') }}</div>
                        <div><strong>{{ trans_db('Comp. 1') }}:</strong> {{ trans_db('Basic comprehension') }}</div>
                        <div><strong>{{ trans_db('Comp. 2') }}:</strong> {{ trans_db('Advanced comprehension') }}</div>
                    </div>
                    @else
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-xs">
                        <div><strong>{{ trans_db('Beginner') }}:</strong> {{ trans_db('Pre-math skills') }}</div>
                        <div><strong>{{ trans_db('1-Digit') }}:</strong> {{ trans_db('Single numbers') }}</div>
                        <div><strong>{{ trans_db('2-Digit') }}:</strong> {{ trans_db('Two-digit operations') }}</div>
                        <div><strong>{{ trans_db('Subtraction') }}:</strong> {{ trans_db('Subtraction skills') }}</div>
                        <div><strong>{{ trans_db('Division') }}:</strong> {{ trans_db('Division operations') }}</div>
                        <div><strong>{{ trans_db('Word Problem') }}:</strong> {{ trans_db('Applied math') }}</div>
                    </div>
                    @endif
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans_db('School') }}
                                    <span class="normal-case text-gray-400" title="{{ trans_db('School name') }}">ⓘ</span>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans_db('Total Assessed') }}
                                    <span class="normal-case text-gray-400" title="{{ trans_db('Total number of students assessed in this subject and cycle') }}">ⓘ</span>
                                </th>
                                @php
                                    $allLevels = $subject === 'khmer' 
                                        ? ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2']
                                        : ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'];
                                @endphp
                                @foreach($allLevels as $level)
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans_db($level) }}
                                    <span class="normal-case text-gray-400" title="{{ trans_db('Number and percentage of students at this level') }}">ⓘ</span>
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($comparisonData as $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $data['school'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($data['total_assessed']) }}
                                </td>
                                @foreach($allLevels as $level)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    @if(isset($data['levels'][$level]) && $data['levels'][$level] > 0)
                                        <div class="font-medium">{{ $data['levels'][$level] }}</div>
                                        <div class="text-xs text-gray-400">
                                            ({{ number_format(($data['levels'][$level] / $data['total_assessed']) * 100, 1) }}%)
                                        </div>
                                    @else
                                        <div class="text-gray-300">0</div>
                                        <div class="text-xs text-gray-300">(0.0%)</div>
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ count($allLevels) + 2 }}" class="px-6 py-4 text-center text-sm text-gray-500">
                                    {{ trans_db('No data available for the selected filters') }}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if(count($comparisonData) > 0)
                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800">
                    <p><strong>{{ trans_db('Reading the table') }}:</strong> {{ trans_db('Each row shows one school\'s results. The first number in each level column is student count, the percentage below shows what portion of that school\'s total this represents. Compare percentages across schools to see relative performance patterns.') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Analysis Insights -->
        @if(count($comparisonData) > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">{{ trans_db('Key Insights to Look For') }}</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>{{ trans_db('High-performing schools') }}:</strong> {{ trans_db('Schools with higher percentages in advanced levels (rightmost columns)') }}</li>
                            <li><strong>{{ trans_db('Schools needing support') }}:</strong> {{ trans_db('Schools with high percentages in beginner levels') }}</li>
                            <li><strong>{{ trans_db('Balanced distributions') }}:</strong> {{ trans_db('Schools with students spread across multiple levels') }}</li>
                            <li><strong>{{ trans_db('Sample size considerations') }}:</strong> {{ trans_db('Compare percentages rather than raw numbers when schools have different total assessed counts') }}</li>
                            <li><strong>{{ trans_db('Improvement opportunities') }}:</strong> {{ trans_db('Use this data to identify schools that could benefit from sharing successful practices') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Export and Actions -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h4 class="text-md font-medium text-gray-900 mb-4">{{ trans_db('Actions') }}</h4>
                
                <div class="mb-3 text-sm text-gray-600">
                    <p>{{ trans_db('Use this comparison data to inform resource allocation, identify best practices, and plan targeted interventions.') }}</p>
                </div>
                
                <div class="flex gap-4">
                    @if(count($comparisonData) > 0)
                    <button onclick="exportChart()" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ trans_db('Export Chart') }}
                    </button>
                    @endif
                    
                    <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        {{ trans_db('Print Report') }}
                    </button>
                    
                    <a href="{{ route('reports.student-performance') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 00-2-2z"></path>
                        </svg>
                        {{ trans_db('View Individual Performance') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script>
// Translation strings for JavaScript charts
const chartTranslations = {
    'Student Distribution by School and Level': @json(trans_db('Student Distribution by School and Level')),
    'Schools': @json(trans_db('Schools')),
    'Number of Students': @json(trans_db('Number of Students')),
    'students': @json(trans_db('students')),
    'Total assessed': @json(trans_db('Total assessed'))
};

let comparisonChart;

$(document).ready(function() {
    const comparisonData = @json($comparisonData);
    const subject = @json($subject);
    
    console.log('Comparison Data:', comparisonData);
    console.log('Subject:', subject);
    
    if (comparisonData.length > 0) {
        // Prepare data for chart
        const schools = comparisonData.map(d => d.school);
        const levels = subject === 'khmer' 
            ? ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2']
            : ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'];
        
        const datasets = levels.map((level, index) => {
            const colors = @json(config('charts.colors.tarl_levels'));
            
            return {
                label: level,
                data: comparisonData.map(d => d.levels[level] || 0),
                backgroundColor: colors[index],
                borderColor: colors[index] + 'DD',
                borderWidth: 1,
                stack: 'stack0'
            };
        });
        
        // Create stacked bar chart
        try {
            const ctx = document.getElementById('comparisonChart').getContext('2d');
            comparisonChart = new Chart(ctx, {
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
                        text: chartTranslations['Student Distribution by School and Level'] + ' - ' + @json(trans_db(ucfirst($subject))),
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    },
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            boxWidth: 15,
                            padding: 10
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = comparisonData[context.dataIndex].total_assessed;
                                const percentage = ((context.raw / total) * 100).toFixed(1);
                                return context.dataset.label + ': ' + context.raw + ' ' + chartTranslations['students'] + ' (' + percentage + '%)';
                            },
                            footer: function(tooltipItems) {
                                const dataIndex = tooltipItems[0].dataIndex;
                                const total = comparisonData[dataIndex].total_assessed;
                                return chartTranslations['Total assessed'] + ': ' + total + ' ' + chartTranslations['students'];
                            }
                        }
                    },
                    datalabels: {
                        display: function(context) {
                            return context.dataset.data[context.dataIndex] > 0;
                        },
                        color: 'white',
                        font: {
                            weight: 'bold',
                            size: 10
                        },
                        formatter: function(value) {
                            return value;
                        },
                        anchor: 'center',
                        align: 'center'
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        title: {
                            display: true,
                            text: chartTranslations['Schools'],
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: chartTranslations['Number of Students'],
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            },
            plugins: [typeof ChartDataLabels !== 'undefined' ? ChartDataLabels : null].filter(Boolean)
        });
        } catch (error) {
            console.error('Error creating chart:', error);
            alert('There was an error creating the chart. Please check the console for details.');
        }
    }
});

// Export chart function
window.exportChart = function() {
    if (comparisonChart) {
        const subject = '{{ $subject }}';
        const cycle = '{{ $cycle }}';
        const date = new Date().toISOString().split('T')[0];
        
        const link = document.createElement('a');
        link.download = 'school-comparison-' + subject + '-' + cycle + '-' + date + '.png';
        link.href = comparisonChart.toBase64Image();
        link.click();
    }
};
</script>
@endpush
@endsection