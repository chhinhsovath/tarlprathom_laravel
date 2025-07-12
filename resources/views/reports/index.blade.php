<x-app-layout>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Statistics - First Row (4 stats) -->
            <div style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem;" class="mb-6">
                @if(isset($stats['total_students']))
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_students']) }}</div>
                            <div class="text-sm font-medium text-gray-600 mt-1">{{ __('Total Students') }}</div>
                        </div>
                        <div class="ml-4">
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                @if(isset($stats['total_assessments']))
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_assessments']) }}</div>
                            <div class="text-sm font-medium text-gray-600 mt-1">{{ __('Total Assessments') }}</div>
                        </div>
                        <div class="ml-4">
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                @if(isset($stats['total_schools']))
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 border-l-4 border-purple-500">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_schools']) }}</div>
                            <div class="text-sm font-medium text-gray-600 mt-1">{{ __('Total Schools') }}</div>
                        </div>
                        <div class="ml-4">
                            <div class="p-3 bg-purple-100 rounded-full">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                @if(isset($stats['total_mentoring_visits']))
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 border-l-4 border-orange-500">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_mentoring_visits']) }}</div>
                            <div class="text-sm font-medium text-gray-600 mt-1">{{ __('Mentoring Visits') }}</div>
                        </div>
                        <div class="ml-4">
                            <div class="p-3 bg-orange-100 rounded-full">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Additional Statistics - Second Row (if applicable) -->
            @if(isset($stats['total_visits']) || isset($stats['schools_visited']) || isset($stats['teachers_mentored']) || isset($stats['avg_mentoring_score']))
            <div style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem;" class="mb-6">
                @if(isset($stats['total_visits']))
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 border-l-4 border-indigo-500">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_visits']) }}</div>
                            <div class="text-sm font-medium text-gray-600 mt-1">{{ __('My Total Visits') }}</div>
                        </div>
                        <div class="ml-4">
                            <div class="p-3 bg-indigo-100 rounded-full">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                @if(isset($stats['schools_visited']))
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 border-l-4 border-pink-500">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['schools_visited']) }}</div>
                            <div class="text-sm font-medium text-gray-600 mt-1">{{ __('Schools Visited') }}</div>
                        </div>
                        <div class="ml-4">
                            <div class="p-3 bg-pink-100 rounded-full">
                                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                @if(isset($stats['teachers_mentored']))
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 border-l-4 border-teal-500">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['teachers_mentored']) }}</div>
                            <div class="text-sm font-medium text-gray-600 mt-1">{{ __('Teachers Mentored') }}</div>
                        </div>
                        <div class="ml-4">
                            <div class="p-3 bg-teal-100 rounded-full">
                                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                @if(isset($stats['avg_mentoring_score']))
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['avg_mentoring_score'], 1) }}%</div>
                            <div class="text-sm font-medium text-gray-600 mt-1">{{ __('Average Score') }}</div>
                        </div>
                        <div class="ml-4">
                            <div class="p-3 bg-red-100 rounded-full">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Available Reports -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Available Reports') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-4xl">
                        @foreach($availableReports as $report)
                        <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                            <h4 class="font-medium text-gray-900 mb-2">{{ __($report['name']) }}</h4>
                            <p class="text-sm text-gray-600 mb-3">{{ __($report['description']) }}</p>
                            <a href="{{ route($report['route']) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                {{ __('Generate Report') }} →
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Export Options -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Export Data') }}</h3>
                    
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('reports.export', ['type' => 'assessments', 'format' => 'xlsx']) }}" 
                           class="export-btn inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ __('Export Assessments (XLSX)') }}
                        </a>
                        
                        <a href="{{ route('reports.export', ['type' => 'mentoring', 'format' => 'xlsx']) }}" 
                           class="export-btn inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ __('Export Mentoring Visits (XLSX)') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            @if(isset($stats['recent_assessments']) && count($stats['recent_assessments']) > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Recent Assessments') }}</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Date') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Student') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('School') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Subject') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Level') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($stats['recent_assessments'] as $assessment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $assessment->assessed_at->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $assessment->student->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $assessment->student->school->school_name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ __($assessment->subject === 'khmer' ? 'Khmer' : 'Math') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if(in_array($assessment->level, ['Story Reader', 'Comp. 1', 'Comp. 2', 'Division', 'Word Problem'])) 
                                                bg-green-100 text-green-800
                                            @elseif(in_array($assessment->level, ['Paragraph Reader', 'Word Level', 'Subtraction', '2-Digit']))
                                                bg-yellow-100 text-yellow-800
                                            @else
                                                bg-red-100 text-red-800
                                            @endif">
                                            {{ __($assessment->level) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>