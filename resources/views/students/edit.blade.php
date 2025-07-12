<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Student') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('students.update', $student) }}" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $student->name)" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Grade -->
                        <div>
                            <x-input-label for="grade" :value="__('Grade')" />
                            <select id="grade" name="grade" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">{{ __('Select Grade') }}</option>
                                @for($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}" {{ old('grade', $student->grade) == $i ? 'selected' : '' }}>
                                        {{ __('Grade') }} {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('grade')" />
                        </div>

                        <!-- Gender -->
                        <div>
                            <x-input-label for="gender" :value="__('Gender')" />
                            <select id="gender" name="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">{{ __('Select Gender') }}</option>
                                <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                        </div>

                        <!-- Class -->
                        <div>
                            <x-input-label for="class_id" :value="__('Class')" />
                            <select id="class_id" name="class_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Select Class') }}</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                                        {{ $class->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('class_id')" />
                        </div>

                        <!-- School -->
                        @if($schools->count() > 1)
                            <div>
                                <x-input-label for="school_id" :value="__('School')" />
                                <select id="school_id" name="school_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">{{ __('Select School') }}</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ old('school_id', $student->school_id) == $school->id ? 'selected' : '' }}>
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

                        <!-- Photo Upload -->
                        <div>
                            <x-input-label for="photo" :value="__('Photo (Optional)')" />
                            
                            @if($student->photo)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-2">{{ __('Current Photo:') }}</p>
                                    <div class="h-32 w-32 rounded-lg overflow-hidden shadow-md">
                                        <img src="{{ Storage::url($student->photo) }}" alt="{{ $student->name }}" class="h-full w-full object-cover" style="height: 64px; width: 64px; object-fit: cover;">
                                    </div>
                                </div>
                            @endif
                            
                            <input type="file" 
                                   id="photo" 
                                   name="photo" 
                                   accept="image/*"
                                   class="mt-1 block w-full text-sm text-gray-500
                                   file:mr-4 file:py-2 file:px-4
                                   file:rounded-md file:border-0
                                   file:text-sm file:font-semibold
                                   file:bg-indigo-50 file:text-indigo-700
                                   hover:file:bg-indigo-100"
                                   onchange="previewPhoto(this)">
                            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
                            <p class="mt-1 text-sm text-gray-500">{{ __('Upload a new photo to replace the existing one (max 5MB)') }}</p>
                            
                            <!-- Photo Preview -->
                            <div id="photoPreview" class="mt-4 hidden">
                                <p class="text-sm text-gray-600 mb-2">{{ __('New Photo:') }}</p>
                                <div class="h-32 w-32 rounded-lg overflow-hidden shadow-md">
                                    <img id="previewImage" class="h-full w-full object-cover" alt="Photo preview" style="height: 64px; width: 64px; object-fit: cover;">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update') }}</x-primary-button>
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
        function previewPhoto(input) {
            const file = input.files[0];
            const preview = document.getElementById('photoPreview');
            const previewImage = document.getElementById('previewImage');
            
            if (file) {
                // Check file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('{{ __("File size must be less than 5MB") }}');
                    input.value = '';
                    preview.classList.add('hidden');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        }
    </script>
    @endpush
</x-app-layout>