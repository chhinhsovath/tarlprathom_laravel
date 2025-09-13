<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                {{ __('School Management') }}
            </h2>
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('schools.create') }}" class="inline-flex justify-center sm:justify-start items-center px-3 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 touch-manipulation">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="hidden sm:inline">{{ __('Add New School') }}</span>
                    <span class="sm:hidden">{{ __('Add School') }}</span>
                </a>
                <a href="{{ route('schools.bulk-import-form') }}" class="inline-flex justify-center sm:justify-start items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 touch-manipulation">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                    </svg>
                    <span class="hidden sm:inline">{{ __('Bulk Import') }}</span>
                    <span class="sm:hidden">{{ __('Import') }}</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">

            <!-- Search -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <form method="GET" action="{{ route('schools.index') }}" class="space-y-3">
                    <div class="w-full">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="{{ __('Search by name, code, province or district...') }}" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 touch-manipulation">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            {{ __('Search') }}
                        </button>
                        
                        @if(request('search'))
                            <a href="{{ route('schools.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 touch-manipulation">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                {{ __('Clear') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Schools Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                @php
                    $headers = [
                        'name' => __('School'),
                        'code' => __('Code'),
                        'location' => __('Location'),
                        'stats' => __('Statistics')
                    ];
                    
                    $tableData = $schools->map(function($school) {
                        $location = $school->district . ', ' . $school->province;
                        if ($school->cluster) {
                            $location .= '<div class="text-xs text-gray-500">' . __('Cluster:') . ' ' . $school->cluster . '</div>';
                        }
                        
                        $stats = '<div class="text-sm">' .
                                '<span class="font-medium">' . ($school->users_count ?? 0) . '</span> ' . __('Users') . '<br>' .
                                '<span class="font-medium">' . ($school->students_count ?? 0) . '</span> ' . trans_db('students') .
                                '</div>';
                        
                        return [
                            'id' => $school->id,
                            'name' => '<div class="font-medium text-gray-900">' . $school->school_name . '</div>',
                            'code' => '<div class="text-sm text-gray-900">' . $school->school_code . '</div>',
                            'location' => $location,
                            'stats' => $stats
                        ];
                    })->toArray();
                    
                    $actions = '<a href="' . route('schools.show', '{{$item}}.id') . '" class="text-indigo-600 hover:text-indigo-900 text-xs px-2 py-1 border border-indigo-600 rounded hover:bg-indigo-50 mr-2">' . __('View') . '</a>' .
                              '<a href="' . route('schools.edit', '{{$item}}.id') . '" class="text-indigo-600 hover:text-indigo-900 text-xs px-2 py-1 border border-indigo-600 rounded hover:bg-indigo-50 mr-2">' . __('Edit') . '</a>';
                @endphp
                
                <x-mobile-table 
                    :data="$tableData" 
                    :headers="$headers" 
                    :actions="$actions">
                    <!-- Desktop table rows -->
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
                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-4">
                                    <div>
                                        <span class="font-medium">{{ $school->users_count ?? 0 }}</span> {{ __('Users') }}
                                    </div>
                                    <div>
                                        <span class="font-medium">{{ $school->students_count ?? 0 }}</span> {{ trans_db('students') }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <a href="{{ route('schools.show', $school) }}" class="text-indigo-600 hover:text-indigo-900 text-xs px-2 py-1 border border-indigo-600 rounded hover:bg-indigo-50">{{ __('View') }}</a>
                                    <a href="{{ route('schools.edit', $school) }}" class="text-indigo-600 hover:text-indigo-900 text-xs px-2 py-1 border border-indigo-600 rounded hover:bg-indigo-50">{{ __('Edit') }}</a>
                                    @if($school->users_count == 0 && $school->students_count == 0)
                                        <form action="{{ route('schools.destroy', $school) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-xs px-2 py-1 border border-red-600 rounded hover:bg-red-50" 
                                                onclick="return confirm('{{ __('Are you sure you want to delete this school?') }}')">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                {{ __('No schools found.') }}
                            </td>
                        </tr>
                    @endforelse
                </x-mobile-table>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $schools->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>