<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                {{ __('User Management') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('users.create') }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('Add New User') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">

            <!-- Search and Filters -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <form method="GET" action="{{ route('users.index') }}" class="space-y-4">
                    <!-- Main search input -->
                    <div class="w-full">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="{{ __('Search by name or email...') }}" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    
                    <!-- Filter dropdowns -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        <div>
                            <select name="role" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">{{ __('All Roles') }}</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                                <option value="coordinator" {{ request('role') == 'coordinator' ? 'selected' : '' }}>{{ __('Coordinator') }}</option>
                                <option value="mentor" {{ request('role') == 'mentor' ? 'selected' : '' }}>{{ __('Mentor') }}</option>
                                <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>{{ __('Teacher') }}</option>
                                <option value="viewer" {{ request('role') == 'viewer' ? 'selected' : '' }}>{{ __('Viewer') }}</option>
                            </select>
                        </div>
                        
                        <div>
                            <select name="school_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">{{ __('All Schools') }}</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                        {{ $school->school_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <select name="is_active" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">{{ __('All Status') }}</option>
                                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Action buttons -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 touch-manipulation">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            {{ __('Search') }}
                        </button>
                        
                        @if(request()->hasAny(['search', 'role', 'school_id', 'is_active', 'sort', 'order']))
                            <a href="{{ route('users.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 touch-manipulation">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                {{ __('Clear') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                @php
                    $headers = [
                        'name' => __('User'),
                        'role' => __('Role'),
                        'school' => __('School'),
                        'status' => __('Status')
                    ];
                    
                    $tableData = $users->map(function($user) {
                        return [
                            'id' => $user->id,
                            'name' => '<div class="flex items-center">' .
                                     '  <div class="flex-shrink-0">' .
                                     ($user->profile_photo ? 
                                        '    <div class="h-8 w-8 rounded-full overflow-hidden">' .
                                        '      <img src="' . Storage::url($user->profile_photo) . '" alt="' . $user->name . '" class="h-8 w-8 object-cover">' .
                                        '    </div>' :
                                        '    <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">' .
                                        '      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">' .
                                        '        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>' .
                                        '      </svg>' .
                                        '    </div>'
                                     ) .
                                     '  </div>' .
                                     '  <div class="ml-3 sm:ml-4">' .
                                     '    <div class="text-sm font-medium text-gray-900">' . $user->name . '</div>' .
                                     '    <div class="text-sm text-gray-500">' . $user->email . '</div>' .
                                     '  </div>' .
                                     '</div>',
                            'role' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' .
                                     ($user->role == 'admin' ? 'bg-purple-100 text-purple-800' : '') .
                                     ($user->role == 'mentor' ? 'bg-blue-100 text-blue-800' : '') .
                                     ($user->role == 'teacher' ? 'bg-green-100 text-green-800' : '') .
                                     ($user->role == 'viewer' ? 'bg-gray-100 text-gray-800' : '') .
                                     ($user->role == 'coordinator' ? 'bg-yellow-100 text-yellow-800' : '') . '">' .
                                     ucfirst($user->role) . '</span>',
                            'school' => $user->school ? $user->school->name : '-',
                            'status' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' .
                                       ($user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') . '">' .
                                       ($user->is_active ? __('Active') : __('Inactive')) . '</span>'
                        ];
                    })->toArray();
                    
                    $actions = '<a href="' . route('users.show', '{{$item}}.id') . '" class="text-indigo-600 hover:text-indigo-900 text-xs px-2 py-1 border border-indigo-600 rounded hover:bg-indigo-50 mr-2">' . __('View') . '</a>' .
                              '<a href="' . route('users.edit', '{{$item}}.id') . '" class="text-indigo-600 hover:text-indigo-900 text-xs px-2 py-1 border border-indigo-600 rounded hover:bg-indigo-50 mr-2">' . __('Edit') . '</a>';
                    
                    if (auth()->id() !== '{{$item}}.id') {
                        $actions .= '<form action="' . route('users.destroy', '{{$item}}.id') . '" method="POST" class="inline">' .
                                   csrf_field() . method_field('DELETE') .
                                   '<button type="submit" class="text-red-600 hover:text-red-900 text-xs px-2 py-1 border border-red-600 rounded hover:bg-red-50" onclick="return confirm(\"' . __('Are you sure you want to delete this user?') . '\")">' .
                                   __('Delete') . '</button></form>';
                    }
                @endphp
                
                <x-mobile-table 
                    :data="$tableData" 
                    :headers="$headers" 
                    :actions="$actions">
                    <!-- Desktop table rows -->
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        @if($user->profile_photo)
                                            <div class="h-8 w-8 rounded-full overflow-hidden">
                                                <img src="{{ Storage::url($user->profile_photo) }}" alt="{{ $user->name }}" class="h-8 w-8 object-cover">
                                            </div>
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $user->role == 'mentor' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $user->role == 'teacher' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $user->role == 'coordinator' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $user->role == 'viewer' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->school ? $user->school->name : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? __('Active') : __('Inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <a href="{{ route('users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900 text-xs px-2 py-1 border border-indigo-600 rounded hover:bg-indigo-50">{{ __('View') }}</a>
                                    <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 text-xs px-2 py-1 border border-indigo-600 rounded hover:bg-indigo-50">{{ __('Edit') }}</a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-xs px-2 py-1 border border-red-600 rounded hover:bg-red-50" 
                                                onclick="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
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
                                {{ __('No users found.') }}
                            </td>
                        </tr>
                    @endforelse
                </x-mobile-table>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>