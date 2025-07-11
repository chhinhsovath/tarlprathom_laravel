<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit User') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Basic Information') }}</h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <x-input-label for="name" :value="__('Name')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <!-- Email -->
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>

                                <!-- Password -->
                                <div>
                                    <x-input-label for="password" :value="__('New Password')" />
                                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                                    <x-input-error class="mt-2" :messages="$errors->get('password')" />
                                    <p class="mt-1 text-sm text-gray-500">{{ __('Leave blank to keep current password.') }}</p>
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
                                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                                    <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                                </div>
                            </div>
                        </div>
                        
                        <!-- Role and Permissions -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Role & Permissions') }}</h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Role -->
                                <div>
                                    <x-input-label for="role" :value="__('Role')" />
                                    <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="">{{ __('Select Role') }}</option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                                        <option value="mentor" {{ old('role', $user->role) == 'mentor' ? 'selected' : '' }}>{{ __('Mentor') }}</option>
                                        <option value="teacher" {{ old('role', $user->role) == 'teacher' ? 'selected' : '' }}>{{ __('Teacher') }}</option>
                                        <option value="viewer" {{ old('role', $user->role) == 'viewer' ? 'selected' : '' }}>{{ __('Viewer') }}</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('role')" />
                                </div>

                                <!-- School -->
                                <div>
                                    <x-input-label for="school_id" :value="__('School')" />
                                    <select id="school_id" name="school_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">{{ __('No School Assigned') }}</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ old('school_id', $user->school_id) == $school->id ? 'selected' : '' }}>
                                                {{ $school->school_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('school_id')" />
                                </div>
                                
                                <!-- Active Status -->
                                <div class="sm:col-span-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-600">{{ __('Active User') }}</span>
                                    </label>
                                    <p class="mt-1 text-sm text-gray-500">{{ __('Active users can log in and access the system.') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Information -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Additional Information') }}</h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Gender -->
                                <div>
                                    <x-input-label for="sex" :value="__('Gender')" />
                                    <select id="sex" name="sex" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">{{ __('Select Gender') }}</option>
                                        <option value="male" {{ old('sex', $user->sex) == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                        <option value="female" {{ old('sex', $user->sex) == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('sex')" />
                                </div>

                                <!-- Phone -->
                                <div>
                                    <x-input-label for="phone" :value="__('Phone')" />
                                    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                                </div>

                                <!-- Telephone -->
                                <div>
                                    <x-input-label for="telephone" :value="__('Telephone')" />
                                    <x-text-input id="telephone" name="telephone" type="text" class="mt-1 block w-full" :value="old('telephone', $user->telephone)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('telephone')" />
                                </div>

                                <!-- Holding Classes -->
                                <div>
                                    <x-input-label for="holding_classes" :value="__('Holding Classes')" />
                                    <x-text-input id="holding_classes" name="holding_classes" type="text" class="mt-1 block w-full" :value="old('holding_classes', $user->holding_classes)" placeholder="{{ __('e.g., Grade 1, Grade 2') }}" />
                                    <x-input-error class="mt-2" :messages="$errors->get('holding_classes')" />
                                </div>
                            </div>
                        </div>
                        
                        <!-- Profile Photo -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Profile Photo') }}</h3>
                            
                            <div>
                                @if($user->profile_photo)
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-600 mb-2">{{ __('Current Photo:') }}</p>
                                        <div class="h-32 w-32 rounded-full overflow-hidden">
                                            <img src="{{ Storage::url($user->profile_photo) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                                        </div>
                                    </div>
                                @endif
                                
                                <x-input-label for="profile_photo" :value="__('New Photo')" />
                                <input type="file" 
                                       id="profile_photo" 
                                       name="profile_photo" 
                                       accept="image/*"
                                       class="mt-1 block w-full text-sm text-gray-500
                                       file:mr-4 file:py-2 file:px-4
                                       file:rounded-md file:border-0
                                       file:text-sm file:font-semibold
                                       file:bg-indigo-50 file:text-indigo-700
                                       hover:file:bg-indigo-100"
                                       onchange="previewPhoto(this)">
                                <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
                                <p class="mt-1 text-sm text-gray-500">{{ __('Upload a new photo to replace the existing one (max 5MB)') }}</p>
                                
                                <!-- Photo Preview -->
                                <div id="photoPreview" class="mt-4 hidden">
                                    <p class="text-sm text-gray-600 mb-2">{{ __('New Photo Preview:') }}</p>
                                    <div class="h-32 w-32 rounded-full overflow-hidden">
                                        <img id="previewImage" class="h-full w-full object-cover" alt="Photo preview">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update User') }}</x-primary-button>
                            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
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