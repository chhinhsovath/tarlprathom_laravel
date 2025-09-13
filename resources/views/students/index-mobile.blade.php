<x-app-layout>
    <div class="py-4 sm:py-6">
        <div class="w-full px-2 sm:px-4 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-3 sm:p-6 lg:p-8 text-gray-900">

                    <!-- Mobile-Optimized Search Form -->
                    <form method="GET" action="{{ route('students.index') }}" class="mb-4 sm:mb-6">
                        <!-- Search Input - Full Width on Mobile -->
                        <div class="mb-3">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="{{ __('students.Search by name...') }}" 
                                class="w-full text-sm sm:text-base rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        
                        <!-- Filter Grid - Responsive -->
                        <div class="grid grid-cols-2 gap-2 mb-3">
                            @if(auth()->user()->isAdmin())
                                <div class="col-span-2">
                                    <select name="pilot_school_id" class="w-full text-sm rounded-md border-gray-300 shadow-sm">
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
                                <select name="class" class="w-full text-sm rounded-md border-gray-300 shadow-sm">
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
                                <select name="gender" class="w-full text-sm rounded-md border-gray-300 shadow-sm">
                                    <option value="">{{ __('students.All Genders') }}</option>
                                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>{{ __('students.Male') }}</option>
                                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>{{ __('students.Female') }}</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Search & Clear Buttons - Side by Side on Mobile -->
                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <button type="submit" class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                {{ __('students.Search') }}
                            </button>
                            @if(request('search') || request('pilot_school_id') || request('class') || request('gender'))
                                <a href="{{ route('students.index') }}" class="inline-flex items-center justify-center px-3 py-2 bg-gray-300 text-gray-700 rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    {{ __('students.Clear') }}
                                </a>
                            @else
                                <div></div>
                            @endif
                        </div>
                    </form>
                    
                    <!-- Action Buttons - Mobile Optimized -->
                    <div class="border-t pt-3 mb-4">
                        @can('create', App\Models\Student::class)
                            <!-- Add Student Buttons - Full Width on Mobile -->
                            <div class="grid grid-cols-2 gap-2 mb-2">
                                <a href="{{ route('students.create') }}" class="inline-flex items-center justify-center px-3 py-2 bg-green-600 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">{{ __('students.Add New Student') }}</span>
                                    <span class="sm:hidden">{{ __('students.Add One') }}</span>
                                </a>
                                <a href="{{ route('students.bulk-add-form') }}" class="inline-flex items-center justify-center px-3 py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">{{ __('students.Add Multiple') }}</span>
                                    <span class="sm:hidden">{{ __('students.Bulk Add') }}</span>
                                </a>
                            </div>
                        @endcan
                        
                        @can('viewAny', App\Models\Student::class)
                            <!-- Export Button - Full Width on Mobile -->
                            <a href="{{ route('students.export') }}?{{ request()->getQueryString() }}" class="w-full inline-flex items-center justify-center px-3 py-2 bg-purple-600 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                {{ __('students.Export to Excel') }}
                            </a>
                        @endcan
                    </div>

                    <!-- Results Summary -->
                    <div class="text-xs sm:text-sm text-gray-600 mb-3">
                        បង្ហាញ {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} នៃ {{ $students->total() }} សិស្ស
                    </div>

                    <!-- Student Table - Mobile Responsive -->
                    <div class="overflow-x-auto -mx-3 sm:mx-0">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('students.Name') }}
                                    </th>
                                    <th scope="col" class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                        {{ __('students.Grade') }}
                                    </th>
                                    <th scope="col" class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                        {{ __('students.Gender') }}
                                    </th>
                                    <th scope="col" class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                        {{ __('students.School') }}
                                    </th>
                                    <th scope="col" class="px-3 sm:px-6 py-2 sm:py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('students.Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($students as $student)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                                <div class="text-xs text-gray-500 sm:hidden">
                                                    {{ __('students.Grade') }} {{ $student->class }} • {{ $student->gender == 'male' ? __('students.M') : __('students.F') }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900 hidden sm:table-cell">
                                            {{ $student->class }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900 hidden sm:table-cell">
                                            {{ $student->gender == 'male' ? __('students.Male') : __('students.Female') }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900 hidden lg:table-cell">
                                            {{ $student->pilotSchool->school_name ?? 'N/A' }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-center">
                                            <!-- Mobile Dropdown Menu -->
                                            <div class="sm:hidden">
                                                <div class="relative inline-block text-left">
                                                    <button type="button" onclick="toggleDropdown('dropdown-{{ $student->id }}')" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-2 py-1 bg-white text-xs font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        {{ __('students.Actions') }}
                                                        <svg class="-mr-1 ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                    <div id="dropdown-{{ $student->id }}" class="hidden origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                                        <div class="py-1">
                                                            @can('view', $student)
                                                                <a href="{{ route('students.show', $student) }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">{{ __('students.View') }}</a>
                                                            @endcan
                                                            @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                                                                <a href="{{ route('assessments.create', ['student_id' => $student->id]) }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">វាយតម្លៃ</a>
                                                            @endif
                                                            @can('update', $student)
                                                                <a href="{{ route('students.edit', $student) }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">{{ __('students.Edit') }}</a>
                                                            @endcan
                                                            @can('delete', $student)
                                                                <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" onclick="return confirm('{{ __('students.Are you sure?') }}')" class="block w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-50">{{ __('students.Delete') }}</button>
                                                                </form>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Desktop Action Links -->
                                            <div class="hidden sm:flex sm:justify-center sm:space-x-2">
                                                @can('view', $student)
                                                    <a href="{{ route('students.show', $student) }}" class="text-indigo-600 hover:text-indigo-900 text-xs">{{ __('students.View') }}</a>
                                                @endcan
                                                @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                                                    <a href="{{ route('assessments.create', ['student_id' => $student->id]) }}" class="text-green-600 hover:text-green-900 text-xs">វាយតម្លៃ</a>
                                                @endif
                                                @can('update', $student)
                                                    <a href="{{ route('students.edit', $student) }}" class="text-blue-600 hover:text-blue-900 text-xs">{{ __('students.Edit') }}</a>
                                                @endcan
                                                @can('delete', $student)
                                                    <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('{{ __('students.Are you sure?') }}')" class="text-red-600 hover:text-red-900 text-xs">{{ __('students.Delete') }}</button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-3 sm:px-6 py-8 text-center text-sm text-gray-500">
                                            {{ __('students.No students found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination - Mobile Optimized -->
                    @if($students->hasPages())
                        <div class="mt-4">
                            {{ $students->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            // Close all other dropdowns
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                if (el.id !== id) {
                    el.classList.add('hidden');
                }
            });
            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.relative')) {
                document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                    el.classList.add('hidden');
                });
            }
        });
    </script>
</x-app-layout>