<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email or Username -->
        <div>
            <x-input-label for="email" :value="__('Email or Username')" />
            <x-text-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="koe.kimsou.bat or koe.kimsou.bat@teacher.tarl.edu.kh" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <p class="mt-1 text-sm text-gray-500">{{ __('Teachers: Use your short username (e.g., koe.kimsou.bat) or full email') }}</p>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            <a href="{{ route('quick-login') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                {{ __('Quick Login for Teachers/Mentors') }} →
            </a>
            
            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
    
    <!-- Additional Help Section -->
    <div class="mt-6 pt-6 border-t border-gray-200 text-center">
        <p class="text-sm text-gray-600">
            {{ __('Teachers and Mentors can use Quick Login for easier access') }}
        </p>
    </div>
</x-guest-layout>
