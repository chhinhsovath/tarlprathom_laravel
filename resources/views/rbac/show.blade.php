<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('rbac.User Profile') }}: {{ $user->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('rbac.edit', $user) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('rbac.Edit User') }}
                </a>
                <form method="POST" action="{{ route('rbac.toggle-status', $user) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-{{ $user->is_active ? 'yellow' : 'green' }}-500 hover:bg-{{ $user->is_active ? 'yellow' : 'green' }}-700 text-white font-bold py-2 px-4 rounded">
                        {{ $user->is_active ? __('rbac.Deactivate') : __('rbac.Activate') }}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- User Information -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Basic Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rbac.User Information') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('rbac.Name') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('rbac.Email') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('rbac.Phone') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->phone ?: 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('rbac.Gender') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($user->sex)
                                            {{ $user->sex === 'M' ? __('rbac.Male') : __('rbac.Female') }}
                                        @else
                                            N/A
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('rbac.Status') }}</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $user->is_active ? __('rbac.Active') : __('rbac.Inactive') }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('rbac.Created Date') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('Y-m-d H:i:s') }}</dd>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Role and Permissions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rbac.Role Assignment') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('rbac.Role') }}</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($user->role === 'admin') bg-red-100 text-red-800
                                            @elseif($user->role === 'coordinator') bg-blue-100 text-blue-800
                                            @elseif($user->role === 'mentor') bg-green-100 text-green-800
                                            @elseif($user->role === 'teacher') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ __('rbac.' . ucfirst($user->role)) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('rbac.Data Isolation Level') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @switch($user->role)
                                            @case('admin')
                                                {{ __('rbac.Full access to all data across all schools and users') }}
                                                @break
                                            @case('coordinator')
                                                {{ __('rbac.Access to all schools and students, limited user management') }}
                                                @break
                                            @case('mentor')
                                                {{ __('rbac.Access only to assigned schools, students, and teachers') }}
                                                @break
                                            @case('teacher')
                                                {{ __('rbac.Access only to own school and assigned students') }}
                                                @break
                                            @case('viewer')
                                                {{ __('rbac.Read-only access to basic data, no modifications allowed') }}
                                                @break
                                            @default
                                                {{ __('rbac.Unknown role') }}
                                        @endswitch
                                    </dd>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- School Assignment -->
                    @if($user->role === 'teacher' || $user->role === 'mentor')
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rbac.School Assignment') }}</h3>
                                
                                @if($user->role === 'teacher')
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">{{ __('rbac.School') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ $user->school ? $user->school->school_name : 'N/A' }}
                                        </dd>
                                    </div>
                                @elseif($user->role === 'mentor')
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">{{ __('rbac.Assigned Schools') }}</dt>
                                        <dd class="mt-2">
                                            @if($user->assignedPilotSchools->count() > 0)
                                                <div class="space-y-2">
                                                    @foreach($user->assignedPilotSchools as $school)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            {{ $school->school_name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-500">{{ __('No schools assigned') }}</span>
                                            @endif
                                        </dd>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Contact Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rbac.Contact Information') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('rbac.Province') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->province ?: 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('rbac.District') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->district ?: 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('rbac.Commune') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->commune ?: 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('rbac.Village') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->village ?: 'N/A' }}</dd>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    @if($user->holding_classes || $user->assigned_subject)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rbac.Additional Information') }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">{{ __('rbac.Holding Classes') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $user->holding_classes ?: 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">{{ __('rbac.Assigned Subject') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $user->assigned_subject ?: 'N/A' }}</dd>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Recent Activities -->
                    @if($activities->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rbac.Recent Activity') }}</h3>
                                <div class="flow-root">
                                    <ul class="-mb-8">
                                        @foreach($activities as $index => $activity)
                                            <li>
                                                <div class="relative pb-8">
                                                    @if(!$loop->last)
                                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                    @endif
                                                    <div class="relative flex space-x-3">
                                                        <div>
                                                            <span class="h-8 w-8 rounded-full bg-{{ $activity['type'] === 'mentoring_visit' ? 'blue' : 'green' }}-500 flex items-center justify-center ring-8 ring-white">
                                                                <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    @if($activity['type'] === 'mentoring_visit')
                                                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                                                    @else
                                                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    @endif
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                            <div>
                                                                <p class="text-sm text-gray-500">{{ $activity['description'] }}</p>
                                                            </div>
                                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                                {{ $activity['date']->diffForHumans() }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Data Statistics Sidebar -->
                <div class="space-y-8">
                    <!-- Data Access Statistics -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rbac.Data Access') }}</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">{{ __('rbac.Accessible Schools') }}</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $dataStats['accessible_schools'] }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">{{ __('rbac.Accessible Students') }}</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $dataStats['accessible_students'] }}</span>
                                </div>
                                @if($user->role === 'teacher')
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500">{{ __('rbac.Own Students') }}</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $dataStats['own_students'] }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">{{ __('rbac.Assessments Conducted') }}</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $dataStats['assessments_conducted'] }}</span>
                                </div>
                                @if($user->role === 'mentor')
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500">{{ __('rbac.Mentoring Visits Given') }}</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $dataStats['mentoring_visits_given'] }}</span>
                                    </div>
                                @endif
                                @if($user->role === 'teacher')
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500">{{ __('rbac.Mentoring Visits Received') }}</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $dataStats['mentoring_visits_received'] }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Role Description -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rbac.Permissions') }}</h3>
                            <div class="text-sm text-gray-600">
                                @switch($user->role)
                                    @case('admin')
                                        {{ __('rbac.Admin Role Description') }}
                                        @break
                                    @case('coordinator')
                                        {{ __('rbac.Coordinator Role Description') }}
                                        @break
                                    @case('mentor')
                                        {{ __('rbac.Mentor Role Description') }}
                                        @break
                                    @case('teacher')
                                        {{ __('rbac.Teacher Role Description') }}
                                        @break
                                    @case('viewer')
                                        {{ __('rbac.Viewer Role Description') }}
                                        @break
                                    @default
                                        {{ __('rbac.Unknown role') }}
                                @endswitch
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-8">
                <a href="{{ route('rbac.users') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('rbac.Back to Users') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>