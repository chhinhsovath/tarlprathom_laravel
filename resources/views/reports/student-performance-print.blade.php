<x-app-layout>
    {{-- Print Styles --}}
    @push('styles')
    <style>
        @media print {
            /* Reset and base styles */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            
            /* Page setup */
            @page {
                size: A4 landscape;
                margin: 15mm 10mm 15mm 10mm;
            }
            
            /* Hide non-printable elements */
            .no-print,
            nav,
            header,
            .navigation,
            button[type="submit"],
            a[href*="reports.index"],
            .export-options {
                display: none !important;
            }
            
            /* Layout adjustments */
            body {
                font-size: 11pt;
                line-height: 1.4;
            }
            
            .print-container {
                width: 100% !important;
                max-width: none !important;
                padding: 0 !important;
            }
            
            /* Header styling */
            .report-header {
                padding: 10px 0;
                border-bottom: 2px solid #000;
                margin-bottom: 20px;
                page-break-after: avoid;
            }
            
            .report-title {
                font-size: 18pt;
                font-weight: bold;
                margin-bottom: 10px;
            }
            
            .report-meta {
                font-size: 10pt;
                color: #333;
            }
            
            /* Filter summary for print */
            .filter-summary {
                background: #f5f5f5;
                padding: 10px;
                margin-bottom: 20px;
                border: 1px solid #ddd;
                page-break-inside: avoid;
            }
            
            /* Chart containers */
            .chart-container {
                page-break-inside: avoid;
                break-inside: avoid;
                margin-bottom: 30px;
                padding: 15px;
                border: 1px solid #ddd;
            }
            
            .chart-wrapper {
                height: 350px !important;
                width: 100% !important;
                page-break-inside: avoid;
            }
            
            canvas {
                max-height: 350px !important;
                width: 100% !important;
            }
            
            /* Tables */
            table {
                width: 100%;
                border-collapse: collapse;
                page-break-inside: avoid;
                font-size: 10pt;
            }
            
            thead {
                display: table-header-group;
            }
            
            th {
                background-color: #f3f4f6 !important;
                font-weight: bold;
                padding: 8px;
                border: 1px solid #d1d5db;
                text-align: left;
            }
            
            td {
                padding: 6px 8px;
                border: 1px solid #d1d5db;
            }
            
            /* Info boxes */
            .info-box {
                background: #f9fafb;
                border: 1px solid #e5e7eb;
                padding: 10px;
                margin-bottom: 20px;
                page-break-inside: avoid;
            }
            
            .info-box h3 {
                font-size: 12pt;
                font-weight: bold;
                margin-bottom: 8px;
            }
            
            .info-box ul {
                margin-left: 20px;
                font-size: 10pt;
            }
            
            /* Page breaks */
            .page-break {
                page-break-after: always;
            }
            
            .avoid-break {
                page-break-inside: avoid;
            }
            
            /* Grid adjustments */
            .grid {
                display: block !important;
            }
            
            .grid > div {
                width: 100% !important;
                margin-bottom: 15px;
            }
            
            /* Performance sections */
            .performance-section {
                page-break-inside: avoid;
                margin-bottom: 30px;
            }
            
            /* Hide shadows and rounded corners */
            .shadow-sm,
            .shadow {
                box-shadow: none !important;
            }
            
            .rounded-lg,
            .sm\\:rounded-lg {
                border-radius: 0 !important;
            }
            
            /* Ensure background colors print */
            .bg-blue-50,
            .bg-yellow-50,
            .bg-gray-50 {
                background-color: #f5f5f5 !important;
            }
            
            .bg-white {
                background-color: white !important;
            }
            
            /* Text adjustments */
            .text-sm {
                font-size: 10pt !important;
            }
            
            .text-xs {
                font-size: 9pt !important;
            }
            
            .text-lg {
                font-size: 14pt !important;
            }
            
            /* Footer for pages */
            .print-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                text-align: center;
                font-size: 9pt;
                color: #666;
                padding: 10px 0;
                border-top: 1px solid #ddd;
            }
        }
        
        /* Screen-only styles */
        @media screen {
            .print-only {
                display: none;
            }
            
            .print-header {
                display: none;
            }
        }
        
        /* Chart optimization for print */
        .chart-for-print {
            position: relative;
            height: 400px;
            margin: 20px 0;
        }
        
        @media print {
            .chart-for-print {
                height: 350px !important;
                max-width: 100% !important;
            }
        }
    </style>
    @endpush

    <div class="py-6 print-container">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            {{-- Print Header (visible only when printing) --}}
            <div class="print-only print-header report-header">
                <h1 class="report-title">{{ __('Student Performance Report') }}</h1>
                <div class="report-meta">
                    <p>{{ __('Generated on') }}: {{ now()->format('F d, Y h:i A') }}</p>
                    <p>{{ __('Report Period') }}: {{ $cycle == 'all' ? __('All Cycles') : ucfirst($cycle) }}</p>
                    <p>{{ __('Subject') }}: {{ $subject == 'all' ? __('All Subjects') : ucfirst($subject) }}</p>
                    @if($schoolId)
                        <p>{{ __('School') }}: {{ $schools->find($schoolId)->school_name ?? __('N/A') }}</p>
                    @endif
                </div>
            </div>

            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 no-print">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Student Performance Report') }}</h3>
                        <div class="flex gap-2">
                            <button onclick="window.print()" class="inline-flex items-center px-3 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                {{ __('Print') }}
                            </button>
                            <button onclick="exportAsPDF()" class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                {{ __('Export PDF') }}
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

            {{-- Filter Summary for Print --}}
            <div class="print-only filter-summary">
                <h3 class="font-bold mb-2">{{ __('Report Filters') }}</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <strong>{{ __('Subject') }}:</strong> {{ $subject == 'all' ? __('All Subjects') : ucfirst($subject) }}
                    </div>
                    <div>
                        <strong>{{ __('Cycle') }}:</strong> {{ $cycle == 'all' ? __('All Cycles') : ucfirst($cycle) }}
                    </div>
                    @if($schoolId)
                    <div>
                        <strong>{{ __('School') }}:</strong> {{ $schools->find($schoolId)->school_name ?? __('N/A') }}
                    </div>
                    @endif
                    <div>
                        <strong>{{ __('Total Assessments') }}:</strong> {{ $assessments->count() }}
                    </div>
                </div>
            </div>

            <!-- Understanding the Assessment System -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 info-box avoid-break">
                <div class="flex">
                    <div class="flex-shrink-0 no-print">
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
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 info-box avoid-break">
                <div class="flex">
                    <div class="flex-shrink-0 no-print">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9.5H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">{{ __('Assessment Cycles') }}</h3>
                        <div class="mt-2 text-sm text-green-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li><strong>{{ __('Baseline') }}:</strong> {{ __('Initial assessment at the beginning of the academic year to establish starting levels') }}</li>
                                <li><strong>{{ __('Midline') }}:</strong> {{ __('Mid-year assessment to track progress and adjust teaching strategies') }}</li>
                                <li><strong>{{ __('Endline') }}:</strong> {{ __('Final assessment to measure overall improvement and learning outcomes') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Continue with the rest of the content from the original file -->
            @include('reports.partials.student-performance-charts')
            
            <!-- Print Footer -->
            <div class="print-only print-footer">
                <p>{{ __('Page') }} <span class="page-number"></span> | {{ __('Generated by TaRL Assessment System') }} | {{ now()->format('F d, Y') }}</p>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        // PDF Export function
        function exportAsPDF() {
            // Trigger print dialog which user can save as PDF
            window.print();
        }
        
        // Optimize charts for printing
        window.addEventListener('beforeprint', function() {
            // Store original chart sizes
            const charts = document.querySelectorAll('canvas');
            charts.forEach(chart => {
                chart.dataset.originalHeight = chart.style.height;
                chart.style.height = '350px';
            });
            
            // Update chart instances if needed
            if (typeof Chart !== 'undefined') {
                Chart.helpers.each(Chart.instances, function(instance) {
                    instance.resize();
                    instance.update();
                });
            }
        });
        
        window.addEventListener('afterprint', function() {
            // Restore original chart sizes
            const charts = document.querySelectorAll('canvas');
            charts.forEach(chart => {
                if (chart.dataset.originalHeight) {
                    chart.style.height = chart.dataset.originalHeight;
                }
            });
            
            // Update chart instances
            if (typeof Chart !== 'undefined') {
                Chart.helpers.each(Chart.instances, function(instance) {
                    instance.resize();
                    instance.update();
                });
            }
        });
    </script>
    @endpush
</x-app-layout>