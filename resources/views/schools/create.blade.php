<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create New School') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('schools.store') }}" class="space-y-6">
                        @csrf
                        
                        <!-- School Name -->
                        <div>
                            <x-input-label for="school_name" :value="__('School Name')" />
                            <x-text-input id="school_name" name="school_name" type="text" class="mt-1 block w-full" :value="old('school_name')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('school_name')" />
                        </div>

                        <!-- School Code -->
                        <div>
                            <x-input-label for="school_code" :value="__('School Code')" />
                            <x-text-input id="school_code" name="school_code" type="text" class="mt-1 block w-full" :value="old('school_code')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('school_code')" />
                            <p class="mt-1 text-sm text-gray-500">{{ __('Unique identifier for the school') }}</p>
                        </div>

                        <!-- Province -->
                        <div>
                            <x-input-label for="province" :value="__('Province')" />
                            <select id="province" name="province" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">{{ __('Select Province') }}</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->province_name_en }}" {{ old('province') == $province->province_name_en ? 'selected' : '' }}>
                                        {{ $province->province_name_en }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('province')" />
                        </div>

                        <!-- District -->
                        <div>
                            <x-input-label for="district" :value="__('District')" />
                            <x-text-input id="district" name="district" type="text" class="mt-1 block w-full" :value="old('district')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('district')" />
                        </div>

                        <!-- Cluster -->
                        <div>
                            <x-input-label for="cluster" :value="__('Cluster (Optional)')" />
                            <x-text-input id="cluster" name="cluster" type="text" class="mt-1 block w-full" :value="old('cluster')" />
                            <x-input-error class="mt-2" :messages="$errors->get('cluster')" />
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Create School') }}</x-primary-button>
                            <a href="{{ route('schools.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>