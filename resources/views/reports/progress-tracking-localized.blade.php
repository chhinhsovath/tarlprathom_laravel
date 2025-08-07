@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ trans_db('Progress Tracking Report') }}</h3>
                    <a href="{{ route('reports.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        ← {{ trans_db('Back to Reports') }}
                    </a>
                </div>
                
                <!-- Filters -->
                <form method="GET" action="{{ route('reports.progress-tracking') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="school_id" class="block text-sm font-medium text-gray-700 mb-1">{{ trans_db('School') }}</label>
                        <select name="school_id" id="school_id" class="w-full h-11 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ trans_db('All Schools') }}</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ $schoolId == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">{{ trans_db('Subject') }}</label>
                        <select name="subject" id="subject" class="w-full h-11 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="khmer" {{ $subject == 'khmer' ? 'selected' : '' }}>{{ trans_db('Khmer') }}</option>
                            <option value="math" {{ $subject == 'math' ? 'selected' : '' }}>{{ trans_db('Math') }}</option>
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

        <!-- Understanding Progress Tracking -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">{{ trans_db('Understanding Progress Tracking') }}</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p class="mb-2">{{ trans_db('This report tracks individual student learning progress by comparing their baseline assessment with their most recent assessment (midline or endline). It shows:') }}</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>{{ trans_db('Students who improved to higher skill levels') }}</li>
                            <li>{{ trans_db('Students who maintained their skill level') }}</li>
                            <li>{{ trans_db('Students whose performance declined') }}</li>
                            <li>{{ trans_db('Score changes between assessment cycles') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- How Progress is Calculated -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">{{ trans_db('How Progress is Calculated') }}</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-semibold mb-1">{{ trans_db('Level Change') }}:</h4>
                                <ul class="list-disc list-inside text-xs space-y-0.5">
                                    <li>{{ trans_db('Compares skill levels between baseline and latest assessment') }}</li>
                                    <li>{{ trans_db('Positive number = moved up levels (improved)') }}</li>
                                    <li>{{ trans_db('Zero = stayed at same level (maintained)') }}</li>
                                    <li>{{ trans_db('Negative number = moved down levels (declined)') }}</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">{{ trans_db('Score Change') }}:</h4>
                                <ul class="list-disc list-inside text-xs space-y-0.5">
                                    <li>{{ trans_db('Difference between latest score and baseline score') }}</li>
                                    <li>{{ trans_db('Positive = score increased') }}</li>
                                    <li>{{ trans_db('Zero = score stayed the same') }}</li>
                                    <li>{{ trans_db('Negative = score decreased') }}</li>
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
                        {{ trans_db('Tracking progress in') }} <strong>{{ trans_db(ucfirst($subject)) }}</strong>
                        @if($schoolId)
                            {{ trans_db('for') }} <strong>{{ $schools->find($schoolId)->name ?? trans_db('Selected School') }}</strong>
                        @else
                            {{ trans_db('across all accessible schools') }}
                        @endif
                        <br>
                        <span class="text-xs">{{ trans_db('Only students with both baseline and follow-up assessments are included') }}</span>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Progress Summary -->
        @if(count($progressData) > 0)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <h4 class="text-md font-medium text-gray-900 mb-4">{{ trans_db('Progress Summary') }} - {{ trans_db(ucfirst($subject)) }}</h4>
                
                <!-- Summary Cards Explanation -->
                <div class="mb-4 p-3 bg-gray-50 rounded-lg text-sm text-gray-600">
                    <p><strong>{{ trans_db('Summary Cards') }}:</strong> {{ trans_db('Each card shows the count and percentage of students in each progress category. The average score change shows the overall trend across all students.') }}</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    @php
                        $totalStudents = count($progressData);
                        $improved = collect($progressData)->where('level_improved', '>', 0)->count();
                        $maintained = collect($progressData)->where('level_improved', '=', 0)->count();
                        $declined = collect($progressData)->where('level_improved', '<', 0)->count();
                        $avgLevelChange = collect($progressData)->avg('level_improved');
                    @endphp
                    
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <div class="text-2xl font-bold text-green-700">{{ $improved }}</div>
                        <div class="text-sm text-green-600">{{ trans_db('Students Improved') }}</div>
                        <div class="text-xs text-green-500">{{ $totalStudents > 0 ? number_format(($improved / $totalStudents) * 100, 1) : 0 }}% {{ trans_db('of total') }}</div>
                        <div class="text-xs text-green-400 mt-1">{{ trans_db('Moved to higher skill levels') }}</div>
                    </div>
                    
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <div class="text-2xl font-bold text-blue-700">{{ $maintained }}</div>
                        <div class="text-sm text-blue-600">{{ trans_db('Students Maintained') }}</div>
                        <div class="text-xs text-blue-500">{{ $totalStudents > 0 ? number_format(($maintained / $totalStudents) * 100, 1) : 0 }}% {{ trans_db('of total') }}</div>
                        <div class="text-xs text-blue-400 mt-1">{{ trans_db('Stayed at same skill level') }}</div>
                    </div>
                    
                    <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                        <div class="text-2xl font-bold text-red-700">{{ $declined }}</div>
                        <div class="text-sm text-red-600">{{ trans_db('Students Declined') }}</div>
                        <div class="text-xs text-red-500">{{ $totalStudents > 0 ? number_format(($declined / $totalStudents) * 100, 1) : 0 }}% {{ trans_db('of total') }}</div>
                        <div class="text-xs text-red-400 mt-1">{{ trans_db('Moved to lower skill levels') }}</div>
                    </div>
                    
                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                        <div class="text-2xl font-bold text-purple-700">{{ $avgScoreChange >= 0 ? '+' : '' }}{{ number_format($avgScoreChange, 1) }}</div>
                        <div class="text-sm text-purple-600">{{ trans_db('Avg Score Change') }}</div>
                        <div class="text-xs text-purple-400 mt-1">
                            @if($avgScoreChange > 0)
                                {{ trans_db('Overall improvement trend') }}
                            @elseif($avgScoreChange < 0)
                                {{ trans_db('Overall decline trend') }}
                            @else
                                {{ trans_db('No overall change') }}
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Progress Visualization Explanation -->
                <div class="mb-4 p-3 bg-gray-50 rounded-lg text-sm text-gray-600">
                    <p><strong>{{ trans_db('Progress Distribution Chart') }}:</strong> {{ trans_db('This doughnut chart shows the proportion of students in each progress category. Larger green sections indicate more students improved, while larger red sections may indicate need for intervention.') }}</p>
                </div>
                
                <!-- Progress Visualization -->
                <div class="relative" style="height: 300px;">
                    <canvas id="progressChart"></canvas>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Detailed Student Progress -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <h4 class="text-md font-medium text-gray-900 mb-4">{{ trans_db('Individual Student Progress') }} - {{ trans_db(ucfirst($subject)) }}</h4>
                
                <!-- Table Explanation -->
                <div class="mb-4 p-3 bg-gray-50 rounded-lg text-sm text-gray-600">
                    <p class="font-medium mb-2">{{ trans_db('Reading the Progress Table') }}:</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs">
                        <div><strong>{{ trans_db('Baseline') }}:</strong> {{ trans_db('Initial skill level and score when first assessed') }}</div>
                        <div><strong>{{ trans_db('Latest') }}:</strong> {{ trans_db('Most recent skill level and score (midline or endline)') }}</div>
                        <div><strong>{{ trans_db('Level Change') }}:</strong> {{ trans_db('Number of skill levels moved up (+) or down (-)') }}</div>
                        <div><strong>{{ trans_db('Score Change') }}:</strong> {{ trans_db('Numerical difference between latest and baseline scores') }}</div>
                    </div>
                    <p class="mt-2 text-xs">{{ trans_db('Students are sorted by level improvement (most improved first), then by score change.') }}</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans_db('Student Name') }}
                                    <span class="normal-case text-gray-400" title="{{ trans_db('Student name') }}">ⓘ</span>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans_db('School') }}
                                    <span class="normal-case text-gray-400" title="{{ trans_db('School where student is enrolled') }}">ⓘ</span>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans_db('Baseline') }}
                                    <span class="normal-case text-gray-400" title="{{ trans_db('Initial assessment level and score') }}">ⓘ</span>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans_db('Latest') }} ({{ trans_db('Cycle') }})
                                    <span class="normal-case text-gray-400" title="{{ trans_db('Most recent assessment level and score') }}">ⓘ</span>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans_db('Level Change') }}
                                    <span class="normal-case text-gray-400" title="{{ trans_db('Change in skill levels (+ improved, - declined, = same)') }}">ⓘ</span>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans_db('Score Change') }}
                                    <span class="normal-case text-gray-400" title="{{ trans_db('Numerical score difference (latest - baseline)') }}">ⓘ</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($progressData as $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $data['student']->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $data['student']->school->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ trans_db($data['baseline_level']) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ trans_db($data['latest_level']) }}
                                    </span>
                                    <div class="text-xs text-gray-400">{{ trans_db(ucfirst($data['latest_cycle'])) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($data['level_improved'] > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ↑ +{{ $data['level_improved'] }} {{ $data['level_improved'] == 1 ? trans_db('level') : trans_db('levels') }}
                                        </span>
                                    @elseif($data['level_improved'] < 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            ↓ {{ $data['level_improved'] }} {{ abs($data['level_improved']) == 1 ? trans_db('level') : trans_db('levels') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            = {{ trans_db('Same level') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($data['level_improved'] > 0)
                                        <span class="text-green-600 font-medium">+{{ $data['level_improved'] }} {{ trans_db('levels') }}</span>
                                    @elseif($data['level_improved'] < 0)
                                        <span class="text-red-600 font-medium">{{ $data['level_improved'] }} {{ trans_db('levels') }}</span>
                                    @else
                                        <span class="text-gray-500">0</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    {{ trans_db('No students with multiple assessments found') }}
                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ trans_db('Students need both baseline and follow-up assessments to appear in this report') }}
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(count($progressData) > 0)
                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800">
                    <p><strong>{{ trans_db('Note') }}:</strong> {{ trans_db('This table shows students sorted by their level improvement. Students who moved up multiple levels appear first, followed by those with smaller improvements, then those who maintained their level, and finally those who declined.') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Interpretation Guide -->
        @if(count($progressData) > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">{{ trans_db('Interpreting Progress Data') }}</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>{{ trans_db('High improvement rates') }} (>70% improved):</strong> {{ trans_db('Indicates effective teaching and learning interventions') }}</li>
                            <li><strong>{{ trans_db('Mixed results') }} (30-70% improved):</strong> {{ trans_db('Normal variation; consider individual student needs') }}</li>
                            <li><strong>{{ trans_db('Low improvement rates') }} (<30% improved):</strong> {{ trans_db('May indicate need for curriculum or teaching method review') }}</li>
                            <li><strong>{{ trans_db('Students maintaining levels') }}:</strong> {{ trans_db('Could be at appropriate challenge level or need different support') }}</li>
                            <li><strong>{{ trans_db('Students declining') }}:</strong> {{ trans_db('May need immediate attention and individualized intervention') }}</li>
                            <li><strong>{{ trans_db('Consider timeframe') }}:</strong> {{ trans_db('Progress between baseline and midline vs. baseline and endline may differ') }}</li>
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
                    <p>{{ trans_db('Use this progress data to identify students who need additional support, recognize successful teaching methods, and plan targeted interventions.') }}</p>
                </div>
                
                <div class="flex gap-4">
                    @if(count($progressData) > 0)
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
                        {{ trans_db('View Overall Performance') }}
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
    'Student Progress Distribution': @json(trans_db('Student Progress Distribution')),
    'students': @json(trans_db('students')),
    'Improved': @json(trans_db('Improved')),
    'Maintained': @json(trans_db('Maintained')),
    'Declined': @json(trans_db('Declined'))
};

let progressChart;

@if(count($progressData) > 0)
$(document).ready(function() {
    const progressData = @json($progressData);
    const levelChanges = progressData.reduce((acc, curr) => {
        const change = curr.level_improved;
        const key = change > 0 ? 'Improved' : (change < 0 ? 'Declined' : 'Maintained');
        acc[key] = (acc[key] || 0) + 1;
        return acc;
    }, {});
    
    // Translate labels
    const translatedLabels = Object.keys(levelChanges).map(key => chartTranslations[key]);
    
    // Create pie chart
    const ctx = document.getElementById('progressChart').getContext('2d');
    progressChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: translatedLabels,
            datasets: [{
                data: Object.values(levelChanges),
                backgroundColor: [
                    '#22c55e', // green for improved
                    '#ef4444', // red for declined
                    '#3b82f6'  // blue for maintained
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                title: {
                    display: true,
                    text: chartTranslations['Student Progress Distribution'] + ' - ' + @json(trans_db(ucfirst($subject))),
                    font: {
                        size: 16,
                        weight: 'bold'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.raw / total) * 100).toFixed(1);
                            return context.label + ': ' + context.raw + ' ' + chartTranslations['students'] + ' (' + percentage + '%)';
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
                        size: 12
                    },
                    formatter: function(value, context) {
                        const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                        const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                        return percentage > 0 ? percentage + '%' : '';
                    },
                    anchor: 'center',
                    align: 'center'
                }
            }
        },
        plugins: [ChartDataLabels]
    });
});

// Export chart function
window.exportChart = function() {
    if (progressChart) {
        const subject = '{{ $subject }}';
        const date = new Date().toISOString().split('T')[0];
        
        const link = document.createElement('a');
        link.download = 'progress-tracking-' + subject + '-' + date + '.png';
        link.href = progressChart.toBase64Image();
        link.click();
    }
};
@endif
</script>
@endpush
@endsection