@props(['student', 'subject'])

<div class="mobile-assessment-card bg-white rounded-lg border border-gray-200 shadow-sm mb-4 p-4">
    <!-- Student Header -->
    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
        <div class="flex items-center space-x-3">
            @if($student->photo && file_exists(public_path('storage/' . $student->photo)))
                <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}" 
                     class="w-10 h-10 rounded-full object-cover border">
            @else
                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            @endif
            <div>
                <h4 class="font-semibold text-gray-900 text-sm">{{ $student->name }}</h4>
                <p class="text-xs text-gray-500">{{ $student->pilotSchool->name ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            @if($student->has_assessment)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path>
                    </svg>
                    {{ trans_db('assessment.Assessed') }}
                </span>
            @endif
            @if($student->is_assessment_locked)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"></path>
                    </svg>
                    {{ trans_db('assessment.Locked') }}
                </span>
            @endif
        </div>
    </div>

    <!-- Previous Assessment Info -->
    @if($student->previous_assessment)
    <div class="mb-4 p-3 bg-blue-50 rounded-lg">
        <div class="flex justify-between items-center">
            <span class="text-xs font-medium text-blue-700">{{ trans_db('assessment.Latest Level') }}</span>
            <div class="text-right">
                <div class="text-sm font-semibold text-blue-900">{{ $student->previous_assessment->level ?? 'N/A' }}</div>
                <div class="text-xs text-blue-600">{{ ucfirst($student->previous_assessment->cycle ?? 'N/A') }}</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Assessment Level Selection -->
    <div class="space-y-3">
        <h5 class="text-sm font-medium text-gray-700 mb-2">{{ trans_db('assessment.Student Level') }}</h5>
        
        @if($subject === 'khmer')
            <div class="grid grid-cols-2 gap-2">
                @foreach(['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2'] as $level)
                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors {{ $student->has_assessment && $student->assessment_level == $level ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                    <input type="radio" 
                           name="level_{{ $student->id }}" 
                           value="{{ $level }}" 
                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                           {{ $student->is_assessment_locked ? 'disabled' : '' }}
                           {{ $student->has_assessment && $student->assessment_level == $level ? 'checked' : '' }}>
                    <span class="ml-2 text-xs font-medium {{ $student->is_assessment_locked ? 'text-gray-400' : 'text-gray-700' }}">
                        {{ trans_db('assessment.' . $level) }}
                    </span>
                </label>
                @endforeach
            </div>
        @else
            <div class="grid grid-cols-2 gap-2">
                @foreach(['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'] as $level)
                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors {{ $student->has_assessment && $student->assessment_level == $level ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                    <input type="radio" 
                           name="level_{{ $student->id }}" 
                           value="{{ $level }}" 
                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                           {{ $student->is_assessment_locked ? 'disabled' : '' }}
                           {{ $student->has_assessment && $student->assessment_level == $level ? 'checked' : '' }}>
                    <span class="ml-2 text-xs font-medium {{ $student->is_assessment_locked ? 'text-gray-400' : 'text-gray-700' }}">
                        {{ trans_db('assessment.' . $level) }}
                    </span>
                </label>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Action Buttons -->
    @if(!$student->is_assessment_locked)
    <div class="mt-4 pt-3 border-t border-gray-100 flex space-x-3">
        <button type="button" 
                class="save-student-btn flex-1 inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 touch-manipulation"
                data-student-id="{{ $student->id }}">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ trans_db('Save') }}
        </button>
    </div>
    @endif
</div>