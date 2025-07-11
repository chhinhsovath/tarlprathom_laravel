<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('About') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- About Section -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <div class="h-16 w-16 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="h-10 w-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ __('TaRL Assessment System') }}</h3>
                            <p class="text-gray-600">{{ __('Teaching at the Right Level') }}</p>
                        </div>
                    </div>
                    
                    <div class="prose prose-indigo max-w-none">
                        <p class="text-gray-600">
                            {{ __('The TaRL Assessment System is a comprehensive educational platform designed to help teachers and mentors track student progress and improve learning outcomes through data-driven insights.') }}
                        </p>
                        
                        <h4 class="text-lg font-medium text-gray-900 mt-6 mb-3">{{ __('Our Mission') }}</h4>
                        <p class="text-gray-600">
                            {{ __('To empower educators with tools that enable them to assess students effectively, identify learning gaps, and provide targeted interventions that help every child learn at their appropriate level.') }}
                        </p>
                        
                        <h4 class="text-lg font-medium text-gray-900 mt-6 mb-3">{{ __('Key Features') }}</h4>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>{{ __('Comprehensive student assessment tracking') }}</li>
                            <li>{{ __('Real-time performance analytics and reporting') }}</li>
                            <li>{{ __('Multi-school management capabilities') }}</li>
                            <li>{{ __('Mentoring visit documentation') }}</li>
                            <li>{{ __('Progress tracking across multiple assessment cycles') }}</li>
                            <li>{{ __('Export capabilities for offline analysis') }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('System Information') }}</h4>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Version') }}</p>
                            <p class="text-sm font-medium text-gray-900">{{ $systemInfo['version'] }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Laravel Version') }}</p>
                            <p class="text-sm font-medium text-gray-900">{{ $systemInfo['laravel_version'] }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">{{ __('PHP Version') }}</p>
                            <p class="text-sm font-medium text-gray-900">{{ $systemInfo['php_version'] }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Environment') }}</p>
                            <p class="text-sm font-medium text-gray-900">{{ ucfirst($systemInfo['environment']) }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Timezone') }}</p>
                            <p class="text-sm font-medium text-gray-900">{{ $systemInfo['timezone'] }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Default Language') }}</p>
                            <p class="text-sm font-medium text-gray-900">{{ strtoupper($systemInfo['locale']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Credits -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mt-6">
                <div class="p-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Credits') }}</h4>
                    <p class="text-gray-600 mb-4">
                        {{ __('This system was developed in collaboration with educational experts and technology partners to support the Teaching at the Right Level initiative.') }}
                    </p>
                    <div class="text-sm text-gray-500">
                        <p>&copy; {{ date('Y') }} {{ __('TaRL Project. All rights reserved.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>