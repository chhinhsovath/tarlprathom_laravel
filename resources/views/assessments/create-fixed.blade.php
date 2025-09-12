@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-800">{{ __('Create Assessment') }}</h2>
                <p class="mt-2 text-gray-600">{{ __('Assess student reading levels and track their progress') }}</p>
            </div>

            <div class="p-6">
                <!-- Assessment Settings -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Assessment Settings') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Subject') }}</label>
                            <select id="subjectSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="khmer" {{ $subject === 'khmer' ? 'selected' : '' }}>{{ __('Khmer') }}</option>
                                <option value="math" {{ $subject === 'math' ? 'selected' : '' }}>{{ __('Math') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Assessment Cycle') }}</label>
                            <select id="cycleSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="baseline" {{ $cycle === 'baseline' ? 'selected' : '' }}>{{ __('Baseline') }}</option>
                                <option value="midline" {{ $cycle === 'midline' ? 'selected' : '' }}>{{ __('Midline') }}</option>
                                <option value="endline" {{ $cycle === 'endline' ? 'selected' : '' }}>{{ __('Endline') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Progress Indicator -->
                <div class="mb-6 bg-blue-50 p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-blue-900">{{ __('Assessment Progress') }}</span>
                        <span class="text-sm text-blue-700">
                            <span id="savedCount">0</span> / <span id="totalCount">{{ count($students) }}</span> {{ __('students assessed') }}
                        </span>
                    </div>
                </div>

                <!-- Assessment Period Status -->
                @php
                    // Ensure $periodStatus is always a string
                    if (!isset($periodStatus)) {
                        $periodStatus = null;
                        if (isset($school) && $school) {
                            $periodStatus = method_exists($school, 'getAssessmentPeriodStatus') ? $school->getAssessmentPeriodStatus($cycle) : 'not_set';
                        }
                    }
                @endphp

                @if($school && !auth()->user()->isAdmin() && $periodStatus)
                    <!-- Period Status Messages -->
                    @if($periodStatus === 'not_set')
                        <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        {{ __('Assessment period dates are not configured for this school. Please contact your administrator.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @elseif($periodStatus === 'upcoming')
                        <div class="mb-4 bg-blue-50 border-l-4 border-blue-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        @php
                                            $dateMessage = __('Assessment period dates not configured.');
                                            if($cycle === 'baseline' && isset($school->baseline_start_date) && $school->baseline_start_date) {
                                                try {
                                                    $dateMessage = __('Baseline assessment period starts on') . ' ' . $school->baseline_start_date->format('d/m/Y') . '.';
                                                } catch (\Exception $e) {
                                                    // If format fails, use default message
                                                }
                                            } elseif($cycle === 'midline' && isset($school->midline_start_date) && $school->midline_start_date) {
                                                try {
                                                    $dateMessage = __('Midline assessment period starts on') . ' ' . $school->midline_start_date->format('d/m/Y') . '.';
                                                } catch (\Exception $e) {
                                                    // If format fails, use default message
                                                }
                                            } elseif($cycle === 'endline' && isset($school->endline_start_date) && $school->endline_start_date) {
                                                try {
                                                    $dateMessage = __('Endline assessment period starts on') . ' ' . $school->endline_start_date->format('d/m/Y') . '.';
                                                } catch (\Exception $e) {
                                                    // If format fails, use default message
                                                }
                                            }
                                        @endphp
                                        {{ $dateMessage }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @elseif($periodStatus === 'expired' || $periodStatus === 'ended')
                        <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">
                                        @php
                                            $dateMessage = __('Assessment period has expired.');
                                            if($cycle === 'baseline' && isset($school->baseline_end_date) && $school->baseline_end_date) {
                                                try {
                                                    $dateMessage = __('Baseline assessment period ended on') . ' ' . $school->baseline_end_date->format('d/m/Y') . '.';
                                                } catch (\Exception $e) {
                                                    // If format fails, use default message
                                                }
                                            } elseif($cycle === 'midline' && isset($school->midline_end_date) && $school->midline_end_date) {
                                                try {
                                                    $dateMessage = __('Midline assessment period ended on') . ' ' . $school->midline_end_date->format('d/m/Y') . '.';
                                                } catch (\Exception $e) {
                                                    // If format fails, use default message
                                                }
                                            } elseif($cycle === 'endline' && isset($school->endline_end_date) && $school->endline_end_date) {
                                                try {
                                                    $dateMessage = __('Endline assessment period ended on') . ' ' . $school->endline_end_date->format('d/m/Y') . '.';
                                                } catch (\Exception $e) {
                                                    // If format fails, use default message
                                                }
                                            }
                                        @endphp
                                        {{ $dateMessage }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @elseif($periodStatus === 'active')
                        <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">
                                        @php
                                            $dateMessage = __('Assessment period is currently active.');
                                            if($cycle === 'baseline' && isset($school->baseline_end_date) && $school->baseline_end_date) {
                                                try {
                                                    $dateMessage = __('Baseline assessment period is active until') . ' ' . $school->baseline_end_date->format('d/m/Y') . '.';
                                                } catch (\Exception $e) {
                                                    // If format fails, use default message
                                                }
                                            } elseif($cycle === 'midline' && isset($school->midline_end_date) && $school->midline_end_date) {
                                                try {
                                                    $dateMessage = __('Midline assessment period is active until') . ' ' . $school->midline_end_date->format('d/m/Y') . '.';
                                                } catch (\Exception $e) {
                                                    // If format fails, use default message
                                                }
                                            } elseif($cycle === 'endline' && isset($school->endline_end_date) && $school->endline_end_date) {
                                                try {
                                                    $dateMessage = __('Endline assessment period is active until') . ' ' . $school->endline_end_date->format('d/m/Y') . '.';
                                                } catch (\Exception $e) {
                                                    // If format fails, use default message
                                                }
                                            }
                                        @endphp
                                        {{ $dateMessage }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Students Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Student') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('School') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Grade') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Level') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                                <tr class="student-row" data-student-id="{{ $student->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                                <div class="text-sm text-gray-500">ID: {{ $student->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $student->pilotSchool->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $student->class ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <select class="level-select w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                                data-student-id="{{ $student->id }}">
                                            <option value="">{{ __('Select Level') }}</option>
                                            @if($subject === 'khmer')
                                                <option value="beginner">{{ __('Beginner') }}</option>
                                                <option value="letter">{{ __('Letter') }}</option>
                                                <option value="word">{{ __('Word') }}</option>
                                                <option value="paragraph">{{ __('Paragraph') }}</option>
                                                <option value="story">{{ __('Story') }}</option>
                                            @else
                                                <option value="beginner">{{ __('Beginner') }}</option>
                                                <option value="1_digit">{{ __('1 Digit') }}</option>
                                                <option value="2_digit">{{ __('2 Digit') }}</option>
                                                <option value="subtraction">{{ __('Subtraction') }}</option>
                                                <option value="division">{{ __('Division') }}</option>
                                                <option value="word_problem">{{ __('Word Problem') }}</option>
                                            @endif
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-badge px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ __('Not Assessed') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        {{ __('No students available for assessment') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex justify-end">
                    <button type="button" id="submitAllBtn" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        {{ __('Submit All Assessments') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const subject = '{{ $subject }}';
    const cycle = '{{ $cycle }}';
    const totalCount = {{ count($students) }};
    let savedCount = 0;
    let assessmentData = {};

    // Load existing assessments
    loadExistingAssessments();

    // Handle level selection
    $('.level-select').on('change', function() {
        const studentId = $(this).data('student-id');
        const level = $(this).val();
        
        if (level) {
            saveStudentAssessment(studentId, level);
        } else {
            removeStudentAssessment(studentId);
        }
    });

    // Handle submit all button
    $('#submitAllBtn').on('click', function() {
        submitAllAssessments();
    });

    // Handle subject/cycle change
    $('#subjectSelect, #cycleSelect').on('change', function() {
        const newSubject = $('#subjectSelect').val();
        const newCycle = $('#cycleSelect').val();
        window.location.href = '{{ route("assessments.create") }}?subject=' + newSubject + '&cycle=' + newCycle;
    });

    function loadExistingAssessments() {
        $.ajax({
            url: '{{ route("assessments.index") }}',
            data: {
                subject: subject,
                cycle: cycle
            },
            success: function(response) {
                if (response.assessments) {
                    response.assessments.forEach(function(assessment) {
                        const $row = $(`.student-row[data-student-id="${assessment.student_id}"]`);
                        if ($row.length) {
                            $row.find('.level-select').val(assessment.level);
                            $row.find('.status-badge')
                                .removeClass('bg-gray-100 text-gray-800')
                                .addClass('bg-green-100 text-green-800')
                                .text('{{ __("Assessed") }}');
                            assessmentData[assessment.student_id] = assessment.level;
                            savedCount++;
                        }
                    });
                    updateProgress();
                }
            }
        });
    }

    function saveStudentAssessment(studentId, level) {
        $.ajax({
            url: '{{ route("api.assessments.save-student") }}',
            method: 'POST',
            data: {
                student_id: studentId,
                subject: subject,
                cycle: cycle,
                level: level,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    const $row = $(`.student-row[data-student-id="${studentId}"]`);
                    $row.find('.status-badge')
                        .removeClass('bg-gray-100 text-gray-800')
                        .addClass('bg-green-100 text-green-800')
                        .text('{{ __("Assessed") }}');
                    
                    if (!assessmentData[studentId]) {
                        savedCount++;
                    }
                    assessmentData[studentId] = level;
                    updateProgress();
                }
            }
        });
    }

    function removeStudentAssessment(studentId) {
        // Implementation for removing assessment if needed
        delete assessmentData[studentId];
        savedCount--;
        updateProgress();
        
        const $row = $(`.student-row[data-student-id="${studentId}"]`);
        $row.find('.status-badge')
            .removeClass('bg-green-100 text-green-800')
            .addClass('bg-gray-100 text-gray-800')
            .text('{{ __("Not Assessed") }}');
    }

    function updateProgress() {
        $('#savedCount').text(savedCount);
        $('#submitAllBtn').prop('disabled', savedCount === 0);
    }

    function submitAllAssessments() {
        if (Object.keys(assessmentData).length === 0) {
            alert('{{ __("No assessments to submit") }}');
            return;
        }

        $.ajax({
            url: '{{ route("api.assessments.submit-all") }}',
            method: 'POST',
            data: {
                assessments: assessmentData,
                subject: subject,
                cycle: cycle,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    alert('{{ __("All assessments submitted successfully") }}');
                    window.location.href = '{{ route("verification.index") }}';
                }
            }
        });
    }
});
</script>
@endpush
@endsection