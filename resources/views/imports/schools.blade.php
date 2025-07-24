@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-xl font-semibold text-gray-900">{{ __('Import Schools') }}</h4>
                    <p class="text-gray-600 mt-1">Upload a CSV file to import multiple schools at once</p>
                </div>
                <a href="{{ route('imports.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    ← Back to Import Overview
                </a>
            </div>
        </div>

        <div class="px-6 py-6">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Import Form -->
                <div>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-school text-blue-600 text-2xl mr-3"></i>
                            <h5 class="text-lg font-semibold text-blue-900">School Import</h5>
                        </div>
                        
                        <form action="{{ route('imports.schools') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-6">
                                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                                    Select CSV File
                                </label>
                                <input type="file" 
                                       id="file"
                                       name="file" 
                                       accept=".csv" 
                                       required
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                <p class="text-xs text-gray-500 mt-2">
                                    Required columns: <code class="bg-gray-100 px-1 rounded">school_name</code>, 
                                    <code class="bg-gray-100 px-1 rounded">school_code</code>, 
                                    <code class="bg-gray-100 px-1 rounded">province</code>, 
                                    <code class="bg-gray-100 px-1 rounded">district</code>, 
                                    <code class="bg-gray-100 px-1 rounded">cluster</code>
                                </p>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <a href="{{ route('imports.template', 'schools') }}" 
                                   class="export-btn text-blue-600 hover:text-blue-800 text-sm font-medium border border-blue-300 px-4 py-2 rounded hover:bg-blue-50 transition">
                                    <i class="fas fa-download mr-2"></i>Download Template
                                </a>
                                <button type="submit" 
                                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition font-medium">
                                    <i class="fas fa-upload mr-2"></i>Import Schools
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Guidelines -->
                <div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <h5 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-info-circle text-gray-600 mr-2"></i>Import Guidelines
                        </h5>
                        
                        <div class="space-y-4">
                            <div>
                                <h6 class="font-medium text-gray-800 mb-2">File Requirements</h6>
                                <ul class="space-y-1 text-sm text-gray-600">
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        CSV format only
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        UTF-8 encoding
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        First row as headers
                                    </li>
                                </ul>
                            </div>

                            <div>
                                <h6 class="font-medium text-gray-800 mb-2">Column Definitions</h6>
                                <div class="space-y-2 text-sm">
                                    <div class="bg-white p-2 rounded border">
                                        <code class="text-blue-600">school_name</code> - Full name of the school
                                    </div>
                                    <div class="bg-white p-2 rounded border">
                                        <code class="text-blue-600">school_code</code> - Unique identifier (required)
                                    </div>
                                    <div class="bg-white p-2 rounded border">
                                        <code class="text-blue-600">province</code> - Province/state location
                                    </div>
                                    <div class="bg-white p-2 rounded border">
                                        <code class="text-blue-600">district</code> - District/county location
                                    </div>
                                    <div class="bg-white p-2 rounded border">
                                        <code class="text-blue-600">cluster</code> - School cluster (optional)
                                    </div>
                                </div>
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 rounded p-3">
                                <p class="text-sm text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>Note:</strong> School codes must be unique. Duplicate codes will be updated with new information.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection