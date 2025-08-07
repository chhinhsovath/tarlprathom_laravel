@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ trans_db('coordinator_dashboard') }}</h1>
                <p class="mt-1 text-sm text-gray-600">{{ trans_db('system_overview_and_data_management_center') }}</p>
            </div>
            <div>
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                    {{ $stats['system_languages']['current_name'] }}
                </span>
            </div>
        </div>

        <!-- Primary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border-2 border-blue-200 p-6">
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">{{ $stats['total_schools'] }}</div>
                    <h3 class="text-lg font-semibold text-blue-800">{{ trans_db('schools') }}</h3>
                    <p class="text-sm text-gray-600">{{ trans_db('total_registered') }}</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border-2 border-green-200 p-6">
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">{{ $stats['total_teachers'] }}</div>
                    <h3 class="text-lg font-semibold text-green-800">{{ trans_db('teachers') }}</h3>
                    <p class="text-sm text-gray-600">{{ trans_db('active_accounts') }}</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border-2 border-yellow-200 p-6">
                <div class="text-center">
                    <div class="text-4xl font-bold text-yellow-600 mb-2">{{ $stats['total_mentors'] }}</div>
                    <h3 class="text-lg font-semibold text-yellow-800">{{ trans_db('mentors') }}</h3>
                    <p class="text-sm text-gray-600">{{ trans_db('active_accounts') }}</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border-2 border-purple-200 p-6">
                <div class="text-center">
                    <div class="text-4xl font-bold text-purple-600 mb-2">{{ $stats['total_coordinators'] }}</div>
                    <h3 class="text-lg font-semibold text-purple-800">{{ trans_db('coordinators') }}</h3>
                    <p class="text-sm text-gray-600">{{ trans_db('active_accounts') }}</p>
                </div>
            </div>
        </div>

        <!-- Import Activity Statistics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">{{ trans_db('this_month_activity') }}</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6 text-center">
                        <div>
                            <div class="text-3xl font-bold text-blue-600">{{ $stats['import_stats']['schools_this_month'] }}</div>
                            <p class="text-sm text-gray-600 mt-1">{{ trans_db('schools_added') }}</p>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-green-600">{{ $stats['import_stats']['users_this_month'] }}</div>
                            <p class="text-sm text-gray-600 mt-1">{{ trans_db('users_added') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">{{ trans_db('today_activity') }}</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6 text-center">
                        <div>
                            <div class="text-3xl font-bold text-blue-600">{{ $stats['import_stats']['schools_today'] }}</div>
                            <p class="text-sm text-gray-600 mt-1">{{ trans_db('schools_added') }}</p>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-green-600">{{ $stats['import_stats']['users_today'] }}</div>
                            <p class="text-sm text-gray-600 mt-1">{{ trans_db('users_added') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Action Areas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Data Import Section -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-blue-500">
                <div class="bg-blue-500 text-white px-6 py-4">
                    <h3 class="text-xl font-bold flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        {{ trans_db('data_import_center') }}
                    </h3>
                </div>
                <div class="p-6">
                    <!-- Import Actions -->
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <a href="{{ route('imports.index') }}" class="group relative bg-blue-50 hover:bg-blue-100 rounded-lg p-6 text-center transition-colors duration-200">
                            <div class="text-blue-600 mb-3">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-900">{{ trans_db('schools_import') }}</span>
                        </a>
                        <a href="{{ route('imports.index') }}#teachers" class="group relative bg-green-50 hover:bg-green-100 rounded-lg p-6 text-center transition-colors duration-200">
                            <div class="text-green-600 mb-3">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-900">{{ trans_db('teachers_import') }}</span>
                        </a>
                        <a href="{{ route('imports.index') }}#mentors" class="group relative bg-yellow-50 hover:bg-yellow-100 rounded-lg p-6 text-center transition-colors duration-200">
                            <div class="text-yellow-600 mb-3">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-900">{{ trans_db('mentors_import') }}</span>
                        </a>
                        <a href="{{ route('imports.index') }}" class="group relative bg-purple-50 hover:bg-purple-100 rounded-lg p-6 text-center transition-colors duration-200">
                            <div class="text-purple-600 mb-3">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-900">{{ trans_db('templates') }}</span>
                        </a>
                    </div>

                    <!-- Quick Templates -->
                    <div class="border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">{{ trans_db('quick_download') }}</h4>
                        <div class="space-y-2">
                            <a href="{{ route('imports.template', 'schools') }}" class="flex items-center justify-between px-3 py-2 text-sm border border-gray-200 rounded-md hover:bg-gray-50 transition-colors">
                                <span class="text-gray-700">{{ trans_db('schools_template') }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                            </a>
                            <a href="{{ route('imports.template', 'teachers') }}" class="flex items-center justify-between px-3 py-2 text-sm border border-gray-200 rounded-md hover:bg-gray-50 transition-colors">
                                <span class="text-gray-700">{{ trans_db('teachers_template') }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                            </a>
                            <a href="{{ route('imports.template', 'mentors') }}" class="flex items-center justify-between px-3 py-2 text-sm border border-gray-200 rounded-md hover:bg-gray-50 transition-colors">
                                <span class="text-gray-700">{{ trans_db('mentors_template') }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Language Management Section -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-purple-500">
                <div class="bg-purple-600 text-white px-6 py-4" style="background-color: #7c3aed !important;">
                    <h3 class="text-xl font-bold flex items-center text-white" style="color: #ffffff !important;">
                        <svg class="w-6 h-6 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #ffffff !important;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ trans_db('language_center') }}
                    </h3>
                </div>
                <div class="p-6">
                    <!-- Language Status -->
                    <div class="text-center mb-6">
                        <div class="text-5xl font-extrabold text-purple-700 mb-3" style="color: #6b21a8 !important;">{{ count($stats['system_languages']['available']) }}</div>
                        <p class="text-lg font-bold text-gray-900 mb-2" style="color: #111827 !important;">{{ trans_db('available_languages') }}</p>
                        <div class="mt-4">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-purple-200 text-purple-900 border-2 border-purple-300" style="color: #581c87 !important; background-color: #e9d5ff !important;">
                                {{ trans_db('current') }}: {{ $stats['system_languages']['current_name'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Language Actions -->
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <a href="{{ route('localization.index') }}" class="group relative bg-purple-50 hover:bg-purple-100 rounded-lg p-6 text-center transition-colors duration-200">
                            <div class="text-purple-600 mb-3">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <span class="font-extrabold text-black text-lg" style="color: #000000 !important;">{{ trans_db('settings') }}</span>
                        </a>
                        <button type="button" onclick="showLanguageModal()" class="group relative bg-purple-50 hover:bg-purple-100 rounded-lg p-6 text-center transition-colors duration-200 border-2 border-purple-300">
                            <div class="text-purple-700 mb-3">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </div>
                            <span class="font-extrabold text-black text-lg" style="color: #000000 !important;">{{ trans_db('switch') }}</span>
                        </button>
                    </div>

                    <!-- Translation Tools -->
                    <div class="border-t-2 border-gray-300 pt-4">
                        <h4 class="text-lg font-extrabold text-black mb-3" style="color: #000000 !important;">{{ trans_db('translation_tools') }}</h4>
                        <div class="space-y-2">
                            <a href="{{ route('localization.edit') }}" class="flex items-center justify-between px-4 py-3 text-base font-bold border-2 border-purple-300 rounded-lg hover:bg-purple-50 transition-colors bg-white">
                                <span class="text-black font-extrabold" style="color: #000000 !important;">{{ trans_db('open_translation_editor') }}</span>
                                <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Overview -->
        <div class="bg-gray-50 rounded-lg p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">{{ trans_db('total_system_users') }}</h3>
                    <div class="mt-2 text-4xl font-bold text-gray-900">{{ $stats['total_users'] }}</div>
                    <p class="mt-1 text-sm text-gray-600">{{ trans_db('teachers_mentors_coordinators') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">{{ trans_db('current_language') }}</h3>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ strtoupper($stats['system_languages']['current']) }}</div>
                    <p class="mt-1 text-sm text-gray-600">{{ $stats['system_languages']['current_name'] }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">{{ trans_db('your_role') }}</h3>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ trans_db('coordinator') }}</div>
                    <p class="mt-1 text-sm text-gray-600">{{ trans_db('data_management_access') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Language Switch Modal -->
<div id="languageModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">{{ trans_db('switch_language') }}</h3>
                <div class="space-y-2">
                    @foreach($stats['system_languages']['available'] as $code => $name)
                        @if($code === $stats['system_languages']['current'])
                            <button class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600" disabled>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ $name }} ({{ trans_db('current') }})
                            </button>
                        @else
                            <a href="{{ route('localization.set', $code) }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                {{ $name }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeLanguageModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ trans_db('close') }}
                </button>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
function showLanguageModal() {
    document.getElementById('languageModal').classList.remove('hidden');
}

function closeLanguageModal() {
    document.getElementById('languageModal').classList.add('hidden');
}


// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const languageModal = document.getElementById('languageModal');
    
    if (event.target === languageModal) {
        closeLanguageModal();
    }
});
</script>
@endpush
@endsection