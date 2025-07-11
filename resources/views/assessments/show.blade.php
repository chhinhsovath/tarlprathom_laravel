<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                {{ __('Assessment Details') }}
            </h2>
            <div class="flex gap-2">
                @can('update', $assessment)
                    <a href="{{ route('assessments.edit', $assessment) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        {{ __('Edit') }}
                    </a>
                @endcan
                <a href="{{ route('assessments.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
            <!-- Assessment Overview Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Assessment Date -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Assessment Date') }}</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $assessment->assessed_at ? $assessment->assessed_at->format('M d, Y') : 'N/A' }}
                            </p>
                        </div>
                        
                        <!-- Cycle -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Cycle') }}</p>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    {{ $assessment->cycle == 'baseline' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $assessment->cycle == 'midline' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $assessment->cycle == 'endline' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ ucfirst($assessment->cycle) }}
                                </span>
                            </p>
                        </div>
                        
                        <!-- Subject -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Subject') }}</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ ucfirst($assessment->subject) }}
                            </p>
                        </div>
                        
                        <!-- Score -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Score') }}</p>
                            <p class="mt-1 text-2xl font-bold text-blue-600">
                                {{ $assessment->score }}%
                            </p>
                        </div>
                    </div>
                    
                    <!-- Level -->
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">{{ __('Assessment Level') }}</p>
                        <p class="mt-1 text-base font-medium text-gray-900">{{ $assessment->level }}</p>
                    </div>
                </div>
            </div>

            <!-- Student Information Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-4 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Student Information') }}</h3>
                    
                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                        <!-- Student Photo -->
                        <div class="flex-shrink-0">
                            @if($assessment->student->photo)
                                <div class="h-20 w-20 sm:h-24 sm:w-24 rounded-lg overflow-hidden shadow-md">
                                    <img src="{{ Storage::url($assessment->student->photo) }}" 
                                         alt="{{ $assessment->student->name }}" 
                                         class="h-full w-full object-cover">
                                </div>
                            @else
                                <div class="h-20 w-20 sm:h-24 sm:w-24 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <svg class="h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Student Details -->
                        <div class="flex-grow">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Name') }}</p>
                                    <p class="mt-1 text-sm font-medium text-gray-900">
                                        <a href="{{ route('students.show', $assessment->student) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ $assessment->student->name }}
                                        </a>
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Grade') }}</p>
                                    <p class="mt-1 text-sm font-medium text-gray-900">
                                        {{ __('Grade') }} {{ $assessment->student->grade ?? 'N/A' }}
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Gender') }}</p>
                                    <p class="mt-1 text-sm font-medium text-gray-900">
                                        {{ ucfirst($assessment->student->gender ?? 'N/A') }}
                                    </p>
                                </div>
                                
                                <div class="sm:col-span-2 lg:col-span-3">
                                    <p class="text-xs text-gray-500 uppercase tracking-wider">{{ __('School') }}</p>
                                    <p class="mt-1 text-sm font-medium text-gray-900">
                                        {{ $assessment->student->school->school_name ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                @can('update', $assessment)
                    <a href="{{ route('assessments.edit', $assessment) }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Edit Assessment') }}
                    </a>
                @endcan
                
                @can('delete', $assessment)
                    <form action="{{ route('assessments.destroy', $assessment) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('{{ __('Are you sure you want to delete this assessment?') }}')"
                                class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Delete Assessment') }}
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>