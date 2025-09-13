@extends('layouts.app')

@include('components.translations')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <div class="bg-blue-600 rounded-lg shadow-lg p-4 sm:p-6 mb-4 sm:mb-6">
            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold mb-1 sm:mb-2 text-white">{{ trans_km('Welcome Back') }}, {{ $user->name }}!</h1>
            <p class="text-base sm:text-lg text-white">{{ trans_km('Teacher Dashboard') }}</p>
        </div>

        @if(!$profileComplete)
            <!-- Profile Incomplete Alert -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            {{ trans_km('Please complete your profile setup to access all features') }}
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('teacher.profile.setup') }}" class="bg-yellow-400 hover:bg-yellow-500 text-yellow-800 font-bold py-2 px-4 rounded">
                                {{ trans_km('Complete Profile Setup') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <!-- Profile Management -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-xs sm:text-sm font-medium text-gray-600 truncate">{{ trans_km('Profile') }}</dt>
                                <dd class="text-sm sm:text-base md:text-lg font-bold text-gray-900">{{ trans_km('Edit Profile') }}</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-3 sm:mt-4">
                        <a href="{{ route('teacher.profile.setup') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-3 sm:px-4 rounded text-sm sm:text-base text-center block">
                            {{ trans_km('Edit Profile') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Student Management -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-xs sm:text-sm font-medium text-gray-600 truncate">{{ trans_km('Students') }}</dt>
                                <dd class="text-sm sm:text-base md:text-lg font-bold text-gray-900">{{ $studentCount }} {{ trans_km('students') }}</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-3 sm:mt-4">
                        <a href="{{ route('teacher.students.manage') }}" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-3 sm:px-4 rounded text-sm sm:text-base text-center block">
                            {{ trans_km('Manage Students') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Assessments -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-xs sm:text-sm font-medium text-gray-600 truncate">{{ trans_km('Assessments') }}</dt>
                                <dd class="text-sm sm:text-base md:text-lg font-bold text-gray-900">{{ trans_km('Conduct Tests') }}</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-3 sm:mt-4">
                        <a href="{{ route('assessments.create') }}" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-3 sm:px-4 rounded text-sm sm:text-base text-center block">
                            {{ trans_km('Start Assessment') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Student List -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-xs sm:text-sm font-medium text-gray-600 truncate">{{ trans_km('Student List') }}</dt>
                                <dd class="text-sm sm:text-base md:text-lg font-bold text-gray-900">{{ trans_km('View All') }}</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-3 sm:mt-4">
                        <a href="{{ route('students.index') }}" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-3 sm:px-4 rounded text-sm sm:text-base text-center block">
                            {{ trans_km('View Students') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if($profileComplete)
        <!-- Assessment Statistics -->
        <div class="bg-white overflow-hidden shadow rounded-lg mb-6 sm:mb-8">
            <div class="p-4 sm:p-6">
                <h3 class="text-base sm:text-lg leading-6 font-medium text-gray-900 mb-3 sm:mb-4">{{ trans_km('Assessment Progress') }}</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
                    @foreach(['baseline', 'midline', 'endline'] as $type)
                    <div class="border rounded-lg p-3 sm:p-4">
                        <h4 class="text-sm sm:text-base font-semibold text-gray-800 mb-2 sm:mb-3">
                            {{ trans_km(ucfirst($type)) }} {{ trans_km('Assessment') }}
                        </h4>
                        <div class="space-y-1 sm:space-y-2">
                            <div class="flex justify-between">
                                <span class="text-xs sm:text-sm text-gray-600">{{ trans_km('Completed') }}:</span>
                                <span class="text-xs sm:text-sm font-medium text-green-600">{{ $stats[$type]['completed'] ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs sm:text-sm text-gray-600">{{ trans_km('In Progress') }}:</span>
                                <span class="text-xs sm:text-sm font-medium text-yellow-600">{{ $stats[$type]['in_progress'] ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs sm:text-sm text-gray-600">{{ trans_km('Not Started') }}:</span>
                                <span class="text-xs sm:text-sm font-medium text-gray-600">{{ $stats[$type]['not_started'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Help and Support -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 sm:p-6">
            <div class="flex">
                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-blue-400 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="text-xs sm:text-sm text-blue-800">
                    <p class="font-semibold mb-1">{{ trans_km('Need Help?') }}</p>
                    <p class="leading-relaxed">{{ trans_km('You can always update your profile, add new students, or conduct assessments from this dashboard') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection