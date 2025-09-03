<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('rbac.Data Access Control') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Data Access Overview -->
            <div class="mb-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rbac.Role-Based Data Access') }}</h3>
                    <p class="text-sm text-gray-600 mb-6">
                        {{ __('This matrix shows how each role can access different types of data in the system. Data isolation ensures users only see information relevant to their role and responsibilities.') }}
                    </p>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('rbac.Role') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('rbac.Schools Access') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('rbac.Students Access') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('rbac.Teachers Access') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('rbac.Data Isolation Level') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach(['admin', 'coordinator', 'mentor', 'teacher', 'viewer'] as $role)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if($role === 'admin') bg-red-100 text-red-800
                                                @elseif($role === 'coordinator') bg-blue-100 text-blue-800
                                                @elseif($role === 'mentor') bg-green-100 text-green-800
                                                @elseif($role === 'teacher') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ __('rbac.' . ucfirst($role)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if(isset($dataAccessMatrix[$role]))
                                                <div class="flex items-center">
                                                    <span class="font-medium">{{ $dataAccessMatrix[$role]['schools_count'] }}</span>
                                                    @if($dataAccessMatrix[$role]['can_view_all_schools'])
                                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                            {{ __('rbac.Full Access') }}
                                                        </span>
                                                    @else
                                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            {{ __('rbac.Limited Access') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if(isset($dataAccessMatrix[$role]))
                                                <span class="font-medium">{{ $dataAccessMatrix[$role]['students_count'] }}</span>
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if(isset($dataAccessMatrix[$role]))
                                                @if($role === 'mentor')
                                                    <span class="font-medium">{{ $dataAccessMatrix[$role]['teachers_count'] }}</span>
                                                @elseif($role === 'admin' || $role === 'coordinator')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                        {{ __('rbac.Full Access') }}
                                                    </span>
                                                @elseif($role === 'teacher')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        {{ __('rbac.Limited Access') }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                        {{ __('rbac.No Access') }}
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            @if(isset($dataAccessMatrix[$role]))
                                                <div class="max-w-xs">
                                                    {{ $dataAccessMatrix[$role]['data_isolation_level'] }}
                                                </div>
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sample Users by Role -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                @foreach(['admin', 'coordinator', 'mentor', 'teacher', 'viewer'] as $role)
                    @if(isset($sampleUsers[$role]) && $sampleUsers[$role])
                        @php $user = $sampleUsers[$role] @endphp
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ __('rbac.' . ucfirst($role)) }} {{ __('Sample') }}
                                    </h3>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($role === 'admin') bg-red-100 text-red-800
                                        @elseif($role === 'coordinator') bg-blue-100 text-blue-800
                                        @elseif($role === 'mentor') bg-green-100 text-green-800
                                        @elseif($role === 'teacher') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ __('rbac.' . ucfirst($role)) }}
                                    </span>
                                </div>
                                
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500">{{ __('rbac.Name') }}</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $user->name }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500">{{ __('rbac.Accessible Schools') }}</span>
                                        <span class="text-sm font-medium text-gray-900">{{ count($user->getAccessibleSchoolIds()) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500">{{ __('rbac.Accessible Students') }}</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $user->getAccessibleStudents()->count() }}</span>
                                    </div>
                                    
                                    @if($role === 'teacher' && $user->school)
                                        <div class="border-t pt-3">
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-500">{{ __('rbac.School') }}</span>
                                                <span class="text-sm font-medium text-gray-900">{{ $user->school->school_name }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($role === 'mentor' && $user->assignedPilotSchools->count() > 0)
                                        <div class="border-t pt-3">
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-500">{{ __('rbac.Assigned Schools') }}</span>
                                                <span class="text-sm font-medium text-gray-900">{{ $user->assignedPilotSchools->count() }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-4 pt-4 border-t">
                                    <p class="text-xs text-gray-500">
                                        @switch($role)
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
                                        @endswitch
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Permission Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rbac.Access Control Matrix') }}</h3>
                    <p class="text-sm text-gray-600 mb-6">
                        {{ __('Detailed breakdown of what each role can do with different types of data.') }}
                    </p>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Data Type') }}
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('rbac.Admin') }}
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('rbac.Coordinator') }}
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('rbac.Mentor') }}
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('rbac.Teacher') }}
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('rbac.Viewer') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach(['Users', 'Schools', 'Students', 'Assessments', 'Mentoring Visits'] as $dataType)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ __('rbac.' . $dataType) }}
                                        </td>
                                        @foreach(['admin', 'coordinator', 'mentor', 'teacher', 'viewer'] as $role)
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @php
                                                    $dataKey = strtolower(str_replace(' ', '_', $dataType));
                                                    $permissions = [];
                                                    
                                                    switch($dataKey) {
                                                        case 'users':
                                                            $permissions = [
                                                                'admin' => 'CRUD',
                                                                'coordinator' => 'R',
                                                                'mentor' => 'R*',
                                                                'teacher' => 'R*',
                                                                'viewer' => '-'
                                                            ];
                                                            break;
                                                        case 'schools':
                                                            $permissions = [
                                                                'admin' => 'CRUD',
                                                                'coordinator' => 'RU',
                                                                'mentor' => 'R*',
                                                                'teacher' => 'R*',
                                                                'viewer' => 'R'
                                                            ];
                                                            break;
                                                        case 'students':
                                                            $permissions = [
                                                                'admin' => 'CRUD',
                                                                'coordinator' => 'CRU',
                                                                'mentor' => 'RU*',
                                                                'teacher' => 'RU*',
                                                                'viewer' => 'R'
                                                            ];
                                                            break;
                                                        case 'assessments':
                                                            $permissions = [
                                                                'admin' => 'CRUD',
                                                                'coordinator' => 'CRU',
                                                                'mentor' => 'R*',
                                                                'teacher' => 'CR*',
                                                                'viewer' => 'R'
                                                            ];
                                                            break;
                                                        case 'mentoring_visits':
                                                            $permissions = [
                                                                'admin' => 'CRUD',
                                                                'coordinator' => 'R',
                                                                'mentor' => 'CRU*',
                                                                'teacher' => 'R*',
                                                                'viewer' => 'R'
                                                            ];
                                                            break;
                                                    }
                                                    
                                                    $permission = $permissions[$role] ?? '-';
                                                @endphp
                                                
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                    @if($permission === 'CRUD') bg-green-100 text-green-800
                                                    @elseif(str_contains($permission, 'C')) bg-blue-100 text-blue-800
                                                    @elseif(str_contains($permission, 'U')) bg-yellow-100 text-yellow-800
                                                    @elseif($permission === 'R' || str_contains($permission, 'R')) bg-gray-100 text-gray-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ $permission }}
                                                </span>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 text-xs text-gray-500">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div><strong>C</strong> = {{ __('rbac.Can Create') }}</div>
                            <div><strong>R</strong> = {{ __('rbac.Can Read') }}</div>
                            <div><strong>U</strong> = {{ __('rbac.Can Update') }}</div>
                            <div><strong>D</strong> = {{ __('rbac.Can Delete') }}</div>
                        </div>
                        <div class="mt-2">
                            <div><strong>*</strong> = {{ __('Limited to assigned/own data') }}</div>
                            <div><strong>-</strong> = {{ __('rbac.No Access') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-8">
                <a href="{{ route('rbac.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('rbac.Back to Dashboard') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>