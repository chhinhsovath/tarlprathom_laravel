<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('rbac.RBAC Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- System Overview Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('rbac.Total Users') }}</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $statistics['total_users'] }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('rbac.Active Users') }}</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $statistics['active_users'] }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('rbac.Total Schools') }}</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $statistics['data_access']['schools'] }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('rbac.Total Students') }}</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $statistics['data_access']['students'] }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Role Distribution -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rbac.Role Distribution') }}</h3>
                        <div class="space-y-3">
                            @foreach($statistics['roles'] as $role => $count)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($role === 'admin') bg-red-100 text-red-800
                                            @elseif($role === 'coordinator') bg-blue-100 text-blue-800
                                            @elseif($role === 'mentor') bg-green-100 text-green-800
                                            @elseif($role === 'teacher') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ __('rbac.' . ucfirst($role)) }}
                                        </span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Data Access Matrix -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('rbac.Data Access Matrix') }}</h3>
                            <a href="{{ route('rbac.data-access') }}" class="text-sm text-blue-600 hover:text-blue-800">
                                {{ __('rbac.View') }} →
                            </a>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between py-2">
                                <span class="text-sm text-gray-600">{{ __('rbac.Total Assessments') }}</span>
                                <span class="text-sm font-medium">{{ $statistics['data_access']['assessments'] }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <span class="text-sm text-gray-600">{{ __('rbac.Total Mentoring Visits') }}</span>
                                <span class="text-sm font-medium">{{ $statistics['data_access']['mentoring_visits'] }}</span>
                            </div>
                            <div class="border-t pt-2">
                                <a href="{{ route('rbac.data-access') }}" class="text-sm text-blue-600 hover:text-blue-800">
                                    {{ __('rbac.Access Control Matrix') }} →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('rbac.Recent Activities') }}</h3>
                        <a href="{{ route('rbac.users') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            {{ __('rbac.User Management') }} →
                        </a>
                    </div>
                    
                    @if($recentActivities->count() > 0)
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach($recentActivities->take(10) as $index => $activity)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">
                                                            <span class="font-medium text-gray-900">{{ $activity->name }}</span>
                                                            ({{ __('rbac.' . ucfirst($activity->role)) }})
                                                            @if($activity->school)
                                                                - {{ $activity->school->school_name }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        {{ $activity->updated_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">{{ __('rbac.No activities found') }}</p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rbac.System Overview') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('rbac.users') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex items-center">
                                <svg class="h-8 w-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                </svg>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">{{ __('rbac.User Management') }}</h4>
                                    <p class="text-sm text-gray-500">{{ __('rbac.Create User') }}, {{ __('rbac.Edit User') }}, {{ __('rbac.View User') }}</p>
                                </div>
                            </div>
                        </a>
                        
                        <a href="{{ route('rbac.create') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex items-center">
                                <svg class="h-8 w-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">{{ __('rbac.Create User') }}</h4>
                                    <p class="text-sm text-gray-500">{{ __('rbac.Role Assignment') }}</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('rbac.data-access') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex items-center">
                                <svg class="h-8 w-8 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">{{ __('rbac.Data Access Control') }}</h4>
                                    <p class="text-sm text-gray-500">{{ __('rbac.Role-Based Data Access') }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>