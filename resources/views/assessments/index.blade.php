<x-app-layout>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Statistics Summary for Mentors -->
            @if(auth()->user()->role === 'mentor' || auth()->user()->role === 'admin')
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <div class="text-sm font-medium text-gray-500">{{ __('assessment.Total Assessments') }}</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900">{{ $assessments->total() }}</div>
                    </div>
                </div>
                <div class="bg-yellow-50 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <div class="text-sm font-medium text-yellow-800">{{ __('assessment.Pending Verification') }}</div>
                        <div class="mt-1 text-2xl font-semibold text-yellow-900">
                            {{ \App\Models\Assessment::where('verification_status', 'pending')->count() }}
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <div class="text-sm font-medium text-green-800">{{ __('assessment.Verified') }}</div>
                        <div class="mt-1 text-2xl font-semibold text-green-900">
                            {{ \App\Models\Assessment::where('verification_status', 'verified')->count() }}
                        </div>
                    </div>
                </div>
                <div class="bg-red-50 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <div class="text-sm font-medium text-red-800">{{ __('assessment.Needs Correction') }}</div>
                        <div class="mt-1 text-2xl font-semibold text-red-900">
                            {{ \App\Models\Assessment::where('verification_status', 'needs_correction')->count() }}
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header with Action Button -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            @if(auth()->user()->role === 'mentor')
                                {{ trans_db('assessment_verification') }}
                            @else
                                {{ trans_db('assessments') }}
                            @endif
                        </h3>
                        <div class="flex space-x-2">
                            @if(in_array(auth()->user()->role, ['admin', 'teacher']))
                            <a href="{{ route('assessments.select-students') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                {{ trans_db('select_students') }}
                            </a>
                            @endif
                            @if(in_array(auth()->user()->role, ['admin', 'teacher', 'mentor']))
                            <a href="{{ route('assessments.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                {{ trans_db('new_assessment') }}
                            </a>
                            @endif
                        </div>
                    </div>

                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('verification.index') }}" class="mb-6">
                        <div class="flex flex-wrap gap-4">
                            <div class="flex-1 min-w-[200px]">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    placeholder="{{ trans_db('search_by_student_name') }}" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            @if(auth()->user()->isAdmin() || auth()->user()->isMentor())
                            <div>
                                <select name="school_id" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ trans_db('all_schools') }}</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                            {{ $school->school_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            
                            <div>
                                <select name="subject" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ trans_db('all_subjects') }}</option>
                                    <option value="khmer" {{ request('subject') == 'khmer' ? 'selected' : '' }}>{{ trans_db('khmer') }}</option>
                                    <option value="math" {{ request('subject') == 'math' ? 'selected' : '' }}>{{ trans_db('math') }}</option>
                                </select>
                            </div>
                            <div>
                                <select name="cycle" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ trans_db('all_cycles') }}</option>
                                    <option value="baseline" {{ request('cycle') == 'baseline' ? 'selected' : '' }}>{{ trans_db('baseline') }}</option>
                                    <option value="midline" {{ request('cycle') == 'midline' ? 'selected' : '' }}>{{ trans_db('midline') }}</option>
                                    <option value="endline" {{ request('cycle') == 'endline' ? 'selected' : '' }}>{{ trans_db('endline') }}</option>
                                </select>
                            </div>
                            
                            <div>
                                <select name="grade" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ trans_db('all_grades') }}</option>
                                    <option value="4" {{ request('grade') == '4' ? 'selected' : '' }}>{{ trans_db('grade') }} 4</option>
                                    <option value="5" {{ request('grade') == '5' ? 'selected' : '' }}>{{ trans_db('grade') }} 5</option>
                                    <option value="6" {{ request('grade') == '6' ? 'selected' : '' }}>{{ trans_db('grade') }} 6</option>
                                </select>
                            </div>
                            
                            <div class="flex gap-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ trans_db('search') }}
                                </button>
                                @if(request('search') || request('subject') || request('cycle') || request('school_id') || request('grade'))
                                    <a href="{{ route('verification.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ trans_db('clear') }}
                                    </a>
                                @endif
                                <a href="{{ route('assessments.export') }}?{{ request()->getQueryString() }}" class="export-btn inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    {{ trans_db('export_to_excel') }}
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Assessments Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ trans_db('student_name') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ trans_db('school') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ trans_db('teacher') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <x-sortable-header column="subject" :current-sort="$sortField" :current-order="$sortOrder">
                                            {{ trans_db('subject') }}
                                        </x-sortable-header>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <x-sortable-header column="cycle" :current-sort="$sortField" :current-order="$sortOrder">
                                            {{ trans_db('test_cycle') }}
                                        </x-sortable-header>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <x-sortable-header column="level" :current-sort="$sortField" :current-order="$sortOrder">
                                            {{ trans_db('student_level') }}
                                        </x-sortable-header>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <x-sortable-header column="assessed_at" :current-sort="$sortField" :current-order="$sortOrder">
                                            {{ trans_db('date') }}
                                        </x-sortable-header>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ trans_db('verification_status') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($assessments as $assessment)
                                <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location.href='{{ route('verification.show', $assessment->id) }}'">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <a href="{{ route('verification.show', $assessment->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ $assessment->student->name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $assessment->student->pilotSchool ? $assessment->student->pilotSchool->school_name : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $assessment->assessor ? $assessment->assessor->name : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ trans_db($assessment->subject === 'khmer' ? 'khmer' : 'math') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ trans_db($assessment->cycle) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if(in_array($assessment->level, ['Story', 'Comp. 1', 'Comp. 2'])) 
                                                bg-green-100 text-green-800
                                            @elseif(in_array($assessment->level, ['Paragraph', 'Word']))
                                                bg-yellow-100 text-yellow-800
                                            @else
                                                bg-red-100 text-red-800
                                            @endif">
                                            {{ trans_db(strtolower(str_replace([' ', '.'], ['_', ''], $assessment->level))) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $assessment->assessed_at->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($assessment->verification_status === 'verified')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                {{ trans_db('verified') }}
                                            </span>
                                        @elseif($assessment->verification_status === 'needs_correction')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                {{ trans_db('needs_correction') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                {{ trans_db('pending') }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                        {{ trans_db('no_assessments_found') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($assessments->hasPages())
                    <div class="mt-4">
                        {{ $assessments->withQueryString()->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>