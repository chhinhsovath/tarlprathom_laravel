<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('students.Add New Student') }}
            </h2>
            <a href="{{ route('students.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← {{ __('students.Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <form method="POST" action="{{ route('students.store') }}" class="space-y-4">
                        @csrf

                        <!-- Two Column Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="name" :value="__('students.Student Name')" class="text-sm font-medium" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full text-sm" :value="old('name')" required autofocus autocomplete="name" />
                                <x-input-error class="mt-1" :messages="$errors->get('name')" />
                            </div>

                            <!-- Grade -->
                            <div>
                                <x-input-label for="class" :value="__('students.Grade')" class="text-sm font-medium" />
                                <select id="class" name="class" class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">{{ __('students.Select Grade') }}</option>
                                    <option value="Grade 4" {{ old('class') == 'Grade 4' ? 'selected' : '' }}>
                                        {{ __('students.Grade 4') }}
                                    </option>
                                    <option value="Grade 5" {{ old('class') == 'Grade 5' ? 'selected' : '' }}>
                                        {{ __('students.Grade 5') }}
                                    </option>
                                </select>
                                <x-input-error class="mt-1" :messages="$errors->get('class')" />
                            </div>

                            <!-- Gender -->
                            <div>
                                <x-input-label for="gender" :value="__('students.Gender')" class="text-sm font-medium" />
                                <select id="gender" name="gender" class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">{{ __('students.Select Gender') }}</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('students.Male') }}</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('students.Female') }}</option>
                                </select>
                                <x-input-error class="mt-1" :messages="$errors->get('gender')" />
                            </div>

                            <!-- Age -->
                            <div>
                                <x-input-label for="age" :value="__('students.Age')" class="text-sm font-medium" />
                                <x-text-input id="age" name="age" type="number" min="3" max="18" class="mt-1 block w-full text-sm" :value="old('age')" required />
                                <x-input-error class="mt-1" :messages="$errors->get('age')" />
                            </div>

                            <!-- School -->
                            @if($schools->count() > 1)
                                <div>
                                    <x-input-label for="school_id" :value="__('students.School')" class="text-sm font-medium" />
                                    <select id="school_id" name="school_id" class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required onchange="loadTeachers(this.value)">
                                        <option value="">{{ __('students.Select School') }}</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                                {{ $school->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-1" :messages="$errors->get('school_id')" />
                                </div>
                            @else
                                <input type="hidden" name="school_id" value="{{ $schools->first()->id }}">
                                <div>
                                    <x-input-label :value="__('students.School')" class="text-sm font-medium" />
                                    <div class="mt-1 px-3 py-2 text-sm bg-gray-50 border border-gray-300 rounded-md text-gray-700">
                                        {{ $schools->first()->name }}
                                    </div>
                                </div>
                            @endif

                            <!-- Teacher (appears after school selection) -->
                            <div id="teacherDiv" style="{{ old('school_id') || $schools->count() == 1 ? '' : 'display: none;' }}">
                                <x-input-label for="teacher_id" :value="__('students.Teacher')" class="text-sm font-medium" />
                                <select id="teacher_id" name="teacher_id" class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('students.Select Teacher') }} ({{ __('students.Optional') }})</option>
                                    @if(old('school_id') || $schools->count() == 1)
                                        @php
                                            $selectedSchoolId = old('school_id') ?? $schools->first()->id;
                                            $teachers = \App\Models\User::where('school_id', $selectedSchoolId)
                                                ->where('role', 'teacher')
                                                ->orderBy('name')
                                                ->get();
                                        @endphp
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <x-input-error class="mt-1" :messages="$errors->get('teacher_id')" />
                            </div>
                        </div>

                        <!-- Important Note -->
                        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-800">
                                        {{ __('students.Student ID will be automatically generated upon creation.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('students.Cancel') }}
                            </a>
                            <x-primary-button class="text-xs uppercase tracking-widest">
                                {{ __('students.Create Student') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        // If only one school, show teachers immediately
        @if($schools->count() == 1)
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('teacherDiv').style.display = 'block';
            });
        @endif

        function loadTeachers(schoolId) {
            const teacherDiv = document.getElementById('teacherDiv');
            const teacherSelect = document.getElementById('teacher_id');
            
            if (!schoolId) {
                teacherDiv.style.display = 'none';
                teacherSelect.innerHTML = '<option value="">{{ __("Select Teacher (Optional)") }}</option>';
                return;
            }
            
            // Show the teacher div
            teacherDiv.style.display = 'block';
            
            // Clear current options
            teacherSelect.innerHTML = '<option value="">{{ __("Loading...") }}</option>';
            
            // Fetch teachers for the selected school
            fetch(`/api/school/${schoolId}/teachers`)
                .then(response => response.json())
                .then(data => {
                    teacherSelect.innerHTML = '<option value="">{{ __("Select Teacher (Optional)") }}</option>';
                    data.forEach(teacher => {
                        const option = document.createElement('option');
                        option.value = teacher.id;
                        option.textContent = teacher.name;
                        teacherSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading teachers:', error);
                    teacherSelect.innerHTML = '<option value="">{{ __("Error loading teachers") }}</option>';
                });
        }
    </script>
    @endpush
</x-app-layout>