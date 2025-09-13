@include('components.translations')

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                {{ trans_km('Add Multiple Students') }}
            </h2>
            <div class="flex space-x-2 text-xs sm:text-sm">
                <a href="{{ route('students.index') }}" class="text-gray-600 hover:text-gray-900">
                    ← {{ trans_km('Back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Mobile-optimized Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 sm:p-4 mb-4 sm:mb-6">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-400 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-xs sm:text-sm text-blue-800">
                        <p class="font-semibold mb-1">{{ trans_km('Quick Add Instructions') }}:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            <li>{{ trans_km('Add up to 20 students at once') }}</li>
                            <li>{{ trans_km('Fill all required fields') }}</li>
                            <li class="hidden sm:list-item">{{ trans_km('Tap Add More for additional students') }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <form id="bulkAddForm" method="POST" action="{{ route('students.bulk-add') }}">
                @csrf
                
                @if(auth()->user()->isTeacher())
                    <!-- Teacher Info Bar - Mobile Optimized -->
                    <div class="bg-gray-50 rounded-lg p-3 mb-4 text-xs sm:text-sm">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div>
                                <span class="font-medium text-gray-600">{{ trans_km('School') }}:</span>
                                <span class="text-gray-900 block sm:inline">{{ auth()->user()->school->school_name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-600">{{ trans_km('Teacher') }}:</span>
                                <span class="text-gray-900 block sm:inline">{{ auth()->user()->name }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- School and Grade selection for non-teachers - Mobile Optimized -->
                    <div class="bg-white rounded-lg shadow-sm p-3 sm:p-4 mb-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="school_id" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                                    {{ trans_km('School') }} <span class="text-red-500">*</span>
                                </label>
                                <select id="school_id" name="school_id" required class="w-full text-sm rounded-md border-gray-300">
                                    <option value="">{{ trans_km('Select School') }}</option>
                                    @foreach(\App\Models\PilotSchool::orderBy('school_name')->get() as $school)
                                        <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="default_grade" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                                    {{ trans_km('Default Grade') }}
                                </label>
                                <select id="default_grade" name="default_grade" class="w-full text-sm rounded-md border-gray-300">
                                    <option value="">{{ trans_km('Select Grade') }}</option>
                                    <option value="4">{{ trans_km('Grade') }} 4</option>
                                    <option value="5">{{ trans_km('Grade') }} 5</option>
                                </select>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Student Cards Container - Mobile First Design -->
                <div id="studentsContainer" class="space-y-3 mb-4">
                    <!-- Initial 3 student cards for mobile -->
                    @for($i = 1; $i <= 3; $i++)
                    <div class="student-card bg-white rounded-lg shadow-sm p-4 relative" data-index="{{ $i }}">
                        <!-- Card Header with Number and Delete -->
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm font-semibold text-gray-600">{{ trans_km('Student') }} #{{ $i }}</span>
                            <button type="button" onclick="clearCard(this)" class="text-red-500 hover:text-red-700 p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Name Field - Full Width -->
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                {{ trans_km('Name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="students[{{ $i }}][name]" 
                                   class="w-full text-sm rounded-md border-gray-300"
                                   placeholder="{{ trans_km('Enter full name') }}">
                        </div>
                        
                        <!-- Three Column Grid for Mobile -->
                        <div class="grid grid-cols-3 gap-2">
                            <!-- Grade -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">
                                    {{ trans_km('Grade') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="students[{{ $i }}][grade]" 
                                        class="w-full text-sm rounded-md border-gray-300 grade-select">
                                    <option value="">--</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            
                            <!-- Gender -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">
                                    {{ trans_km('Gender') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="students[{{ $i }}][gender]" 
                                        class="w-full text-sm rounded-md border-gray-300">
                                    <option value="">--</option>
                                    <option value="male">{{ trans_km('Male') }}</option>
                                    <option value="female">{{ trans_km('Female') }}</option>
                                </select>
                            </div>
                            
                            <!-- Age -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">
                                    {{ trans_km('Age') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="students[{{ $i }}][age]" 
                                       min="3" max="18" 
                                       class="w-full text-sm rounded-md border-gray-300"
                                       placeholder="10">
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>

                <!-- Add More Button - Mobile Optimized -->
                <button type="button" 
                        onclick="addMoreCards()" 
                        class="w-full sm:w-auto mb-4 px-4 py-3 sm:py-2 border-2 border-dashed border-gray-300 rounded-lg text-sm font-medium text-gray-600 hover:border-gray-400 hover:text-gray-700 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"></path>
                    </svg>
                    {{ trans_km('Add More Students') }}
                </button>

                <!-- Quick Actions - Mobile Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-4">
                    <button type="button" 
                            onclick="quickFillGrade(4)" 
                            class="px-3 py-2 text-xs sm:text-sm bg-blue-50 hover:bg-blue-100 rounded-lg text-blue-700 font-medium">
                        {{ trans_km('All Grade 4') }}
                    </button>
                    <button type="button" 
                            onclick="quickFillGrade(5)" 
                            class="px-3 py-2 text-xs sm:text-sm bg-blue-50 hover:bg-blue-100 rounded-lg text-blue-700 font-medium">
                        {{ trans_km('All Grade 5') }}
                    </button>
                    <button type="button" 
                            onclick="quickFillAge(10)" 
                            class="px-3 py-2 text-xs sm:text-sm bg-green-50 hover:bg-green-100 rounded-lg text-green-700 font-medium">
                        {{ trans_km('Age 10') }}
                    </button>
                    <button type="button" 
                            onclick="clearAll()" 
                            class="px-3 py-2 text-xs sm:text-sm bg-red-50 hover:bg-red-100 rounded-lg text-red-700 font-medium">
                        {{ trans_km('Clear All') }}
                    </button>
                </div>

                <!-- Submit Buttons - Fixed Bottom on Mobile -->
                <div class="fixed bottom-0 left-0 right-0 bg-white border-t p-3 sm:relative sm:border-0 sm:p-0 sm:bg-transparent">
                    <div class="flex gap-2">
                        <a href="{{ route('students.index') }}" 
                           class="flex-1 sm:flex-none px-4 py-3 sm:py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 text-center">
                            {{ trans_km('Cancel') }}
                        </a>
                        <button type="submit" 
                                class="flex-1 sm:flex-none px-4 py-3 sm:py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-medium flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ trans_km('Save All Students') }}
                        </button>
                    </div>
                </div>
                
                <!-- Spacer for fixed bottom buttons on mobile -->
                <div class="h-20 sm:hidden"></div>
            </form>
        </div>
    </div>

    <script>
        let cardCount = 3;
        const maxCards = 20;
        
        // Set default grade if selected
        document.addEventListener('DOMContentLoaded', function() {
            const defaultGrade = document.getElementById('default_grade');
            if (defaultGrade) {
                defaultGrade.addEventListener('change', function() {
                    if (this.value) {
                        document.querySelectorAll('.grade-select').forEach(select => {
                            if (!select.value) {
                                select.value = this.value;
                            }
                        });
                    }
                });
            }
        });
        
        function addMoreCards() {
            if (cardCount >= maxCards) {
                alert('{{ trans_km("Maximum 20 students at once") }}')
                return;
            }
            
            const container = document.getElementById('studentsContainer');
            const cardsToAdd = window.innerWidth < 640 ? 2 : 3; // Add 2 on mobile, 3 on desktop
            
            for (let i = 0; i < cardsToAdd && cardCount < maxCards; i++) {
                cardCount++;
                const card = createStudentCard(cardCount);
                container.appendChild(card);
            }
            
            // Apply default grade to new cards
            const defaultGrade = document.getElementById('default_grade');
            if (defaultGrade && defaultGrade.value) {
                container.querySelectorAll('.student-card:last-child .grade-select').forEach(select => {
                    select.value = defaultGrade.value;
                });
            }
        }
        
        function createStudentCard(index) {
            const div = document.createElement('div');
            div.className = 'student-card bg-white rounded-lg shadow-sm p-4 relative';
            div.dataset.index = index;
            div.innerHTML = `
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm font-semibold text-gray-600">{{ trans_km('Student') }} #${index}</span>
                    <button type="button" onclick="clearCard(this)" class="text-red-500 hover:text-red-700 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        {{ trans_km('Name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="students[${index}][name]" 
                           class="w-full text-sm rounded-md border-gray-300"
                           placeholder="{{ trans_km('Enter full name') }}">
                </div>
                
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            {{ trans_km('Grade') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="students[${index}][grade]" 
                                class="w-full text-sm rounded-md border-gray-300 grade-select">
                            <option value="">--</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            {{ trans_km('Gender') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="students[${index}][gender]" 
                                class="w-full text-sm rounded-md border-gray-300">
                            <option value="">--</option>
                            <option value="male">{{ trans_km('Male') }}</option>
                            <option value="female">{{ trans_km('Female') }}</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            {{ trans_km('Age') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="students[${index}][age]" 
                               min="3" max="18" 
                               class="w-full text-sm rounded-md border-gray-300"
                               placeholder="10">
                    </div>
                </div>
            `;
            return div;
        }
        
        function clearCard(button) {
            const card = button.closest('.student-card');
            card.querySelectorAll('input').forEach(input => input.value = '');
            card.querySelectorAll('select').forEach(select => select.value = '');
            card.style.opacity = '0.5';
        }
        
        function clearAll() {
            if (confirm('{{ trans_km("Are you sure you want to clear all entries?") }}')) {
                document.querySelectorAll('.student-card').forEach(card => {
                    card.querySelectorAll('input').forEach(input => input.value = '');
                    card.querySelectorAll('select').forEach(select => select.value = '');
                    card.style.opacity = '1';
                });
            }
        }
        
        function quickFillGrade(grade) {
            document.querySelectorAll('select[name*="[grade]"]').forEach(select => {
                const card = select.closest('.student-card');
                const nameInput = card.querySelector('input[name*="[name]"]');
                if (nameInput && nameInput.value.trim()) {
                    select.value = grade;
                }
            });
        }
        
        function quickFillAge(age) {
            document.querySelectorAll('input[name*="[age]"]').forEach(input => {
                const card = input.closest('.student-card');
                const nameInput = card.querySelector('input[name*="[name]"]');
                if (nameInput && nameInput.value.trim()) {
                    input.value = age;
                }
            });
        }
        
        // Form submission handler
        document.getElementById('bulkAddForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const filledStudents = [];
            let hasError = false;
            
            document.querySelectorAll('.student-card').forEach((card, index) => {
                const nameInput = card.querySelector('input[name*="[name]"]');
                if (nameInput && nameInput.value.trim()) {
                    const gradeSelect = card.querySelector('select[name*="[grade]"]');
                    const genderSelect = card.querySelector('select[name*="[gender]"]');
                    const ageInput = card.querySelector('input[name*="[age]"]');
                    
                    if (!gradeSelect.value || !genderSelect.value || !ageInput.value) {
                        alert(`{{ trans_km('Please fill all fields for student') }} #${card.dataset.index}`);
                        hasError = true;
                        
                        // Highlight missing fields
                        if (!gradeSelect.value) gradeSelect.classList.add('border-red-500');
                        if (!genderSelect.value) genderSelect.classList.add('border-red-500');
                        if (!ageInput.value) ageInput.classList.add('border-red-500');
                        
                        return false;
                    }
                    
                    filledStudents.push({
                        name: nameInput.value.trim(),
                        grade: gradeSelect.value,
                        gender: genderSelect.value,
                        age: ageInput.value
                    });
                }
            });
            
            if (hasError) return false;
            
            if (filledStudents.length === 0) {
                alert('{{ trans_km("Please add at least one student") }}')
                return false;
            }
            
            // Show loading state on submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> {{ trans_km("Saving...") }}';
            
            this.submit();
        });
        
        // Remove red border when field is filled
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('border-red-500')) {
                if (e.target.value) {
                    e.target.classList.remove('border-red-500');
                }
            }
        });
    </script>
</x-app-layout>