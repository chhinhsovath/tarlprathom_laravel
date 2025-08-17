<x-app-layout>
    {{-- Print Styles --}}
    @push('styles')
    <style>
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            
            @page {
                size: A4 landscape;
                margin: 10mm;
            }
            
            .no-print {
                display: none !important;
            }
            
            body {
                font-size: 10pt;
                line-height: 1.3;
            }
            
            .print-container {
                width: 100% !important;
                max-width: none !important;
                padding: 0 !important;
            }
            
            /* Chart specific print styles */
            .chart-container {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                margin-bottom: 20px !important;
                padding: 10px !important;
                background: white !important;
                position: relative !important;
            }
            
            /* Hide canvas in print, show print table instead */
            canvas {
                display: none !important;
            }
            
            /* Show print-friendly chart replacement */
            .print-chart-table {
                display: block !important;
                width: 100% !important;
            }
            
            /* Force chart wrapper height */
            div[style*="height: 300px"] {
                height: auto !important;
            }
            
            /* Ensure the chart box doesn't break */
            .bg-white.overflow-hidden {
                page-break-inside: avoid !important;
            }
            
            /* Ensure chart sections don't break */
            .avoid-break {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            
            /* Table adjustments */
            table {
                page-break-inside: avoid;
                font-size: 8pt;
                width: 100%;
            }
            
            th, td {
                padding: 2px 4px !important;
                font-size: 8pt !important;
            }
            
            /* Hide table info icons in print */
            .normal-case.text-gray-400 {
                display: none !important;
            }
            
            /* Compact the summary table */
            .mt-6.overflow-x-auto {
                margin-top: 10px !important;
            }
            
            /* Background colors for print */
            .bg-blue-50, .bg-yellow-50, .bg-green-50 {
                background-color: #f9f9f9 !important;
                border: 1px solid #ddd !important;
            }
            
            /* Remove shadows */
            .shadow-sm, .shadow {
                box-shadow: none !important;
            }
            
            /* Page breaks */
            .page-break {
                page-break-after: always;
            }
            
            /* Hide export buttons in print */
            button[onclick*="exportSingleChart"] {
                display: none !important;
            }
            
            /* Adjust margins for print */
            .mb-6 {
                margin-bottom: 10px !important;
            }
            
            .p-6 {
                padding: 10px !important;
            }
        }
        
        @media screen {
            .print-only {
                display: none;
            }
            .print-chart-table {
                display: none;
            }
        }
    </style>
    @endpush

    <div class="py-6 print-container">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Student Performance Report') }}</h3>
                        <div class="flex gap-2 no-print">
                            <button onclick="window.print()" class="inline-flex items-center px-3 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                {{ __('Print') }}
                            </button>
                            <a href="{{ route('reports.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                ← {{ __('Back to Reports') }}
                            </a>
                        </div>
                    </div>
                    
                    <!-- Filters -->
                    <form method="GET" action="{{ route('reports.student-performance') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="school_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('School') }}</label>
                            <select name="school_id" id="school_id" class="w-full h-11 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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
                            <select name="subject" id="subject" class="w-full h-11 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="all" {{ $subject == 'all' ? 'selected' : '' }}>{{ __('All Subjects') }}</option>
                                <option value="khmer" {{ $subject == 'khmer' ? 'selected' : '' }}>{{ __('Khmer') }}</option>
                                <option value="math" {{ $subject == 'math' ? 'selected' : '' }}>{{ __('Math') }}</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="cycle" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Test Cycle') }}</label>
                            <select name="cycle" id="cycle" class="w-full h-11 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="all" {{ $cycle == 'all' ? 'selected' : '' }}>{{ __('All Cycles') }}</option>
                                <option value="baseline" {{ $cycle == 'baseline' ? 'selected' : '' }}>{{ __('Baseline') }}</option>
                                <option value="midline" {{ $cycle == 'midline' ? 'selected' : '' }}>{{ __('Midline') }}</option>
                                <option value="endline" {{ $cycle == 'endline' ? 'selected' : '' }}>{{ __('Endline') }}</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="w-full h-11 inline-flex justify-center items-center px-4 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                {{ __('Apply Filters') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Understanding the Assessment System -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">{{ __('Understanding Student Performance Levels') }}</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p class="mb-2">{{ __('This report shows how students are distributed across different skill levels in Khmer and Math subjects. Each level represents a specific learning milestone:') }}</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                                <div>
                                    <h4 class="font-semibold text-blue-800">{{ __('Khmer Levels') }}</h4>
                                    <ul class="list-disc list-inside text-xs mt-1 space-y-0.5">
                                        <li><strong>{{ __('Beginner') }}:</strong> {{ __('Cannot read letters or words') }}</li>
                                        <li><strong>{{ __('Letter') }}:</strong> {{ __('Can identify individual letters') }}</li>
                                        <li><strong>{{ __('Word') }}:</strong> {{ __('Can read simple words') }}</li>
                                        <li><strong>{{ __('Paragraph') }}:</strong> {{ __('Can read simple paragraphs') }}</li>
                                        <li><strong>{{ __('Story') }}:</strong> {{ __('Can read complete stories') }}</li>
                                        <li><strong>{{ __('Comp. 1') }}:</strong> {{ __('Basic reading comprehension') }}</li>
                                        <li><strong>{{ __('Comp. 2') }}:</strong> {{ __('Advanced reading comprehension') }}</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-blue-800">{{ __('Math Levels') }}</h4>
                                    <ul class="list-disc list-inside text-xs mt-1 space-y-0.5">
                                        <li><strong>{{ __('Beginner') }}:</strong> {{ __('Cannot perform basic math operations') }}</li>
                                        <li><strong>{{ __('1-Digit') }}:</strong> {{ __('Can work with single-digit numbers') }}</li>
                                        <li><strong>{{ __('2-Digit') }}:</strong> {{ __('Can work with two-digit numbers') }}</li>
                                        <li><strong>{{ __('Subtraction') }}:</strong> {{ __('Can perform subtraction operations') }}</li>
                                        <li><strong>{{ __('Division') }}:</strong> {{ __('Can perform division operations') }}</li>
                                        <li><strong>{{ __('Word Problem') }}:</strong> {{ __('Can solve mathematical word problems') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assessment Cycles Explanation -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">{{ __('Assessment Cycles') }}</h3>
                        <div class="mt-2 text-sm text-green-700">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <span class="font-semibold">{{ __('Baseline') }}:</span> {{ __('Initial assessment to determine starting skill levels') }}
                                </div>
                                <div>
                                    <span class="font-semibold">{{ __('Midline') }}:</span> {{ __('Mid-program assessment to track progress') }}
                                </div>
                                <div>
                                    <span class="font-semibold">{{ __('Endline') }}:</span> {{ __('Final assessment to measure overall improvement') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($subject === 'all')
                <!-- Performance by Level - Khmer -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 avoid-break">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-md font-medium text-gray-900">{{ __('Khmer Performance by Level') }}</h4>
                            <button onclick="exportSingleChart('khmerPerformanceChart', 'khmer')" class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 no-print">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ __('Export Chart') }}
                            </button>
                        </div>
                        
                        <div class="mb-4 p-3 bg-gray-50 rounded-lg text-sm text-gray-600">
                            <p><strong>{{ __('What this shows') }}:</strong> {{ __('Distribution of students across Khmer reading levels. Colors progress from red (lowest) to teal (highest) to visually represent skill progression.') }}</p>
                        </div>
                        
                        <div class="relative chart-container" style="height: 300px;" data-chart="khmer">
                            <canvas id="khmerPerformanceChart"></canvas>
                            <!-- Print-friendly chart replacement -->
                            <div class="print-chart-table">
                                <div class="flex justify-between items-center gap-2">
                                    @php
                                        $khmerPrintData = $performanceByLevelAndSubject['khmer'] ?? collect();
                                        $khmerPrintTotal = $khmerPrintData->sum('count');
                                    @endphp
                                    @foreach($khmerLevels as $level)
                                        @php
                                            $levelData = $khmerPrintData->firstWhere('level', $level);
                                            $count = $levelData ? $levelData->count : 0;
                                            $percent = $khmerPrintTotal > 0 ? round(($count / $khmerPrintTotal) * 100) : 0;
                                            $height = $percent * 2; // Scale for visual representation
                                        @endphp
                                        <div class="flex-1 text-center">
                                            <div class="relative">
                                                <div class="bg-gray-200 rounded" style="height: 200px; position: relative;">
                                                    <div class="absolute bottom-0 left-0 right-0 bg-blue-500 rounded" style="height: {{ $height }}px;"></div>
                                                </div>
                                                <div class="mt-2 text-xs font-bold">{{ $count }}</div>
                                                <div class="text-xs">{{ $level }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- Summary Table -->
                        <div class="mt-6 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Level') }}
                                            <span class="normal-case text-gray-400" title="{{ __('Khmer reading skill level') }}">ⓘ</span>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Number of Students') }}
                                            <span class="normal-case text-gray-400" title="{{ __('Total students assessed at this level') }}">ⓘ</span>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Percentage') }}
                                            <span class="normal-case text-gray-400" title="{{ __('Percentage of all Khmer assessments') }}">ⓘ</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $khmerData = $performanceByLevelAndSubject['khmer'] ?? collect();
                                        $khmerTotal = $khmerData->sum('count');
                                    @endphp
                                    @foreach($khmerLevels as $level)
                                        @php
                                            $levelData = $khmerData->firstWhere('level', $level);
                                            $count = $levelData ? $levelData->count : 0;
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ __($level) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($count) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $khmerTotal > 0 ? number_format(($count / $khmerTotal) * 100, 1) : 0 }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-gray-50 font-semibold">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ __('Total') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($khmerTotal) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">100.0%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Performance by Level - Math -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-md font-medium text-gray-900">{{ __('Math Performance by Level') }}</h4>
                            <button onclick="exportSingleChart('mathPerformanceChart', 'math')" class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 no-print">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ __('Export Chart') }}
                            </button>
                        </div>
                        
                        <div class="mb-4 p-3 bg-gray-50 rounded-lg text-sm text-gray-600">
                            <p><strong>{{ __('What this shows') }}:</strong> {{ __('Distribution of students across Math skill levels. Colors progress from red (lowest) to emerald (highest) to visually represent skill progression.') }}</p>
                        </div>
                        
                        <div class="relative chart-container" style="height: 300px;" data-chart="math">
                            <canvas id="mathPerformanceChart"></canvas>
                            <!-- Print-friendly chart replacement -->
                            <div class="print-chart-table">
                                <div class="flex justify-between items-center gap-2">
                                    @php
                                        $mathPrintData = $performanceByLevelAndSubject['math'] ?? collect();
                                        $mathPrintTotal = $mathPrintData->sum('count');
                                    @endphp
                                    @foreach($mathLevels as $level)
                                        @php
                                            $levelData = $mathPrintData->firstWhere('level', $level);
                                            $count = $levelData ? $levelData->count : 0;
                                            $percent = $mathPrintTotal > 0 ? round(($count / $mathPrintTotal) * 100) : 0;
                                            $height = $percent * 2; // Scale for visual representation
                                        @endphp
                                        <div class="flex-1 text-center">
                                            <div class="relative">
                                                <div class="bg-gray-200 rounded" style="height: 200px; position: relative;">
                                                    <div class="absolute bottom-0 left-0 right-0 bg-green-500 rounded" style="height: {{ $height }}px;"></div>
                                                </div>
                                                <div class="mt-2 text-xs font-bold">{{ $count }}</div>
                                                <div class="text-xs">{{ $level }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- Summary Table -->
                        <div class="mt-6 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Level') }}
                                            <span class="normal-case text-gray-400" title="{{ __('Math skill level') }}">ⓘ</span>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Number of Students') }}
                                            <span class="normal-case text-gray-400" title="{{ __('Total students assessed at this level') }}">ⓘ</span>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Percentage') }}
                                            <span class="normal-case text-gray-400" title="{{ __('Percentage of all Math assessments') }}">ⓘ</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $mathData = $performanceByLevelAndSubject['math'] ?? collect();
                                        $mathTotal = $mathData->sum('count');
                                    @endphp
                                    @foreach($mathLevels as $level)
                                        @php
                                            $levelData = $mathData->firstWhere('level', $level);
                                            $count = $levelData ? $levelData->count : 0;
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ __($level) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($count) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $mathTotal > 0 ? number_format(($count / $mathTotal) * 100, 1) : 0 }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-gray-50 font-semibold">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ __('Total') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($mathTotal) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">100.0%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <!-- Performance by Level - Single Subject -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-md font-medium text-gray-900">{{ __('Performance by Level') }} - {{ ucfirst($subject) }}</h4>
                            <button onclick="exportSingleChart('performanceChart', '{{ $subject }}')" class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 no-print">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ __('Export Chart') }}
                            </button>
                        </div>
                        
                        <div class="mb-4 p-3 bg-gray-50 rounded-lg text-sm text-gray-600">
                            <p><strong>{{ __('What this shows') }}:</strong> 
                                @if($subject === 'khmer')
                                    {{ __('Distribution of students across Khmer reading levels. Each student is assessed and placed at the level that best matches their reading ability.') }}
                                @else
                                    {{ __('Distribution of students across Math skill levels. Each student is assessed and placed at the level that best matches their mathematical ability.') }}
                                @endif
                            </p>
                            <p class="mt-1"><strong>{{ __('Color coding') }}:</strong> {{ __('Colors progress from red (beginner) to green/teal (advanced) to show skill progression.') }}</p>
                        </div>
                        
                        <div class="relative chart-container" style="height: 300px;" data-chart="single">
                            <canvas id="performanceChart"></canvas>
                            <!-- Print-friendly chart replacement -->
                            <div class="print-chart-table">
                                <div class="flex justify-between items-center gap-2">
                                    @php
                                        $singlePrintData = $performanceByLevel;
                                        $singlePrintTotal = $singlePrintData->sum('count');
                                        $singleLevels = $subject === 'khmer' ? $khmerLevels : $mathLevels;
                                    @endphp
                                    @foreach($singleLevels as $level)
                                        @php
                                            $levelData = $singlePrintData->firstWhere('level', $level);
                                            $count = $levelData ? $levelData->count : 0;
                                            $percent = $singlePrintTotal > 0 ? round(($count / $singlePrintTotal) * 100) : 0;
                                            $height = $percent * 2; // Scale for visual representation
                                            $barColor = $subject === 'khmer' ? 'bg-blue-500' : 'bg-green-500';
                                        @endphp
                                        <div class="flex-1 text-center">
                                            <div class="relative">
                                                <div class="bg-gray-200 rounded" style="height: 200px; position: relative;">
                                                    <div class="absolute bottom-0 left-0 right-0 {{ $barColor }} rounded" style="height: {{ $height }}px;"></div>
                                                </div>
                                                <div class="mt-2 text-xs font-bold">{{ $count }}</div>
                                                <div class="text-xs">{{ $level }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- Summary Table -->
                        <div class="mt-6 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Level') }}
                                            <span class="normal-case text-gray-400" title="{{ __('Skill level determined by assessment') }}">ⓘ</span>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Number of Students') }}
                                            <span class="normal-case text-gray-400" title="{{ __('Total students assessed at this level') }}">ⓘ</span>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Percentage') }}
                                            <span class="normal-case text-gray-400" title="{{ __('Percentage of all assessments for this subject') }}">ⓘ</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $total = $performanceByLevel->sum('count');
                                        $levels = $subject === 'khmer' ? $khmerLevels : $mathLevels;
                                    @endphp
                                    @foreach($levels as $level)
                                        @php
                                            $levelData = $performanceByLevel->firstWhere('level', $level);
                                            $count = $levelData ? $levelData->count : 0;
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ __($level) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($count) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $total > 0 ? number_format(($count / $total) * 100, 1) : 0 }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-gray-50 font-semibold">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ __('Total') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($total) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">100.0%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Data Interpretation Guide -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">{{ __('How to Interpret This Data') }}</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li><strong>{{ __('Higher level distributions') }}:</strong> {{ __('More students at advanced levels indicates stronger overall performance') }}</li>
                                <li><strong>{{ __('Beginner concentrations') }}:</strong> {{ __('Large numbers at beginner level may indicate need for foundational support') }}</li>
                                <li><strong>{{ __('Even distributions') }}:</strong> {{ __('Students spread across levels suggests mixed abilities requiring differentiated instruction') }}</li>
                                <li><strong>{{ __('Filter usage') }}:</strong> {{ __('Use school, subject, and cycle filters to analyze specific groups or track progress over time') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Export Options -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h4 class="text-md font-medium text-gray-900 mb-4">{{ __('Export Options') }}</h4>
                    
                    <div class="mb-3 text-sm text-gray-600">
                        <p>{{ __('Save charts and data for presentations, reports, or further analysis.') }}</p>
                    </div>
                    
                    <div class="flex gap-4">
                        <button onclick="exportCharts()" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ __('Export Charts as Images') }}
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
            const khmerLevels = @json($khmerLevels);
            const mathLevels = @json($mathLevels);
            const subject = '{{ $subject }}';
            
            if (subject === 'all') {
                // Create Khmer chart
                const khmerData = @json($performanceByLevelAndSubject['khmer'] ?? collect());
                createChart('khmerPerformanceChart', khmerData, khmerLevels, 'Khmer', 'khmer');
                
                // Create Math chart
                const mathData = @json($performanceByLevelAndSubject['math'] ?? collect());
                createChart('mathPerformanceChart', mathData, mathLevels, 'Math', 'math');
            } else {
                // Create single subject chart
                const performanceData = @json($performanceByLevel);
                const levels = subject === 'khmer' ? khmerLevels : mathLevels;
                createChart('performanceChart', performanceData, levels, subject.charAt(0).toUpperCase() + subject.slice(1), subject);
            }
            
            function createChart(canvasId, data, levelOrder, subjectName, subjectType) {
                // Prepare data in correct order
                const chartData = levelOrder.map(level => {
                    const item = data.find(d => d.level === level);
                    return item ? item.count : 0;
                });
                
                // Color schemes
                const khmerColors = [
                    '#ef4444', // red for beginner
                    '#f59e0b', // amber for letter
                    '#eab308', // yellow for word
                    '#84cc16', // lime for paragraph
                    '#22c55e', // green for story
                    '#10b981', // emerald for comp 1
                    '#14b8a6'  // teal for comp 2
                ];
                
                const mathColors = [
                    '#ef4444', // red for beginner
                    '#f59e0b', // amber for 1-digit
                    '#eab308', // yellow for 2-digit
                    '#84cc16', // lime for subtraction
                    '#22c55e', // green for division
                    '#10b981', // emerald for word problem
                ];
                
                const colors = subjectType === 'khmer' ? khmerColors : mathColors;
                
                // Create chart
                const ctx = document.getElementById(canvasId).getContext('2d');
                window[canvasId + 'Chart'] = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: levelOrder,
                        datasets: [{
                            label: '{{ __("Number of Students") }}',
                            data: chartData,
                            backgroundColor: colors.slice(0, levelOrder.length),
                            borderWidth: 1,
                            borderColor: colors.slice(0, levelOrder.length).map(color => color + 'DD')
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
                                text: subjectName + ' - {{ __("Student Distribution by Level") }}',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((context.parsed.y / total) * 100).toFixed(1) : 0;
                                        return context.parsed.y + ' {{ __("students") }} (' + percentage + '%)';
                                    }
                                }
                            },
                            // Data labels removed to prevent recursion error
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: '{{ __("Number of Students") }}',
                                    font: {
                                        size: 14,
                                        weight: 'bold',
                                        color: '#374151'
                                    }
                                },
                                ticks: {
                                    stepSize: 1,
                                    font: {
                                        size: 12
                                    },
                                    color: '#6B7280',
                                    callback: function(value) {
                                        if (Math.floor(value) === value) {
                                            return value;
                                        }
                                    }
                                },
                                grid: {
                                    display: true,
                                    drawBorder: true,
                                    drawOnChartArea: true,
                                    borderDash: [5, 5],
                                    color: '#E5E7EB'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: '{{ __("Skill Level") }} ({{ __("Beginner to Advanced") }})',
                                    font: {
                                        size: 14,
                                        weight: 'bold',
                                        color: '#374151'
                                    }
                                },
                                ticks: {
                                    font: {
                                        size: 12
                                    },
                                    color: '#6B7280'
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    },
                    plugins: []
                });
            }
            
            // Export single chart function
            window.exportSingleChart = function(chartId, subjectType) {
                try {
                    const date = new Date().toISOString().split('T')[0];
                    const chart = window[chartId + 'Chart'];
                    
                    if (!chart) {
                        console.error('Chart not found:', chartId);
                        alert('Chart not found. Please wait for the chart to load and try again.');
                        return;
                    }
                    
                    // Use Chart.js built-in toBase64Image method
                    const imageUrl = chart.toBase64Image();
                    
                    // Download
                    const link = document.createElement('a');
                    link.download = subjectType + '-performance-chart-' + date + '.png';
                    link.href = imageUrl;
                    link.click();
                    
                } catch (error) {
                    console.error('Error exporting chart:', error);
                    alert('Error exporting chart. Please try again.');
                }
            };
            
            // Export charts function (for backward compatibility)
            window.exportCharts = function() {
                const subject = '{{ $subject }}';
                const date = new Date().toISOString().split('T')[0];
                
                if (subject === 'all') {
                    // Export both charts
                    exportSingleChart('khmerPerformanceChart', 'khmer');
                    setTimeout(() => {
                        exportSingleChart('mathPerformanceChart', 'math');
                    }, 500);
                } else {
                    // Export single chart
                    exportSingleChart('performanceChart', subject);
                }
            };
            
            // Print event handlers - charts are hidden during print and replaced with HTML bars
            window.addEventListener('beforeprint', function() {
                // Charts will be hidden via CSS during print
                // The print-chart-table divs will be shown instead
                console.log('Preparing for print...');
            });
            
            window.addEventListener('afterprint', function() {
                // Nothing to restore as CSS handles visibility
                console.log('Print completed.');
            });
        });
    </script>
    @endpush
</x-app-layout>