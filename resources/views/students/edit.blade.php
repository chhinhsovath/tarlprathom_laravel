<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Student') }}
            </h2>
            <a href="{{ route('students.show', $student) }}" class="text-sm text-gray-600 hover:text-gray-900">
                {{ __('View Student') }} →
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <form method="POST" action="{{ route('students.update', $student) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <!-- Two Column Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="name" :value="__('Student Name')" class="text-sm font-medium" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full text-sm" :value="old('name', $student->name)" required autofocus autocomplete="name" />
                                <x-input-error class="mt-1" :messages="$errors->get('name')" />
                            </div>

                            <!-- Grade -->
                            <div>
                                <x-input-label for="class" :value="__('Grade')" class="text-sm font-medium" />
                                <select id="class" name="class" class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">{{ __('Select Grade') }}</option>
                                    <option value="Grade 4" {{ old('class', $student->class) == 'Grade 4' ? 'selected' : '' }}>
                                        {{ __('Grade') }} 4
                                    </option>
                                    <option value="Grade 5" {{ old('class', $student->class) == 'Grade 5' ? 'selected' : '' }}>
                                        {{ __('Grade') }} 5
                                    </option>
                                </select>
                                <x-input-error class="mt-1" :messages="$errors->get('class')" />
                            </div>

                            <!-- Gender -->
                            <div>
                                <x-input-label for="gender" :value="__('Gender')" class="text-sm font-medium" />
                                <select id="gender" name="gender" class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">{{ __('Select Gender') }}</option>
                                    <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                    <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                                </select>
                                <x-input-error class="mt-1" :messages="$errors->get('gender')" />
                            </div>

                            <!-- Age -->
                            <div>
                                <x-input-label for="age" :value="__('Age')" class="text-sm font-medium" />
                                <x-text-input id="age" name="age" type="number" min="3" max="18" class="mt-1 block w-full text-sm" :value="old('age', $student->age)" required />
                                <x-input-error class="mt-1" :messages="$errors->get('age')" />
                            </div>

                            <!-- School -->
                            @if($schools->count() > 1)
                                <div>
                                    <x-input-label for="school_id" :value="__('School')" class="text-sm font-medium" />
                                    <select id="school_id" name="school_id" class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="">{{ __('Select School') }}</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ old('school_id', $student->school_id) == $school->id ? 'selected' : '' }}>
                                                {{ $school->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-1" :messages="$errors->get('school_id')" />
                                </div>
                            @else
                                <input type="hidden" name="school_id" value="{{ $schools->first()->id }}">
                                <div>
                                    <x-input-label :value="__('School')" class="text-sm font-medium" />
                                    <div class="mt-1 px-3 py-2 text-sm bg-gray-50 border border-gray-300 rounded-md text-gray-700">
                                        {{ $schools->first()->name }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Student Info Summary -->
                        <div class="mt-6 p-3 bg-gray-50 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">{{ __('Current Information') }}</h3>
                            <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">{{ __('Student ID') }}:</span>
                                    <span class="font-medium text-gray-900">{{ $student->student_id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">{{ __('Created') }}:</span>
                                    <span class="font-medium text-gray-900">{{ $student->created_at->format('M d, Y') }}</span>
                                </div>
                                @if($student->teacher)
                                <div class="flex justify-between col-span-2">
                                    <span class="text-gray-500">{{ __('Teacher') }}:</span>
                                    <span class="font-medium text-gray-900">{{ $student->teacher->name }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button class="text-xs uppercase tracking-widest">
                                {{ __('Update Student') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>