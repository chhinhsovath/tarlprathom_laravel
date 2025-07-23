<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Class Details') }}
            </h2>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('classes.edit', $class) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    {{ __('Edit Class') }}
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Class Information -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Class Information') }}</h3>
                            
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Class Name') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $class->name }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Grade Level') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ __('Grade') }} {{ $class->grade_level }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('School') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $class->school->school_name }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Teacher') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($class->teacher)
                                            {{ $class->teacher->name }}
                                        @else
                                            <span class="text-gray-400">{{ __('Not Assigned') }}</span>
                                        @endif
                                    </dd>
                                </div>
                                
                                @if($class->academic_year)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">{{ __('Academic Year') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $class->academic_year }}</dd>
                                    </div>
                                @endif
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Total Students') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $class->students->count() }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Status') }}</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $class->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $class->is_active ? __('Active') : __('Inactive') }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Students List -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Students in this Class') }}</h3>
                                @can('create', App\Models\Student::class)
                                    <a href="{{ route('students.create') }}?class_id={{ $class->id }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        {{ __('Add Student') }}
                                    </a>
                                @endcan
                            </div>

                            @if($class->students->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Photo') }}
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Name') }}
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Gender') }}
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Actions') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($class->students as $student)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            @if($student->photo)
                                                                <div class="h-10 w-10 flex-shrink-0 overflow-hidden rounded-full">
                                                                    <img src="{{ Storage::url($student->photo) }}" alt="{{ $student->name }}" class="h-10 w-10 object-cover" style="height: 40px; width: 40px; object-fit: cover;">
                                                                </div>
                                                            @else
                                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                                                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                                    </svg>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $student->name }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">{{ ucfirst($student->gender) }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="{{ route('students.show', $student) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('View') }}</a>
                                                        @can('update', $student)
                                                            <a href="{{ route('students.edit', $student) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-8">{{ __('No students in this class yet.') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>