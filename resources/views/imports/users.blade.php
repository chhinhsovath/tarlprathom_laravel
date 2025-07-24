@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-xl font-semibold text-gray-900">{{ __('Import Users') }}</h4>
                    <p class="text-gray-600 mt-1">Upload CSV files to import teachers and mentors</p>
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
                <!-- Import Forms -->
                <div class="space-y-6">
                    <!-- Teachers Import -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-chalkboard-teacher text-green-600 text-2xl mr-3"></i>
                            <h5 class="text-lg font-semibold text-green-900">Import Teachers</h5>
                        </div>
                        
                        <form action="{{ route('imports.users') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="user_type" value="teacher">
                            
                            <div class="mb-4">
                                <label for="teacher_file" class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Teacher CSV File
                                </label>
                                <input type="file" 
                                       id="teacher_file"
                                       name="file" 
                                       accept=".csv" 
                                       required
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-gray-300 rounded-md">
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <a href="{{ route('imports.template', 'teachers') }}" 
                                   class="export-btn text-green-600 hover:text-green-800 text-sm font-medium border border-green-300 px-3 py-2 rounded hover:bg-green-50 transition">
                                    <i class="fas fa-download mr-1"></i>Template
                                </a>
                                <button type="submit" 
                                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition font-medium">
                                    <i class="fas fa-upload mr-2"></i>Import Teachers
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Mentors Import -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-user-tie text-yellow-600 text-2xl mr-3"></i>
                            <h5 class="text-lg font-semibold text-yellow-900">Import Mentors</h5>
                        </div>
                        
                        <form action="{{ route('imports.users') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="user_type" value="mentor">
                            
                            <div class="mb-4">
                                <label for="mentor_file" class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Mentor CSV File
                                </label>
                                <input type="file" 
                                       id="mentor_file"
                                       name="file" 
                                       accept=".csv" 
                                       required
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100 border border-gray-300 rounded-md">
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <a href="{{ route('imports.template', 'mentors') }}" 
                                   class="export-btn text-yellow-600 hover:text-yellow-800 text-sm font-medium border border-yellow-300 px-3 py-2 rounded hover:bg-yellow-50 transition">
                                    <i class="fas fa-download mr-1"></i>Template
                                </a>
                                <button type="submit" 
                                        class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition font-medium">
                                    <i class="fas fa-upload mr-2"></i>Import Mentors
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
                                <h6 class="font-medium text-gray-800 mb-2">Required Columns</h6>
                                <div class="space-y-2 text-sm">
                                    <div class="bg-white p-2 rounded border">
                                        <code class="text-blue-600">name</code> - Full name of the user
                                    </div>
                                    <div class="bg-white p-2 rounded border">
                                        <code class="text-blue-600">email</code> - Email address (must be unique)
                                    </div>
                                    <div class="bg-white p-2 rounded border">
                                        <code class="text-blue-600">school_name</code> - Exact school name
                                    </div>
                                    <div class="bg-white p-2 rounded border">
                                        <code class="text-blue-600">phone</code> - Phone number (optional)
                                    </div>
                                    <div class="bg-white p-2 rounded border">
                                        <code class="text-blue-600">sex</code> - Gender: male or female (optional)
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded p-3">
                                <p class="text-sm text-blue-800">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Default Password:</strong> All imported users will have the password "password123" and should change it on first login.
                                </p>
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 rounded p-3">
                                <p class="text-sm text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>School Requirement:</strong> Schools must be imported first. The school_name must exactly match existing school names.
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