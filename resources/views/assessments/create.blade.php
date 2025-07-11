<x-app-layout>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <!-- Subject and Cycle Selection -->
                    <div class="mb-6 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Subject') }}:</label>
                                <select id="subject" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="khmer" {{ $subject === 'khmer' ? 'selected' : '' }}>{{ __('Khmer') }}</option>
                                    <option value="math" {{ $subject === 'math' ? 'selected' : '' }}>{{ __('Math') }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Test Cycle') }}:</label>
                                <select id="cycle" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="baseline" {{ $cycle === 'baseline' ? 'selected' : '' }}>{{ __('Baseline') }}</option>
                                    <option value="midline" {{ $cycle === 'midline' ? 'selected' : '' }}>{{ __('Midline') }}</option>
                                    <option value="endline" {{ $cycle === 'endline' ? 'selected' : '' }}>{{ __('Endline') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            <span id="savedCount">0</span> / <span id="totalCount">{{ count($students) }}</span> {{ __('students assessed') }}
                        </div>
                    </div>

                    <!-- Assessment Table -->
                    @if(count($students) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Student Name') }}
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" colspan="2">
                                        {{ __('Student Gender') }}
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" colspan="7">
                                        {{ __('Student Level') }}
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        
                                    </th>
                                </tr>
                                <tr>
                                    <th class="px-4 py-2"></th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">{{ __('Male') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">{{ __('Female') }}</th>
                                    @if($subject === 'khmer')
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">{{ __('Beginner') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">{{ __('Letter') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">{{ __('Word') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">{{ __('Paragraph') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">{{ __('Story') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">{{ __('Comp. 1') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">{{ __('Comp. 2') }}</th>
                                    @else
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">{{ __('Beginner') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">{{ __('1-Digit') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">{{ __('2-Digit') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">{{ __('Subtraction') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">{{ __('Division') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">{{ __('Word Problem') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500"></th>
                                    @endif
                                    <th class="px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($students as $student)
                                <tr class="student-row" data-student-id="{{ $student->id }}" data-saved="false">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $student->name }}
                                    </td>
                                    <!-- Gender -->
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="gender_{{ $student->id }}" value="male" 
                                               {{ $student->gender === 'male' ? 'checked' : '' }}
                                               class="gender-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="gender_{{ $student->id }}" value="female" 
                                               {{ $student->gender === 'female' ? 'checked' : '' }}
                                               class="gender-radio">
                                    </td>
                                    <!-- Levels -->
                                    @if($subject === 'khmer')
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Beginner" class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Letter Reader" class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Word Level" class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Paragraph Reader" class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Story Reader" class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Comp. 1" class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Comp. 2" class="level-radio">
                                    </td>
                                    @else
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Beginner" class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="1-Digit" class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="2-Digit" class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Subtraction" class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Division" class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Word Problem" class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <!-- Empty cell for Math to align with submit button -->
                                    </td>
                                    @endif
                                    <td class="px-4 py-4 text-center">
                                        <button type="button" 
                                                class="submit-btn inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                                onclick="submitStudent({{ $student->id }})"
                                                disabled>
                                            {{ __('Submit') }}
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Submit All Button -->
                    <div class="mt-6 flex justify-center">
                        <button type="button" 
                                id="submitAllBtn"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                onclick="submitAll()">
                            {{ __('Submit') }}
                        </button>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No eligible students') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if($subject === 'khmer' && in_array($cycle, ['midline', 'endline']))
                                {{ __('No students from baseline assessment (Beginner to Story level) found for this cycle.') }}
                            @elseif($subject === 'math' && in_array($cycle, ['midline', 'endline']))
                                {{ __('No students from baseline assessment (Beginner to Subtraction level) found for this cycle.') }}
                            @else
                                {{ __('No students found.') }}
                            @endif
                        </p>
                        @if($cycle !== 'baseline')
                        <div class="mt-6">
                            <a href="{{ route('assessments.create', ['subject' => $subject, 'cycle' => 'baseline']) }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Go to Baseline Assessment') }}
                            </a>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let savedCount = 0;
        const totalCount = {{ count($students) }};
        
        $(document).ready(function() {
            // Load existing assessments
            loadExistingAssessments();
            
            // Enable submit button when both gender and level are selected
            $('.gender-radio, .level-radio').change(function() {
                const row = $(this).closest('tr');
                const studentId = row.data('student-id');
                const hasGender = $(`input[name="gender_${studentId}"]:checked`).length > 0;
                const hasLevel = $(`input[name="level_${studentId}"]:checked`).length > 0;
                
                if (hasGender && hasLevel) {
                    row.find('.submit-btn').prop('disabled', false);
                } else {
                    row.find('.submit-btn').prop('disabled', true);
                }
            });
            
            // Subject or cycle change
            $('#subject, #cycle').change(function() {
                // Reload page with new parameters to get the correct student list
                const subject = $('#subject').val();
                const cycle = $('#cycle').val();
                window.location.href = '{{ route("assessments.create") }}?subject=' + subject + '&cycle=' + cycle;
            });
        });
        
        function loadExistingAssessments() {
            const subject = $('#subject').val();
            const cycle = $('#cycle').val();
            
            // Reset all
            savedCount = 0;
            $('.student-row').each(function() {
                $(this).attr('data-saved', 'false');
                $(this).removeClass('bg-green-50');
                $(this).find('.submit-btn').text('{{ __("Submit") }}').removeClass('bg-green-600').addClass('bg-blue-600');
            });
            
            // Load existing assessments via AJAX
            showLoading('{{ __("Loading existing assessments...") }}');
            $.ajax({
                url: '{{ route("assessments.index") }}',
                data: {
                    subject: subject,
                    cycle: cycle,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    // Process existing assessments
                    if (data.assessments) {
                        data.assessments.forEach(function(assessment) {
                            const row = $(`.student-row[data-student-id="${assessment.student_id}"]`);
                            if (row.length) {
                                // Mark the level
                                row.find(`input[name="level_${assessment.student_id}"][value="${assessment.level}"]`).prop('checked', true);
                                
                                // Mark as saved
                                row.attr('data-saved', 'true');
                                row.addClass('bg-green-50');
                                row.find('.submit-btn').text('{{ __("Saved") }}').removeClass('bg-blue-600').addClass('bg-green-600');
                                
                                // Enable the button since it's already saved
                                row.find('.submit-btn').prop('disabled', false);
                            }
                        });
                    }
                    updateSavedCount();
                },
                complete: function() {
                    hideLoading();
                }
            });
        }
        
        function submitStudent(studentId) {
            const row = $(`.student-row[data-student-id="${studentId}"]`);
            const gender = $(`input[name="gender_${studentId}"]:checked`).val();
            const level = $(`input[name="level_${studentId}"]:checked`).val();
            const subject = $('#subject').val();
            const cycle = $('#cycle').val();
            
            if (!gender || !level) {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("Error") }}',
                    text: '{{ __("Please select both gender and level") }}'
                });
                return;
            }
            
            // Show loading on button
            const btn = row.find('.submit-btn');
            setButtonLoading(btn, true);
            
            $.ajax({
                url: '{{ route("api.assessments.save-student") }}',
                method: 'POST',
                data: {
                    student_id: studentId,
                    gender: gender,
                    level: level,
                    subject: subject,
                    cycle: cycle,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        row.attr('data-saved', 'true');
                        row.addClass('bg-green-50');
                        btn.text('{{ __("Saved") }}').removeClass('bg-blue-600').addClass('bg-green-600');
                        updateSavedCount();
                        
                        // Show toast
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        
                        Toast.fire({
                            icon: 'success',
                            title: '{{ __("Assessment saved") }}'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("Error") }}',
                        text: xhr.responseJSON?.message || '{{ __("Failed to save assessment") }}'
                    });
                },
                complete: function() {
                    setButtonLoading(btn, false);
                }
            });
        }
        
        function updateSavedCount() {
            savedCount = $('.student-row[data-saved="true"]').length;
            $('#savedCount').text(savedCount);
        }
        
        function submitAll() {
            if (savedCount === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: '{{ __("Warning") }}',
                    text: '{{ __("No assessments have been saved yet") }}'
                });
                return;
            }
            
            Swal.fire({
                title: '{{ __("Submit All Assessments?") }}',
                text: `{{ __("You have assessed") }} ${savedCount} {{ __("out of") }} ${totalCount} {{ __("students") }}. {{ __("Do you want to submit?") }}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ __("Yes, submit all") }}',
                cancelButtonText: '{{ __("Cancel") }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading('{{ __("Submitting assessments...") }}');
                    $.ajax({
                        url: '{{ route("api.assessments.submit-all") }}',
                        method: 'POST',
                        data: {
                            subject: $('#subject').val(),
                            cycle: $('#cycle').val(),
                            submitted_count: savedCount,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '{{ __("Success") }}',
                                    text: response.message,
                                    confirmButtonText: '{{ __("OK") }}'
                                }).then(() => {
                                    window.location.href = response.redirect;
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: '{{ __("Error") }}',
                                text: xhr.responseJSON?.message || '{{ __("Failed to submit assessments") }}'
                            });
                        },
                        complete: function() {
                            hideLoading();
                        }
                    });
                }
            });
        }
    </script>
    @endpush
</x-app-layout>