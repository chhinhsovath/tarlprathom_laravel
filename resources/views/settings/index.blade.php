<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('System Settings') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
                @csrf
                
                <!-- General Settings -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">{{ __('General Settings') }}</h3>
                        
                        <div class="space-y-6">
                            <!-- Application Name -->
                            <div>
                                <x-input-label for="app_name" :value="__('Application Name')" />
                                <x-text-input id="app_name" name="app_name" type="text" class="mt-1 block w-full" :value="old('app_name', $settings['app_name'])" required />
                                <x-input-error class="mt-2" :messages="$errors->get('app_name')" />
                                <p class="mt-1 text-sm text-gray-500">{{ __('The name of your application that appears in the header and emails.') }}</p>
                            </div>

                            <!-- Timezone -->
                            <div>
                                <x-input-label for="app_timezone" :value="__('Timezone')" />
                                <select id="app_timezone" name="app_timezone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="UTC" {{ old('app_timezone', $settings['app_timezone']) == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    <option value="Asia/Phnom_Penh" {{ old('app_timezone', $settings['app_timezone']) == 'Asia/Phnom_Penh' ? 'selected' : '' }}>Asia/Phnom_Penh</option>
                                    <option value="Asia/Bangkok" {{ old('app_timezone', $settings['app_timezone']) == 'Asia/Bangkok' ? 'selected' : '' }}>Asia/Bangkok</option>
                                    <option value="America/New_York" {{ old('app_timezone', $settings['app_timezone']) == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('app_timezone')" />
                            </div>

                            <!-- Default Language -->
                            <div>
                                <x-input-label for="app_locale" :value="__('Default Language')" />
                                <select id="app_locale" name="app_locale" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="en" {{ old('app_locale', $settings['app_locale']) == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="km" {{ old('app_locale', $settings['app_locale']) == 'km' ? 'selected' : '' }}>Khmer</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('app_locale')" />
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Email Settings -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">{{ __('Email Settings') }}</h3>
                        
                        <div class="space-y-6">
                            <!-- From Email -->
                            <div>
                                <x-input-label for="mail_from_address" :value="__('From Email Address')" />
                                <x-text-input id="mail_from_address" name="mail_from_address" type="email" class="mt-1 block w-full" :value="old('mail_from_address', $settings['mail_from_address'])" />
                                <x-input-error class="mt-2" :messages="$errors->get('mail_from_address')" />
                                <p class="mt-1 text-sm text-gray-500">{{ __('The email address that system emails will be sent from.') }}</p>
                            </div>

                            <!-- From Name -->
                            <div>
                                <x-input-label for="mail_from_name" :value="__('From Name')" />
                                <x-text-input id="mail_from_name" name="mail_from_name" type="text" class="mt-1 block w-full" :value="old('mail_from_name', $settings['mail_from_name'])" />
                                <x-input-error class="mt-2" :messages="$errors->get('mail_from_name')" />
                                <p class="mt-1 text-sm text-gray-500">{{ __('The name that will appear as the sender of system emails.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Save Button -->
                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Save Settings') }}</x-primary-button>
                    <p class="text-sm text-gray-500">{{ __('Note: Some settings may require server restart to take effect.') }}</p>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>