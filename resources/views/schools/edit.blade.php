<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit School') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- School Details Section -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('School Information') }}</h3>
                    <form method="POST" action="{{ route('schools.update', $school) }}" id="schoolForm" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- School Name -->
                        <div>
                            <x-input-label for="name" :value="__('School Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $school->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- School Code -->
                        <div>
                            <x-input-label for="school_code" :value="__('School Code')" />
                            <x-text-input id="school_code" name="school_code" type="text" class="mt-1 block w-full" :value="old('school_code', $school->school_code)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('school_code')" />
                            <p class="mt-1 text-sm text-gray-500">{{ __('Unique identifier for the school') }}</p>
                        </div>

                        <!-- Province -->
                        <div>
                            <x-input-label for="province" :value="__('Province')" />
                            <select id="province" name="province" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">{{ __('Select Province') }}</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->province_name_en }}" {{ old('province', $school->province) == $province->province_name_en ? 'selected' : '' }}>
                                        {{ $province->province_name_en }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('province')" />
                        </div>

                        <!-- District -->
                        <div>
                            <x-input-label for="district" :value="__('District')" />
                            <x-text-input id="district" name="district" type="text" class="mt-1 block w-full" :value="old('district', $school->district)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('district')" />
                        </div>

                        <!-- Cluster -->
                        <div>
                            <x-input-label for="cluster" :value="__('Cluster (Optional)')" />
                            <x-text-input id="cluster" name="cluster" type="text" class="mt-1 block w-full" :value="old('cluster', $school->cluster)" />
                            <x-input-error class="mt-2" :messages="$errors->get('cluster')" />
                        </div>
                        
                        <!-- Assessment Dates Section -->
                        <div class="border-t pt-6 mt-6">
                            <h4 class="text-md font-semibold text-gray-900 mb-4">{{ __('Assessment Dates') }}</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Baseline Dates -->
                                <div class="space-y-4">
                                    <h5 class="text-sm font-medium text-gray-700">{{ __('Baseline Assessment') }}</h5>
                                    <div>
                                        <x-input-label for="baseline_start_date" :value="__('Start Date')" />
                                        <x-text-input id="baseline_start_date" name="baseline_start_date" type="date" class="mt-1 block w-full" :value="old('baseline_start_date', $school->baseline_start_date?->format('Y-m-d'))" />
                                        <x-input-error class="mt-2" :messages="$errors->get('baseline_start_date')" />
                                    </div>
                                    <div>
                                        <x-input-label for="baseline_end_date" :value="__('End Date')" />
                                        <x-text-input id="baseline_end_date" name="baseline_end_date" type="date" class="mt-1 block w-full" :value="old('baseline_end_date', $school->baseline_end_date?->format('Y-m-d'))" />
                                        <x-input-error class="mt-2" :messages="$errors->get('baseline_end_date')" />
                                    </div>
                                    @if($school->baseline_start_date && $school->baseline_end_date)
                                        <p class="text-sm text-gray-600">
                                            Status: <span class="font-medium {{ $school->getAssessmentPeriodStatus('baseline') === 'active' ? 'text-green-600' : ($school->getAssessmentPeriodStatus('baseline') === 'upcoming' ? 'text-blue-600' : 'text-gray-600') }}">
                                                {{ ucfirst($school->getAssessmentPeriodStatus('baseline')) }}
                                            </span>
                                        </p>
                                    @endif
                                </div>
                                
                                <!-- Midline Dates -->
                                <div class="space-y-4">
                                    <h5 class="text-sm font-medium text-gray-700">{{ __('Midline Assessment') }}</h5>
                                    <div>
                                        <x-input-label for="midline_start_date" :value="__('Start Date')" />
                                        <x-text-input id="midline_start_date" name="midline_start_date" type="date" class="mt-1 block w-full" :value="old('midline_start_date', $school->midline_start_date?->format('Y-m-d'))" />
                                        <x-input-error class="mt-2" :messages="$errors->get('midline_start_date')" />
                                    </div>
                                    <div>
                                        <x-input-label for="midline_end_date" :value="__('End Date')" />
                                        <x-text-input id="midline_end_date" name="midline_end_date" type="date" class="mt-1 block w-full" :value="old('midline_end_date', $school->midline_end_date?->format('Y-m-d'))" />
                                        <x-input-error class="mt-2" :messages="$errors->get('midline_end_date')" />
                                    </div>
                                    @if($school->midline_start_date && $school->midline_end_date)
                                        <p class="text-sm text-gray-600">
                                            Status: <span class="font-medium {{ $school->getAssessmentPeriodStatus('midline') === 'active' ? 'text-green-600' : ($school->getAssessmentPeriodStatus('midline') === 'upcoming' ? 'text-blue-600' : 'text-gray-600') }}">
                                                {{ ucfirst($school->getAssessmentPeriodStatus('midline')) }}
                                            </span>
                                        </p>
                                    @endif
                                </div>
                                
                                <!-- Endline Dates -->
                                <div class="space-y-4">
                                    <h5 class="text-sm font-medium text-gray-700">{{ __('Endline Assessment') }}</h5>
                                    <div>
                                        <x-input-label for="endline_start_date" :value="__('Start Date')" />
                                        <x-text-input id="endline_start_date" name="endline_start_date" type="date" class="mt-1 block w-full" :value="old('endline_start_date', $school->endline_start_date?->format('Y-m-d'))" />
                                        <x-input-error class="mt-2" :messages="$errors->get('endline_start_date')" />
                                    </div>
                                    <div>
                                        <x-input-label for="endline_end_date" :value="__('End Date')" />
                                        <x-text-input id="endline_end_date" name="endline_end_date" type="date" class="mt-1 block w-full" :value="old('endline_end_date', $school->endline_end_date?->format('Y-m-d'))" />
                                        <x-input-error class="mt-2" :messages="$errors->get('endline_end_date')" />
                                    </div>
                                    @if($school->endline_start_date && $school->endline_end_date)
                                        <p class="text-sm text-gray-600">
                                            Status: <span class="font-medium {{ $school->getAssessmentPeriodStatus('endline') === 'active' ? 'text-green-600' : ($school->getAssessmentPeriodStatus('endline') === 'upcoming' ? 'text-blue-600' : 'text-gray-600') }}">
                                                {{ ucfirst($school->getAssessmentPeriodStatus('endline')) }}
                                            </span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex items-center gap-4">
                            <x-primary-button type="submit">{{ __('Update School') }}</x-primary-button>
                            <a href="{{ route('schools.show', $school) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>