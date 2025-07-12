<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-900">{{ __('Mentoring Visit Details') }}</h3>
                        <div class="flex gap-2">
                            @if(auth()->user()->isAdmin() || auth()->user()->id == $mentoringVisit->mentor_id)
                                <a href="{{ route('mentoring.edit', $mentoringVisit) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                    {{ __('Edit') }}
                                </a>
                            @endif
                            <a href="{{ route('mentoring.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                {{ __('Back to List') }}
                            </a>
                        </div>
                    </div>

                    <!-- Visit Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Visit Details Section -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">{{ __('Visit Details') }}</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Visit Date') }}</dt>
                                    <dd class="text-sm text-gray-900">{{ $mentoringVisit->visit_date->format('F d, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Region') }}</dt>
                                    <dd class="text-sm text-gray-900">{{ $mentoringVisit->region ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Province') }}</dt>
                                    <dd class="text-sm text-gray-900">{{ $mentoringVisit->province ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('School') }}</dt>
                                    <dd class="text-sm text-gray-900">{{ $mentoringVisit->school->school_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Mentor') }}</dt>
                                    <dd class="text-sm text-gray-900">{{ $mentoringVisit->mentor->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Teacher') }}</dt>
                                    <dd class="text-sm text-gray-900">{{ $mentoringVisit->teacher->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Program Type') }}</dt>
                                    <dd class="text-sm text-gray-900">{{ $mentoringVisit->program_type ?? 'TaRL' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Class Status Section -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">{{ __('Class Status') }}</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Class in Session') }}</dt>
                                    <dd class="text-sm text-gray-900">
                                        @if($mentoringVisit->class_in_session)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ __('Yes') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ __('No') }}
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                @if(!$mentoringVisit->class_in_session && $mentoringVisit->class_not_in_session_reason)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Reason') }}</dt>
                                    <dd class="text-sm text-gray-900">{{ $mentoringVisit->class_not_in_session_reason }}</dd>
                                </div>
                                @endif
                                @if($mentoringVisit->class_in_session)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Full Session Observed') }}</dt>
                                    <dd class="text-sm text-gray-900">
                                        @if($mentoringVisit->full_session_observed)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ __('Yes') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ __('No') }}
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        @if($mentoringVisit->class_in_session)
                        <!-- Observation Details Section -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">{{ __('Observation Details') }}</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Grade Group') }}</dt>
                                    <dd class="text-sm text-gray-900">{{ $mentoringVisit->grade_group ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Grades Observed') }}</dt>
                                    <dd class="text-sm text-gray-900">
                                        @if($mentoringVisit->grades_observed)
                                            {{ implode(', ', $mentoringVisit->grades_observed) }}
                                        @else
                                            -
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Subject Observed') }}</dt>
                                    <dd class="text-sm text-gray-900">{{ $mentoringVisit->subject_observed ?? '-' }}</dd>
                                </div>
                                @if($mentoringVisit->subject_observed == 'Language' && $mentoringVisit->language_levels_observed)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Language Levels') }}</dt>
                                    <dd class="text-sm text-gray-900">{{ implode(', ', $mentoringVisit->language_levels_observed) }}</dd>
                                </div>
                                @endif
                                @if($mentoringVisit->subject_observed == 'Numeracy' && $mentoringVisit->numeracy_levels_observed)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Numeracy Levels') }}</dt>
                                    <dd class="text-sm text-gray-900">{{ implode(', ', $mentoringVisit->numeracy_levels_observed) }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                        @endif

                        <!-- Additional Information Section -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">{{ __('Additional Information') }}</h4>
                            <dl class="space-y-2">
                                @if($mentoringVisit->score)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Score') }}</dt>
                                    <dd class="text-sm text-gray-900">{{ $mentoringVisit->score }}%</dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Follow-up Required') }}</dt>
                                    <dd class="text-sm text-gray-900">
                                        @if($mentoringVisit->follow_up_required)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ __('Yes') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ __('No') }}
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Created At') }}</dt>
                                    <dd class="text-sm text-gray-900">{{ $mentoringVisit->created_at->format('F d, Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Observations and Action Plan -->
                    @if($mentoringVisit->observation || $mentoringVisit->action_plan)
                    <div class="mt-6 space-y-4">
                        @if($mentoringVisit->observation)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-2">{{ __('Observations') }}</h4>
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $mentoringVisit->observation }}</p>
                        </div>
                        @endif

                        @if($mentoringVisit->action_plan)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-2">{{ __('Action Plan') }}</h4>
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $mentoringVisit->action_plan }}</p>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Photo -->
                    @if($mentoringVisit->photo)
                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 mb-2">{{ __('Visit Photo') }}</h4>
                        <div class="max-w-2xl">
                            <img src="{{ Storage::url($mentoringVisit->photo) }}" 
                                 alt="{{ __('Visit Photo') }}" 
                                 class="rounded-lg shadow-sm w-full">
                        </div>
                    </div>
                    @endif

                    <!-- Dynamic Questionnaire Data -->
                    @if($mentoringVisit->questionnaire_data && count($mentoringVisit->questionnaire_data) > 0)
                    <div class="mt-6 bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">{{ __('Additional Questionnaire Responses') }}</h4>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($mentoringVisit->questionnaire_data as $key => $value)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ str_replace('_', ' ', ucfirst($key)) }}</dt>
                                <dd class="text-sm text-gray-900">
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