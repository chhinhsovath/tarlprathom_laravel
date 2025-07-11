<x-app-layout>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Mentoring Visit Details') }}</h3>
                        <a href="{{ route('mentoring.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            ← {{ __('Back to list') }}
                        </a>
                    </div>

                    <!-- Visit Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">{{ __('Visit Date') }}</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $mentoringVisit->visit_date->format('Y-m-d') }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">{{ __('Score') }}</h4>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    @if($mentoringVisit->score >= 80) bg-green-100 text-green-800
                                    @elseif($mentoringVisit->score >= 60) bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $mentoringVisit->score }}%
                                </span>
                            </p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">{{ __('School') }}</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $mentoringVisit->school->school_name }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">{{ __('Teacher') }}</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $mentoringVisit->teacher->name }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">{{ __('Mentor') }}</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $mentoringVisit->mentor->name }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-500">{{ __('Observation Notes') }}</h4>
                        <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $mentoringVisit->observation }}</p>
                    </div>
                    
                    @if($mentoringVisit->photo)
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">{{ __('Photo') }}</h4>
                        <img src="{{ Storage::url($mentoringVisit->photo) }}" 
                             alt="Visit photo" 
                             class="max-w-full h-auto rounded-lg shadow-md"
                             style="max-height: 400px;">
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>