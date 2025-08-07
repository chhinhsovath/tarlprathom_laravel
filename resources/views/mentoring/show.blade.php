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
                                    <dd class="text-xs text-gray-900 truncate max-w-[150px]" title="{{ $mentoringVisit->school->name }}">{{ $mentoringVisit->school->name }}</dd>
                                </div>
                                <div class="flex justify-between items-center py-1">
                                    <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Mentor') }}</dt>
                                    <dd class="text-xs text-gray-900">{{ $mentoringVisit->mentor->name }}</dd>
                                </div>
                                <div class="flex justify-between items-center py-1">
                                    <dt class="text-xs font-medium text-gray-500">{{ __('mentoring.Teacher') }}</dt>
                                    <dd class="text-xs text-gray-900">{{ $mentoringVisit->teacher->name }}</dd>
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