<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                {{ trans_db('school_details') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('schools.edit', $school) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    {{ trans_db('edit') }}
                </a>
                <a href="{{ route('schools.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                    </svg>
                    {{ trans_db('back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            @if (session('warning'))
                <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('warning') }}</span>
                </div>
            @endif
            
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            <!-- School Info Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-4 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $school->school_name }}</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">{{ trans_db('school_code') }}</span>
                            <p class="text-sm font-medium text-gray-900">{{ $school->school_code }}</p>
                        </div>
                        
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">{{ trans_db('province') }}</span>
                            <p class="text-sm font-medium text-gray-900">{{ $school->province }}</p>
                        </div>
                        
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">{{ trans_db('district') }}</span>
                            <p class="text-sm font-medium text-gray-900">{{ $school->district }}</p>
                        </div>
                        
                        @if($school->cluster)
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">{{ trans_db('cluster') }}</span>
                                <p class="text-sm font-medium text-gray-900">{{ $school->cluster }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <p class="text-xs text-gray-500 uppercase">{{ trans_db('total_users') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $school->users_count ?? 0 }}</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <p class="text-xs text-gray-500 uppercase">{{ trans_db('total_students') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $school->students_count ?? 0 }}</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <p class="text-xs text-gray-500 uppercase">{{ trans_db('teachers') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $teachers->count() }}</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <p class="text-xs text-gray-500 uppercase">{{ trans_db('mentors') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $mentors->count() }}</p>
                </div>
            </div>

            <!-- Teachers Section -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-4 sm:p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ trans_db('teachers') }}</h3>
                        @if(in_array(auth()->user()->role, ['admin', 'coordinator']))
                            <div class="flex gap-2">
                                <button onclick="openAddTeacherModal()" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    {{ trans_db('add_teacher') }}
                                </button>
                                <a href="{{ route('schools.download-teacher-template', $school) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    {{ trans_db('download_template') }}
                                </a>
                                <button onclick="openImportTeachersModal()" class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    {{ trans_db('import_teachers') }}
                                </button>
                            </div>
                        @endif
                    </div>
                    
                    @if($teachers->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($teachers as $teacher)
                                <div class="flex items-center gap-3 p-3 border rounded-lg relative group">
                                    @if($teacher->profile_photo)
                                        <div class="h-10 w-10 rounded-full overflow-hidden flex-shrink-0">
                                            <img src="{{ Storage::url($teacher->profile_photo) }}" alt="{{ $teacher->name }}" class="h-full w-full object-cover">
                                        </div>
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $teacher->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $teacher->email }}</p>
                                    </div>
                                    @if(auth()->user()->role === 'admin')
                                        <form action="{{ route('schools.remove-teacher', $school) }}" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                                            <button type="submit" onclick="return confirm('{{ trans_db('remove_teacher_confirm') }}')" class="text-red-600 hover:text-red-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">{{ trans_db('no_teachers_assigned') }}</p>
                    @endif
                </div>
            </div>

            <!-- Mentors Section -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-4 sm:p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ trans_db('mentors') }}</h3>
                        @if(auth()->user()->role === 'admin')
                            <button onclick="openAddMentorModal()" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ trans_db('add_mentor') }}
                            </button>
                        @endif
                    </div>
                    
                    @if($mentors->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($mentors as $mentor)
                                <div class="flex items-center gap-3 p-3 border rounded-lg relative group">
                                    @if($mentor->profile_photo)
                                        <div class="h-10 w-10 rounded-full overflow-hidden flex-shrink-0">
                                            <img src="{{ Storage::url($mentor->profile_photo) }}" alt="{{ $mentor->name }}" class="h-full w-full object-cover">
                                        </div>
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $mentor->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $mentor->email }}</p>
                                    </div>
                                    @if(auth()->user()->role === 'admin')
                                        <form action="{{ route('schools.remove-mentor', $school) }}" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="mentor_id" value="{{ $mentor->id }}">
                                            <button type="submit" onclick="return confirm('{{ trans_db('remove_mentor_confirm') }}')" class="text-red-600 hover:text-red-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">{{ trans_db('no_mentors_assigned') }}</p>
                    @endif
                </div>
            </div>

            <!-- Students Section -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-4 sm:p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ trans_db('students') }}</h3>
                        <div class="flex gap-2">
                            @if(in_array(auth()->user()->role, ['admin', 'coordinator']))
                                <a href="{{ route('schools.download-student-template', $school) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    {{ trans_db('download_template') }}
                                </a>
                                <button onclick="openImportStudentsModal()" class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    {{ trans_db('import_students') }}
                                </button>
                            @endif
                            <a href="{{ route('students.index', ['school_id' => $school->id]) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ trans_db('view_all_students') }}
                            </a>
                        </div>
                    </div>
                    
                    @if($recentStudents && $recentStudents->count() > 0)
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ trans_db('name') }}
                                        </th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ trans_db('grade') }}
                                        </th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ trans_db('gender') }}
                                        </th>
                                        @if(in_array(auth()->user()->role, ['admin', 'coordinator']))
                                            <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ trans_db('actions') }}
                                            </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentStudents as $student)
                                        <tr>
                                            <td class="px-3 py-3 whitespace-nowrap">
                                                <a href="{{ route('students.show', $student) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                                    {{ $student->name }}
                                                </a>
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ trans_db('grade') }} {{ $student->grade ?? 'N/A' }}
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ ucfirst($student->gender ?? 'N/A') }}
                                            </td>
                                            @if(in_array(auth()->user()->role, ['admin', 'coordinator']))
                                                <td class="px-3 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                    <form action="{{ route('schools.remove-student', $school) }}" method="POST" class="inline" onsubmit="return confirm('{{ trans_db('remove_student_confirm') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">{{ trans_db('no_students_enrolled') }}</p>
                    @endif
                </div>
            </div>
            
            <!-- Actions -->
            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('schools.edit', $school) }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ trans_db('edit_school') }}
                </a>
                
                @if($school->users_count == 0 && $school->students_count == 0)
                    <form action="{{ route('schools.destroy', $school) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('{{ trans_db('delete_school_confirm') }}')"
                                class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ trans_db('delete_school') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Import Teachers Modal -->
    <div id="importTeachersModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity hidden z-50">
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="absolute right-0 top-0 pr-4 pt-4">
                        <button type="button" onclick="closeImportTeachersModal()" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">{{ __("Close") }}</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">
                                {{ trans_db('import_teachers_to') }} {{ $school->school_name }}
                            </h3>
                            
                            <form action="{{ route('schools.import-teachers', $school) }}" method="POST" enctype="multipart/form-data" id="importTeachersForm">
                                @csrf
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ trans_db('download_template') }}
                                    </label>
                                    <a href="{{ route('schools.download-teacher-template', $school) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="mr-2 -ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        {{ trans_db('download_excel_template') }}
                                    </a>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="teacher_file" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ trans_db('select_excel_file') }}
                                    </label>
                                    <input type="file" name="file" id="teacher_file" accept=".xlsx,.xls" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <p class="mt-1 text-xs text-gray-500">{{ trans_db('accepts_excel_files_only') }}</p>
                                </div>
                                
                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-2">
                                    <button type="submit" class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:w-auto">
                                        {{ trans_db('import_teachers') }}
                                    </button>
                                    <button type="button" onclick="closeImportTeachersModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                        {{ trans_db('cancel') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Students Modal -->
    <div id="importStudentsModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity hidden z-50">
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="absolute right-0 top-0 pr-4 pt-4">
                        <button type="button" onclick="closeImportStudentsModal()" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">{{ __("Close") }}</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">
                                {{ trans_db('import_students_to') }} {{ $school->school_name }}
                            </h3>
                            
                            <form action="{{ route('schools.import-students', $school) }}" method="POST" enctype="multipart/form-data" id="importStudentsForm">
                                @csrf
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ trans_db('download_template') }}
                                    </label>
                                    <a href="{{ route('schools.download-student-template', $school) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="mr-2 -ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        {{ trans_db('download_excel_template') }}
                                    </a>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="student_file" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ trans_db('select_excel_file') }}
                                    </label>
                                    <input type="file" name="file" id="student_file" accept=".xlsx,.xls" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <p class="mt-1 text-xs text-gray-500">{{ trans_db('accepts_excel_files_only') }}</p>
                                </div>
                                
                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-2">
                                    <button type="submit" class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:w-auto">
                                        {{ trans_db('import_students') }}
                                    </button>
                                    <button type="button" onclick="closeImportStudentsModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                        {{ trans_db('cancel') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Teacher Modal -->
    <div id="addTeacherModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity hidden z-50">
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="absolute right-0 top-0 pr-4 pt-4">
                        <button type="button" onclick="closeAddTeacherModal()" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">{{ __("Close") }}</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4" id="modal-title">
                                {{ trans_db('add_teacher_to_school') }}
                            </h3>
                            
                            <form action="{{ route('schools.add-teacher', $school) }}" method="POST" id="addTeacherForm">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="teacher_search" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ trans_db('search_teacher') }}
                                    </label>
                                    <input type="text" id="teacher_search" placeholder="វាយបញ្ចូលដើម្បីស្វែងរកគ្រូបង្រៀន..." 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        onkeyup="searchTeachers(this.value)">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ trans_db('available_teachers') }}
                                    </label>
                                    <div id="teachersList" class="max-h-60 overflow-y-auto border rounded-md p-2">
                                        <p class="text-sm text-gray-500 text-center py-4">{{ trans_db('start_typing_search_teachers') }}</p>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ trans_db('selected_teachers') }}
                                    </label>
                                    <div id="selectedTeachers" class="border rounded-md p-2 min-h-[60px]">
                                        <p class="text-sm text-gray-500 text-center py-2">{{ trans_db('no_teachers_selected') }}</p>
                                    </div>
                                </div>
                                
                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-2">
                                    <button type="submit" class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:w-auto">
                                        {{ trans_db('add_teachers') }}
                                    </button>
                                    <button type="button" onclick="closeAddTeacherModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                        {{ trans_db('cancel') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Mentor Modal -->
    <div id="addMentorModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity hidden z-50">
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="absolute right-0 top-0 pr-4 pt-4">
                        <button type="button" onclick="closeAddMentorModal()" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">{{ __("Close") }}</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4" id="modal-title">
                                {{ trans_db('add_mentor_to_school') }}
                            </h3>
                            
                            <form action="{{ route('schools.add-mentor', $school) }}" method="POST" id="addMentorForm">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="mentor_search" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ trans_db('search_mentor') }}
                                    </label>
                                    <input type="text" id="mentor_search" placeholder="វាយបញ្ចូលដើម្បីស្វែងរកទីប្រឹក្សា..." 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        onkeyup="searchMentors(this.value)">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ trans_db('available_mentors') }}
                                    </label>
                                    <div id="mentorsList" class="max-h-60 overflow-y-auto border rounded-md p-2">
                                        <p class="text-sm text-gray-500 text-center py-4">{{ trans_db('start_typing_search_mentors') }}</p>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ trans_db('selected_mentors') }}
                                    </label>
                                    <div id="selectedMentors" class="border rounded-md p-2 min-h-[60px]">
                                        <p class="text-sm text-gray-500 text-center py-2">{{ trans_db('no_mentors_selected') }}</p>
                                    </div>
                                </div>
                                
                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-2">
                                    <button type="submit" class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:w-auto">
                                        {{ trans_db('add_mentors') }}
                                    </button>
                                    <button type="button" onclick="closeAddMentorModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                        {{ trans_db('cancel') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Import modal functions
        function openImportTeachersModal() {
            document.getElementById('importTeachersModal').classList.remove('hidden');
        }

        function closeImportTeachersModal() {
            document.getElementById('importTeachersModal').classList.add('hidden');
            document.getElementById('teacher_file').value = '';
        }

        function openImportStudentsModal() {
            document.getElementById('importStudentsModal').classList.remove('hidden');
        }

        function closeImportStudentsModal() {
            document.getElementById('importStudentsModal').classList.add('hidden');
            document.getElementById('student_file').value = '';
        }

        let selectedTeacherIds = [];
        let searchTimeout;

        function openAddTeacherModal() {
            document.getElementById('addTeacherModal').classList.remove('hidden');
        }

        function closeAddTeacherModal() {
            document.getElementById('addTeacherModal').classList.add('hidden');
            selectedTeacherIds = [];
            updateSelectedTeachersList();
        }

        function searchTeachers(query) {
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                document.getElementById('teachersList').innerHTML = '<p class="text-sm text-gray-500 text-center py-4">Start typing to search for teachers...</p>';
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('schools.search-teachers', $school) }}?q=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        let html = '';
                        if (data.length === 0) {
                            html = '<p class="text-sm text-gray-500 text-center py-4">រកមិនឃើញគ្រូបង្រៀន</p>';
                        } else {
                            data.forEach(teacher => {
                                const isSelected = selectedTeacherIds.includes(teacher.id);
                                html += `
                                    <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded cursor-pointer ${isSelected ? 'bg-indigo-50' : ''}" 
                                         onclick="toggleTeacher(${teacher.id}, '${teacher.name}', '${teacher.email}')">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">${teacher.name}</p>
                                            <p class="text-xs text-gray-500">${teacher.email}</p>
                                        </div>
                                        <input type="checkbox" ${isSelected ? 'checked' : ''} class="ml-2">
                                    </div>
                                `;
                            });
                        }
                        document.getElementById('teachersList').innerHTML = html;
                    });
            }, 300);
        }

        function toggleTeacher(id, name, email) {
            const index = selectedTeacherIds.indexOf(id);
            if (index > -1) {
                selectedTeacherIds.splice(index, 1);
            } else {
                selectedTeacherIds.push(id);
            }
            updateSelectedTeachersList();
            searchTeachers(document.getElementById('teacher_search').value);
        }

        function updateSelectedTeachersList() {
            const container = document.getElementById('selectedTeachers');
            if (selectedTeacherIds.length === 0) {
                container.innerHTML = '<p class="text-sm text-gray-500 text-center py-2">មិនមានគ្រូបង្រៀនដែលបានជ្រើសរើស</p>';
            } else {
                container.innerHTML = selectedTeacherIds.map(id => 
                    `<input type="hidden" name="teacher_ids[]" value="${id}">`
                ).join('') + `<p class="text-sm text-gray-700">${selectedTeacherIds.length} teacher(s) selected</p>`;
            }
        }

        // Mentor Modal Functions
        let selectedMentorIds = [];
        let mentorSearchTimeout;

        function openAddMentorModal() {
            document.getElementById('addMentorModal').classList.remove('hidden');
        }

        function closeAddMentorModal() {
            document.getElementById('addMentorModal').classList.add('hidden');
            selectedMentorIds = [];
            updateSelectedMentorsList();
        }

        function searchMentors(query) {
            clearTimeout(mentorSearchTimeout);
            
            if (query.length < 2) {
                document.getElementById('mentorsList').innerHTML = '<p class="text-sm text-gray-500 text-center py-4">Start typing to search for mentors...</p>';
                return;
            }

            mentorSearchTimeout = setTimeout(() => {
                fetch(`{{ route('schools.search-mentors', $school) }}?q=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        let html = '';
                        if (data.length === 0) {
                            html = '<p class="text-sm text-gray-500 text-center py-4">រកមិនឃើញទីប្រឹក្សា</p>';
                        } else {
                            data.forEach(mentor => {
                                const isSelected = selectedMentorIds.includes(mentor.id);
                                html += `
                                    <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded cursor-pointer ${isSelected ? 'bg-indigo-50' : ''}" 
                                         onclick="toggleMentor(${mentor.id}, '${mentor.name}', '${mentor.email}')">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">${mentor.name}</p>
                                            <p class="text-xs text-gray-500">${mentor.email}</p>
                                        </div>
                                        <input type="checkbox" ${isSelected ? 'checked' : ''} class="ml-2">
                                    </div>
                                `;
                            });
                        }
                        document.getElementById('mentorsList').innerHTML = html;
                    });
            }, 300);
        }

        function toggleMentor(id, name, email) {
            const index = selectedMentorIds.indexOf(id);
            if (index > -1) {
                selectedMentorIds.splice(index, 1);
            } else {
                selectedMentorIds.push(id);
            }
            updateSelectedMentorsList();
            searchMentors(document.getElementById('mentor_search').value);
        }

        function updateSelectedMentorsList() {
            const container = document.getElementById('selectedMentors');
            if (selectedMentorIds.length === 0) {
                container.innerHTML = '<p class="text-sm text-gray-500 text-center py-2">មិនមានទីប្រឹក្សាដែលបានជ្រើសរើស</p>';
            } else {
                container.innerHTML = selectedMentorIds.map(id => 
                    `<input type="hidden" name="mentor_ids[]" value="${id}">`
                ).join('') + `<p class="text-sm text-gray-700">${selectedMentorIds.length} mentor(s) selected</p>`;
            }
        }
    </script>
</x-app-layout>