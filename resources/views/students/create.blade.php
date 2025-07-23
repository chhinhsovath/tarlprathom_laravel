<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Student') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('students.store') }}" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Age -->
                        <div>
                            <x-input-label for="age" :value="__('Age')" />
                            <x-text-input id="age" name="age" type="number" min="3" max="18" class="mt-1 block w-full" :value="old('age')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('age')" />
                        </div>

                        <!-- Gender -->
                        <div>
                            <x-input-label for="gender" :value="__('Gender')" />
                            <select id="gender" name="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">{{ __('Select Gender') }}</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                        </div>

                        <!-- Grade -->
                        <div>
                            <x-input-label for="grade" :value="__('Grade')" />
                            <select id="grade" name="grade" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">{{ __('Select Grade') }}</option>
                                <option value="4" {{ old('grade') == 4 ? 'selected' : '' }}>
                                    {{ __('Grade') }} 4
                                </option>
                                <option value="5" {{ old('grade') == 5 ? 'selected' : '' }}>
                                    {{ __('Grade') }} 5
                                </option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('grade')" />
                        </div>

                        <!-- School -->
                        @if($schools->count() > 1)
                            <div>
                                <x-input-label for="school_id" :value="__('School')" />
                                <select id="school_id" name="school_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required onchange="loadTeachers(this.value)">
                                    <option value="">{{ __('Select School') }}</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                            {{ $school->school_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('school_id')" />
                            </div>
                        @else
                            <input type="hidden" name="school_id" value="{{ $schools->first()->id }}">
                            <div>
                                <x-input-label :value="__('School')" />
                                <p class="mt-1 text-sm text-gray-600">{{ $schools->first()->school_name }}</p>
                            </div>
                        @endif

                        <!-- Teacher (appears after school selection) -->
                        <div id="teacherDiv" style="{{ old('school_id') || $schools->count() == 1 ? '' : 'display: none;' }}">
                            <x-input-label for="teacher_id" :value="__('Teacher')" />
                            <select id="teacher_id" name="teacher_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Select Teacher') }}</option>
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
                            <x-input-error class="mt-2" :messages="$errors->get('teacher_id')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                            <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
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
                teacherSelect.innerHTML = '<option value="">{{ __("Select Teacher") }}</option>';
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
                    teacherSelect.innerHTML = '<option value="">{{ __("Select Teacher") }}</option>';
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