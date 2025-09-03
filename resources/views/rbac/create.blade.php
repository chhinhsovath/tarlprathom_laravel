<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('rbac.Create User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('rbac.store') }}" enctype="multipart/form-data" class="p-6">
                    @csrf

                    <!-- User Information Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rbac.User Information') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('rbac.Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('rbac.Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Password -->
                            <div>
                                <x-input-label for="password" :value="__('rbac.Password')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                                <p class="mt-1 text-sm text-gray-500">{{ __('rbac.Password must be at least 8 characters and include uppercase, lowercase, number, and special character') }}</p>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Phone -->
                            <div>
                                <x-input-label for="phone" :value="__('rbac.Phone')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <!-- Gender -->
                            <div>
                                <x-input-label for="sex" :value="__('rbac.Gender')" />
                                <select id="sex" name="sex" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">{{ __('Select...') }}</option>
                                    <option value="M" {{ old('sex') === 'M' ? 'selected' : '' }}>{{ __('rbac.Male') }}</option>
                                    <option value="F" {{ old('sex') === 'F' ? 'selected' : '' }}>{{ __('rbac.Female') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('sex')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Role Assignment Section -->
                    <div class="mb-8 border-t pt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rbac.Role Assignment') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Role -->
                            <div>
                                <x-input-label for="role" :value="__('rbac.Role')" />
                                <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">{{ __('Select...') }}</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                                            {{ __('rbac.' . ucfirst($role)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="is_active" :value="__('rbac.Status')" />
                                <select id="is_active" name="is_active" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="1" {{ old('is_active', '1') === '1' ? 'selected' : '' }}>{{ __('rbac.Active') }}</option>
                                    <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>{{ __('rbac.Inactive') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- School Assignment Section -->
                    <div class="mb-8 border-t pt-8" id="school-assignment" style="display: none;">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rbac.School Assignment') }}</h3>
                        
                        <!-- Single School (for Teachers) -->
                        <div id="single-school" class="grid grid-cols-1 gap-6" style="display: none;">
                            <div>
                                <x-input-label for="school_id" :value="__('rbac.School')" />
                                <select id="school_id" name="school_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">{{ __('Select...') }}</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                            {{ $school->school_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-sm text-gray-500">{{ __('rbac.Select the primary school for this teacher') }}</p>
                                <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Multiple Schools (for Mentors) -->
                        <div id="multiple-schools" class="grid grid-cols-1 gap-6" style="display: none;">
                            <div>
                                <x-input-label for="assigned_schools" :value="__('rbac.Assigned Schools')" />
                                <div class="mt-2 space-y-2 max-h-64 overflow-y-auto border border-gray-300 rounded-md p-3">
                                    @foreach($schools as $school)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="assigned_schools[]" value="{{ $school->id }}" 
                                                   {{ in_array($school->id, old('assigned_schools', [])) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-700">{{ $school->school_name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="mt-1 text-sm text-gray-500">{{ __('rbac.Select multiple schools for this mentor') }}</p>
                                <x-input-error :messages="$errors->get('assigned_schools')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="mb-8 border-t pt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rbac.Contact Information') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Province -->
                            <div>
                                <x-input-label for="province" :value="__('rbac.Province')" />
                                <x-text-input id="province" class="block mt-1 w-full" type="text" name="province" :value="old('province')" />
                                <x-input-error :messages="$errors->get('province')" class="mt-2" />
                            </div>

                            <!-- District -->
                            <div>
                                <x-input-label for="district" :value="__('rbac.District')" />
                                <x-text-input id="district" class="block mt-1 w-full" type="text" name="district" :value="old('district')" />
                                <x-input-error :messages="$errors->get('district')" class="mt-2" />
                            </div>

                            <!-- Commune -->
                            <div>
                                <x-input-label for="commune" :value="__('rbac.Commune')" />
                                <x-text-input id="commune" class="block mt-1 w-full" type="text" name="commune" :value="old('commune')" />
                                <x-input-error :messages="$errors->get('commune')" class="mt-2" />
                            </div>

                            <!-- Village -->
                            <div>
                                <x-input-label for="village" :value="__('rbac.Village')" />
                                <x-text-input id="village" class="block mt-1 w-full" type="text" name="village" :value="old('village')" />
                                <x-input-error :messages="$errors->get('village')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information Section -->
                    <div class="mb-8 border-t pt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rbac.Additional Information') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Holding Classes -->
                            <div>
                                <x-input-label for="holding_classes" :value="__('rbac.Holding Classes')" />
                                <x-text-input id="holding_classes" class="block mt-1 w-full" type="text" name="holding_classes" :value="old('holding_classes')" />
                                <x-input-error :messages="$errors->get('holding_classes')" class="mt-2" />
                            </div>

                            <!-- Assigned Subject -->
                            <div>
                                <x-input-label for="assigned_subject" :value="__('rbac.Assigned Subject')" />
                                <x-text-input id="assigned_subject" class="block mt-1 w-full" type="text" name="assigned_subject" :value="old('assigned_subject')" />
                                <x-input-error :messages="$errors->get('assigned_subject')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 border-t pt-8">
                        <a href="{{ route('rbac.users') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('rbac.Cancel') }}
                        </a>
                        <x-primary-button>
                            {{ __('rbac.Create') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for Role-based School Assignment -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const schoolAssignment = document.getElementById('school-assignment');
            const singleSchool = document.getElementById('single-school');
            const multipleSchools = document.getElementById('multiple-schools');
            const schoolIdSelect = document.getElementById('school_id');

            function toggleSchoolAssignment() {
                const selectedRole = roleSelect.value;
                
                if (selectedRole === 'teacher') {
                    schoolAssignment.style.display = 'block';
                    singleSchool.style.display = 'block';
                    multipleSchools.style.display = 'none';
                    schoolIdSelect.setAttribute('required', 'required');
                } else if (selectedRole === 'mentor') {
                    schoolAssignment.style.display = 'block';
                    singleSchool.style.display = 'none';
                    multipleSchools.style.display = 'block';
                    schoolIdSelect.removeAttribute('required');
                } else {
                    schoolAssignment.style.display = 'none';
                    singleSchool.style.display = 'none';
                    multipleSchools.style.display = 'none';
                    schoolIdSelect.removeAttribute('required');
                }
            }

            roleSelect.addEventListener('change', toggleSchoolAssignment);
            
            // Initialize on page load
            toggleSchoolAssignment();
        });
    </script>
</x-app-layout>