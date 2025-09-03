<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('rbac.User Management') }}
            </h2>
            <a href="{{ route('rbac.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('rbac.Create User') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Filters and Search -->
                <div class="p-6 border-b border-gray-200">
                    <form method="GET" action="{{ route('rbac.users') }}" class="space-y-4 md:space-y-0 md:flex md:items-center md:space-x-4">
                        <div class="flex-1">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="{{ __('rbac.Search users...') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <select name="role" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">{{ __('rbac.All Roles') }}</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>
                                        {{ __('rbac.' . ucfirst($role)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <select name="school_id" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">{{ __('rbac.All Schools') }}</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                        {{ $school->school_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <select name="status" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">{{ __('rbac.All Statuses') }}</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('rbac.Active') }}</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('rbac.Inactive') }}</option>
                            </select>
                        </div>

                        <div class="flex space-x-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('rbac.Filter') }}
                            </button>
                            <a href="{{ route('rbac.users') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('rbac.Reset') }}
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Users Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <x-sortable-header column="name" :current-sort="$sortColumn" :current-direction="$sortDirection" route="rbac.users">
                                        {{ __('rbac.User Info') }}
                                    </x-sortable-header>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('rbac.Contact Info') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <x-sortable-header column="role" :current-sort="$sortColumn" :current-direction="$sortDirection" route="rbac.users">
                                        {{ __('rbac.Role & School') }}
                                    </x-sortable-header>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <x-sortable-header column="is_active" :current-sort="$sortColumn" :current-direction="$sortDirection" route="rbac.users">
                                        {{ __('rbac.Status') }}
                                    </x-sortable-header>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <x-sortable-header column="created_at" :current-sort="$sortColumn" :current-direction="$sortDirection" route="rbac.users">
                                        {{ __('rbac.Created Date') }}
                                    </x-sortable-header>
                                </th>
                                <th class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($user->profile_photo)
                                                    <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $user->profile_photo) }}" alt="">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700">{{ substr($user->name, 0, 1) }}</span>
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
                                        <div class="text-sm text-gray-900">
                                            @if($user->phone)
                                                <div>{{ $user->phone }}</div>
                                            @endif
                                            @if($user->province || $user->district)
                                                <div class="text-sm text-gray-500">
                                                    {{ $user->province }}{{ $user->district ? ', ' . $user->district : '' }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($user->role === 'admin') bg-red-100 text-red-800
                                            @elseif($user->role === 'coordinator') bg-blue-100 text-blue-800
                                            @elseif($user->role === 'mentor') bg-green-100 text-green-800
                                            @elseif($user->role === 'teacher') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ __('rbac.' . ucfirst($user->role)) }}
                                        </span>
                                        @if($user->school)
                                            <div class="text-xs text-gray-500 mt-1">{{ $user->school->school_name }}</div>
                                        @endif
                                        @if($user->role === 'mentor' && $user->assignedPilotSchools->count() > 0)
                                            <div class="text-xs text-gray-500 mt-1">{{ $user->assignedPilotSchools->count() }} {{ __('rbac.Assigned Schools') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $user->is_active ? __('rbac.Active') : __('rbac.Inactive') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->created_at->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('rbac.show', $user) }}" class="text-indigo-600 hover:text-indigo-900" title="{{ __('rbac.View User') }}">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('rbac.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900" title="{{ __('rbac.Edit User') }}">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('rbac.toggle-status', $user) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900" 
                                                        title="{{ $user->is_active ? __('rbac.Deactivate') : __('rbac.Activate') }}">
                                                    @if($user->is_active)
                                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>
                                            @if($user->id !== Auth::id() || \App\Models\User::where('role', 'admin')->where('is_active', true)->count() > 1)
                                                <form method="POST" action="{{ route('rbac.destroy', $user) }}" class="inline" 
                                                      onsubmit="return confirm('{{ __('rbac.Are you sure you want to delete this user?') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="{{ __('rbac.Delete User') }}">
                                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6.5a7.5 7.5 0 01-15 0V5z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        {{ __('rbac.No users found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

            <!-- Back Button -->
            <div class="mt-6">
                <a href="{{ route('rbac.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('rbac.Back to Dashboard') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>