@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-xl font-semibold text-gray-900">{{ __('Bulk Data Import') }}</h4>
            <p class="text-gray-600 mt-1">{{ __('Import schools, teachers, mentors, and students from CSV files') }}</p>
        </div>

        <div class="px-6 py-6">

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Schools Import -->
                <div class="bg-white border border-blue-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="bg-blue-600 text-white px-6 py-4 rounded-t-lg">
                        <h5 class="text-lg font-semibold flex items-center">
                            <i class="fas fa-school mr-2"></i>
                            {{ __('Import Schools') }}
                        </h5>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">{{ __('Upload CSV files to import school data including location and administrative information.') }}</p>
                        <div class="space-y-3">
                            <div class="text-sm text-gray-500">
                                <strong>{{ __('Columns:') }}</strong> {{ __('school_name, school_code, province, district, cluster') }}
                            </div>
                            <div class="flex justify-between items-center">
                                <a href="{{ route('imports.template', 'schools') }}" 
                                   class="export-btn text-blue-600 hover:text-blue-800 text-sm font-medium border border-blue-300 px-3 py-2 rounded hover:bg-blue-50 transition">
                                    <i class="fas fa-download mr-1"></i>{{ __('Template') }}
                                </a>
                                <a href="{{ route('imports.schools.show') }}" 
                                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition font-medium">
                                    <i class="fas fa-arrow-right mr-2"></i>{{ __('Import Schools') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Import -->
                <div class="bg-white border border-green-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="bg-green-600 text-white px-6 py-4 rounded-t-lg">
                        <h5 class="text-lg font-semibold flex items-center">
                            <i class="fas fa-users mr-2"></i>
                            {{ __('Import Users') }}
                        </h5>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">{{ __('Upload CSV files to import teachers and mentors with their school assignments.') }}</p>
                        <div class="space-y-3">
                            <div class="text-sm text-gray-500">
                                <strong>{{ __('Columns:') }}</strong> {{ __('name, email, school_name, phone, sex') }}
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="space-x-2">
                                    <a href="{{ route('imports.template', 'teachers') }}" 
                                       class="export-btn text-green-600 hover:text-green-800 text-xs font-medium border border-green-300 px-2 py-1 rounded hover:bg-green-50 transition">
                                        {{ __('Teachers') }}
                                    </a>
                                    <a href="{{ route('imports.template', 'mentors') }}" 
                                       class="export-btn text-yellow-600 hover:text-yellow-800 text-xs font-medium border border-yellow-300 px-2 py-1 rounded hover:bg-yellow-50 transition">
                                        {{ __('Mentors') }}
                                    </a>
                                </div>
                                <a href="{{ route('imports.users.show') }}" 
                                   class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition font-medium">
                                    <i class="fas fa-arrow-right mr-2"></i>{{ __('Import Users') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Students Import -->
                <div class="bg-white border border-cyan-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="bg-cyan-600 text-white px-6 py-4 rounded-t-lg">
                        <h5 class="text-lg font-semibold flex items-center">
                            <i class="fas fa-user-graduate mr-2"></i>
                            {{ __('Import Students') }}
                        </h5>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">{{ __('Upload CSV files to import student information including demographics and school enrollment.') }}</p>
                        <div class="space-y-3">
                            <div class="text-sm text-gray-500">
                                <strong>{{ __('Columns:') }}</strong> {{ __('name, sex, age, class, school_name') }}
                            </div>
                            <div class="flex justify-between items-center">
                                <a href="{{ route('imports.template', 'students') }}" 
                                   class="export-btn text-cyan-600 hover:text-cyan-800 text-sm font-medium border border-cyan-300 px-3 py-2 rounded hover:bg-cyan-50 transition">
                                    <i class="fas fa-download mr-1"></i>{{ __('Template') }}
                                </a>
                                <a href="{{ route('imports.students.show') }}" 
                                   class="bg-cyan-600 text-white px-4 py-2 rounded hover:bg-cyan-700 transition font-medium">
                                    <i class="fas fa-arrow-right mr-2"></i>{{ __('Import Students') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="bg-gray-600 text-white px-6 py-4 rounded-t-lg">
                        <h5 class="text-lg font-semibold flex items-center">
                            <i class="fas fa-link mr-2"></i>
                            {{ __('Quick Actions') }}
                        </h5>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">{{ __('Access commonly used import tools and resources.') }}</p>
                        <div class="space-y-3">
                            <a href="{{ route('imports.schools.show') }}" 
                               class="block w-full text-left px-3 py-2 text-blue-600 hover:bg-blue-50 rounded transition">
                                <i class="fas fa-school w-4 mr-2"></i>{{ __('Schools Import Tool') }}
                            </a>
                            <a href="{{ route('imports.users.show') }}" 
                               class="block w-full text-left px-3 py-2 text-green-600 hover:bg-green-50 rounded transition">
                                <i class="fas fa-users w-4 mr-2"></i>{{ __('Users Import Tool') }}
                            </a>
                            <a href="{{ route('imports.students.show') }}" 
                               class="block w-full text-left px-3 py-2 text-cyan-600 hover:bg-cyan-50 rounded transition">
                                <i class="fas fa-user-graduate w-4 mr-2"></i>{{ __('Students Import Tool') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Language Context Section -->
            <div class="mt-8">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="bg-gray-600 text-white px-6 py-4 rounded-t-lg">
                        <h5 class="text-lg font-semibold">{{ __('Language Context Management') }}</h5>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-6">{{ __('Manage localization and language settings for the application.') }}</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h6 class="text-base font-medium text-gray-900 mb-3">{{ __('Current Language Settings') }}</h6>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded">
                                        <span class="text-gray-700">{{ __('Default Language') }}</span>
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ app()->getLocale() }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded">
                                        <span class="text-gray-700">{{ __('Available Languages') }}</span>
                                        <span class="bg-cyan-100 text-cyan-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ __('English, Khmer') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h6 class="text-base font-medium text-gray-900 mb-3">{{ __('Language Actions') }}</h6>
                                <div class="space-y-2">
                                    <a href="{{ route('settings.index') }}" class="block w-full text-center bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-50 transition">
                                        {{ __('Manage Language Settings') }}
                                    </a>
                                    <button type="button" class="w-full bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-50 transition" onclick="exportTranslations()">
                                        {{ __('Export Translation Files') }}
                                    </button>
                                    <button type="button" class="w-full bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-50 transition" onclick="importTranslations()">
                                        {{ __('Import Translation Files') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Import Guidelines -->
            <div class="mt-8">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h5 class="text-lg font-semibold text-gray-900">{{ __('Import Guidelines') }}</h5>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h6 class="text-base font-medium text-gray-900 mb-3">{{ __('File Format Requirements') }}</h6>
                                <ul class="space-y-2 text-gray-700">
                                    <li class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        {{ __('Files must be in CSV format') }}
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        {{ __('First row must contain column headers') }}
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        {{ __('Use UTF-8 encoding for special characters') }}
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        {{ __('Date format: YYYY-MM-DD') }}
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h6 class="text-base font-medium text-gray-900 mb-3">{{ __('Import Order') }}</h6>
                                <ol class="space-y-2 text-gray-700">
                                    <li class="flex items-center">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full mr-3">1</span>
                                        {{ __('Import Schools first') }}
                                    </li>
                                    <li class="flex items-center">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full mr-3">2</span>
                                        {{ __('Import Teachers and Mentors') }}
                                    </li>
                                    <li class="flex items-center">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full mr-3">3</span>
                                        {{ __('Import Students last') }}
                                    </li>
                                </ol>
                                <small class="text-gray-500 mt-2 block">{{ __('This order ensures all dependencies are met.') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportTranslations() {
    alert("{{ __('Translation export functionality will be implemented based on your localization needs.') }}");
}

function importTranslations() {
    alert("{{ __('Translation import functionality will be implemented based on your localization needs.') }}");
}
</script>
@endsection