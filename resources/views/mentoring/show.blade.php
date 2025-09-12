<x-app-layout>
    <div class="py-4">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 bg-white border-b border-gray-200">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-3">
                            <h3 class="text-xl font-semibold text-gray-900">{{ __('mentoring.Visit Details') }}</h3>
                            @if($mentoringVisit->is_locked ?? false)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    {{ __('mentoring.Locked') }}
                                </span>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            @if((auth()->user()->isAdmin() || auth()->user()->id == $mentoringVisit->mentor_id) && (!($mentoringVisit->is_locked ?? false) || auth()->user()->isAdmin()))
                                <a href="{{ route('mentoring.edit', $mentoringVisit) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                    {{ __('mentoring.Edit') }}
                                </a>
                            @endif
                            @if(auth()->user()->isAdmin() || auth()->user()->id == $mentoringVisit->mentor_id)
                                <form action="{{ route('mentoring.destroy', $mentoringVisit) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('{{ __('mentoring.Are you sure you want to delete this visit?') }}')" 
                                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                        {{ __('mentoring.Delete') }}
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('mentoring.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                {{ __('mentoring.Back to List') }}
                            </a>
                        </div>
                    </div>

                    <!-- Visit Information -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Visit Details Section -->
                        <div class="bg-gray-50 rounded-lg p-3">
                            <h4 class="font-semibold text-gray-900 mb-2 text-sm">{{ __('mentoring.Visit Details') }}</h4>
                            <dl class="space-y-1">
                                <div class="flex justify-between items-center py-1">
                                    <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Visit Date') }}</dt>
                                    <dd class="text-xs text-gray-900 font-medium">{{ $mentoringVisit->visit_date->format('M d, Y') }}</dd>
                                </div>
                                <div class="flex justify-between items-center py-1">
                                    <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Location') }}</dt>
                                    <dd class="text-xs text-gray-900">{{ $mentoringVisit->province ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between items-center py-1">
                                    <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.School') }}</dt>
                                    <dd class="text-xs text-gray-900 truncate max-w-[150px]" title="{{ $mentoringVisit->pilotSchool ? $mentoringVisit->pilotSchool->school_name : ($mentoringVisit->school ? $mentoringVisit->school->name : '-') }}">
                                        {{ $mentoringVisit->pilotSchool ? $mentoringVisit->pilotSchool->school_name : ($mentoringVisit->school ? $mentoringVisit->school->name : '-') }}
                                    </dd>
                                </div>
                                <div class="flex justify-between items-center py-1">
                                    <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Mentor') }}</dt>
                                    <dd class="text-xs text-gray-900">{{ $mentoringVisit->mentor ? $mentoringVisit->mentor->name : '-' }}</dd>
                                </div>
                                <div class="flex justify-between items-center py-1">
                                    <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Teacher') }}</dt>
                                    <dd class="text-xs text-gray-900">{{ $mentoringVisit->teacher ? $mentoringVisit->teacher->name : '-' }}</dd>
                                </div>
                            </dl>
                        </div>


                        <!-- Observation Details Section -->
                        <div class="bg-gray-50 rounded-lg p-3">
                            <h4 class="font-semibold text-gray-900 mb-2 text-sm">{{ __('mentoring.Observation Details') }}</h4>
                            <dl class="space-y-1">
                                <div class="flex justify-between items-center py-1">
                                    <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Grade Group') }}</dt>
                                    <dd class="text-xs text-gray-900">{{ $mentoringVisit->grade_group ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between items-center py-1">
                                    <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Grades') }}</dt>
                                    <dd class="text-xs text-gray-900">
                                        @if($mentoringVisit->grades_observed)
                                            {{ implode(', ', $mentoringVisit->grades_observed) }}
                                        @else
                                            -
                                        @endif
                                    </dd>
                                </div>
                                <div class="flex justify-between items-center py-1">
                                    <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Subject') }}</dt>
                                    <dd class="text-xs text-gray-900">{{ $mentoringVisit->subject_observed ?? '-' }}</dd>
                                </div>
                                @if($mentoringVisit->subject_observed == 'Language' && $mentoringVisit->language_levels_observed)
                                <div class="flex justify-between items-center py-1">
                                    <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Levels') }}</dt>
                                    <dd class="text-xs text-gray-900">{{ implode(', ', $mentoringVisit->language_levels_observed) }}</dd>
                                </div>
                                @endif
                                @if($mentoringVisit->subject_observed == 'Numeracy' && $mentoringVisit->numeracy_levels_observed)
                                <div class="flex justify-between items-center py-1">
                                    <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Levels') }}</dt>
                                    <dd class="text-xs text-gray-900">{{ implode(', ', $mentoringVisit->numeracy_levels_observed) }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                        <!-- Additional Information Section -->
                        <div class="bg-gray-50 rounded-lg p-3">
                            <h4 class="font-semibold text-gray-900 mb-2 text-sm">{{ __('mentoring.Additional Information') }}</h4>
                            <dl class="space-y-1">
                                @if($mentoringVisit->score)
                                <div class="flex justify-between items-center py-1">
                                    <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Score') }}</dt>
                                    <dd class="text-xs text-gray-900 font-semibold">{{ $mentoringVisit->score }}%</dd>
                                </div>
                                @endif
                                <div class="flex justify-between items-center py-1">
                                    <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Follow-up') }}</dt>
                                    <dd class="text-xs">
                                        @if($mentoringVisit->follow_up_required)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ __('mentoring.Required') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ __('mentoring.Not Required') }}
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="flex justify-between items-center py-1">
                                    <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Created') }}</dt>
                                    <dd class="text-xs text-gray-900">{{ $mentoringVisit->created_at->format('M d, Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Student Statistics Section -->
                    @if($mentoringVisit->total_students_enrolled || $mentoringVisit->students_present || $mentoringVisit->students_improved_from_last_week)
                    <div class="mt-4 bg-gray-50 rounded-lg p-3">
                        <h4 class="font-semibold text-gray-900 mb-2 text-sm">{{ __('mentoring.Student Statistics') }}</h4>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Total Enrolled') }}</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $mentoringVisit->total_students_enrolled ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Present') }}</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $mentoringVisit->students_present ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Improved') }}</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $mentoringVisit->students_improved_from_last_week ?? '-' }}</dd>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Teaching & Planning Section -->
                    @if($mentoringVisit->teacher_has_lesson_plan !== null || $mentoringVisit->teaching_materials)
                    <div class="mt-4 bg-gray-50 rounded-lg p-3">
                        <h4 class="font-semibold text-gray-900 mb-2 text-sm">{{ __('mentoring.Teaching & Planning') }}</h4>
                        <dl class="space-y-2">
                            <div class="flex justify-between items-center py-1">
                                <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Has Lesson Plan') }}</dt>
                                <dd class="text-xs">
                                    @if($mentoringVisit->teacher_has_lesson_plan)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ __('mentoring.Yes') }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ __('mentoring.No') }}</span>
                                    @endif
                                </dd>
                            </div>
                            @if($mentoringVisit->teaching_materials)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 mb-1">{{ __('mentoring.Teaching Materials') }}</dt>
                                <dd class="text-xs text-gray-900">
                                    @php
                                        $materials = is_string($mentoringVisit->teaching_materials) ? json_decode($mentoringVisit->teaching_materials, true) : $mentoringVisit->teaching_materials;
                                    @endphp
                                    @if($materials && count($materials) > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($materials as $material)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">{{ $material }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        -
                                    @endif
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                    @endif

                    <!-- Class Session Status -->
                    <div class="mt-4 bg-gray-50 rounded-lg p-3">
                        <h4 class="font-semibold text-gray-900 mb-2 text-sm">{{ __('mentoring.Class Session Status') }}</h4>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Class in Session') }}</dt>
                                <dd class="text-xs">
                                    @if($mentoringVisit->class_in_session)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ __('mentoring.Yes') }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ __('mentoring.No') }}</span>
                                        @if($mentoringVisit->class_not_in_session_reason)
                                            <p class="text-xs text-gray-600 mt-1">{{ $mentoringVisit->class_not_in_session_reason }}</p>
                                        @endif
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Full Session Observed') }}</dt>
                                <dd class="text-xs">
                                    @if($mentoringVisit->full_session_observed)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ __('mentoring.Yes') }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ __('mentoring.No') }}</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Classes Conducted Before Visit') }}</dt>
                                <dd class="text-xs text-gray-900">{{ $mentoringVisit->classes_conducted_before ?? $mentoringVisit->classes_conducted_before_visit ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Class Started on Time') }}</dt>
                                <dd class="text-xs">
                                    @if($mentoringVisit->class_started_on_time == 1 || $mentoringVisit->class_started_on_time == 'បាទ/ចាស')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ __('mentoring.Yes') }}</span>
                                    @elseif($mentoringVisit->class_started_on_time == 0 || $mentoringVisit->class_started_on_time == 'ទេ')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ __('mentoring.No') }}</span>
                                        @if($mentoringVisit->late_start_reason)
                                            <p class="text-xs text-gray-600 mt-1">{{ $mentoringVisit->late_start_reason }}</p>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ __('mentoring.Unknown') }}</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Classroom Organization -->
                    <div class="mt-4 bg-gray-50 rounded-lg p-3">
                        <h4 class="font-semibold text-gray-900 mb-2 text-sm">{{ __('mentoring.Classroom Organization') }}</h4>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Students Grouped by Level') }}</dt>
                                <dd class="text-xs">
                                    @if($mentoringVisit->students_grouped_by_level || $mentoringVisit->children_grouped_appropriately)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ __('mentoring.Yes') }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ __('mentoring.No') }}</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Students Active Participation') }}</dt>
                                <dd class="text-xs">
                                    @if($mentoringVisit->students_active_participation || $mentoringVisit->students_fully_involved)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ __('mentoring.Yes') }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ __('mentoring.No') }}</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                        @if($mentoringVisit->materials_present)
                        <div class="mt-3">
                            <dt class="text-xs font-medium text-gray-500 mb-1">{{ __('mentoring.Materials Present in Classroom') }}</dt>
                            <dd class="text-xs text-gray-900">
                                @php
                                    $materialsPresent = is_string($mentoringVisit->materials_present) ? json_decode($mentoringVisit->materials_present, true) : $mentoringVisit->materials_present;
                                @endphp
                                @if($materialsPresent && count($materialsPresent) > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($materialsPresent as $material)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">{{ $material }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </dd>
                        </div>
                        @endif
                    </div>

                    <!-- Teacher Planning Details -->
                    @if($mentoringVisit->has_session_plan !== null || $mentoringVisit->followed_session_plan !== null || $mentoringVisit->session_plan_appropriate !== null)
                    <div class="mt-4 bg-gray-50 rounded-lg p-3">
                        <h4 class="font-semibold text-gray-900 mb-2 text-sm">{{ __('mentoring.Teacher Session Planning') }}</h4>
                        <dl class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Has Session Plan') }}</dt>
                                <dd class="text-xs">
                                    @if($mentoringVisit->has_session_plan)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ __('mentoring.Yes') }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ __('mentoring.No') }}</span>
                                    @endif
                                </dd>
                            </div>
                            @if($mentoringVisit->has_session_plan)
                            <div>
                                <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Followed Session Plan') }}</dt>
                                <dd class="text-xs">
                                    @if($mentoringVisit->followed_session_plan || $mentoringVisit->followed_lesson_plan)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ __('mentoring.Yes') }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ __('mentoring.No') }}</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Plan Appropriate for Levels') }}</dt>
                                <dd class="text-xs">
                                    @if($mentoringVisit->session_plan_appropriate || $mentoringVisit->plan_appropriate_for_levels)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ __('mentoring.Yes') }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ __('mentoring.No') }}</span>
                                    @endif
                                </dd>
                            </div>
                            @endif
                        </dl>
                        @if($mentoringVisit->no_session_plan_reason || $mentoringVisit->no_lesson_plan_reason)
                        <div class="mt-2">
                            <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Reason for No Plan') }}</dt>
                            <dd class="text-xs text-gray-700 mt-1">{{ $mentoringVisit->no_session_plan_reason ?? $mentoringVisit->no_lesson_plan_reason }}</dd>
                        </div>
                        @endif
                        @if($mentoringVisit->session_plan_notes || $mentoringVisit->lesson_plan_feedback)
                        <div class="mt-2">
                            <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Session Plan Feedback') }}</dt>
                            <dd class="text-xs text-gray-700 mt-1">{{ $mentoringVisit->session_plan_notes ?? $mentoringVisit->lesson_plan_feedback }}</dd>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Activities Section -->
                    @if($mentoringVisit->activity1_type || $mentoringVisit->activity2_type || $mentoringVisit->num_activities_observed || $mentoringVisit->number_of_activities)
                    <div class="mt-4 bg-gray-50 rounded-lg p-3">
                        <h4 class="font-semibold text-gray-900 mb-2 text-sm">{{ __('mentoring.Activities Observed') }} 
                            @if($mentoringVisit->num_activities_observed || $mentoringVisit->number_of_activities)
                                <span class="text-xs font-normal text-gray-600">({{ $mentoringVisit->num_activities_observed ?? $mentoringVisit->number_of_activities }} {{ __('mentoring.activities') }})</span>
                            @endif
                        </h4>
                        <div class="space-y-3">
                            @if($mentoringVisit->activity1_type || $mentoringVisit->activity1_name_language || $mentoringVisit->activity1_name_numeracy)
                            <div class="border-l-4 border-indigo-400 pl-3">
                                <h5 class="text-xs font-semibold text-gray-700">Activity 1</h5>
                                <p class="text-xs text-gray-600">{{ $mentoringVisit->activity1_type ?? $mentoringVisit->activity1_name_language ?? $mentoringVisit->activity1_name_numeracy ?? '-' }}</p>
                                @if($mentoringVisit->activity1_duration)
                                    <p class="text-xs text-gray-500">{{ __('mentoring.Duration') }}: {{ $mentoringVisit->activity1_duration }} {{ __('mentoring.minutes') }}</p>
                                @endif
                                @if($mentoringVisit->activity1_clear_instructions !== null)
                                    <p class="text-xs text-gray-500">{{ __('mentoring.Clear Instructions') }}: {{ $mentoringVisit->activity1_clear_instructions ? __('mentoring.Yes') : __('mentoring.No') }}</p>
                                @endif
                                @if($mentoringVisit->activity1_followed_process !== null)
                                    <p class="text-xs text-gray-500">{{ __('mentoring.Followed Process') }}: {{ $mentoringVisit->activity1_followed_process ? __('mentoring.Yes') : __('mentoring.No') }}</p>
                                @endif
                                @if($mentoringVisit->activity1_unclear_reason || $mentoringVisit->activity1_no_clear_instructions_reason)
                                    <p class="text-xs text-gray-500">{{ __('mentoring.Notes') }}: {{ $mentoringVisit->activity1_unclear_reason ?? $mentoringVisit->activity1_no_clear_instructions_reason }}</p>
                                @endif
                            </div>
                            @endif
                            @if($mentoringVisit->activity2_type || $mentoringVisit->activity2_name_language || $mentoringVisit->activity2_name_numeracy)
                            <div class="border-l-4 border-indigo-400 pl-3">
                                <h5 class="text-xs font-semibold text-gray-700">Activity 2</h5>
                                <p class="text-xs text-gray-600">{{ $mentoringVisit->activity2_type ?? $mentoringVisit->activity2_name_language ?? $mentoringVisit->activity2_name_numeracy ?? '-' }}</p>
                                @if($mentoringVisit->activity2_duration)
                                    <p class="text-xs text-gray-500">{{ __('mentoring.Duration') }}: {{ $mentoringVisit->activity2_duration }} {{ __('mentoring.minutes') }}</p>
                                @endif
                                @if($mentoringVisit->activity2_clear_instructions !== null)
                                    <p class="text-xs text-gray-500">{{ __('mentoring.Clear Instructions') }}: {{ $mentoringVisit->activity2_clear_instructions ? __('mentoring.Yes') : __('mentoring.No') }}</p>
                                @endif
                                @if($mentoringVisit->activity2_followed_process !== null)
                                    <p class="text-xs text-gray-500">{{ __('mentoring.Followed Process') }}: {{ $mentoringVisit->activity2_followed_process ? __('mentoring.Yes') : __('mentoring.No') }}</p>
                                @endif
                                @if($mentoringVisit->activity2_unclear_reason || $mentoringVisit->activity2_no_clear_instructions_reason)
                                    <p class="text-xs text-gray-500">{{ __('mentoring.Notes') }}: {{ $mentoringVisit->activity2_unclear_reason ?? $mentoringVisit->activity2_no_clear_instructions_reason }}</p>
                                @endif
                            </div>
                            @endif
                            @if($mentoringVisit->activity3_name_language || $mentoringVisit->activity3_name_numeracy)
                            <div class="border-l-4 border-indigo-400 pl-3">
                                <h5 class="text-xs font-semibold text-gray-700">Activity 3</h5>
                                <p class="text-xs text-gray-600">{{ $mentoringVisit->activity3_name_language ?? $mentoringVisit->activity3_name_numeracy ?? '-' }}</p>
                                @if($mentoringVisit->activity3_duration)
                                    <p class="text-xs text-gray-500">{{ __('mentoring.Duration') }}: {{ $mentoringVisit->activity3_duration }} {{ __('mentoring.minutes') }}</p>
                                @endif
                                @if($mentoringVisit->activity3_clear_instructions !== null)
                                    <p class="text-xs text-gray-500">{{ __('mentoring.Clear Instructions') }}: {{ $mentoringVisit->activity3_clear_instructions ? __('mentoring.Yes') : __('mentoring.No') }}</p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Teacher Feedback -->
                    @if($mentoringVisit->teacher_feedback || $mentoringVisit->feedback_for_teacher)
                    <div class="mt-4 bg-gray-50 rounded-lg p-3">
                        <h4 class="font-semibold text-gray-900 mb-2 text-sm">{{ __('mentoring.Teacher Feedback') }}</h4>
                        <p class="text-xs text-gray-700 whitespace-pre-wrap">{{ $mentoringVisit->teacher_feedback ?? $mentoringVisit->feedback_for_teacher }}</p>
                    </div>
                    @endif

                    <!-- Observations and Action Plan -->
                    @if($mentoringVisit->observation || $mentoringVisit->action_plan)
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($mentoringVisit->observation)
                        <div class="bg-gray-50 rounded-lg p-3">
                            <h4 class="font-semibold text-gray-900 mb-2 text-sm">{{ __('mentoring.Observations') }}</h4>
                            <p class="text-xs text-gray-700 whitespace-pre-wrap">{{ $mentoringVisit->observation }}</p>
                        </div>
                        @endif

                        @if($mentoringVisit->action_plan)
                        <div class="bg-gray-50 rounded-lg p-3">
                            <h4 class="font-semibold text-gray-900 mb-2 text-sm">{{ __('mentoring.Action Plan') }}</h4>
                            <p class="text-xs text-gray-700 whitespace-pre-wrap">{{ $mentoringVisit->action_plan }}</p>
                        </div>
                        @endif
                    </div>
                    @endif


                    <!-- Dynamic Questionnaire Data -->
                    @if($mentoringVisit->questionnaire_data && count($mentoringVisit->questionnaire_data) > 0)
                    <div class="mt-4 bg-gray-50 rounded-lg p-3">
                        <h4 class="font-semibold text-gray-900 mb-2 text-sm">{{ __('mentoring.Additional Questionnaire Responses') }}</h4>
                        <dl class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            @foreach($mentoringVisit->questionnaire_data as $key => $value)
                            <div class="border-l-2 border-gray-200 pl-2">
                                <dt class="text-xs font-medium text-gray-500">{{ str_replace('_', ' ', ucfirst($key)) }}</dt>
                                <dd class="text-xs text-gray-900 mt-0.5">
                                    @if(is_array($value))
                                        {{ implode(', ', $value) }}
                                    @else
                                        {{ $value }}
                                    @endif
                                </dd>
                            </div>
                            @endforeach
                        </dl>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>