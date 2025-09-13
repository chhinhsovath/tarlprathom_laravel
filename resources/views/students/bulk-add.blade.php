<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('students.Add Multiple Students') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('students.bulk-import-form') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    {{ __('students.Import from Excel') }} →
                </a>
                <a href="{{ route('students.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    ← {{ __('students.Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold">{{ __('students.Quick Add Instructions') }}:</p>
                        <ul class="list-disc list-inside mt-1">
                            <li>{{ __('students.Add up to 20 students at once') }}</li>
                            <li>{{ __('students.Fill in the required fields for each student') }}</li>
                            <li>{{ __('students.Click Add More Rows if you need more than 5') }}</li>
                            <li>{{ __('students.All students will be assigned to your school and class') }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form id="bulkAddForm" method="POST" action="{{ route('students.bulk-add') }}">
                        @csrf
                        
                        @if(auth()->user()->isTeacher())
                            <!-- Show school and grade info for teachers -->
                            <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700">{{ __('students.School') }}:</span>
                                        <span class="text-gray-900">{{ auth()->user()->school->school_name ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">{{ __('students.Teacher') }}:</span>
                                        <span class="text-gray-900">{{ auth()->user()->name }}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- School and Grade selection for non-teachers -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="school_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ __('students.School') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select id="school_id" name="school_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">{{ __('students.Select School') }}</option>
                                        @foreach(\App\Models\PilotSchool::orderBy('school_name')->get() as $school)
                                            <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="grade" class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ __('students.Grade') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select id="grade" name="grade" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">{{ __('students.Select Grade') }}</option>
                                        <option value="4">{{ __('students.Grade 4') }}</option>
                                        <option value="5">{{ __('students.Grade 5') }}</option>
                                    </select>
                                </div>
                            </div>
                        @endif

                        <!-- Student Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('students.Student Name') }} <span class="text-red-500">*</span>
                                        </th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('students.Grade') }} <span class="text-red-500">*</span>
                                        </th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('students.Gender') }} <span class="text-red-500">*</span>
                                        </th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('students.Age') }} <span class="text-red-500">*</span>
                                        </th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('students.Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="studentsTableBody">
                                    <!-- Initial 5 rows -->
                                    @for($i = 1; $i <= 5; $i++)
                                    <tr class="student-row">
                                        <td class="px-3 py-2 text-sm text-gray-500">{{ $i }}</td>
                                        <td class="px-3 py-2">
                                            <input type="text" name="students[{{ $i }}][name]" 
                                                   class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                   placeholder="{{ __('students.Enter name') }}">
                                        </td>
                                        <td class="px-3 py-2">
                                            <select name="students[{{ $i }}][grade]" 
                                                    class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">{{ __('students.Select') }}</option>
                                                <option value="4">{{ __('students.Grade 4') }}</option>
                                                <option value="5">{{ __('students.Grade 5') }}</option>
                                            </select>
                                        </td>
                                        <td class="px-3 py-2">
                                            <select name="students[{{ $i }}][gender]" 
                                                    class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">{{ __('students.Select') }}</option>
                                                <option value="male">{{ __('students.Male') }}</option>
                                                <option value="female">{{ __('students.Female') }}</option>
                                            </select>
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" name="students[{{ $i }}][age]" 
                                                   min="3" max="18" 
                                                   class="w-20 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                   placeholder="10">
                                        </td>
                                        <td class="px-3 py-2">
                                            <button type="button" onclick="clearRow(this)" class="text-red-600 hover:text-red-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 flex items-center justify-between">
                            <button type="button" onclick="addMoreRows()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"></path>
                                </svg>
                                {{ __('students.Add 5 More Rows') }}
                            </button>
                            
                            <div class="flex space-x-3">
                                <button type="button" onclick="clearAll()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('students.Clear All') }}
                                </button>
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ __('students.Save All Students') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Fill Options -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('students.Quick Fill Options') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <button type="button" onclick="quickFillGrade(4)" class="p-3 text-sm bg-blue-50 hover:bg-blue-100 rounded-lg text-blue-700 font-medium">
                            {{ __('students.Set All to Grade 4') }}
                        </button>
                        <button type="button" onclick="quickFillGrade(5)" class="p-3 text-sm bg-blue-50 hover:bg-blue-100 rounded-lg text-blue-700 font-medium">
                            {{ __('students.Set All to Grade 5') }}
                        </button>
                        <button type="button" onclick="quickFillAge(10)" class="p-3 text-sm bg-green-50 hover:bg-green-100 rounded-lg text-green-700 font-medium">
                            {{ __('students.Set All Ages to 10') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let rowCount = 5;
        
        function addMoreRows() {
            const tbody = document.getElementById('studentsTableBody');
            for (let i = 1; i <= 5; i++) {
                rowCount++;
                const row = document.createElement('tr');
                row.className = 'student-row';
                row.innerHTML = `
                    <td class="px-3 py-2 text-sm text-gray-500">${rowCount}</td>
                    <td class="px-3 py-2">
                        <input type="text" name="students[${rowCount}][name]" 
                               class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="{{ __('students.Enter name') }}">
                    </td>
                    <td class="px-3 py-2">
                        <select name="students[${rowCount}][grade]" 
                                class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('students.Select') }}</option>
                            <option value="4">{{ __('students.Grade 4') }}</option>
                            <option value="5">{{ __('students.Grade 5') }}</option>
                        </select>
                    </td>
                    <td class="px-3 py-2">
                        <select name="students[${rowCount}][gender]" 
                                class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('students.Select') }}</option>
                            <option value="male">{{ __('students.Male') }}</option>
                            <option value="female">{{ __('students.Female') }}</option>
                        </select>
                    </td>
                    <td class="px-3 py-2">
                        <input type="number" name="students[${rowCount}][age]" 
                               min="3" max="18" 
                               class="w-20 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="10">
                    </td>
                    <td class="px-3 py-2">
                        <button type="button" onclick="clearRow(this)" class="text-red-600 hover:text-red-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            }
            
            if (rowCount >= 20) {
                alert('{{ __("students.Maximum 20 students at once") }}');
                document.querySelector('button[onclick="addMoreRows()"]').disabled = true;
            }
        }
        
        function clearRow(button) {
            const row = button.closest('tr');
            row.querySelectorAll('input').forEach(input => input.value = '');
            row.querySelectorAll('select').forEach(select => select.value = '');
        }
        
        function clearAll() {
            if (confirm('{{ __("students.Are you sure you want to clear all entries?") }}')) {
                document.querySelectorAll('.student-row input').forEach(input => input.value = '');
                document.querySelectorAll('.student-row select').forEach(select => select.value = '');
            }
        }
        
        function quickFillGrade(grade) {
            document.querySelectorAll('select[name*="[grade]"]').forEach(select => {
                if (!select.closest('tr').querySelector('input[name*="[name]"]').value) return;
                select.value = grade;
            });
        }
        
        function quickFillAge(age) {
            document.querySelectorAll('input[name*="[age]"]').forEach(input => {
                if (!input.closest('tr').querySelector('input[name*="[name]"]').value) return;
                input.value = age;
            });
        }
        
        // Form submission handler
        document.getElementById('bulkAddForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Collect only filled rows
            const filledStudents = [];
            document.querySelectorAll('.student-row').forEach((row, index) => {
                const nameInput = row.querySelector('input[name*="[name]"]');
                if (nameInput && nameInput.value.trim()) {
                    const student = {
                        name: nameInput.value.trim(),
                        grade: row.querySelector('select[name*="[grade]"]').value,
                        gender: row.querySelector('select[name*="[gender]"]').value,
                        age: row.querySelector('input[name*="[age]"]').value
                    };
                    
                    // Validate required fields
                    if (!student.grade || !student.gender || !student.age) {
                        alert(`{{ __('students.Please fill all fields for student') }} ${index + 1}: ${student.name}`);
                        e.preventDefault();
                        return false;
                    }
                    
                    filledStudents.push(student);
                }
            });
            
            if (filledStudents.length === 0) {
                alert('{{ __("students.Please add at least one student") }}');
                return false;
            }
            
            // Submit the form
            this.submit();
        });
    </script>
</x-app-layout>