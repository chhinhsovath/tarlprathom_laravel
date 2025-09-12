<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    {{ trans_db('assessment_details') }}
                </h2>
                @if($assessment->is_locked ?? false)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <svg class="-ml-0.5 mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        {{ trans_db('locked') }}
                    </span>
                @endif
            </div>
            <div class="flex gap-2">
                <a href="{{ route('verification.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                    </svg>
                    {{ trans_db('back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Assessment Overview Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Assessment Date -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">{{ trans_db('assessment_date') }}</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $assessment->assessed_at ? $assessment->assessed_at->format('M d, Y') : 'គ្មាន' }}
                            </p>
                        </div>
                        
                        <!-- Cycle -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">{{ trans_db('cycle') }}</p>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    {{ $assessment->cycle == 'baseline' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $assessment->cycle == 'midline' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $assessment->cycle == 'endline' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ trans_db($assessment->cycle) }}
                                </span>
                            </p>
                        </div>
                        
                        <!-- Subject -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">{{ trans_db('subject') }}</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ trans_db($assessment->subject) }}
                            </p>
                        </div>
                        
                        <!-- Score -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">{{ trans_db('score') }}</p>
                            <p class="mt-1 text-2xl font-bold text-blue-600">
                                {{ $assessment->score }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assessment Criteria and Results Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-4 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ trans_db('assessment_criteria_and_results') }}</h3>
                    
                    <!-- Current Assessment Level -->
                    <div class="mb-6">
                        <p class="text-sm font-medium text-gray-700 mb-3">{{ trans_db('student_competency_level') }}</p>
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-lg font-semibold text-blue-900">{{ trans_db($assessment->level) }}</p>
                                    <p class="text-sm text-blue-700">{{ trans_db('assessed_level_for') }} {{ trans_db($assessment->subject) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Level Progression Scale with Comparison -->
                    <div class="mb-6">
                        <p class="text-sm font-medium text-gray-700 mb-3">{{ trans_db('assessment_standard_comparison') }}</p>
                        
                        <!-- Legend -->
                        <div class="flex flex-wrap gap-4 mb-4 text-sm">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                                <span>{{ trans_db('teacher_assessment') }}</span>
                            </div>
                            @if($assessment->mentor_assessed_level)
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                                <span>{{ trans_db('mentors_assessment') }}</span>
                            </div>
                            @endif
                            @if($assessment->mentor_assessed_level && $assessment->mentor_assessed_level !== $assessment->level)
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                                <span>{{ trans_db('discrepancy') }}</span>
                            </div>
                            @endif
                        </div>
                        
                        @if($assessment->subject === 'khmer')
                        <!-- Khmer Levels -->
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-2">
                            @php
                                $khmerLevels = ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2'];
                                $currentLevelIndex = array_search($assessment->level, $khmerLevels);
                                $mentorLevelIndex = array_search($assessment->mentor_assessed_level, $khmerLevels);
                            @endphp
                            @foreach($khmerLevels as $index => $level)
                            <div class="text-center p-2 rounded-lg border-2 transition-all relative
                                {{ $assessment->level === $level && $assessment->mentor_assessed_level === $level ? 'bg-yellow-100 border-yellow-500 shadow-md' : '' }}
                                {{ $assessment->level === $level && $assessment->mentor_assessed_level !== $level ? 'bg-blue-100 border-blue-500 shadow-md' : '' }}
                                {{ $assessment->mentor_assessed_level === $level && $assessment->level !== $level ? 'bg-green-100 border-green-500 shadow-md' : '' }}
                                {{ $assessment->level !== $level && $assessment->mentor_assessed_level !== $level ? 'bg-gray-50 border-gray-200' : '' }}">
                                
                                <!-- Level indicators -->
                                <div class="absolute -top-2 -right-2 flex gap-1">
                                    @if($assessment->level === $level)
                                    <div class="w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center" title="{{ __('assessment.teacher_assessment') }}">
                                        <span class="text-white text-xs font-bold">T</span>
                                    </div>
                                    @endif
                                    @if($assessment->mentor_assessed_level === $level)
                                    <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center" title="{{ __('assessment.mentor_assessment') }}">
                                        <span class="text-white text-xs font-bold">M</span>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="text-xs font-semibold {{ ($assessment->level === $level || $assessment->mentor_assessed_level === $level) ? 'text-gray-700' : 'text-gray-600' }}">
                                    {{ __('assessment.level') }} {{ $index + 1 }}
                                </div>
                                <div class="text-sm {{ ($assessment->level === $level || $assessment->mentor_assessed_level === $level) ? 'font-bold text-gray-900' : 'text-gray-700' }}">
                                    {{ trans_db($level) }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Khmer Level Descriptions -->
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 mb-2">{{ trans_db('level_descriptions') }}:</p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li><span class="font-semibold">{{ trans_db('beginner') }}:</span> {{ trans_db('cannot_recognize_letters') }}</li>
                                <li><span class="font-semibold">{{ trans_db('letter') }}:</span> {{ trans_db('can_recognize_individual_letters') }}</li>
                                <li><span class="font-semibold">{{ trans_db('word') }}:</span> {{ trans_db('can_read_simple_words') }}</li>
                                <li><span class="font-semibold">{{ trans_db('paragraph') }}:</span> {{ trans_db('can_read_paragraphs_with_comprehension') }}</li>
                                <li><span class="font-semibold">{{ trans_db('story') }}:</span> {{ trans_db('can_read_stories_with_understanding') }}</li>
                                <li><span class="font-semibold">{{ trans_db('comp_1') }}:</span> {{ trans_db('basic_comprehension_skills') }}</li>
                                <li><span class="font-semibold">{{ trans_db('comp_2') }}:</span> {{ trans_db('advanced_comprehension_skills') }}</li>
                            </ul>
                        </div>
                        
                        @else
                        <!-- Math Levels -->
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2">
                            @php
                                $mathLevels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'];
                                $currentLevelIndex = array_search($assessment->level, $mathLevels);
                                $mentorLevelIndex = array_search($assessment->mentor_assessed_level, $mathLevels);
                            @endphp
                            @foreach($mathLevels as $index => $level)
                            <div class="text-center p-2 rounded-lg border-2 transition-all relative
                                {{ $assessment->level === $level && $assessment->mentor_assessed_level === $level ? 'bg-yellow-100 border-yellow-500 shadow-md' : '' }}
                                {{ $assessment->level === $level && $assessment->mentor_assessed_level !== $level ? 'bg-blue-100 border-blue-500 shadow-md' : '' }}
                                {{ $assessment->mentor_assessed_level === $level && $assessment->level !== $level ? 'bg-green-100 border-green-500 shadow-md' : '' }}
                                {{ $assessment->level !== $level && $assessment->mentor_assessed_level !== $level ? 'bg-gray-50 border-gray-200' : '' }}">
                                
                                <!-- Level indicators -->
                                <div class="absolute -top-2 -right-2 flex gap-1">
                                    @if($assessment->level === $level)
                                    <div class="w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center" title="{{ __('assessment.teacher_assessment') }}">
                                        <span class="text-white text-xs font-bold">T</span>
                                    </div>
                                    @endif
                                    @if($assessment->mentor_assessed_level === $level)
                                    <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center" title="{{ __('assessment.mentor_assessment') }}">
                                        <span class="text-white text-xs font-bold">M</span>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="text-xs font-semibold {{ ($assessment->level === $level || $assessment->mentor_assessed_level === $level) ? 'text-gray-700' : 'text-gray-600' }}">
                                    {{ __('assessment.level') }} {{ $index + 1 }}
                                </div>
                                <div class="text-sm {{ ($assessment->level === $level || $assessment->mentor_assessed_level === $level) ? 'font-bold text-gray-900' : 'text-gray-700' }}">
                                    {{ trans_db($level) }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Math Level Descriptions -->
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 mb-2">{{ trans_db('level_descriptions') }}:</p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li><span class="font-semibold">{{ trans_db('beginner') }}:</span> {{ trans_db('cannot_recognize_numbers') }}</li>
                                <li><span class="font-semibold">{{ trans_db('1_digit') }}:</span> {{ trans_db('can_do_single_digit_operations') }}</li>
                                <li><span class="font-semibold">{{ trans_db('2_digit') }}:</span> {{ trans_db('can_do_two_digit_operations') }}</li>
                                <li><span class="font-semibold">{{ trans_db('subtraction') }}:</span> {{ trans_db('can_do_subtraction_problems') }}</li>
                                <li><span class="font-semibold">{{ trans_db('division') }}:</span> {{ trans_db('can_do_division_problems') }}</li>
                                <li><span class="font-semibold">{{ trans_db('word_problem') }}:</span> {{ trans_db('can_solve_word_problems') }}</li>
                            </ul>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Additional Assessment Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">{{ trans_db('assessment_details') }}</p>
                            <dl class="space-y-1">
                                <div class="flex justify-between text-sm">
                                    <dt class="text-gray-500">{{ trans_db('assessor') }}:</dt>
                                    <dd class="text-gray-900 font-medium">{{ $assessment->assessor ? $assessment->assessor->name : trans_db('none') }}</dd>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <dt class="text-gray-500">{{ trans_db('method') }}:</dt>
                                    <dd class="text-gray-900">
                                        @if($assessment->assessment_method == 'oral')
                                            {{ trans_db('oral') }}
                                        @elseif($assessment->assessment_method == 'written')
                                            {{ trans_db('written') }}
                                        @else
                                            {{ trans_db('standard') }}
                                        @endif
                                    </dd>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <dt class="text-gray-500">{{ trans_db('academic_year') }}:</dt>
                                    <dd class="text-gray-900">{{ $assessment->academic_year ?? '2024-2025' }}</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">{{ trans_db('competency_metrics') }}</p>
                            <dl class="space-y-1">
                                @if($assessment->total_questions)
                                <div class="flex justify-between text-sm">
                                    <dt class="text-gray-500">{{ trans_db('total_questions') }}:</dt>
                                    <dd class="text-gray-900">{{ $assessment->total_questions }}</dd>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <dt class="text-gray-500">{{ trans_db('correct_answers') }}:</dt>
                                    <dd class="text-gray-900">{{ $assessment->correct_answers ?? trans_db('none') }}</dd>
                                </div>
                                @endif
                                <div class="flex justify-between text-sm">
                                    <dt class="text-gray-500">{{ trans_db('percentage_score') }}:</dt>
                                    <dd class="text-gray-900 font-semibold">{{ $assessment->percentage_score ?? $assessment->score }}%</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    
                    <!-- Assessor Comments -->
                    @if($assessment->assessor_comments)
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">{{ trans_db('assessor_comments') }}</p>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-sm text-gray-700">{{ $assessment->assessor_comments }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Student Information Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-4 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ trans_db('student_information') }}</h3>
                    
                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                        <!-- Student Photo -->
                        <div class="flex-shrink-0">
                            @if($assessment->student->photo)
                                <div class="h-20 w-20 sm:h-24 sm:w-24 rounded-lg overflow-hidden shadow-md">
                                    <img src="{{ Storage::url($assessment->student->photo) }}" 
                                         alt="{{ $assessment->student->name }}" 
                                         class="h-full w-full object-cover">
                                </div>
                            @else
                                <div class="h-20 w-20 sm:h-24 sm:w-24 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <svg class="h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Student Details -->
                        <div class="flex-grow">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider">{{ trans_db('name') }}</p>
                                    <p class="mt-1 text-sm font-medium text-gray-900">
                                        <a href="{{ route('students.show', $assessment->student) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ $assessment->student->name }}
                                        </a>
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider">{{ trans_db('grade') }}</p>
                                    <p class="mt-1 text-sm font-medium text-gray-900">
                                        {{ trans_db('grade') }} {{ $assessment->student->grade ?? trans_db('none') }}
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider">{{ trans_db('gender') }}</p>
                                    <p class="mt-1 text-sm font-medium text-gray-900">
                                        @if($assessment->student->gender)
                                            @if(strtoupper($assessment->student->gender) == 'M' || strtolower($assessment->student->gender) == 'male')
                                                {{ trans_db('male') }}
                                            @elseif(strtoupper($assessment->student->gender) == 'F' || strtolower($assessment->student->gender) == 'female')
                                                {{ trans_db('female') }}
                                            @else
                                                {{ $assessment->student->gender }}
                                            @endif
                                        @else
                                            {{ trans_db('none') }}
                                        @endif
                                    </p>
                                </div>
                                
                                <div class="sm:col-span-2 lg:col-span-3">
                                    <p class="text-xs text-gray-500 uppercase tracking-wider">{{ trans_db('school') }}</p>
                                    <p class="mt-1 text-sm font-medium text-gray-900">
                                        {{ $assessment->student->pilotSchool ? $assessment->student->pilotSchool->school_name : trans_db('none') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verification Section (for Mentors) -->
            @if(auth()->user()->role === 'mentor' || auth()->user()->role === 'admin')
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mt-6">
                <div class="p-4 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ trans_db('verification') }}</h3>
                    
                    <!-- Current Verification Status -->
                    <div class="mb-4">
                        <p class="text-sm text-gray-500 mb-2">{{ trans_db('current_status') }}</p>
                        <div class="flex items-center gap-4">
                            @if($assessment->verification_status === 'verified')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="-ml-0.5 mr-1.5 h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    {{ trans_db('verified') }}
                                </span>
                                @if($assessment->verifiedBy)
                                    <span class="text-sm text-gray-500">
                                        {{ trans_db('by') }} {{ $assessment->verifiedBy->name }} {{ trans_db('on') }} {{ $assessment->verified_at->format('M d, Y') }}
                                    </span>
                                @endif
                            @elseif($assessment->verification_status === 'needs_correction')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <svg class="-ml-0.5 mr-1.5 h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    {{ trans_db('needs_correction') }}
                                </span>
                                @if($assessment->verifiedBy)
                                    <span class="text-sm text-gray-500">
                                        {{ trans_db('by') }} {{ $assessment->verifiedBy->name }} {{ trans_db('on') }} {{ $assessment->verified_at->format('M d, Y') }}
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="-ml-0.5 mr-1.5 h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    {{ trans_db('pending_verification') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Verification Notes (if any) -->
                    @if($assessment->verification_notes)
                    <div class="mb-4">
                        <p class="text-sm text-gray-500 mb-2">{{ trans_db('verification_notes') }}</p>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-sm text-gray-700">{{ $assessment->verification_notes }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Mentor's Assessment Section -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ trans_db('mentor_assessment_review') }}</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">{{ trans_db('teacher_assessment') }}:</p>
                                <p class="text-lg font-semibold text-blue-700">{{ trans_db($assessment->level) }}</p>
                            </div>
                            @if($assessment->mentor_assessed_level)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">{{ trans_db('your_previous_assessment') }}:</p>
                                <p class="text-lg font-semibold text-green-700">{{ trans_db($assessment->mentor_assessed_level) }}</p>
                            </div>
                            @endif
                        </div>
                        
                        @if($assessment->mentor_assessed_level && $assessment->mentor_assessed_level !== $assessment->level)
                        <div class="p-3 bg-yellow-100 rounded-md mb-3">
                            <p class="text-sm text-yellow-800">
                                <strong>{{ trans_db('discrepancy_found') }}:</strong> 
                                {{ trans_db('teacher_assessed_as') }} <span class="font-semibold">{{ trans_db($assessment->level) }}</span>, 
                                {{ trans_db('you_assessed_as') }} <span class="font-semibold">{{ trans_db($assessment->mentor_assessed_level) }}</span>
                            </p>
                            @if($assessment->level_discrepancy_notes)
                            <p class="text-sm text-yellow-700 mt-1">{{ $assessment->level_discrepancy_notes }}</p>
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- Verification Actions -->
                    <form action="{{ route('verification.update', $assessment) }}" method="POST" class="space-y-4" id="verification-form">
                        @csrf
                        @method('PATCH')
                        
                        <!-- Mentor's Assessment Level Selection -->
                        <div>
                            <label for="mentor_assessed_level" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ trans_db('select_your_assessment_level') }}
                            </label>
                            <div class="space-y-2">
                                @php
                                    $levels = $assessment->subject === 'khmer' 
                                        ? ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2']
                                        : ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'];
                                @endphp
                                @foreach($levels as $index => $level)
                                <div class="flex items-center p-3 border rounded-lg hover:bg-gray-50 {{ $assessment->mentor_assessed_level === $level ? 'bg-green-50 border-green-500' : 'border-gray-300' }}">
                                    <input type="radio" 
                                        id="mentor_level_{{ $index }}" 
                                        name="mentor_assessed_level" 
                                        value="{{ $level }}" 
                                        {{ $assessment->mentor_assessed_level === $level ? 'checked' : '' }}
                                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                    <label for="mentor_level_{{ $index }}" 
                                        class="ml-3 flex-1 cursor-pointer">
                                        <span class="text-sm font-medium {{ $assessment->mentor_assessed_level === $level ? 'text-green-700' : 'text-gray-700' }}">
                                            {{ trans_db($level) }}
                                        </span>
                                        @if($assessment->level === $level)
                                        <span class="ml-2 text-xs text-blue-600 font-semibold">({{ trans_db('teacher') }})</span>
                                        @endif
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Discrepancy Notes (shown when there's a difference) -->
                        <div id="discrepancy-notes" style="display: none;">
                            <label for="level_discrepancy_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ trans_db('explain_discrepancy') }}
                            </label>
                            <textarea name="level_discrepancy_notes" id="level_discrepancy_notes" rows="2" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="{{ trans_db('please_explain_assessment_difference') }}">{{ old('level_discrepancy_notes', $assessment->level_discrepancy_notes) }}</textarea>
                        </div>
                        
                        <div>
                            <label for="verification_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ trans_db('verification_notes') }}
                            </label>
                            <textarea name="verification_notes" id="verification_notes" rows="3" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="{{ trans_db('add_verification_notes') }}">{{ old('verification_notes', $assessment->verification_notes) }}</textarea>
                        </div>
                        
                        <div class="flex flex-wrap gap-2">
                            <button type="submit" name="verification_status" value="verified" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ trans_db('mark_as_verified') }}
                            </button>
                            
                            <button type="submit" name="verification_status" value="needs_correction" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ trans_db('needs_correction') }}
                            </button>
                            
                            <button type="submit" name="verification_status" value="pending" 
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                {{ trans_db('reset_to_pending') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
            
        </div>
    </div>
    
    
    @push('scripts')
    <script>
        
        // Form submission handling with loading state
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('verification-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Get all submit buttons in the form
                    const submitButtons = form.querySelectorAll('button[type="submit"]');
                    
                    // Disable all submit buttons and show loading state
                    submitButtons.forEach(button => {
                        button.disabled = true;
                        const originalContent = button.innerHTML;
                        button.setAttribute('data-original-content', originalContent);
                        
                        // Add loading spinner
                        button.innerHTML = `
                            <svg class="animate-spin h-4 w-4 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ trans_db('processing') }}...
                        `;
                    });
                });
            }
        });
        
        // Show/hide discrepancy notes based on selection
        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('input[name="mentor_assessed_level"]');
            const discrepancyDiv = document.getElementById('discrepancy-notes');
            const teacherLevel = '{{ $assessment->level }}';
            
            function checkDiscrepancy() {
                const selectedLevel = document.querySelector('input[name="mentor_assessed_level"]:checked');
                if (selectedLevel && selectedLevel.value !== teacherLevel) {
                    discrepancyDiv.style.display = 'block';
                } else {
                    discrepancyDiv.style.display = 'none';
                }
            }
            
            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    checkDiscrepancy();
                    console.log('Radio button selected:', this.value);
                });
            });
            
            // Check on load
            checkDiscrepancy();
            
            // Debug: Log radio buttons found
            console.log('Found radio buttons:', radioButtons.length);
        });
    </script>
    @endpush
</x-app-layout>