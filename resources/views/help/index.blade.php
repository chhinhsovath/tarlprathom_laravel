<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Help & Support') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Welcome to TaRL Assessment System Help') }}</h3>
                    <p class="text-gray-600 mb-2">{{ __('This system is designed specifically for Grade 4 and Grade 5 students only.') }}</p>
                    <p class="text-gray-600">{{ __('Find step-by-step instructions and learn how to use the system effectively.') }}</p>
                </div>
            </div>

            <!-- Quick Navigation -->
            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-6">
                <h4 class="font-medium text-indigo-900 mb-2">{{ __('Quick Links') }}</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2">
                    <a href="#getting-started" class="text-indigo-600 hover:text-indigo-800 text-sm">→ {{ __('Getting Started') }}</a>
                    <a href="#student-management" class="text-indigo-600 hover:text-indigo-800 text-sm">→ {{ __('Student Management') }}</a>
                    <a href="#assessments" class="text-indigo-600 hover:text-indigo-800 text-sm">→ {{ __('Assessments') }}</a>
                    <a href="#mentoring" class="text-indigo-600 hover:text-indigo-800 text-sm">→ {{ __('Mentoring Visits') }}</a>
                    <a href="#reports" class="text-indigo-600 hover:text-indigo-800 text-sm">→ {{ __('Reports') }}</a>
                    <a href="#bulk-import" class="text-indigo-600 hover:text-indigo-800 text-sm">→ {{ __('Bulk Import') }}</a>
                    <a href="#resources" class="text-indigo-600 hover:text-indigo-800 text-sm">→ {{ __('Resources') }}</a>
                    <a href="#workflows" class="text-indigo-600 hover:text-indigo-800 text-sm">→ {{ __('Common Workflows') }}</a>
                </div>
            </div>

            <!-- User Roles Section -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('User Roles & Permissions') }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="border rounded-lg p-4">
                            <h5 class="font-medium text-gray-900 mb-2">{{ __('Administrator') }}</h5>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• {{ __('Full system access') }}</li>
                                <li>• {{ __('Manage all users') }}</li>
                                <li>• {{ __('Assign mentors to schools') }}</li>
                                <li>• {{ __('Access all reports') }}</li>
                            </ul>
                        </div>
                        <div class="border rounded-lg p-4">
                            <h5 class="font-medium text-gray-900 mb-2">{{ __('Teacher') }}</h5>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• {{ __('Manage own students') }}</li>
                                <li>• {{ __('Conduct assessments') }}</li>
                                <li>• {{ __('View class reports') }}</li>
                                <li>• {{ __('Access resources') }}</li>
                            </ul>
                        </div>
                        <div class="border rounded-lg p-4">
                            <h5 class="font-medium text-gray-900 mb-2">{{ __('Mentor') }}</h5>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• {{ __('Visit assigned schools') }}</li>
                                <li>• {{ __('Verify assessments') }}</li>
                                <li>• {{ __('Document visits') }}</li>
                                <li>• {{ __('View school reports') }}</li>
                            </ul>
                        </div>
                        <div class="border rounded-lg p-4">
                            <h5 class="font-medium text-gray-900 mb-2">{{ __('Viewer') }}</h5>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• {{ __('Read-only access') }}</li>
                                <li>• {{ __('View reports') }}</li>
                                <li>• {{ __('Export data') }}</li>
                                <li>• {{ __('No editing rights') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step-by-Step Guides -->
            <div class="space-y-6">
                <!-- Getting Started -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg" id="getting-started">
                    <div class="p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            {{ __('Getting Started') }}
                        </h4>
                        <div class="space-y-4">
                            <details class="group" open>
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('How to Log In') }}</summary>
                                <div class="mt-2 text-sm text-gray-600 pl-4 space-y-2">
                                    <p class="font-medium">{{ __('Steps:') }}</p>
                                    <ol class="list-decimal list-inside space-y-1 ml-2">
                                        <li>{{ __('Go to the login page') }}</li>
                                        <li>{{ __('Enter your email address') }}</li>
                                        <li>{{ __('Enter your password') }}</li>
                                        <li>{{ __('Click "Login" button') }}</li>
                                    </ol>
                                    <p class="text-xs text-gray-500 mt-2">{{ __('Note: If you forgot your password, click "Forgot Password" link below the login form') }}</p>
                                </div>
                            </details>
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('Update Your Profile') }}</summary>
                                <div class="mt-2 text-sm text-gray-600 pl-4 space-y-2">
                                    <p class="font-medium">{{ __('Steps:') }}</p>
                                    <ol class="list-decimal list-inside space-y-1 ml-2">
                                        <li>{{ __('Click your name in the top right corner') }}</li>
                                        <li>{{ __('Select "My Profile" from dropdown') }}</li>
                                        <li>{{ __('Update your information:') }}
                                            <ul class="list-disc list-inside ml-4 mt-1">
                                                <li>{{ __('Name') }}</li>
                                                <li>{{ __('Email') }}</li>
                                                <li>{{ __('Phone number') }}</li>
                                                <li>{{ __('Profile photo (max 5MB)') }}</li>
                                            </ul>
                                        </li>
                                        <li>{{ __('Click "Save" button') }}</li>
                                    </ol>
                                </div>
                            </details>
                        </div>
                    </div>
                </div>

                <!-- Student Management -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg" id="student-management">
                    <div class="p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            {{ __('Student Management') }}
                        </h4>
                        <div class="space-y-4">
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('Add a New Student') }}</summary>
                                <div class="mt-2 text-sm text-gray-600 pl-4 space-y-2">
                                    <p class="font-medium">{{ __('Steps:') }}</p>
                                    <ol class="list-decimal list-inside space-y-1 ml-2">
                                        <li>{{ __('Go to "Students" menu') }}</li>
                                        <li>{{ __('Click "Add New Student" button') }}</li>
                                        <li>{{ __('Fill in required information:') }}
                                            <ul class="list-disc list-inside ml-4 mt-1">
                                                <li>{{ __('Student ID') }}</li>
                                                <li>{{ __('Full name') }}</li>
                                                <li>{{ __('Grade (4 or 5 only)') }}</li>
                                                <li>{{ __('Gender') }}</li>
                                                <li>{{ __('Date of birth') }}</li>
                                                <li>{{ __('School') }}</li>
                                                <li>{{ __('Class/Section') }}</li>
                                            </ul>
                                        </li>
                                        <li>{{ __('Upload student photo (optional)') }}</li>
                                        <li>{{ __('Click "Save" button') }}</li>
                                    </ol>
                                    <p class="text-xs text-gray-500 mt-2">{{ __('Important: Only Grade 4 and 5 students can be added') }}</p>
                                </div>
                            </details>
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('Select Students for Assessments') }}</summary>
                                <div class="mt-2 text-sm text-gray-600 pl-4 space-y-2">
                                    <p class="font-medium">{{ __('For Midline/Endline Assessments:') }}</p>
                                    <ol class="list-decimal list-inside space-y-1 ml-2">
                                        <li>{{ __('Go to "Assessments" page') }}</li>
                                        <li>{{ __('Click "Select Students" button') }}</li>
                                        <li>{{ __('Choose "Midline" or "Endline" tab') }}</li>
                                        <li>{{ __('Use filters to find students:') }}
                                            <ul class="list-disc list-inside ml-4 mt-1">
                                                <li>{{ __('Search by name') }}</li>
                                                <li>{{ __('Filter by school') }}</li>
                                                <li>{{ __('Filter by grade') }}</li>
                                            </ul>
                                        </li>
                                        <li>{{ __('Select students using checkboxes') }}</li>
                                        <li>{{ __('Click "Save Selection"') }}</li>
                                    </ol>
                                    <p class="text-xs text-gray-500 mt-2">{{ __('Note: Baseline assessments include all students automatically') }}</p>
                                </div>
                            </details>
                        </div>
                    </div>
                </div>

                <!-- Assessments -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg" id="assessments">
                    <div class="p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            {{ __('Assessments') }}
                        </h4>
                        <div class="space-y-4">
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('Create an Assessment') }}</summary>
                                <div class="mt-2 text-sm text-gray-600 pl-4 space-y-2">
                                    <p class="font-medium">{{ __('Steps:') }}</p>
                                    <ol class="list-decimal list-inside space-y-1 ml-2">
                                        <li>{{ __('Go to "Assessments" page') }}</li>
                                        <li>{{ __('Click "New Assessment" button') }}</li>
                                        <li>{{ __('Select assessment details:') }}
                                            <ul class="list-disc list-inside ml-4 mt-1">
                                                <li>{{ __('Subject (Khmer or Math)') }}</li>
                                                <li>{{ __('Assessment Cycle:') }}
                                                    <ul class="list-circle list-inside ml-4">
                                                        <li>{{ __('Baseline (all students)') }}</li>
                                                        <li>{{ __('Midline (selected students)') }}</li>
                                                        <li>{{ __('Endline (selected students)') }}</li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>{{ __('Enter scores for each student') }}</li>
                                        <li>{{ __('Click "Save Assessment"') }}</li>
                                    </ol>
                                </div>
                            </details>
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('Assessment Cycles Explained') }}</summary>
                                <div class="mt-2 text-sm text-gray-600 pl-4 space-y-2">
                                    <div class="space-y-3">
                                        <div>
                                            <p class="font-medium">{{ __('Baseline Assessment:') }}</p>
                                            <ul class="list-disc list-inside ml-4 mt-1">
                                                <li>{{ __('Initial assessment at start of year') }}</li>
                                                <li>{{ __('All students participate') }}</li>
                                                <li>{{ __('Establishes starting level') }}</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <p class="font-medium">{{ __('Midline Assessment:') }}</p>
                                            <ul class="list-disc list-inside ml-4 mt-1">
                                                <li>{{ __('Mid-year progress check') }}</li>
                                                <li>{{ __('Only selected students') }}</li>
                                                <li>{{ __('Tracks improvement') }}</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <p class="font-medium">{{ __('Endline Assessment:') }}</p>
                                            <ul class="list-disc list-inside ml-4 mt-1">
                                                <li>{{ __('Final assessment') }}</li>
                                                <li>{{ __('Only selected students') }}</li>
                                                <li>{{ __('Measures total progress') }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </details>
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('Setting Assessment Dates for Schools') }} <span class="text-xs text-gray-500">({{ __('Admin only') }})</span></summary>
                                <div class="mt-2 text-sm text-gray-600 pl-4 space-y-2">
                                    <p class="font-medium">{{ __('Steps:') }}</p>
                                    <ol class="list-decimal list-inside space-y-1 ml-2">
                                        <li>{{ __('Go to "Schools" page') }}</li>
                                        <li>{{ __('Click on a school name to view details') }}</li>
                                        <li>{{ __('Click "Edit" button on the school page') }}</li>
                                        <li>{{ __('Set assessment period dates:') }}
                                            <ul class="list-disc list-inside ml-4 mt-1">
                                                <li>{{ __('Baseline Assessment Start Date') }}</li>
                                                <li>{{ __('Baseline Assessment End Date') }}</li>
                                                <li>{{ __('Midline Assessment Start Date') }}</li>
                                                <li>{{ __('Midline Assessment End Date') }}</li>
                                                <li>{{ __('Endline Assessment Start Date') }}</li>
                                                <li>{{ __('Endline Assessment End Date') }}</li>
                                            </ul>
                                        </li>
                                        <li>{{ __('Click "Update School" to save') }}</li>
                                    </ol>
                                    <p class="text-xs text-gray-500 mt-2">{{ __('Note: These dates determine when teachers can conduct each type of assessment for the school') }}</p>
                                </div>
                            </details>
                        </div>
                    </div>
                </div>

                <!-- Mentoring Visits -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg" id="mentoring">
                    <div class="p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            {{ __('Mentoring Visits') }} <span class="text-sm text-gray-500 ml-2">({{ __('For Mentors') }})</span>
                        </h4>
                        <div class="space-y-4">
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('School Assignment for Mentors') }}</summary>
                                <div class="mt-2 text-sm text-gray-600 pl-4 space-y-2">
                                    <p class="font-medium">{{ __('For Administrators:') }}</p>
                                    <ol class="list-decimal list-inside space-y-1 ml-2">
                                        <li>{{ __('Go to "Users" page') }}</li>
                                        <li>{{ __('Click on a mentor user') }}</li>
                                        <li>{{ __('Click "Assign Schools" button') }}</li>
                                        <li>{{ __('Select schools with checkboxes') }}</li>
                                        <li>{{ __('Click "Save School Assignments"') }}</li>
                                    </ol>
                                    <p class="text-xs text-gray-500 mt-2">{{ __('Note: Mentors can only visit assigned schools') }}</p>
                                </div>
                            </details>
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('Log a Mentoring Visit') }}</summary>
                                <div class="mt-2 text-sm text-gray-600 pl-4 space-y-2">
                                    <p class="font-medium">{{ __('Steps:') }}</p>
                                    <ol class="list-decimal list-inside space-y-1 ml-2">
                                        <li>{{ __('Go to "Mentoring Log" page') }}</li>
                                        <li>{{ __('Click "Log Visit" button') }}</li>
                                        <li>{{ __('Fill in visit details:') }}
                                            <ul class="list-disc list-inside ml-4 mt-1">
                                                <li>{{ __('Visit date') }}</li>
                                                <li>{{ __('Select school (only assigned schools shown)') }}</li>
                                                <li>{{ __('Select teacher') }}</li>
                                                <li>{{ __('Observation notes') }}</li>
                                                <li>{{ __('Action plan') }}</li>
                                                <li>{{ __('Follow-up requirements') }}</li>
                                            </ul>
                                        </li>
                                        <li>{{ __('Complete questionnaire sections') }}</li>
                                        <li>{{ __('Upload photos (optional)') }}</li>
                                        <li>{{ __('Submit visit log') }}</li>
                                    </ol>
                                </div>
                            </details>
                        </div>
                    </div>
                </div>

                <!-- Reports -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg" id="reports">
                    <div class="p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            {{ __('Reports') }}
                        </h4>
                        <div class="space-y-4">
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('Available Reports') }}</summary>
                                <div class="mt-2 text-sm text-gray-600 pl-4 space-y-2">
                                    <div class="space-y-3">
                                        <div>
                                            <p class="font-medium">{{ __('Student Performance Report:') }}</p>
                                            <ul class="list-disc list-inside ml-4 mt-1">
                                                <li>{{ __('Individual student progress') }}</li>
                                                <li>{{ __('Accessible by all roles') }}</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <p class="font-medium">{{ __('Progress Tracking Report:') }}</p>
                                            <ul class="list-disc list-inside ml-4 mt-1">
                                                <li>{{ __('Baseline to endline improvement') }}</li>
                                                <li>{{ __('Accessible by all roles') }}</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <p class="font-medium">{{ __('School Comparison Report:') }}</p>
                                            <ul class="list-disc list-inside ml-4 mt-1">
                                                <li>{{ __('Performance across schools') }}</li>
                                                <li>{{ __('Admin and Mentors only') }}</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <p class="font-medium">{{ __('Mentoring Impact Report:') }}</p>
                                            <ul class="list-disc list-inside ml-4 mt-1">
                                                <li>{{ __('Correlation between visits and improvement') }}</li>
                                                <li>{{ __('Admin and Mentors only') }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </details>
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('Generate and Export Reports') }}</summary>
                                <div class="mt-2 text-sm text-gray-600 pl-4 space-y-2">
                                    <p class="font-medium">{{ __('Steps:') }}</p>
                                    <ol class="list-decimal list-inside space-y-1 ml-2">
                                        <li>{{ __('Go to "Reports" page') }}</li>
                                        <li>{{ __('Select report type') }}</li>
                                        <li>{{ __('Apply filters:') }}
                                            <ul class="list-disc list-inside ml-4 mt-1">
                                                <li>{{ __('Date range') }}</li>
                                                <li>{{ __('School') }}</li>
                                                <li>{{ __('Grade (4 or 5)') }}</li>
                                                <li>{{ __('Subject') }}</li>
                                                <li>{{ __('Assessment cycle') }}</li>
                                            </ul>
                                        </li>
                                        <li>{{ __('Click "Generate Report"') }}</li>
                                        <li>{{ __('To export: Click "Export" button') }}</li>
                                    </ol>
                                </div>
                            </details>
                        </div>
                    </div>
                </div>

                <!-- Bulk Import -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg" id="bulk-import">
                    <div class="p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            {{ __('Bulk Import') }}
                        </h4>
                        <div class="space-y-4">
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('Import Students') }}</summary>
                                <div class="mt-2 text-sm text-gray-600 pl-4 space-y-2">
                                    <p class="font-medium">{{ __('Steps:') }}</p>
                                    <ol class="list-decimal list-inside space-y-1 ml-2">
                                        <li>{{ __('Go to "Students" page') }}</li>
                                        <li>{{ __('Click "Import Students" button') }}</li>
                                        <li>{{ __('Download the CSV template') }}</li>
                                        <li>{{ __('Fill in the template with:') }}
                                            <ul class="list-disc list-inside ml-4 mt-1">
                                                <li>{{ __('Student ID') }}</li>
                                                <li>{{ __('Name') }}</li>
                                                <li>{{ __('Grade (4 or 5)') }}</li>
                                                <li>{{ __('Gender') }}</li>
                                                <li>{{ __('Date of birth') }}</li>
                                                <li>{{ __('School') }}</li>
                                                <li>{{ __('Class') }}</li>
                                            </ul>
                                        </li>
                                        <li>{{ __('Save as CSV file') }}</li>
                                        <li>{{ __('Upload the file') }}</li>
                                        <li>{{ __('Review import results') }}</li>
                                    </ol>
                                </div>
                            </details>
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('Import Users') }}</summary>
                                <div class="mt-2 text-sm text-gray-600 pl-4 space-y-2">
                                    <p class="font-medium">{{ __('Steps:') }} <span class="text-xs text-gray-500">({{ __('Admin only') }})</span></p>
                                    <ol class="list-decimal list-inside space-y-1 ml-2">
                                        <li>{{ __('Go to "Users" page') }}</li>
                                        <li>{{ __('Click "Import Users" button') }}</li>
                                        <li>{{ __('Download the CSV template') }}</li>
                                        <li>{{ __('Fill in user data:') }}
                                            <ul class="list-disc list-inside ml-4 mt-1">
                                                <li>{{ __('Name') }}</li>
                                                <li>{{ __('Email') }}</li>
                                                <li>{{ __('Password') }}</li>
                                                <li>{{ __('Role (admin/teacher/mentor/viewer)') }}</li>
                                                <li>{{ __('School') }}</li>
                                                <li>{{ __('Phone number') }}</li>
                                            </ul>
                                        </li>
                                        <li>{{ __('Upload the completed CSV') }}</li>
                                        <li>{{ __('Review import results') }}</li>
                                    </ol>
                                </div>
                            </details>
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('Import Schools') }}</summary>
                                <div class="mt-2 text-sm text-gray-600 pl-4 space-y-2">
                                    <p class="font-medium">{{ __('Steps:') }} <span class="text-xs text-gray-500">({{ __('Admin only') }})</span></p>
                                    <ol class="list-decimal list-inside space-y-1 ml-2">
                                        <li>{{ __('Go to "Schools" page') }}</li>
                                        <li>{{ __('Click "Import Schools" button') }}</li>
                                        <li>{{ __('Download the CSV template') }}</li>
                                        <li>{{ __('Fill in school data:') }}
                                            <ul class="list-disc list-inside ml-4 mt-1">
                                                <li>{{ __('School name') }}</li>
                                                <li>{{ __('Province') }}</li>
                                                <li>{{ __('District') }}</li>
                                                <li>{{ __('Commune') }}</li>
                                            </ul>
                                        </li>
                                        <li>{{ __('Upload the completed CSV') }}</li>
                                        <li>{{ __('Review import results') }}</li>
                                    </ol>
                                </div>
                            </details>
                        </div>
                    </div>
                </div>

                <!-- Resources -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg" id="resources">
                    <div class="p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            {{ __('Resource Management') }}
                        </h4>
                        <div class="space-y-4">
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('Add Resources') }} <span class="text-xs text-gray-500">({{ __('Admin only') }})</span></summary>
                                <div class="mt-2 text-sm text-gray-600 pl-4 space-y-2">
                                    <p class="font-medium">{{ __('Steps:') }}</p>
                                    <ol class="list-decimal list-inside space-y-1 ml-2">
                                        <li>{{ __('Go to "Manage Resources" (Admin menu)') }}</li>
                                        <li>{{ __('Click "Add Resource" button') }}</li>
                                        <li>{{ __('Fill in resource details:') }}
                                            <ul class="list-disc list-inside ml-4 mt-1">
                                                <li>{{ __('Title') }}</li>
                                                <li>{{ __('Description') }}</li>
                                                <li>{{ __('Type (YouTube/Excel/PDF)') }}</li>
                                                <li>{{ __('For YouTube: Enter URL') }}</li>
                                                <li>{{ __('For Files: Upload file') }}</li>
                                                <li>{{ __('Set visibility (Public/Private)') }}</li>
                                            </ul>
                                        </li>
                                        <li>{{ __('Save the resource') }}</li>
                                    </ol>
                                </div>
                            </details>
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('Access Resources') }}</summary>
                                <div class="mt-2 text-sm text-gray-600 pl-4 space-y-2">
                                    <p class="font-medium">{{ __('Steps:') }}</p>
                                    <ol class="list-decimal list-inside space-y-1 ml-2">
                                        <li>{{ __('Go to "Resources" page') }}</li>
                                        <li>{{ __('Browse available resources') }}</li>
                                        <li>{{ __('Use type filter to find specific resources') }}</li>
                                        <li>{{ __('Click to view or download') }}</li>
                                    </ol>
                                </div>
                            </details>
                        </div>
                    </div>
                </div>

                <!-- Common Workflows -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg" id="workflows">
                    <div class="p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            {{ __('Common Workflows') }}
                        </h4>
                        <div class="space-y-4">
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('Setting Up a New School Year') }}</summary>
                                <div class="mt-2 text-sm text-gray-600 pl-4 space-y-2">
                                    <p class="font-medium">{{ __('Administrator Tasks:') }}</p>
                                    <ol class="list-decimal list-inside space-y-1 ml-2">
                                        <li>{{ __('Import or create schools') }}</li>
                                        <li>{{ __('Import or create users (teachers and mentors)') }}</li>
                                        <li>{{ __('Assign schools to mentors') }}</li>
                                        <li>{{ __('Import students') }}</li>
                                    </ol>
                                    <p class="font-medium mt-3">{{ __('Teacher Tasks:') }}</p>
                                    <ol class="list-decimal list-inside space-y-1 ml-2">
                                        <li>{{ __('Verify student lists') }}</li>
                                        <li>{{ __('Conduct baseline assessments for all students') }}</li>
                                    </ol>
                                    <p class="font-medium mt-3">{{ __('Mid-Year Tasks:') }}</p>
                                    <ol class="list-decimal list-inside space-y-1 ml-2">
                                        <li>{{ __('Select students for midline assessments') }}</li>
                                        <li>{{ __('Conduct midline assessments') }}</li>
                                    </ol>
                                    <p class="font-medium mt-3">{{ __('End-Year Tasks:') }}</p>
                                    <ol class="list-decimal list-inside space-y-1 ml-2">
                                        <li>{{ __('Select students for endline assessments') }}</li>
                                        <li>{{ __('Conduct endline assessments') }}</li>
                                    </ol>
                                </div>
                            </details>
                            <details class="group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-900 hover:text-indigo-600">{{ __('End of Year Process') }}</summary>
                                <div class="mt-2 text-sm text-gray-600 pl-4 space-y-2">
                                    <p class="font-medium">{{ __('Steps:') }}</p>
                                    <ol class="list-decimal list-inside space-y-1 ml-2">
                                        <li>{{ __('Export all assessment data') }}</li>
                                        <li>{{ __('Generate final reports:') }}
                                            <ul class="list-disc list-inside ml-4 mt-1">
                                                <li>{{ __('Student Performance Report') }}</li>
                                                <li>{{ __('Progress Tracking Report') }}</li>
                                                <li>{{ __('School Comparison Report') }}</li>
                                                <li>{{ __('Mentoring Impact Report') }}</li>
                                            </ul>
                                        </li>
                                        <li>{{ __('Archive the data') }}</li>
                                        <li>{{ __('Prepare for next year') }}</li>
                                    </ol>
                                </div>
                            </details>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Tips -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                <h4 class="font-medium text-blue-900 mb-2">{{ __('Quick Tips') }}</h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• {{ __('For new mentors: First get schools assigned by an administrator before starting any activities') }}</li>
                    <li>• {{ __('For assessment planning: Select students for midline/endline before the assessment period begins') }}</li>
                    <li>• {{ __('Regular backups: Export data regularly using the export features') }}</li>
                    <li>• {{ __('Filter usage: Clear filters by submitting empty filter forms to see all data') }}</li>
                    <li>• {{ __('Language: Switch between Khmer and English using the language selector in navigation') }}</li>
                </ul>
            </div>

            <!-- Contact Support -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mt-6">
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
                            <p class="text-sm text-gray-600">chhinhs@gmail.com</p>
                        </div>
                        <div class="text-center">
                            <div class="mx-auto h-12 w-12 text-indigo-600 mb-3">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                </svg>
                            </div>
                            <h5 class="font-medium text-gray-900 mb-1">{{ __('Telegram Support') }}</h5>
                            <a href="https://t.me/chhinh_sovath" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800">@chhinh_sovath</a>
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