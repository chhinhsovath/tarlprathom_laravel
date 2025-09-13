<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Quick Login Header -->
    <div class="text-center mb-6">
        <h2 class="text-xl font-bold text-gray-900">{{ __('Quick Login') }}</h2>
        <p class="mt-1 text-sm text-gray-600">{{ __('For Teachers and Mentors') }}</p>
    </div>

    <form method="POST" action="{{ route('quick-login.store') }}" class="space-y-4">
        @csrf

        <!-- User Selection -->
        <div>
            <x-input-label for="user_id" :value="__('Select Your Name')" />
            <select id="user_id" name="user_id" 
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                required autofocus>
                <option value="">-- {{ __('Choose your name') }} --</option>
                
                @if(isset($groupedUsers['mentor']) && $groupedUsers['mentor']->count() > 0)
                    <optgroup label="{{ __('Mentors') }}">
                        @foreach($groupedUsers['mentor'] as $user)
                            <option value="{{ $user['id'] }}" 
                                {{ old('user_id') == $user['id'] ? 'selected' : '' }}
                                data-role="{{ $user['role'] }}"
                                data-school="{{ $user['school'] }}">
                                {{ $user['name'] }} - {{ $user['school'] }}
                            </option>
                        @endforeach
                    </optgroup>
                @endif
                
                @if(isset($groupedUsers['teacher']) && $groupedUsers['teacher']->count() > 0)
                    <optgroup label="{{ __('Teachers') }}">
                        @foreach($groupedUsers['teacher'] as $user)
                            <option value="{{ $user['id'] }}" 
                                {{ old('user_id') == $user['id'] ? 'selected' : '' }}
                                data-role="{{ $user['role'] }}"
                                data-school="{{ $user['school'] }}">
                                {{ $user['name'] }} - {{ $user['school'] }}
                            </option>
                        @endforeach
                    </optgroup>
                @endif
            </select>
            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
        </div>

        <!-- Selected User Info Display -->
        <div id="userInfo" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-3">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-blue-900" id="selectedUserName"></p>
                    <p class="text-xs text-blue-700" id="selectedUserRole"></p>
                    <p class="text-xs text-blue-600" id="selectedUserSchool"></p>
                </div>
            </div>
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <x-text-input id="password" 
                    class="block mt-1 w-full pr-10"
                    type="password"
                    name="password"
                    required 
                    autocomplete="current-password"
                    placeholder="{{ __('Enter your password') }}" />
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center mt-1">
                    <svg id="eyeIcon" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            
            <!-- Password Hint -->
            <p class="mt-2 text-xs text-gray-500">
                {{ __('Default password: admin123') }}
            </p>
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-between mt-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← {{ __('Standard login') }}
            </a>
            
            <x-primary-button class="ms-3">
                {{ __('Sign in') }}
            </x-primary-button>
        </div>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const userSelect = document.getElementById('user_id');
        const userInfo = document.getElementById('userInfo');
        const selectedUserName = document.getElementById('selectedUserName');
        const selectedUserRole = document.getElementById('selectedUserRole');
        const selectedUserSchool = document.getElementById('selectedUserSchool');
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');
        
        // Show user info when a user is selected
        userSelect.addEventListener('change', function() {
            if (this.value) {
                const selectedOption = this.options[this.selectedIndex];
                const userName = selectedOption.text.split(' - ')[0];
                const userRole = selectedOption.dataset.role;
                const userSchool = selectedOption.dataset.school;
                
                selectedUserName.textContent = userName;
                selectedUserRole.textContent = userRole.charAt(0).toUpperCase() + userRole.slice(1);
                selectedUserSchool.textContent = userSchool;
                
                userInfo.classList.remove('hidden');
                
                // Focus on password field
                passwordInput.focus();
            } else {
                userInfo.classList.add('hidden');
            }
        });
        
        // Toggle password visibility
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle eye icon
            if (type === 'password') {
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                `;
            } else {
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                `;
            }
        });
        
        // Auto-select if there's an old value
        if (userSelect.value) {
            userSelect.dispatchEvent(new Event('change'));
        }
    });
    </script>
</x-guest-layout>