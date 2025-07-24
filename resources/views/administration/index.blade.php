<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-6">{{ __('System Administration') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- User Management -->
                        <div class="bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <h4 class="font-semibold text-lg">{{ __('Manage Users') }}</h4>
                            </div>
                            <p class="text-gray-600 mb-4">{{ __('Add, edit, and manage user accounts and permissions') }}</p>
                            <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                {{ __('Go to User Management') }} →
                            </a>
                        </div>

                        <!-- Import Users -->
                        <div class="bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <h4 class="font-semibold text-lg">{{ __('Import Users') }}</h4>
                            </div>
                            <p class="text-gray-600 mb-4">{{ __('Bulk import users from Excel or CSV files') }}</p>
                            <a href="{{ route('users.bulk-import-form') }}" class="text-green-600 hover:text-green-800 font-medium">
                                {{ __('Import Users') }} →
                            </a>
                        </div>

                        <!-- Import Students -->
                        <div class="bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <h4 class="font-semibold text-lg">{{ __('Import Students') }}</h4>
                            </div>
                            <p class="text-gray-600 mb-4">{{ __('Bulk import student data from spreadsheets') }}</p>
                            <a href="{{ route('students.bulk-import-form') }}" class="text-purple-600 hover:text-purple-800 font-medium">
                                {{ __('Import Students') }} →
                            </a>
                        </div>

                        <!-- School Management -->
                        <div class="bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <h4 class="font-semibold text-lg">{{ __('Manage Schools') }}</h4>
                            </div>
                            <p class="text-gray-600 mb-4">{{ __('Add and manage school information') }}</p>
                            <a href="{{ route('schools.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                {{ __('Go to School Management') }} →
                            </a>
                        </div>

                        <!-- Assessment Dates -->
                        <div class="bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <h4 class="font-semibold text-lg">{{ __('Assessment Dates') }}</h4>
                            </div>
                            <p class="text-gray-600 mb-4">{{ __('Configure assessment dates for schools') }}</p>
                            <a href="{{ route('schools.assessment-dates') }}" class="text-yellow-600 hover:text-yellow-800 font-medium">
                                {{ __('Manage Dates') }} →
                            </a>
                        </div>

                        <!-- Assessment Management -->
                        <div class="bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h4 class="font-semibold text-lg">{{ __('Assessment Management') }}</h4>
                            </div>
                            <p class="text-gray-600 mb-4">{{ __('Lock/unlock assessments and manage data') }}</p>
                            <a href="{{ route('assessment-management.index') }}" class="text-red-600 hover:text-red-800 font-medium">
                                {{ __('Manage Assessments') }} →
                            </a>
                        </div>

                        <!-- Mentoring Management -->
                        <div class="bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-teal-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <h4 class="font-semibold text-lg">{{ __('Mentoring Management') }}</h4>
                            </div>
                            <p class="text-gray-600 mb-4">{{ __('Manage mentoring visits and activities') }}</p>
                            <a href="{{ route('assessment-management.mentoring-visits') }}" class="text-teal-600 hover:text-teal-800 font-medium">
                                {{ __('Manage Mentoring') }} →
                            </a>
                        </div>

                        <!-- Resource Management -->
                        <div class="bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <h4 class="font-semibold text-lg">{{ __('Manage Resources') }}</h4>
                            </div>
                            <p class="text-gray-600 mb-4">{{ __('Upload and manage educational resources') }}</p>
                            <a href="{{ route('resources.index') }}" class="text-orange-600 hover:text-orange-800 font-medium">
                                {{ __('Manage Resources') }} →
                            </a>
                        </div>

                        <!-- Settings -->
                        <div class="bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <h4 class="font-semibold text-lg">{{ __('Settings') }}</h4>
                            </div>
                            <p class="text-gray-600 mb-4">{{ __('Configure system settings and preferences') }}</p>
                            <a href="{{ route('settings.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                                {{ __('Go to Settings') }} →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>