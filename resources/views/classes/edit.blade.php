<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Class') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('classes.update', $class) }}">
                        @csrf
                        @method('PUT')

                        <!-- Class Name -->
                        <div>
                            <x-input-label for="name" :value="__('Class Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $class->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            <p class="mt-1 text-sm text-gray-500">{{ __('e.g., Section A, Section B, Morning Class, etc.') }}</p>
                        </div>

                        <!-- Grade Level -->
                        <div class="mt-4">
                            <x-input-label for="grade_level" :value="__('Grade Level')" />
                            <select id="grade_level" name="grade_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">{{ __('Select Grade Level') }}</option>
                                <option value="4" {{ old('grade_level', $class->grade_level) == 4 ? 'selected' : '' }}>
                                    {{ __('Grade') }} 4
                                </option>
                                <option value="5" {{ old('grade_level', $class->grade_level) == 5 ? 'selected' : '' }}>
                                    {{ __('Grade') }} 5
                                </option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('grade_level')" />
                        </div>

                        <!-- School -->
                        <div class="mt-4">
                            <x-input-label for="school_id" :value="__('School')" />
                            <select id="school_id" name="school_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required onchange="loadTeachers()">
                                <option value="">{{ __('Select School') }}</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ old('school_id', $class->school_id) == $school->id ? 'selected' : '' }}>
                                        {{ $school->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('school_id')" />
                        </div>

                        <!-- Teacher -->
                        <div class="mt-4">
                            <x-input-label for="teacher_id" :value="__('Assign Teacher (Optional)')" />
                            <select id="teacher_id" name="teacher_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Select Teacher') }}</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $class->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('teacher_id')" />
                        </div>

                        <!-- Academic Year -->
                        <div class="mt-4">
                            <x-input-label for="academic_year" :value="__('Academic Year (Optional)')" />
                            <x-text-input id="academic_year" name="academic_year" type="text" class="mt-1 block w-full" :value="old('academic_year', $class->academic_year)" placeholder="{{ __('2024-2025') }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('academic_year')" />
                        </div>

                        <!-- Is Active -->
                        <div class="mt-4">
                            <label for="is_active" class="inline-flex items-center">
                                <input id="is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active" value="1" {{ old('is_active', $class->is_active) ? 'checked' : '' }}>
                                <span class="ms-2 text-sm text-gray-600">{{ __('Active') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>{{ __('Update Class') }}</x-primary-button>
                            <a href="{{ route('classes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>