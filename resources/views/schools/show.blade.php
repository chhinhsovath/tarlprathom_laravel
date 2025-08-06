<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                {{ __('School Details') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('schools.edit', $school) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('schools.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                    </svg>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- School Info Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-4 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $school->name }}</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('School Code') }}</span>
                            <p class="text-sm font-medium text-gray-900">{{ $school->school_code }}</p>
                        </div>
                        
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Province') }}</span>
                            <p class="text-sm font-medium text-gray-900">{{ $school->province }}</p>
                        </div>
                        
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('District') }}</span>
                            <p class="text-sm font-medium text-gray-900">{{ $school->district }}</p>
                        </div>
                        
                        @if($school->cluster)
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Cluster') }}</span>
                                <p class="text-sm font-medium text-gray-900">{{ $school->cluster }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <p class="text-xs text-gray-500 uppercase">{{ __('Total Users') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $school->users_count ?? 0 }}</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <p class="text-xs text-gray-500 uppercase">{{ __('Total Students') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $school->students_count ?? 0 }}</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <p class="text-xs text-gray-500 uppercase">{{ __('Teachers') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $teachers->count() }}</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <p class="text-xs text-gray-500 uppercase">{{ __('Mentors') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $mentors->count() }}</p>
                </div>
            </div>

            <!-- Teachers Section -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-4 sm:p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('Teachers') }}</h3>
                        @if(auth()->user()->role === 'admin')
                            <button onclick="openAddTeacherModal()" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ __('Add Teacher') }}
                            </button>
                        @endif
                    </div>
                    
                    @if($teachers->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($teachers as $teacher)
                                <div class="flex items-center gap-3 p-3 border rounded-lg relative group">
                                    @if($teacher->profile_photo)
                                        <div class="h-10 w-10 rounded-full overflow-hidden flex-shrink-0">
                                            <img src="{{ Storage::url($teacher->profile_photo) }}" alt="{{ $teacher->name }}" class="h-full w-full object-cover">
                                        </div>
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $teacher->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $teacher->email }}</p>
                                    </div>
                                    @if(auth()->user()->role === 'admin')
                                        <form action="{{ route('schools.remove-teacher', $school) }}" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                                            <button type="submit" onclick="return confirm('Remove this teacher from the school?')" class="text-red-600 hover:text-red-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">{{ __('No teachers assigned to this school yet.') }}</p>
                    @endif
                </div>
            </div>

            <!-- Mentors Section -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-4 sm:p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('Mentors') }}</h3>
                        @if(auth()->user()->role === 'admin')
                            <button onclick="openAddMentorModal()" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ __('Add Mentor') }}
                            </button>
                        @endif
                    </div>
                    
                    @if($mentors->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($mentors as $mentor)
                                <div class="flex items-center gap-3 p-3 border rounded-lg relative group">
                                    @if($mentor->profile_photo)
                                        <div class="h-10 w-10 rounded-full overflow-hidden flex-shrink-0">
                                            <img src="{{ Storage::url($mentor->profile_photo) }}" alt="{{ $mentor->name }}" class="h-full w-full object-cover">
                                        </div>
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $mentor->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $mentor->email }}</p>
                                    </div>
                                    @if(auth()->user()->role === 'admin')
                                        <form action="{{ route('schools.remove-mentor', $school) }}" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="mentor_id" value="{{ $mentor->id }}">
                                            <button type="submit" onclick="return confirm('Remove this mentor from the school?')" class="text-red-600 hover:text-red-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">{{ __('No mentors assigned to this school yet.') }}</p>
                    @endif
                </div>
            </div>

            <!-- Recent Students Section -->
            @if($recentStudents->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-4 sm:p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('Recent Students') }}</h3>
                            <a href="{{ route('students.index', ['school_id' => $school->id]) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                {{ __('View All') }} →
                            </a>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Name') }}
                                        </th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Grade') }}
                                        </th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Gender') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentStudents as $student)
                                        <tr>
                                            <td class="px-3 py-3 whitespace-nowrap">
                                                <a href="{{ route('students.show', $student) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                                    {{ $student->name }}
                                                </a>
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ __('Grade') }} {{ $student->grade ?? 'N/A' }}
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ ucfirst($student->gender ?? 'N/A') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Actions -->
            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('schools.edit', $school) }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Edit School') }}
                </a>
                
                @if($school->users_count == 0 && $school->students_count == 0)
                    <form action="{{ route('schools.destroy', $school) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('{{ __('Are you sure you want to delete this school?') }}')"
                                class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Delete School') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Teacher Modal -->
    <div id="addTeacherModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity hidden z-50">
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="absolute right-0 top-0 pr-4 pt-4">
                        <button type="button" onclick="closeAddTeacherModal()" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">{{ __("Close") }}</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4" id="modal-title">
                                {{ __('Add Teacher to School') }}
                            </h3>
                            
                            <form action="{{ route('schools.add-teacher', $school) }}" method="POST" id="addTeacherForm">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="teacher_search" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Search Teacher') }}
                                    </label>
                                    <input type="text" id="teacher_search" placeholder="Type to search teachers..." 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        onkeyup="searchTeachers(this.value)">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Available Teachers') }}
                                    </label>
                                    <div id="teachersList" class="max-h-60 overflow-y-auto border rounded-md p-2">
                                        <p class="text-sm text-gray-500 text-center py-4">{{ __('Start typing to search for teachers...') }}</p>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Selected Teachers') }}
                                    </label>
                                    <div id="selectedTeachers" class="border rounded-md p-2 min-h-[60px]">
                                        <p class="text-sm text-gray-500 text-center py-2">{{ __('No teachers selected') }}</p>
                                    </div>
                                </div>
                                
                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-2">
                                    <button type="submit" class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:w-auto">
                                        {{ __('Add Teachers') }}
                                    </button>
                                    <button type="button" onclick="closeAddTeacherModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                        {{ __('Cancel') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Mentor Modal -->
    <div id="addMentorModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity hidden z-50">
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="absolute right-0 top-0 pr-4 pt-4">
                        <button type="button" onclick="closeAddMentorModal()" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">{{ __("Close") }}</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4" id="modal-title">
                                {{ __('Add Mentor to School') }}
                            </h3>
                            
                            <form action="{{ route('schools.add-mentor', $school) }}" method="POST" id="addMentorForm">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="mentor_search" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Search Mentor') }}
                                    </label>
                                    <input type="text" id="mentor_search" placeholder="Type to search mentors..." 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        onkeyup="searchMentors(this.value)">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Available Mentors') }}
                                    </label>
                                    <div id="mentorsList" class="max-h-60 overflow-y-auto border rounded-md p-2">
                                        <p class="text-sm text-gray-500 text-center py-4">{{ __('Start typing to search for mentors...') }}</p>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Selected Mentors') }}
                                    </label>
                                    <div id="selectedMentors" class="border rounded-md p-2 min-h-[60px]">
                                        <p class="text-sm text-gray-500 text-center py-2">{{ __('No mentors selected') }}</p>
                                    </div>
                                </div>
                                
                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-2">
                                    <button type="submit" class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:w-auto">
                                        {{ __('Add Mentors') }}
                                    </button>
                                    <button type="button" onclick="closeAddMentorModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                        {{ __('Cancel') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedTeacherIds = [];
        let searchTimeout;

        function openAddTeacherModal() {
            document.getElementById('addTeacherModal').classList.remove('hidden');
        }

        function closeAddTeacherModal() {
            document.getElementById('addTeacherModal').classList.add('hidden');
            selectedTeacherIds = [];
            updateSelectedTeachersList();
        }

        function searchTeachers(query) {
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                document.getElementById('teachersList').innerHTML = '<p class="text-sm text-gray-500 text-center py-4">Start typing to search for teachers...</p>';
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('schools.search-teachers', $school) }}?q=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        let html = '';
                        if (data.length === 0) {
                            html = '<p class="text-sm text-gray-500 text-center py-4">No teachers found</p>';
                        } else {
                            data.forEach(teacher => {
                                const isSelected = selectedTeacherIds.includes(teacher.id);
                                html += `
                                    <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded cursor-pointer ${isSelected ? 'bg-indigo-50' : ''}" 
                                         onclick="toggleTeacher(${teacher.id}, '${teacher.name}', '${teacher.email}')">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">${teacher.name}</p>
                                            <p class="text-xs text-gray-500">${teacher.email}</p>
                                        </div>
                                        <input type="checkbox" ${isSelected ? 'checked' : ''} class="ml-2">
                                    </div>
                                `;
                            });
                        }
                        document.getElementById('teachersList').innerHTML = html;
                    });
            }, 300);
        }

        function toggleTeacher(id, name, email) {
            const index = selectedTeacherIds.indexOf(id);
            if (index > -1) {
                selectedTeacherIds.splice(index, 1);
            } else {
                selectedTeacherIds.push(id);
            }
            updateSelectedTeachersList();
            searchTeachers(document.getElementById('teacher_search').value);
        }

        function updateSelectedTeachersList() {
            const container = document.getElementById('selectedTeachers');
            if (selectedTeacherIds.length === 0) {
                container.innerHTML = '<p class="text-sm text-gray-500 text-center py-2">No teachers selected</p>';
            } else {
                container.innerHTML = selectedTeacherIds.map(id => 
                    `<input type="hidden" name="teacher_ids[]" value="${id}">`
                ).join('') + `<p class="text-sm text-gray-700">${selectedTeacherIds.length} teacher(s) selected</p>`;
            }
        }

        // Mentor Modal Functions
        let selectedMentorIds = [];
        let mentorSearchTimeout;

        function openAddMentorModal() {
            document.getElementById('addMentorModal').classList.remove('hidden');
        }

        function closeAddMentorModal() {
            document.getElementById('addMentorModal').classList.add('hidden');
            selectedMentorIds = [];
            updateSelectedMentorsList();
        }

        function searchMentors(query) {
            clearTimeout(mentorSearchTimeout);
            
            if (query.length < 2) {
                document.getElementById('mentorsList').innerHTML = '<p class="text-sm text-gray-500 text-center py-4">Start typing to search for mentors...</p>';
                return;
            }

            mentorSearchTimeout = setTimeout(() => {
                fetch(`{{ route('schools.search-mentors', $school) }}?q=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        let html = '';
                        if (data.length === 0) {
                            html = '<p class="text-sm text-gray-500 text-center py-4">No mentors found</p>';
                        } else {
                            data.forEach(mentor => {
                                const isSelected = selectedMentorIds.includes(mentor.id);
                                html += `
                                    <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded cursor-pointer ${isSelected ? 'bg-indigo-50' : ''}" 
                                         onclick="toggleMentor(${mentor.id}, '${mentor.name}', '${mentor.email}')">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">${mentor.name}</p>
                                            <p class="text-xs text-gray-500">${mentor.email}</p>
                                        </div>
                                        <input type="checkbox" ${isSelected ? 'checked' : ''} class="ml-2">
                                    </div>
                                `;
                            });
                        }
                        document.getElementById('mentorsList').innerHTML = html;
                    });
            }, 300);
        }

        function toggleMentor(id, name, email) {
            const index = selectedMentorIds.indexOf(id);
            if (index > -1) {
                selectedMentorIds.splice(index, 1);
            } else {
                selectedMentorIds.push(id);
            }
            updateSelectedMentorsList();
            searchMentors(document.getElementById('mentor_search').value);
        }

        function updateSelectedMentorsList() {
            const container = document.getElementById('selectedMentors');
            if (selectedMentorIds.length === 0) {
                container.innerHTML = '<p class="text-sm text-gray-500 text-center py-2">No mentors selected</p>';
            } else {
                container.innerHTML = selectedMentorIds.map(id => 
                    `<input type="hidden" name="mentor_ids[]" value="${id}">`
                ).join('') + `<p class="text-sm text-gray-700">${selectedMentorIds.length} mentor(s) selected</p>`;
            }
        }
    </script>
</x-app-layout>