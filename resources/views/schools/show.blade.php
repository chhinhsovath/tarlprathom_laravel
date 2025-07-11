<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                {{ __('School Details') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('schools.edit', $school) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('schools.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                    </svg>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- School Info Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-4 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $school->school_name }}</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('School Code') }}</span>
                            <p class="text-sm font-medium text-gray-900">{{ $school->school_code }}</p>
                        </div>
                        
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Province') }}</span>
                            <p class="text-sm font-medium text-gray-900">{{ $school->province }}</p>
                        </div>
                        
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('District') }}</span>
                            <p class="text-sm font-medium text-gray-900">{{ $school->district }}</p>
                        </div>
                        
                        @if($school->cluster)
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Cluster') }}</span>
                                <p class="text-sm font-medium text-gray-900">{{ $school->cluster }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <p class="text-xs text-gray-500 uppercase">{{ __('Total Users') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $school->users_count ?? 0 }}</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <p class="text-xs text-gray-500 uppercase">{{ __('Total Students') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $school->students_count ?? 0 }}</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <p class="text-xs text-gray-500 uppercase">{{ __('Teachers') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $teachers->count() }}</p>
                </div>
            </div>

            <!-- Teachers Section -->
            @if($teachers->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Teachers') }}</h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($teachers as $teacher)
                                <div class="flex items-center gap-3 p-3 border rounded-lg">
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
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $teacher->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $teacher->email }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Students Section -->
            @if($recentStudents->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-4 sm:p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('Recent Students') }}</h3>
                            <a href="{{ route('students.index', ['school_id' => $school->id]) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                {{ __('View All') }} →
                            </a>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Name') }}
                                        </th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Grade') }}
                                        </th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Gender') }}
                                        </th>
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
                                                {{ __('Grade') }} {{ $student->grade ?? $student->class ?? 'N/A' }}
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ ucfirst($student->gender ?? 'N/A') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Actions -->
            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('schools.edit', $school) }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Edit School') }}
                </a>
                
                @if($school->users_count == 0 && $school->students_count == 0)
                    <form action="{{ route('schools.destroy', $school) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('{{ __('Are you sure you want to delete this school?') }}')"
                                class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Delete School') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>