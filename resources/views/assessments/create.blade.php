<x-app-layout>
    @php
        // Ensure all variables are the correct type to prevent htmlspecialchars errors
        $subject = is_array($subject ?? null) ? reset($subject) : (string)($subject ?? 'khmer');
        $cycle = is_array($cycle ?? null) ? reset($cycle) : (string)($cycle ?? 'baseline');
        $periodStatus = is_array($periodStatus ?? null) ? reset($periodStatus) : (string)($periodStatus ?? 'not_set');
        $selectedStudentId = is_array($selectedStudentId ?? null) ? reset($selectedStudentId) : $selectedStudentId;
    @endphp
    <style>
        /* Sticky table styles */
        .sticky {
            position: sticky !important;
        }
        
        /* Ensure sticky row stays on top */
        thead tr.sticky {
            position: sticky !important;
            top: 0 !important;
            z-index: 20 !important;
        }
        
        /* Add shadow to sticky row */
        thead tr.sticky {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        /* Style for sticky name column */
        .sticky.left-0 {
            box-shadow: 2px 0 4px rgba(0,0,0,0.1);
        }
        
        /* Ensure proper z-index layering */
        thead th.sticky.left-0 {
            z-index: 30 !important;
        }
        
        /* Style for saved rows */
        .student-row[data-saved="true"] td.sticky {
            background-color: #f0fdf4;
        }
        
        /* Add scroll indicators */
        .table-container {
            position: relative;
        }
        
        .table-container::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40px;
            background: linear-gradient(to top, rgba(255,255,255,1), rgba(255,255,255,0));
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .table-container.scrollable::after {
            opacity: 1;
        }
    </style>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <!-- Subject and Cycle Selection -->
                    <div class="mb-6 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('assessment.Subject') }}:</label>
                                <select id="subject" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="khmer" {{ $subject === 'khmer' ? 'selected' : '' }}>{{ __('assessment.Khmer') }}</option>
                                    <option value="math" {{ $subject === 'math' ? 'selected' : '' }}>{{ __('assessment.Math') }}</option>
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
                            <span id="savedCount">0</span> / <span id="totalCount">{{ count($students) }}</span> {{ __('students.Students') }} {{ __('assessment.assessed') }}
                        </div>
                    </div>

                    <!-- Assessment Period Notice -->
                    @php
                        // Use periodStatus from controller, don't recalculate
                        // Map 'ended' to 'expired' for backward compatibility
                        if (isset($periodStatus) && $periodStatus === 'ended') {
                            $periodStatus = 'expired';
                        }
                    @endphp
                    
                    @if($school && !auth()->user()->isAdmin() && $periodStatus)
                        
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
                                            {{ __('Assessment dates have not been set for your school. Please contact your administrator.') }}
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
                                                $dateField = $cycle . '_start_date';
                                                $startDate = $school->$dateField ?? null;
                                                $dateString = '';
                                                if ($startDate) {
                                                    if (is_object($startDate) && method_exists($startDate, 'format')) {
                                                        $dateString = $startDate->format('d/m/Y');
                                                    } elseif (is_array($startDate)) {
                                                        // Handle array case - shouldn't happen but just in case
                                                        $dateString = '';
                                                    } else {
                                                        // Cast to string for any other type
                                                        $dateString = (string) $startDate;
                                                    }
                                                }
                                            @endphp
                                            @if($dateString)
                                                {{ __('Assessment period starts on') }} {{ (string) $dateString }}.
                                            @else
                                                {{ __('Assessment period dates not configured.') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @elseif($periodStatus === 'expired')
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
                                                $endDateField = $cycle . '_end_date';
                                                $endDate = $school->$endDateField ?? null;
                                                $endDateString = '';
                                                if ($endDate) {
                                                    if (is_object($endDate) && method_exists($endDate, 'format')) {
                                                        $endDateString = $endDate->format('d/m/Y');
                                                    } elseif (is_array($endDate)) {
                                                        // Handle array case - shouldn't happen but just in case
                                                        $endDateString = '';
                                                    } else {
                                                        // Cast to string for any other type
                                                        $endDateString = (string) $endDate;
                                                    }
                                                }
                                            @endphp
                                            @if($endDateString)
                                                {{ __('Assessment period ended on') }} {{ (string) $endDateString }}.
                                            @else
                                                {{ __('Assessment period has expired.') }}
                                            @endif
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
                                                $activeEndDateField = $cycle . '_end_date';
                                                $activeEndDate = $school->$activeEndDateField ?? null;
                                                $activeEndDateString = '';
                                                if ($activeEndDate) {
                                                    if (is_object($activeEndDate) && method_exists($activeEndDate, 'format')) {
                                                        $activeEndDateString = $activeEndDate->format('d/m/Y');
                                                    } elseif (is_array($activeEndDate)) {
                                                        // Handle array case - shouldn't happen but just in case
                                                        $activeEndDateString = '';
                                                    } else {
                                                        // Cast to string for any other type
                                                        $activeEndDateString = (string) $activeEndDate;
                                                    }
                                                }
                                            @endphp
                                            @if($activeEndDateString)
                                                {{ __('Assessment period is active until') }} {{ (string) $activeEndDateString }}.
                                            @else
                                                {{ __('Assessment period is currently active.') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Locked Assessments Notice -->
                    @php
                        $lockedCount = $students->filter(function($student) {
                            return $student->is_assessment_locked;
                        })->count();
                    @endphp
                    @if($lockedCount > 0 && !auth()->user()->isAdmin())
                        <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        @php
                                            $translationKey = ':count assessment(s) are locked by administrators and cannot be edited.';
                                            $translationParams = ['count' => $lockedCount];
                                            $translatedText = __($translationKey, $translationParams);
                                            if (is_array($translatedText)) {
                                                $translatedText = json_encode($translatedText);
                                            }
                                        @endphp
                                        {{ $translatedText }}
                                        {{ __('You can only view these assessments.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Floating Column Headers (Mobile) -->
                    <div class="md:hidden fixed bottom-4 left-4 right-4 bg-white rounded-lg shadow-lg p-3 z-50" style="display: none;" id="floatingHeaders">
                        <div class="text-xs text-gray-600 font-medium">
                            <div class="text-center">
                                <div class="text-gray-500">{{ __('assessment.Levels') }}</div>
                                <div class="text-xs mt-1">
                                    @if($subject === 'khmer')
                                        {{ __('Beg → Let → Wrd → Par → Sto → C1 → C2') }}
                                    @else
                                        {{ __('Beg → 1D → 2D → Sub → Div → WP') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assessment Table -->
                    @if(count($students) > 0)
                    <div class="relative table-container" style="height: calc(100vh - 250px); overflow: auto;">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-20">
                                        {{ __('assessment.Student Name') }}
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('assessment.Latest Level') }}
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" colspan="7">
                                        {{ __('assessment.Student Level') }}
                                    </th>
                                </tr>
                                <tr class="sticky top-0 z-10 shadow-sm bg-gray-50">
                                    <th class="px-4 py-2 sticky left-0 bg-gray-50 z-20 border-b-2 border-gray-300"></th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 bg-gray-50 border-b-2 border-gray-300"></th>
                                    @if($subject === 'khmer')
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 bg-gray-50 border-b-2 border-gray-300">{{ __('assessment.Beginner') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 bg-gray-50 border-b-2 border-gray-300">{{ __('assessment.Letter') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 bg-gray-50 border-b-2 border-gray-300">{{ __('assessment.Word') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 bg-gray-50 border-b-2 border-gray-300">{{ __('assessment.Paragraph') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 bg-gray-50 border-b-2 border-gray-300">{{ __('assessment.Story') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 bg-gray-50 border-b-2 border-gray-300">{{ __('assessment.Comp. 1') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 bg-gray-50 border-b-2 border-gray-300">{{ __('assessment.Comp. 2') }}</th>
                                    @else
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 bg-gray-50 border-b-2 border-gray-300">{{ __('assessment.Beginner') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 bg-gray-50 border-b-2 border-gray-300">{{ __('assessment.1-Digit') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 bg-gray-50 border-b-2 border-gray-300">{{ __('assessment.2-Digit') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 bg-gray-50 border-b-2 border-gray-300">{{ __('assessment.Subtraction') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 bg-gray-50 border-b-2 border-gray-300">{{ __('assessment.Division') }}</th>
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 bg-gray-50 border-b-2 border-gray-300">{{ __('assessment.Word Problem') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($students as $student)
                                <tr class="student-row {{ $student->is_assessment_locked ? 'opacity-60' : '' }}" data-student-id="{{ $student->id }}" data-saved="false" {{ $student->is_assessment_locked ? 'data-locked="true"' : '' }}>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10" data-student-gender="{{ $student->gender }}">
                                        <div class="flex items-center">
                                            {{ $student->name }}
                                            @if($student->has_assessment)
                                                @if($student->is_assessment_locked)
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                        <svg class="-ml-0.5 mr-1 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3" />
                                                        </svg>
                                                        {{ __('assessment.Locked') }}
                                                    </span>
                                                @else
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ __('assessment.Assessed') }}
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <!-- Previous Assessment -->
                                    <td class="px-2 py-4 text-center">
                                        <div class="text-sm">
                                            @if($student->previous_assessment && is_object($student->previous_assessment))
                                                @php
                                                    $prevLevel = $student->previous_assessment->level ?? '';
                                                    $prevCycle = $student->previous_assessment->cycle ?? '';
                                                    // Ensure they are strings
                                                    $prevLevel = is_array($prevLevel) ? json_encode($prevLevel) : (string)$prevLevel;
                                                    $prevCycle = is_array($prevCycle) ? json_encode($prevCycle) : (string)$prevCycle;
                                                @endphp
                                                <span class="font-medium text-gray-700">{{ __($prevLevel) }}</span>
                                                <br>
                                                <span class="text-xs text-gray-500">{{ __(ucfirst($prevCycle)) }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <!-- Levels -->
                                    @if($subject === 'khmer')
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Beginner" {{ $student->is_assessment_locked ? 'disabled' : '' }} {{ $student->has_assessment && $student->assessment_level == 'Beginner' ? 'checked' : '' }} class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Letter" {{ $student->is_assessment_locked ? 'disabled' : '' }} {{ $student->has_assessment && $student->assessment_level == 'Letter' ? 'checked' : '' }} class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Word" {{ $student->is_assessment_locked ? 'disabled' : '' }} {{ $student->has_assessment && $student->assessment_level == 'Word' ? 'checked' : '' }} class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Paragraph" {{ $student->is_assessment_locked ? 'disabled' : '' }} {{ $student->has_assessment && $student->assessment_level == 'Paragraph' ? 'checked' : '' }} class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Story" {{ $student->is_assessment_locked ? 'disabled' : '' }} {{ $student->has_assessment && $student->assessment_level == 'Story' ? 'checked' : '' }} class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Comp. 1" {{ $student->is_assessment_locked ? 'disabled' : '' }} {{ $student->has_assessment && $student->assessment_level == 'Comp. 1' ? 'checked' : '' }} class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Comp. 2" {{ $student->is_assessment_locked ? 'disabled' : '' }} {{ $student->has_assessment && $student->assessment_level == 'Comp. 2' ? 'checked' : '' }} class="level-radio">
                                    </td>
                                    @else
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Beginner" {{ $student->is_assessment_locked ? 'disabled' : '' }} {{ $student->has_assessment && $student->assessment_level == 'Beginner' ? 'checked' : '' }} class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="1-Digit" {{ $student->is_assessment_locked ? 'disabled' : '' }} {{ $student->has_assessment && $student->assessment_level == '1-Digit' ? 'checked' : '' }} class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="2-Digit" {{ $student->is_assessment_locked ? 'disabled' : '' }} {{ $student->has_assessment && $student->assessment_level == '2-Digit' ? 'checked' : '' }} class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Subtraction" {{ $student->is_assessment_locked ? 'disabled' : '' }} {{ $student->has_assessment && $student->assessment_level == 'Subtraction' ? 'checked' : '' }} class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Division" {{ $student->is_assessment_locked ? 'disabled' : '' }} {{ $student->has_assessment && $student->assessment_level == 'Division' ? 'checked' : '' }} class="level-radio">
                                    </td>
                                    <td class="px-2 py-4 text-center">
                                        <input type="radio" name="level_{{ $student->id }}" value="Word Problem" {{ $student->is_assessment_locked ? 'disabled' : '' }} {{ $student->has_assessment && $student->assessment_level == 'Word Problem' ? 'checked' : '' }} class="level-radio">
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Submit All Button -->
                    @php
                        $showSubmitButton = true;
                        if($school && !auth()->user()->isAdmin()) {
                            // Use existing periodStatus, don't recalculate
                            $showSubmitButton = in_array($periodStatus, ['active', 'not_set']); // Allow if not set or active
                        }
                    @endphp
                    
                    @if($showSubmitButton)
                        <div class="mt-6 flex justify-center">
                            <button type="button" 
                                    id="submitAllBtn"
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    onclick="submitAll()">
                                {{ __('assessment.Submit') }}
                            </button>
                        </div>
                    @else
                        <div class="mt-6 text-center">
                            <p class="text-sm text-gray-500">
                                @if(isset($periodStatus) && $periodStatus === 'not_set')
                                    {{ __('Submit button is disabled. Assessment dates have not been set for your school.') }}
                                @elseif(isset($periodStatus) && $periodStatus === 'upcoming')
                                    {{ __('Submit button will be enabled when the assessment period begins.') }}
                                @elseif(isset($periodStatus) && $periodStatus === 'expired')
                                    {{ __('Submit button is disabled. The assessment period has ended.') }}
                                @else
                                    {{ __('Submit button is currently disabled.') }}
                                @endif
                            </p>
                        </div>
                    @endif
                    @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('assessment.No eligible students') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if($subject === 'khmer' && in_array($cycle, ['midline', 'endline']))
                                {{ __('No students from baseline assessment (Beginner to Story level) found for this cycle.') }}
                            @elseif($subject === 'math' && in_array($cycle, ['midline', 'endline']))
                                {{ __('No students from baseline assessment (Beginner to Subtraction level) found for this cycle.') }}
                            @else
                                {{ __('assessment.No students found.') }}
                            @endif
                        </p>
                        @if($cycle !== 'baseline')
                        <div class="mt-6">
                            <a href="{{ route('assessments.create', ['subject' => $subject, 'cycle' => 'baseline']) }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('assessment.Go to Baseline Assessment') }}
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
        
        // Loading functions using SweetAlert2
        function showLoading(message) {
            Swal.fire({
                title: message,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        function hideLoading() {
            Swal.close();
        }
        
        $(document).ready(function() {
            // Load existing assessments
            loadExistingAssessments();
            
            // Check if table is scrollable
            const tableContainer = $('.table-container')[0];
            if (tableContainer) {
                const checkScrollable = () => {
                    if (tableContainer.scrollHeight > tableContainer.clientHeight) {
                        $(tableContainer).addClass('scrollable');
                    } else {
                        $(tableContainer).removeClass('scrollable');
                    }
                };
                
                checkScrollable();
                $(window).on('resize', checkScrollable);
                
                // Show floating headers on scroll (mobile)
                let scrollTimer = null;
                $(tableContainer).on('scroll', function() {
                    if (window.innerWidth < 768) {
                        $('#floatingHeaders').fadeIn(200);
                        
                        clearTimeout(scrollTimer);
                        scrollTimer = setTimeout(() => {
                            $('#floatingHeaders').fadeOut(200);
                        }, 3000);
                    }
                });
            }
            
            // Track changes when level is selected
            $('.level-radio').change(function() {
                const row = $(this).closest('tr');
                const studentId = row.data('student-id');
                
                // Skip if locked
                if (row.attr('data-locked') === 'true') {
                    return;
                }
                
                const hasLevel = $(`input[name="level_${studentId}"]:checked`).length > 0;
                const selectedLevel = $(`input[name="level_${studentId}"]:checked`).val();
                const currentLevel = row.attr('data-current-level');
                
                if (hasLevel) {
                    // Mark row as having unsaved changes
                    row.addClass('bg-yellow-50');
                    
                    // If this is an update and level changed, add visual indicator
                    if (currentLevel && selectedLevel !== currentLevel) {
                        row.addClass('border-l-4 border-orange-500');
                    } else if (currentLevel) {
                        row.removeClass('border-l-4 border-orange-500');
                    }
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
                $(this).find('.submit-btn').text('{{ __("assessment.Submit") }}').removeClass('bg-green-600').addClass('bg-blue-600');
            });
            
            // Load existing assessments via AJAX
            showLoading('{{ __("assessment.Loading existing assessments...") }}');
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
                                row.attr('data-current-level', assessment.level);
                                row.addClass('bg-green-50');
                                row.removeClass('bg-yellow-50 border-l-4 border-orange-500');
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
        
        function updateSavedCount() {
            savedCount = $('.student-row[data-saved="true"]').length;
            $('#savedCount').text(savedCount);
        }
        
        function submitAll() {
            // Collect all assessments with selected levels
            const assessments = [];
            let hasUnsavedChanges = false;
            
            $('.student-row').each(function() {
                const row = $(this);
                const studentId = row.data('student-id');
                
                // Skip if locked
                if (row.attr('data-locked') === 'true') {
                    return;
                }
                
                const level = $(`input[name="level_${studentId}"]:checked`).val();
                const gender = row.find('td:first').data('student-gender') || 'unknown';
                
                if (level) {
                    assessments.push({
                        student_id: studentId,
                        gender: gender,
                        level: level
                    });
                    
                    // Check if this is unsaved
                    if (row.attr('data-saved') !== 'true' || row.attr('data-current-level') !== level) {
                        hasUnsavedChanges = true;
                    }
                }
            });
            
            if (assessments.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: '{{ __("assessment.Warning") }}',
                    text: '{{ __("assessment.No assessments selected") }}'
                });
                return;
            }
            
            const confirmText = hasUnsavedChanges 
                ? `{{ __("assessment.You have assessed") }} ${assessments.length} {{ __("assessment.out of") }} ${totalCount} {{ __("assessment.Students") }}. {{ __("assessment.Do you want to save and submit?") }}`
                : `{{ __("assessment.All assessments are already saved. Do you want to submit?") }}`;
            
            Swal.fire({
                title: '{{ __("assessment.Submit All Assessments?") }}',
                text: confirmText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ __("assessment.Yes, submit all") }}',
                cancelButtonText: '{{ __("assessment.Cancel") }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading('{{ __("assessment.Submitting assessments...") }}');
                    
                    // Save all assessments
                    const savePromises = assessments.map(assessment => {
                        return $.ajax({
                            url: '{{ route("api.assessments.save-student") }}',
                            method: 'POST',
                            data: {
                                student_id: assessment.student_id,
                                gender: assessment.gender,
                                level: assessment.level,
                                subject: $('#subject').val(),
                                cycle: $('#cycle').val(),
                                _token: '{{ csrf_token() }}'
                            }
                        });
                    });
                    
                    // Wait for all saves to complete
                    Promise.all(savePromises)
                        .then(() => {
                            // Now submit all
                            return $.ajax({
                                url: '{{ route("api.assessments.submit-all") }}',
                                method: 'POST',
                                data: {
                                    subject: $('#subject').val(),
                                    cycle: $('#cycle').val(),
                                    submitted_count: assessments.length,
                                    _token: '{{ csrf_token() }}'
                                }
                            });
                        })
                        .then(response => {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '{{ __("assessment.Success") }}',
                                    text: response.message,
                                    confirmButtonText: '{{ __("assessment.OK") }}'
                                }).then(() => {
                                    window.location.href = response.redirect;
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: '{{ __("assessment.Error") }}',
                                text: error.responseJSON?.message || '{{ __("assessment.Failed to submit assessments") }}'
                            });
                        })
                        .finally(() => {
                            hideLoading();
                        });
                }
            });
        }
    </script>
    @endpush
</x-app-layout>