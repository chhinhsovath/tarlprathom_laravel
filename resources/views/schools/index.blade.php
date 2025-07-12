<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                {{ __('School Management') }}
            </h2>
            <a href="{{ route('schools.create') }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                {{ __('Add New School') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Search -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <form method="GET" action="{{ route('schools.index') }}" class="flex gap-3">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="{{ __('Search by name, code, province or district...') }}" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    
                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Search') }}
                    </button>
                    
                    @if(request('search'))
                        <a href="{{ route('schools.index') }}" class="inline-flex items-center px-3 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Clear') }}
                        </a>
                    @endif
                </form>
            </div>

            <!-- Schools Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <x-sortable-header column="school_name" :current-sort="$sortField" :current-order="$sortOrder">
                                        {{ __('School Name') }}
                                    </x-sortable-header>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <x-sortable-header column="school_code" :current-sort="$sortField" :current-order="$sortOrder">
                                        {{ __('Code') }}
                                    </x-sortable-header>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <x-sortable-header column="province" :current-sort="$sortField" :current-order="$sortOrder">
                                        {{ __('Location') }}
                                    </x-sortable-header>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Statistics') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($schools as $school)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $school->school_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $school->school_code }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $school->district }}, {{ $school->province }}</div>
                                        @if($school->cluster)
                                            <div class="text-xs text-gray-500">{{ __('Cluster:') }} {{ $school->cluster }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex gap-4">
                                            <div>
                                                <span class="font-medium">{{ $school->users_count ?? 0 }}</span> {{ __('Users') }}
                                            </div>
                                            <div>
                                                <span class="font-medium">{{ $school->students_count ?? 0 }}</span> {{ __('Students') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('schools.show', $school) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('View') }}</a>
                                        <a href="{{ route('schools.edit', $school) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('Edit') }}</a>
                                        @if($school->users_count == 0 && $school->students_count == 0)
                                            <form action="{{ route('schools.destroy', $school) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" 
                                                    onclick="return confirm('{{ __('Are you sure you want to delete this school?') }}')">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        {{ __('No schools found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $schools->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>