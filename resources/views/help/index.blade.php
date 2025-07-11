<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Help & Support') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Welcome to TaRL Assessment System Help') }}</h3>
                    <p class="text-gray-600">{{ __('Find answers to common questions and learn how to use the system effectively.') }}</p>
                </div>
            </div>

            <!-- FAQ Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Getting Started -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            {{ __('Getting Started') }}
                        </h4>
                        <div class="space-y-3">
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('How do I log in?') }}</summary>
                                <p class="mt-2 text-sm text-gray-600 pl-4">{{ __('Use your email and password provided by your administrator. If you forgot your password, click "Forgot Password" on the login page.') }}</p>
                            </details>
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('How do I change my password?') }}</summary>
                                <p class="mt-2 text-sm text-gray-600 pl-4">{{ __('Go to your profile by clicking your name in the top right corner, then select "Change Password".') }}</p>
                            </details>
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('How do I update my profile?') }}</summary>
                                <p class="mt-2 text-sm text-gray-600 pl-4">{{ __('Click on your name in the top right corner and select "My Profile". You can update your information and upload a profile photo.') }}</p>
                            </details>
                        </div>
                    </div>
                </div>

                <!-- Student Management -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            {{ __('Student Management') }}
                        </h4>
                        <div class="space-y-3">
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('How do I add a new student?') }}</summary>
                                <p class="mt-2 text-sm text-gray-600 pl-4">{{ __('Go to Students menu and click "Add New Student". Fill in all required information and click Save.') }}</p>
                            </details>
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('How do I upload student photos?') }}</summary>
                                <p class="mt-2 text-sm text-gray-600 pl-4">{{ __('When creating or editing a student, use the photo upload field. Photos must be less than 5MB in size.') }}</p>
                            </details>
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('How do I search for students?') }}</summary>
                                <p class="mt-2 text-sm text-gray-600 pl-4">{{ __('Use the search bar on the Students page. You can search by name or filter by school, grade, and gender.') }}</p>
                            </details>
                        </div>
                    </div>
                </div>

                <!-- Assessments -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            {{ __('Assessments') }}
                        </h4>
                        <div class="space-y-3">
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('How do I create an assessment?') }}</summary>
                                <p class="mt-2 text-sm text-gray-600 pl-4">{{ __('Go to Assessments menu and click "Create Assessment". Select students and enter their scores for each subject.') }}</p>
                            </details>
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('What are the assessment cycles?') }}</summary>
                                <p class="mt-2 text-sm text-gray-600 pl-4">{{ __('There are three cycles: Baseline (beginning), Midline (middle), and Endline (end of term).') }}</p>
                            </details>
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('How do I view assessment results?') }}</summary>
                                <p class="mt-2 text-sm text-gray-600 pl-4">{{ __('Go to Reports menu to view various assessment reports and analytics.') }}</p>
                            </details>
                        </div>
                    </div>
                </div>

                <!-- Reports -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            {{ __('Reports') }}
                        </h4>
                        <div class="space-y-3">
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('What reports are available?') }}</summary>
                                <p class="mt-2 text-sm text-gray-600 pl-4">{{ __('Student Performance, School Comparison, Progress Tracking, and more. Access them from the Reports menu.') }}</p>
                            </details>
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('How do I export reports?') }}</summary>
                                <p class="mt-2 text-sm text-gray-600 pl-4">{{ __('Most reports have an "Export" button that allows you to download data in Excel format.') }}</p>
                            </details>
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('Can I filter report data?') }}</summary>
                                <p class="mt-2 text-sm text-gray-600 pl-4">{{ __('Yes, most reports allow filtering by date range, school, grade, and other criteria.') }}</p>
                            </details>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Need More Help?') }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="mx-auto h-12 w-12 text-indigo-600 mb-3">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h5 class="font-medium text-gray-900 mb-1">{{ __('Email Support') }}</h5>
                            <p class="text-sm text-gray-600">support@tarlproject.org</p>
                        </div>
                        <div class="text-center">
                            <div class="mx-auto h-12 w-12 text-indigo-600 mb-3">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <h5 class="font-medium text-gray-900 mb-1">{{ __('Phone Support') }}</h5>
                            <p class="text-sm text-gray-600">+855 23 123 456</p>
                        </div>
                        <div class="text-center">
                            <div class="mx-auto h-12 w-12 text-indigo-600 mb-3">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h5 class="font-medium text-gray-900 mb-1">{{ __('Support Hours') }}</h5>
                            <p class="text-sm text-gray-600">{{ __('Mon-Fri: 8AM-5PM') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>