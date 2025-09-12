@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-xl font-semibold text-gray-900">{{ __('Import Students') }}</h4>
                    <p class="text-gray-600 mt-1">ផ្ទុកឯកសារ CSV ដើម្បីនាំចូលសិស្សច្រើននាក់ក្នុងពេលតែម្តង</p>
                </div>
                <a href="{{ route('imports.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    ← ត្រឡប់ទៅទិដ្ឋភាពនាំចូល
                </a>
            </div>
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

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Import Form -->
                <div>
                    <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-user-graduate text-cyan-600 text-2xl mr-3"></i>
                            <h5 class="text-lg font-semibold text-cyan-900">នាំចូលសិស្ស</h5>
                        </div>
                        
                        <form action="{{ route('imports.students') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-6">
                                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                                    ជ្រើសរើសឯកសារ CSV
                                </label>
                                <input type="file" 
                                       id="file"
                                       name="file" 
                                       accept=".csv" 
                                       required
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100 border border-gray-300 rounded-md">
                                <p class="text-xs text-gray-500 mt-2">
                                    Required columns: <code class="bg-gray-100 px-1 rounded">name</code>, 
                                    <code class="bg-gray-100 px-1 rounded">sex</code>, 
                                    <code class="bg-gray-100 px-1 rounded">age</code>, 
                                    <code class="bg-gray-100 px-1 rounded">class</code>, 
                                    <code class="bg-gray-100 px-1 rounded">school_name</code>
                                </p>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <a href="{{ route('imports.template', 'students') }}" 
                                   class="export-btn text-cyan-600 hover:text-cyan-800 text-sm font-medium border border-cyan-300 px-4 py-2 rounded hover:bg-cyan-50 transition">
                                    <i class="fas fa-download mr-2"></i>Download Template
                                </a>
                                <button type="submit" 
                                        class="bg-cyan-600 text-white px-6 py-2 rounded hover:bg-cyan-700 transition font-medium">
                                    <i class="fas fa-upload mr-2"></i>Import Students
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
                                        <code class="text-cyan-600">name</code> - Full name of the student
                                    </div>
                                    <div class="bg-white p-2 rounded border">
                                        <code class="text-cyan-600">sex</code> - Gender: male or female
                                    </div>
                                    <div class="bg-white p-2 rounded border">
                                        <code class="text-cyan-600">age</code> - Age in years (3-20)
                                    </div>
                                    <div class="bg-white p-2 rounded border">
                                        <code class="text-cyan-600">class</code> - Class or grade level
                                    </div>
                                    <div class="bg-white p-2 rounded border">
                                        <code class="text-cyan-600">school_name</code> - Exact school name
                                    </div>
                                </div>
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 rounded p-3">
                                <p class="text-sm text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>Prerequisites:</strong> Schools must be imported first. The school_name must exactly match existing school names.
                                </p>
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded p-3">
                                <p class="text-sm text-blue-800">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Validation:</strong> Age must be between 3-20 years. Gender must be either "male" or "female".
                                </p>
                            </div>

                            <div>
                                <h6 class="font-medium text-gray-800 mb-2">Import Order</h6>
                                <ol class="space-y-1 text-sm text-gray-600">
                                    <li class="flex items-center">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full mr-2">1</span>
                                        Schools first
                                    </li>
                                    <li class="flex items-center">
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full mr-2">2</span>
                                        Teachers & Mentors
                                    </li>
                                    <li class="flex items-center">
                                        <span class="bg-cyan-100 text-cyan-800 text-xs font-medium px-2 py-1 rounded-full mr-2">3</span>
                                        Students last
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection