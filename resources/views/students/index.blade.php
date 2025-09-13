<x-app-layout>
    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-4 sm:p-6 lg:p-8 text-gray-900">

                    <!-- Search Form -->
                    <form method="GET" action="{{ route('students.index') }}" class="mb-6">
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-4">
                            <!-- Search Input -->
                            <div class="w-full sm:flex-1">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    placeholder="{{ __('students.Search by name...') }}" 
                                    class="w-full text-sm sm:text-base rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <!-- Filter Dropdowns -->
                            <div class="grid grid-cols-2 sm:flex gap-2 sm:gap-4">
                                @if(auth()->user()->isAdmin())
                                    <div class="col-span-2 sm:col-span-1">
                                        <select name="pilot_school_id" class="w-full text-sm sm:text-base rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">{{ __('students.All Schools') }}</option>
                                            @foreach($schools as $school)
                                                <option value="{{ $school->id }}" {{ request('pilot_school_id') == $school->id ? 'selected' : '' }}>
                                                    {{ $school->school_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div>
                                    <select name="class" class="w-full text-sm sm:text-base rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">{{ __('students.All Grades') }}</option>
                                        <option value="Grade 4" {{ request('class') == 'Grade 4' ? 'selected' : '' }}>
                                            {{ __('students.Grade 4') }}
                                        </option>
                                        <option value="Grade 5" {{ request('class') == 'Grade 5' ? 'selected' : '' }}>
                                            {{ __('students.Grade 5') }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <select name="gender" class="w-full text-sm sm:text-base rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">{{ __('students.All Genders') }}</option>
                                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>{{ __('students.Male') }}</option>
                                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>{{ __('students.Female') }}</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Mobile-first responsive action buttons -->
                            <div class="flex flex-col space-y-3 sm:flex-row sm:flex-wrap sm:gap-3 sm:space-y-0">
                                <!-- Primary actions - search and clear -->
                                <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-3">
                                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 touch-manipulation">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        {{ __('students.Search') }}
                                    </button>
                                    @if(request('search') || request('pilot_school_id') || request('class') || request('gender'))
                                        <a href="{{ route('students.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 touch-manipulation">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            {{ __('students.Clear') }}
                                        </a>
                                    @endif
                                </div>

                                <!-- Secondary actions - create buttons -->
                                @can('create', App\Models\Student::class)
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:space-x-3 gap-2 sm:gap-3">
                                    <a href="{{ route('students.create') }}" class="inline-flex justify-center items-center px-4 py-3 sm:py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 touch-manipulation">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <span class="hidden sm:inline">{{ __('students.Add New Student') }}</span>
                                        <span class="sm:hidden">{{ __('students.Add Student') }}</span>
                                    </a>
                                    <a href="{{ route('students.bulk-add-form') }}" class="inline-flex justify-center items-center px-4 py-3 sm:py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 touch-manipulation">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                        <span class="hidden sm:inline">{{ __('students.Add Multiple Students') }}</span>
                                        <span class="sm:hidden">{{ __('students.Bulk Add') }}</span>
                                    </a>
                                </div>
                                @endcan

                                <!-- Export action -->
                                @can('viewAny', App\Models\Student::class)
                                <div class="w-full sm:w-auto">
                                    <a href="{{ route('students.export') }}?{{ request()->getQueryString() }}" class="export-btn w-full inline-flex justify-center items-center px-4 py-3 sm:py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 touch-manipulation">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        {{ __('students.Export to Excel') }}
                                    </a>
                                </div>
                                @endcan
                            </div>
                        </div>
                    </form>

                    <!-- Students Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <x-sortable-header column="name" :current-sort="$sortField" :current-order="$sortOrder">
                                            {{ __('students.Name') }}
                                        </x-sortable-header>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('students.Age') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <x-sortable-header column="class" :current-sort="$sortField" :current-order="$sortOrder">
                                            {{ __('students.Grade') }}
                                        </x-sortable-header>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <x-sortable-header column="gender" :current-sort="$sortField" :current-order="$sortOrder">
                                            {{ __('students.Gender') }}
                                        </x-sortable-header>
                                    </th>
                                    @if(!auth()->user()->isTeacher())
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('students.Teacher') }}
                                    </th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('students.Mentor(s)') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('students.School') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('students.Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($students as $student)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $student->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $student->age ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $student->class ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ ucfirst($student->gender) }}</div>
                                        </td>
                                        @if(!auth()->user()->isTeacher())
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $student->teacher ? $student->teacher->name : 'N/A' }}</div>
                                        </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                @if($student->pilotSchool && $student->pilotSchool->assignedMentors->count() > 0)
                                                    {{ $student->pilotSchool->assignedMentors->pluck('name')->join(', ') }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $student->pilotSchool->school_name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('students.show', $student) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('students.View') }}</a>
                                            @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                                                <a href="{{ route('assessments.create', ['student_id' => $student->id]) }}" class="text-green-600 hover:text-green-900 mr-3">វាយតម្លៃ</a>
                                            @endif
                                            @can('update', $student)
                                                <a href="{{ route('students.edit', $student) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('students.Edit') }}</a>
                                            @endcan
                                            @can('delete', $student)
                                                @if($student->assessments_count == 0)
                                                    <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" 
                                                            onclick="return confirm('{{ __('students.Are you sure you want to delete this student?') }}')">
                                                            {{ __('students.Delete') }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-400 text-sm" title="{{ __('students.Cannot delete student with assessments') }}">
                                                        {{ __('students.Has Assessments') }}
                                                    </span>
                                                @endif
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                            {{ __('students.No students found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $students->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>