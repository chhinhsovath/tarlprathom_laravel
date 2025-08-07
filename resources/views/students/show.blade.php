<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                {{ __('students.Student Details') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('students.assessment-history', $student) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('students.Assessment History') }}
                </a>
                @can('update', $student)
                    <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        {{ __('students.Edit') }}
                    </a>
                @endcan
                <a href="{{ route('students.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                    </svg>
                    {{ __('students.Back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Student Info Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-4 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $student->name }}</h3>
                    
                    <!-- Personal Information -->
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-700 uppercase tracking-wider mb-2">{{ __('students.Personal Information') }}</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('students.Age') }}</span>
                                <p class="text-sm font-medium text-gray-900">{{ $student->age ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('students.Gender') }}</span>
                                <p class="text-sm font-medium text-gray-900">{{ ucfirst($student->gender) }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('students.Grade') }}</span>
                                <p class="text-sm font-medium text-gray-900">{{ $student->class ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- School Information -->
                    <div class="mb-4 border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-700 uppercase tracking-wider mb-2">{{ __('students.School Information') }}</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('students.School') }}</span>
                                <p class="text-sm font-medium text-gray-900">{{ $student->school->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('students.District') }}</span>
                                <p class="text-sm font-medium text-gray-900">{{ $student->school->district ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('students.Cluster') }}</span>
                                <p class="text-sm font-medium text-gray-900">{{ $student->school->cluster ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('students.Province') }}</span>
                                <p class="text-sm font-medium text-gray-900">{{ $student->school->province ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('students.Teacher') }}</span>
                                <p class="text-sm font-medium text-gray-900">{{ $student->teacher->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('students.School Code') }}</span>
                                <p class="text-sm font-medium text-gray-900">{{ $student->school->school_code ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            @if($student->assessments->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                    @php
                        $mathScores = $student->assessments->where('subject', 'math')->pluck('score');
                        $khmerScores = $student->assessments->where('subject', 'khmer')->pluck('score');
                        $latestAssessment = $student->assessments->sortByDesc('assessed_at')->first();
                    @endphp
                    
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <p class="text-xs text-gray-500 uppercase">{{ __('students.Total Assessments') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $student->assessments->count() }}</p>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <p class="text-xs text-gray-500 uppercase">{{ __('students.Avg Math Score') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $mathScores->count() > 0 ? round($mathScores->avg()) : '-' }}%
                        </p>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <p class="text-xs text-gray-500 uppercase">{{ __('students.Avg Khmer Score') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $khmerScores->count() > 0 ? round($khmerScores->avg()) : '-' }}%
                        </p>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <p class="text-xs text-gray-500 uppercase">{{ __('students.Latest Assessment') }}</p>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $latestAssessment && $latestAssessment->assessed_at ? $latestAssessment->assessed_at->format('M d, Y') : '-' }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- Assessment History Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('students.Current Assessments') }}</h3>
                        <div class="flex gap-4">
                            @if($student->assessments->count() > 0)
                                <span class="text-sm text-gray-500 mt-1 sm:mt-0">
                                    {{ $student->assessments->count() }} {{ __('assessment(s)') }}
                                </span>
                            @endif
                            @php
                                $historyCount = \App\Models\AssessmentHistory::where('student_id', $student->id)->count();
                            @endphp
                            @if($historyCount > 0)
                                <span class="text-sm text-gray-500 mt-1 sm:mt-0">
                                    {{ $historyCount }} {{ __('change(s)') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    @if($historyCount > 0)
                        <div class="mb-4 text-sm text-gray-600 bg-blue-50 p-3 rounded-lg">
                            <span class="font-medium">{{ __('Note') }}:</span> 
                            {{ __('This shows current assessments only.') }} 
                            {{ __('Click "Assessment History" button above to view all') }} {{ $historyCount }} {{ __('changes.') }}
                        </div>
                    @endif
                    
                    @if($student->assessments->count() > 0)
                        <!-- Mobile View -->
                        <div class="sm:hidden space-y-3">
                            @foreach($student->assessments as $assessment)
                                <div class="border rounded-lg p-3 bg-gray-50">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ ucfirst($assessment->subject) }}</p>
                                            <p class="text-xs text-gray-500">{{ $assessment->assessed_at ? $assessment->assessed_at->format('M d, Y') : 'N/A' }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $assessment->score }}%
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                        <div>
                                            <span class="text-gray-500">{{ __('Cycle:') }}</span>
                                            <span class="text-gray-900 ml-1">{{ ucfirst($assessment->cycle) }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">{{ __('Level:') }}</span>
                                            <span class="text-gray-900 ml-1">{{ $assessment->level }}</span>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('assessments.show', $assessment) }}" class="text-xs text-indigo-600 hover:text-indigo-900">
                                            {{ __('View Details') }} →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Desktop/Tablet View -->
                        <div class="hidden sm:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Date') }}
                                        </th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Cycle') }}
                                        </th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Subject') }}
                                        </th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Level') }}
                                        </th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Score') }}
                                        </th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($student->assessments as $assessment)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ $assessment->assessed_at ? $assessment->assessed_at->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-sm">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                                    {{ $assessment->cycle == 'baseline' ? 'bg-gray-100 text-gray-800' : '' }}
                                                    {{ $assessment->cycle == 'midline' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $assessment->cycle == 'endline' ? 'bg-green-100 text-green-800' : '' }}">
                                                    {{ ucfirst($assessment->cycle) }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ ucfirst($assessment->subject) }}
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ $assessment->level }}
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-sm">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $assessment->score }}%
                                                </span>
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('assessments.show', $assessment) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    {{ __('View') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">{{ __('students.No assessments found for this student.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>