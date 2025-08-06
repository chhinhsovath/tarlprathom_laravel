@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-xl font-semibold text-gray-900">{{ __('Import Schools') }}</h4>
                    <p class="text-gray-600 mt-1">ផ្ទុកឯកសារ CSV ដើម្បីនាំចូលសាលាច្រើនក្នុងពេលតែម្តង</p>
                </div>
                <a href="{{ route('imports.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    ← ត្រឡប់ទៅទិដ្ឋភាពនាំចូល
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
                            <h5 class="text-lg font-semibold text-blue-900">នាំចូលសាលា</h5>
                        </div>
                        
                        <form action="{{ route('imports.schools') }}" method="POST" enctype="multipart/form-data">
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
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                <p class="text-xs text-gray-500 mt-2">
                                    ជួរដេញដែលត្រូវការ: <code class="bg-gray-100 px-1 rounded">school_name</code>, 
                                    <code class="bg-gray-100 px-1 rounded">school_code</code>, 
                                    <code class="bg-gray-100 px-1 rounded">province</code>, 
                                    <code class="bg-gray-100 px-1 rounded">district</code>, 
                                    <code class="bg-gray-100 px-1 rounded">cluster</code>
                                </p>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <a href="{{ route('imports.template', 'schools') }}" 
                                   class="export-btn text-blue-600 hover:text-blue-800 text-sm font-medium border border-blue-300 px-4 py-2 rounded hover:bg-blue-50 transition">
                                    <i class="fas fa-download mr-2"></i>ទាញយកគំរូ
                                </a>
                                <button type="submit" 
                                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition font-medium">
                                    <i class="fas fa-upload mr-2"></i>នាំចូលសាលា
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Guidelines -->
                <div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <h5 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-info-circle text-gray-600 mr-2"></i>គោលការណ៍នាំចូល
                        </h5>
                        
                        <div class="space-y-4">
                            <div>
                                <h6 class="font-medium text-gray-800 mb-2">តម្រូវការឯកសារ</h6>
                                <ul class="space-y-1 text-sm text-gray-600">
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        ទម្រង់ CSV តែប៉ុណ្ណោះ
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        ការអ៊ីនកូដ UTF-8
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        ជួរដេញដំបូងជាបណ្តាំក្បាល
                                    </li>
                                </ul>
                            </div>

                            <div>
                                <h6 class="font-medium text-gray-800 mb-2">និយមន័យជួរដេញ</h6>
                                <div class="space-y-2 text-sm">
                                    <div class="bg-white p-2 rounded border">
                                        <code class="text-blue-600">school_name</code> - ឈ្មោះពេញរបស់សាលា
                                    </div>
                                    <div class="bg-white p-2 rounded border">
                                        <code class="text-blue-600">school_code</code> - លេខកូដសាលា (ត្រូវការ)
                                    </div>
                                    <div class="bg-white p-2 rounded border">
                                        <code class="text-blue-600">province</code> - ទីតាំងខេត្ត
                                    </div>
                                    <div class="bg-white p-2 rounded border">
                                        <code class="text-blue-600">district</code> - ទីតាំងស្រុក/ក្រុង
                                    </div>
                                    <div class="bg-white p-2 rounded border">
                                        <code class="text-blue-600">cluster</code> - ក្រុមសាលា (មិនចាំបាច់)
                                    </div>
                                </div>
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 rounded p-3">
                                <p class="text-sm text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>ចំណាំ:</strong> លេខកូដសាលាត្រូវតែមិនដូចគ្នា។ លេខកូដដែលដូចគ្នានឹងត្រូវធ្វើបច្ចុប្បន្នភាពជាមួយព័ត៌មានថ្មី។
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