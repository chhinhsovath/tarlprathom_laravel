<x-app-layout>
    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">{{ __('Edit Mentoring Visit') }}</h3>
                    
                    <form id="mentoringForm" method="POST" action="{{ route('mentoring.update', $mentoringVisit) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Progress Indicator -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ __('Progress') }}</span>
                                <span class="text-sm text-gray-600"><span id="progressPercent">0</span>%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                        </div>

                        <!-- Section 1: Visit Details -->
                        <div class="questionnaire-section mb-8" data-section="visit_details">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">{{ __('Visit Details') }}</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Visit Date -->
                                <div>
                                    <label for="visit_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Date of Visit') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" 
                                           id="visit_date" 
                                           name="visit_date" 
                                           value="{{ old('visit_date', $mentoringVisit->visit_date->format('Y-m-d')) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           required>
                                    @error('visit_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Region -->
                                <div>
                                    <label for="region" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Region') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="region" 
                                           name="region" 
                                           value="{{ old('region', $mentoringVisit->region) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           required>
                                    @error('region')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Province -->
                                <div>
                                    <label for="province" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Province') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select id="province" 
                                            name="province" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            required>
                                        <option value="">{{ __('Select Province') }}</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province }}" {{ old('province', $mentoringVisit->province) == $province ? 'selected' : '' }}>
                                                {{ $province }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('province')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Mentor (for admin) -->
                                @if(auth()->user()->isAdmin())
                                <div>
                                    <label for="mentor_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Name of Mentor') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select id="mentor_id" 
                                            name="mentor_id" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            required>
                                        <option value="">{{ __('Select Mentor') }}</option>
                                        @foreach($mentors as $mentor)
                                            <option value="{{ $mentor->id }}" {{ old('mentor_id', $mentoringVisit->mentor_id) == $mentor->id ? 'selected' : '' }}>
                                                {{ $mentor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('mentor_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif
                                
                                <!-- School -->
                                <div>
                                    <label for="school_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('School Name') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select id="school_id" 
                                            name="school_id" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            required>
                                        <option value="">{{ __('Select School') }}</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ old('school_id', $mentoringVisit->school_id) == $school->id ? 'selected' : '' }}>
                                                {{ $school->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('school_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Program Type -->
                                <div>
                                    <label for="program_type" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Program Type') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="program_type" 
                                           name="program_type" 
                                           value="{{ old('program_type', $mentoringVisit->program_type ?? 'TaRL') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           required>
                                    @error('program_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Program Status -->
                        <div class="questionnaire-section mb-8" data-section="program_status">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">{{ __('Program Status') }}</h4>
                            
                            <div class="space-y-6">
                                <!-- Class Taking Place -->
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-3">
                                        {{ __('Is the TaRL class taking place on the day of the visit?') }} <span class="text-red-500">*</span>
                                    </p>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="class_in_session" value="Yes" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('class_in_session', $mentoringVisit->class_in_session ? 'Yes' : 'No') == 'Yes' ? 'checked' : '' }}
                                                   required>
                                            <span class="text-sm text-gray-700">{{ __('Yes') }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="class_in_session" value="No" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('class_in_session', $mentoringVisit->class_in_session ? 'Yes' : 'No') == 'No' ? 'checked' : '' }}
                                                   required>
                                            <span class="text-sm text-gray-700">{{ __('No') }}</span>
                                        </label>
                                    </div>
                                    @error('class_in_session')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Reason for Not Taking Place -->
                                <div id="notTakingPlaceReason" class="{{ old('class_in_session', $mentoringVisit->class_in_session ? 'Yes' : 'No') == 'Yes' ? 'hidden' : '' }}">
                                    <label for="class_not_in_session_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Why is the TaRL class not taking place?') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select id="class_not_in_session_reason" 
                                            name="class_not_in_session_reason" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">{{ __('Select Reason') }}</option>
                                        <option value="Teacher is Absent" {{ old('class_not_in_session_reason', $mentoringVisit->class_not_in_session_reason) == 'Teacher is Absent' ? 'selected' : '' }}>{{ __('Teacher is Absent') }}</option>
                                        <option value="Most students are absent" {{ old('class_not_in_session_reason', $mentoringVisit->class_not_in_session_reason) == 'Most students are absent' ? 'selected' : '' }}>{{ __('Most students are absent') }}</option>
                                        <option value="The students have exams" {{ old('class_not_in_session_reason', $mentoringVisit->class_not_in_session_reason) == 'The students have exams' ? 'selected' : '' }}>{{ __('The students have exams') }}</option>
                                        <option value="The school has declared a holiday" {{ old('class_not_in_session_reason', $mentoringVisit->class_not_in_session_reason) == 'The school has declared a holiday' ? 'selected' : '' }}>{{ __('The school has declared a holiday') }}</option>
                                        <option value="Others" {{ old('class_not_in_session_reason', $mentoringVisit->class_not_in_session_reason) == 'Others' ? 'selected' : '' }}>{{ __('Others') }}</option>
                                    </select>
                                    @error('class_not_in_session_reason')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Teacher and Observation (shown only if class is in session) -->
                        <div id="observationSection" class="questionnaire-section mb-8 {{ old('class_in_session', $mentoringVisit->class_in_session ? 'Yes' : 'No') == 'No' ? 'hidden' : '' }}" data-section="observation">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">{{ __('Teacher and Observation Details') }}</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Teacher -->
                                <div class="md:col-span-2">
                                    <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Name of Teacher') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select id="teacher_id" 
                                            name="teacher_id" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            required>
                                        <option value="">{{ __('Select Teacher') }}</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" 
                                                    data-school="{{ $teacher->school_id }}"
                                                    {{ old('teacher_id', $mentoringVisit->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}{{ $teacher->school ? ' (' . $teacher->school->name . ')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Full Session Observed -->
                                <div class="md:col-span-2">
                                    <p class="text-sm font-medium text-gray-700 mb-3">
                                        {{ __('Did you observe the full session?') }} <span class="text-red-500">*</span>
                                    </p>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="full_session_observed" value="Yes" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('full_session_observed', $mentoringVisit->full_session_observed ? 'Yes' : 'No') == 'Yes' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('Yes') }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="full_session_observed" value="No" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('full_session_observed', $mentoringVisit->full_session_observed ? 'Yes' : 'No') == 'No' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('No') }}</span>
                                        </label>
                                    </div>
                                    @error('full_session_observed')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Grade Group -->
                                <div>
                                    <label for="grade_group" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Grade Group') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select id="grade_group" 
                                            name="grade_group" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">{{ __('Select Grade Group') }}</option>
                                        <option value="Std. 1-2" {{ old('grade_group', $mentoringVisit->grade_group) == 'Std. 1-2' ? 'selected' : '' }}>{{ __('Std. 1-2') }}</option>
                                        <option value="Std. 3-6" {{ old('grade_group', $mentoringVisit->grade_group) == 'Std. 3-6' ? 'selected' : '' }}>{{ __('Std. 3-6') }}</option>
                                    </select>
                                    @error('grade_group')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Grades Observed -->
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Grade(s) Observed') }} <span class="text-red-500">*</span>
                                    </p>
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach(['1', '2', '3', '4', '5', '6'] as $grade)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="grades_observed[]" value="{{ $grade }}" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ in_array($grade, old('grades_observed', $mentoringVisit->grades_observed ?? [])) ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('Grade') }} {{ $grade }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                    @error('grades_observed')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Subject Observed -->
                                <div class="md:col-span-2">
                                    <label for="subject_observed" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Subject Observed') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select id="subject_observed" 
                                            name="subject_observed" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">{{ __('Select Subject') }}</option>
                                        <option value="Language" {{ old('subject_observed', $mentoringVisit->subject_observed) == 'Language' ? 'selected' : '' }}>{{ __('Language') }}</option>
                                        <option value="Numeracy" {{ old('subject_observed', $mentoringVisit->subject_observed) == 'Numeracy' ? 'selected' : '' }}>{{ __('Numeracy') }}</option>
                                    </select>
                                    @error('subject_observed')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Language Levels -->
                                <div id="languageLevelsDiv" class="md:col-span-2 {{ old('subject_observed', $mentoringVisit->subject_observed) != 'Language' ? 'hidden' : '' }}">
                                    <p class="text-sm font-medium text-gray-700 mb-2">
                                        {{ __('TaRL Level(s) observed (Language)') }} <span class="text-red-500">*</span>
                                    </p>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                        @foreach(['Beginner', 'Letter Level', 'Word', 'Paragraph', 'Story'] as $level)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="language_levels_observed[]" value="{{ $level }}" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ in_array($level, old('language_levels_observed', $mentoringVisit->language_levels_observed ?? [])) ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __($level) }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                    @error('language_levels_observed')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Numeracy Levels -->
                                <div id="numeracyLevelsDiv" class="md:col-span-2 {{ old('subject_observed', $mentoringVisit->subject_observed) != 'Numeracy' ? 'hidden' : '' }}">
                                    <p class="text-sm font-medium text-gray-700 mb-2">
                                        {{ __('TaRL Level(s) observed (Numeracy)') }} <span class="text-red-500">*</span>
                                    </p>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                        @foreach(['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division'] as $level)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="numeracy_levels_observed[]" value="{{ $level }}" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ in_array($level, old('numeracy_levels_observed', $mentoringVisit->numeracy_levels_observed ?? [])) ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __($level) }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                    @error('numeracy_levels_observed')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Delivery Questions (shown only if class is in session) -->
                        <div id="deliverySection" class="questionnaire-section mb-8 {{ old('class_in_session', $mentoringVisit->class_in_session ? 'Yes' : 'No') == 'No' ? 'hidden' : '' }}" data-section="delivery">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">{{ __('Delivery Questions') }}</h4>
                            
                            <div class="space-y-6">
                                <!-- Class Started on Time -->
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-3">
                                        {{ __('Did the class start on time (i.e. within 5 minutes of the scheduled time)?') }} <span class="text-red-500">*</span>
                                    </p>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="class_started_on_time" value="Yes" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('class_started_on_time', $mentoringVisit->class_started_on_time ? 'Yes' : 'No') == 'Yes' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('Yes') }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="class_started_on_time" value="No" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('class_started_on_time', $mentoringVisit->class_started_on_time ? 'Yes' : 'No') == 'No' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('No') }}</span>
                                        </label>
                                    </div>
                                    @error('class_started_on_time')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Late Start Reason -->
                                <div id="lateStartReason" class="{{ old('class_started_on_time', $mentoringVisit->class_started_on_time ? 'Yes' : 'No') == 'Yes' ? 'hidden' : '' }}">
                                    <label for="late_start_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('If no, then why did the class not start on time?') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select id="late_start_reason" 
                                            name="late_start_reason" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">{{ __('Select Reason') }}</option>
                                        <option value="Teacher came late" {{ old('late_start_reason', $mentoringVisit->late_start_reason) == 'Teacher came late' ? 'selected' : '' }}>{{ __('Teacher came late') }}</option>
                                        <option value="Pupils came late" {{ old('late_start_reason', $mentoringVisit->late_start_reason) == 'Pupils came late' ? 'selected' : '' }}>{{ __('Pupils came late') }}</option>
                                        <option value="Others" {{ old('late_start_reason', $mentoringVisit->late_start_reason) == 'Others' ? 'selected' : '' }}>{{ __('Others') }}</option>
                                    </select>
                                    @error('late_start_reason')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section 5: Classroom Related Questions (shown only if class is in session) -->
                        <div id="classroomSection" class="questionnaire-section mb-8 {{ old('class_in_session', $mentoringVisit->class_in_session ? 'Yes' : 'No') == 'No' ? 'hidden' : '' }}" data-section="classroom">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">{{ __('Classroom Related Questions') }}</h4>
                            
                            <div class="space-y-6">
                                <!-- Materials Present -->
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Which materials did you see present in the classroom?') }}
                                    </p>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                        @foreach(['TaRL materials', 'Teaching aids', 'Student notebooks', 'Reading materials', 'Math manipulatives', 'Flash cards', 'Number charts', 'Letter charts', 'Story books', 'Activity sheets'] as $material)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="materials_present[]" value="{{ $material }}" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ in_array($material, old('materials_present', $mentoringVisit->materials_present ?? [])) ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __($material) }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                    @error('materials_present')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Children Grouped Appropriately -->
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-3">
                                        {{ __('Were the children grouped appropriately?') }} <span class="text-red-500">*</span>
                                    </p>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="children_grouped_appropriately" value="Yes" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('children_grouped_appropriately', $mentoringVisit->children_grouped_appropriately ? 'Yes' : 'No') == 'Yes' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('Yes') }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="children_grouped_appropriately" value="No" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('children_grouped_appropriately', $mentoringVisit->children_grouped_appropriately ? 'Yes' : 'No') == 'No' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('No') }}</span>
                                        </label>
                                    </div>
                                    @error('children_grouped_appropriately')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Students Fully Involved -->
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-3">
                                        {{ __('Were the students fully involved in the activities?') }} <span class="text-red-500">*</span>
                                    </p>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="students_fully_involved" value="Yes" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('students_fully_involved', $mentoringVisit->students_fully_involved ? 'Yes' : 'No') == 'Yes' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('Yes') }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="students_fully_involved" value="No" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('students_fully_involved', $mentoringVisit->students_fully_involved ? 'Yes' : 'No') == 'No' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('No') }}</span>
                                        </label>
                                    </div>
                                    @error('students_fully_involved')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section 6: Teacher Related Questions (shown only if class is in session) -->
                        <div id="teacherSection" class="questionnaire-section mb-8 {{ old('class_in_session', $mentoringVisit->class_in_session ? 'Yes' : 'No') == 'No' ? 'hidden' : '' }}" data-section="teacher">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">{{ __('Teacher Related Questions') }}</h4>
                            
                            <div class="space-y-6">
                                <!-- Has Session Plan -->
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-3">
                                        {{ __('Did the teacher have a session plan?') }} <span class="text-red-500">*</span>
                                    </p>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="has_session_plan" value="Yes" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('has_session_plan', $mentoringVisit->has_session_plan ? 'Yes' : 'No') == 'Yes' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('Yes') }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="has_session_plan" value="No" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('has_session_plan', $mentoringVisit->has_session_plan ? 'Yes' : 'No') == 'No' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('No') }}</span>
                                        </label>
                                    </div>
                                    @error('has_session_plan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- No Session Plan Reason -->
                                <div id="noSessionPlanReason" class="{{ old('has_session_plan', $mentoringVisit->has_session_plan ? 'Yes' : 'No') == 'Yes' ? 'hidden' : '' }}">
                                    <label for="no_session_plan_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Why did the teacher not have a session plan?') }}
                                    </label>
                                    <textarea id="no_session_plan_reason" 
                                              name="no_session_plan_reason" 
                                              rows="3"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                              placeholder="{{ __('Describe the reason...') }}">{{ old('no_session_plan_reason', $mentoringVisit->no_session_plan_reason) }}</textarea>
                                    @error('no_session_plan_reason')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Followed Session Plan -->
                                <div id="followedSessionPlanDiv" class="{{ old('has_session_plan', $mentoringVisit->has_session_plan ? 'Yes' : 'No') == 'No' ? 'hidden' : '' }}">
                                    <p class="text-sm font-medium text-gray-700 mb-3">
                                        {{ __('Did the teacher follow the session plan?') }} <span class="text-red-500">*</span>
                                    </p>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="followed_session_plan" value="Yes" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('followed_session_plan', $mentoringVisit->followed_session_plan ? 'Yes' : 'No') == 'Yes' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('Yes') }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="followed_session_plan" value="No" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('followed_session_plan', $mentoringVisit->followed_session_plan ? 'Yes' : 'No') == 'No' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('No') }}</span>
                                        </label>
                                    </div>
                                    @error('followed_session_plan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- No Follow Plan Reason -->
                                <div id="noFollowPlanReason" class="{{ old('followed_session_plan', $mentoringVisit->followed_session_plan ? 'Yes' : 'No') == 'Yes' ? 'hidden' : '' }}">
                                    <label for="no_follow_plan_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Why did the teacher not follow the session plan?') }}
                                    </label>
                                    <textarea id="no_follow_plan_reason" 
                                              name="no_follow_plan_reason" 
                                              rows="3"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                              placeholder="{{ __('Describe the reason...') }}">{{ old('no_follow_plan_reason', $mentoringVisit->no_follow_plan_reason) }}</textarea>
                                    @error('no_follow_plan_reason')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Session Plan Appropriate -->
                                <div id="sessionPlanAppropriateDiv" class="{{ old('has_session_plan', $mentoringVisit->has_session_plan ? 'Yes' : 'No') == 'No' ? 'hidden' : '' }}">
                                    <p class="text-sm font-medium text-gray-700 mb-3">
                                        {{ __('Was the session plan appropriate for the children\'s learning level?') }} <span class="text-red-500">*</span>
                                    </p>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="session_plan_appropriate" value="Yes" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('session_plan_appropriate', $mentoringVisit->session_plan_appropriate ? 'Yes' : 'No') == 'Yes' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('Yes') }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="session_plan_appropriate" value="No" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('session_plan_appropriate', $mentoringVisit->session_plan_appropriate ? 'Yes' : 'No') == 'No' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('No') }}</span>
                                        </label>
                                    </div>
                                    @error('session_plan_appropriate')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section 7: Activity Overview (shown only if class is in session) -->
                        <div id="activityOverviewSection" class="questionnaire-section mb-8 {{ old('class_in_session', $mentoringVisit->class_in_session ? 'Yes' : 'No') == 'No' ? 'hidden' : '' }}" data-section="activity_overview">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">{{ __('Activity Overview') }}</h4>
                            
                            <div class="space-y-6">
                                <!-- Number of Activities -->
                                <div>
                                    <label for="number_of_activities" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('How many activities were conducted?') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select id="number_of_activities" 
                                            name="number_of_activities" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">{{ __('Select Number') }}</option>
                                        <option value="1" {{ old('number_of_activities', $mentoringVisit->number_of_activities) == '1' ? 'selected' : '' }}>{{ __('1') }}</option>
                                        <option value="2" {{ old('number_of_activities', $mentoringVisit->number_of_activities) == '2' ? 'selected' : '' }}>{{ __('2') }}</option>
                                        <option value="3" {{ old('number_of_activities', $mentoringVisit->number_of_activities) == '3' ? 'selected' : '' }}>{{ __('3') }}</option>
                                    </select>
                                    @error('number_of_activities')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Activity Sections - Will be shown based on number of activities and subject -->
                        @for($i = 1; $i <= 3; $i++)
                        <div id="activity{{ $i }}Section" class="questionnaire-section mb-8 hidden" data-section="activity_{{ $i }}">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">{{ __('Activity') }} {{ $i }} {{ __('Details') }}</h4>
                            
                            <div class="space-y-6">
                                <!-- Activity Name (Language) -->
                                <div id="activity{{ $i }}LanguageDiv" class="hidden">
                                    <label for="activity{{ $i }}_name_language" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Which was the') }} {{ $i == 1 ? __('first') : ($i == 2 ? __('second') : __('third')) }} {{ __('activity conducted? (Language)') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select id="activity{{ $i }}_name_language" 
                                            name="activity{{ $i }}_name_language" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">{{ __('Select Activity') }}</option>
                                        @foreach(['Letter recognition', 'Letter writing', 'Word building', 'Word reading', 'Sentence reading', 'Story reading', 'Comprehension activities', 'Vocabulary games', 'Phonics activities', 'Others'] as $activity)
                                        <option value="{{ $activity }}" {{ old('activity'.$i.'_name_language', $mentoringVisit->{'activity'.$i.'_name_language'}) == $activity ? 'selected' : '' }}>{{ __($activity) }}</option>
                                        @endforeach
                                    </select>
                                    @error('activity{{ $i }}_name_language')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Activity Name (Numeracy) -->
                                <div id="activity{{ $i }}NumeracyDiv" class="hidden">
                                    <label for="activity{{ $i }}_name_numeracy" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Which was the') }} {{ $i == 1 ? __('first') : ($i == 2 ? __('second') : __('third')) }} {{ __('activity conducted? (Numeracy)') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select id="activity{{ $i }}_name_numeracy" 
                                            name="activity{{ $i }}_name_numeracy" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">{{ __('Select Activity') }}</option>
                                        @foreach(['Number chart reading activity', 'Recognition of the numbers with symbol and objects', 'Puzzles', 'Number Jump', 'Basket game', 'Clap and Snap', 'What next - Count Before / Count After', 'Number line - Counting and find the numbers move to left and move to right', 'Fine with Nine', 'Place value - Bundle and sticks with numbers up to 20', 'Making bundles and counting 10, 20, 30, 40, …', 'Learning two digit numbers with bundle and sticks', 'Addition of single digit numbers with sticks and without any material by using frame and word problems', 'Subtraction of single digit numbers with sticks and without any material, by using and word problems', 'Word problem of two digit number of addition and subtraction without any material', 'Who is my third partner - with numbers 1 to 9'] as $activity)
                                        <option value="{{ $activity }}" {{ old('activity'.$i.'_name_numeracy', $mentoringVisit->{'activity'.$i.'_name_numeracy'}) == $activity ? 'selected' : '' }}>{{ __($activity) }}</option>
                                        @endforeach
                                    </select>
                                    @error('activity{{ $i }}_name_numeracy')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Activity Duration -->
                                <div>
                                    <label for="activity{{ $i }}_duration" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('What was the duration of the') }} {{ $i == 1 ? __('first') : ($i == 2 ? __('second') : __('third')) }} {{ __('activity? (Mins)') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           id="activity{{ $i }}_duration" 
                                           name="activity{{ $i }}_duration" 
                                           value="{{ old('activity'.$i.'_duration', $mentoringVisit->{'activity'.$i.'_duration'}) }}"
                                           min="1"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('activity{{ $i }}_duration')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Clear Instructions -->
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-3">
                                        {{ __('Did the teacher give clear instructions for the') }} {{ $i == 1 ? __('first') : ($i == 2 ? __('second') : __('third')) }} {{ __('activity?') }} <span class="text-red-500">*</span>
                                    </p>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="activity{{ $i }}_clear_instructions" value="Yes" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('activity'.$i.'_clear_instructions', $mentoringVisit->{'activity'.$i.'_clear_instructions'} ? 'Yes' : 'No') == 'Yes' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('Yes') }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="activity{{ $i }}_clear_instructions" value="No" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('activity'.$i.'_clear_instructions', $mentoringVisit->{'activity'.$i.'_clear_instructions'} ? 'Yes' : 'No') == 'No' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('No') }}</span>
                                        </label>
                                    </div>
                                    @error('activity{{ $i }}_clear_instructions')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- No Clear Instructions Reason -->
                                <div id="activity{{ $i }}NoInstructionsReason" class="{{ old('activity'.$i.'_clear_instructions', $mentoringVisit->{'activity'.$i.'_clear_instructions'} ? 'Yes' : 'No') == 'Yes' ? 'hidden' : '' }}">
                                    <label for="activity{{ $i }}_no_clear_instructions_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Why did the teacher not give clear instructions for the') }} {{ $i == 1 ? __('first') : ($i == 2 ? __('second') : __('third')) }} {{ __('activity?') }}
                                    </label>
                                    <textarea id="activity{{ $i }}_no_clear_instructions_reason" 
                                              name="activity{{ $i }}_no_clear_instructions_reason" 
                                              rows="3"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                              placeholder="{{ __('Describe the reason...') }}">{{ old('activity'.$i.'_no_clear_instructions_reason', $mentoringVisit->{'activity'.$i.'_no_clear_instructions_reason'}) }}</textarea>
                                    @error('activity{{ $i }}_no_clear_instructions_reason')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Activity Demonstrated -->
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-3">
                                        {{ __('Did the teacher demonstrate the') }} {{ $i == 1 ? __('first') : ($i == 2 ? __('second') : __('third')) }} {{ __('activity?') }} <span class="text-red-500">*</span>
                                    </p>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="activity{{ $i }}_demonstrated" value="Yes" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('activity'.$i.'_demonstrated', $mentoringVisit->{'activity'.$i.'_demonstrated'} ? 'Yes' : 'No') == 'Yes' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('Yes') }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="activity{{ $i }}_demonstrated" value="No" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('activity'.$i.'_demonstrated', $mentoringVisit->{'activity'.$i.'_demonstrated'} ? 'Yes' : 'No') == 'No' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('No') }}</span>
                                        </label>
                                    </div>
                                    @error('activity{{ $i }}_demonstrated')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Students Practice -->
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-3">
                                        {{ __('Did the teacher make a few students practice the') }} {{ $i == 1 ? __('first') : ($i == 2 ? __('second') : __('third')) }} {{ __('activity in front of the whole class?') }} <span class="text-red-500">*</span>
                                    </p>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="activity{{ $i }}_students_practice" value="Yes" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('activity'.$i.'_students_practice', $mentoringVisit->{'activity'.$i.'_students_practice'}) == 'Yes' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('Yes') }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="activity{{ $i }}_students_practice" value="No" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('activity'.$i.'_students_practice', $mentoringVisit->{'activity'.$i.'_students_practice'}) == 'No' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('No') }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="activity{{ $i }}_students_practice" value="Not applicable" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('activity'.$i.'_students_practice', $mentoringVisit->{'activity'.$i.'_students_practice'}) == 'Not applicable' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('Not applicable') }}</span>
                                        </label>
                                    </div>
                                    @error('activity{{ $i }}_students_practice')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Small Groups -->
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-3">
                                        {{ __('Did the students perform the') }} {{ $i == 1 ? __('first') : ($i == 2 ? __('second') : __('third')) }} {{ __('activity in small groups?') }} <span class="text-red-500">*</span>
                                    </p>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="activity{{ $i }}_small_groups" value="Yes" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('activity'.$i.'_small_groups', $mentoringVisit->{'activity'.$i.'_small_groups'}) == 'Yes' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('Yes') }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="activity{{ $i }}_small_groups" value="No" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('activity'.$i.'_small_groups', $mentoringVisit->{'activity'.$i.'_small_groups'}) == 'No' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('No') }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="activity{{ $i }}_small_groups" value="Not Applicable" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('activity'.$i.'_small_groups', $mentoringVisit->{'activity'.$i.'_small_groups'}) == 'Not Applicable' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('Not Applicable') }}</span>
                                        </label>
                                    </div>
                                    @error('activity{{ $i }}_small_groups')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Individual -->
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-3">
                                        {{ __('Did the students individually perform the') }} {{ $i == 1 ? __('first') : ($i == 2 ? __('second') : __('third')) }} {{ __('activity?') }} <span class="text-red-500">*</span>
                                    </p>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="activity{{ $i }}_individual" value="Yes" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('activity'.$i.'_individual', $mentoringVisit->{'activity'.$i.'_individual'}) == 'Yes' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('Yes') }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="activity{{ $i }}_individual" value="No" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('activity'.$i.'_individual', $mentoringVisit->{'activity'.$i.'_individual'}) == 'No' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('No') }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="activity{{ $i }}_individual" value="Not Applicable" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('activity'.$i.'_individual', $mentoringVisit->{'activity'.$i.'_individual'}) == 'Not Applicable' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('Not Applicable') }}</span>
                                        </label>
                                    </div>
                                    @error('activity{{ $i }}_individual')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @endfor

                        <!-- Section 8: Additional Observations -->
                        <div class="questionnaire-section mb-8" data-section="additional">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">{{ __('Additional Observations') }}</h4>
                            
                            <div class="space-y-6">
                                <!-- General Observations -->
                                <div>
                                    <label for="observation" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('General Observations') }}
                                    </label>
                                    <textarea id="observation" 
                                              name="observation" 
                                              rows="4"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                              placeholder="{{ __('Describe your observations from the visit...') }}">{{ old('observation', $mentoringVisit->observation) }}</textarea>
                                    @error('observation')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Action Plan -->
                                <div>
                                    <label for="action_plan" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Action Plan') }}
                                    </label>
                                    <textarea id="action_plan" 
                                              name="action_plan" 
                                              rows="4"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                              placeholder="{{ __('Describe recommended actions and next steps...') }}">{{ old('action_plan', $mentoringVisit->action_plan) }}</textarea>
                                    @error('action_plan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Follow-up Required -->
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-3">
                                        {{ __('Is follow-up required?') }}
                                    </p>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="follow_up_required" value="Yes" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('follow_up_required', $mentoringVisit->follow_up_required ? 'Yes' : 'No') == 'Yes' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('Yes') }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="follow_up_required" value="No" 
                                                   class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                                   {{ old('follow_up_required', $mentoringVisit->follow_up_required ? 'Yes' : 'No') == 'No' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ __('No') }}</span>
                                        </label>
                                    </div>
                                    @error('follow_up_required')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-between pt-6 border-t">
                            <a href="{{ route('mentoring.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                            
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ __('Update Visit Report') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // School-Teacher filtering
            const schoolSelect = document.getElementById('school_id');
            const teacherSelect = document.getElementById('teacher_id');
            const teacherOptions = Array.from(teacherSelect.options);
            
            function filterTeachers() {
                const selectedSchoolId = schoolSelect.value;
                const currentSelection = teacherSelect.value;
                teacherSelect.innerHTML = '<option value="">{{ __("Select Teacher") }}</option>';
                
                teacherOptions.forEach(option => {
                    if (option.value) {
                        // Show teacher if no school is selected, or if teacher's school matches, or if teacher has no school assigned
                        if (!selectedSchoolId || 
                            option.dataset.school == selectedSchoolId || 
                            !option.dataset.school || 
                            option.dataset.school === '') {
                            const newOption = option.cloneNode(true);
                            if (newOption.value == currentSelection) {
                                newOption.selected = true;
                            }
                            teacherSelect.appendChild(newOption);
                        }
                    }
                });
            }
            
            schoolSelect.addEventListener('change', filterTeachers);
            
            // Class in session logic
            const classInSessionRadios = document.getElementsByName('class_in_session');
            const notTakingPlaceReason = document.getElementById('notTakingPlaceReason');
            const observationSection = document.getElementById('observationSection');
            
            function toggleClassSections() {
                const isInSession = document.querySelector('input[name="class_in_session"]:checked')?.value === 'Yes';
                
                if (isInSession) {
                    notTakingPlaceReason.classList.add('hidden');
                    observationSection.classList.remove('hidden');
                    document.getElementById('class_not_in_session_reason').removeAttribute('required');
                } else {
                    notTakingPlaceReason.classList.remove('hidden');
                    observationSection.classList.add('hidden');
                    document.getElementById('class_not_in_session_reason').setAttribute('required', 'required');
                }
                
                updateProgress();
            }
            
            classInSessionRadios.forEach(radio => {
                radio.addEventListener('change', toggleClassSections);
            });
            
            function toggleClassSessionSections() {
                const isInSession = document.querySelector('input[name="class_in_session"]:checked')?.value === 'Yes';
                const deliverySection = document.getElementById('deliverySection');
                const classroomSection = document.getElementById('classroomSection');
                const teacherSection = document.getElementById('teacherSection');
                const activityOverviewSection = document.getElementById('activityOverviewSection');
                
                if (isInSession) {
                    deliverySection.classList.remove('hidden');
                    classroomSection.classList.remove('hidden');
                    teacherSection.classList.remove('hidden');
                    activityOverviewSection.classList.remove('hidden');
                } else {
                    deliverySection.classList.add('hidden');
                    classroomSection.classList.add('hidden');
                    teacherSection.classList.add('hidden');
                    activityOverviewSection.classList.add('hidden');
                }
                updateProgress();
            }
            
            classInSessionRadios.forEach(radio => {
                radio.addEventListener('change', toggleClassSessionSections);
            });
            
            // Class start time logic
            const classStartedRadios = document.getElementsByName('class_started_on_time');
            const lateStartReason = document.getElementById('lateStartReason');
            
            function toggleLateStartReason() {
                const startedOnTime = document.querySelector('input[name="class_started_on_time"]:checked')?.value === 'Yes';
                if (startedOnTime) {
                    lateStartReason.classList.add('hidden');
                    document.getElementById('late_start_reason').removeAttribute('required');
                } else {
                    lateStartReason.classList.remove('hidden');
                    document.getElementById('late_start_reason').setAttribute('required', 'required');
                }
                updateProgress();
            }
            
            classStartedRadios.forEach(radio => {
                radio.addEventListener('change', toggleLateStartReason);
            });
            
            // Session plan logic
            const sessionPlanRadios = document.getElementsByName('has_session_plan');
            const noSessionPlanReason = document.getElementById('noSessionPlanReason');
            const followedSessionPlanDiv = document.getElementById('followedSessionPlanDiv');
            const sessionPlanAppropriateDiv = document.getElementById('sessionPlanAppropriateDiv');
            
            function toggleSessionPlanSections() {
                const hasSessionPlan = document.querySelector('input[name="has_session_plan"]:checked')?.value === 'Yes';
                
                if (hasSessionPlan) {
                    noSessionPlanReason.classList.add('hidden');
                    followedSessionPlanDiv.classList.remove('hidden');
                    sessionPlanAppropriateDiv.classList.remove('hidden');
                } else {
                    noSessionPlanReason.classList.remove('hidden');
                    followedSessionPlanDiv.classList.add('hidden');
                    sessionPlanAppropriateDiv.classList.add('hidden');
                }
                updateProgress();
            }
            
            sessionPlanRadios.forEach(radio => {
                radio.addEventListener('change', toggleSessionPlanSections);
            });
            
            // Followed session plan logic
            const followedPlanRadios = document.getElementsByName('followed_session_plan');
            const noFollowPlanReason = document.getElementById('noFollowPlanReason');
            
            function toggleFollowPlanReason() {
                const followedPlan = document.querySelector('input[name="followed_session_plan"]:checked')?.value === 'Yes';
                if (followedPlan) {
                    noFollowPlanReason.classList.add('hidden');
                } else {
                    noFollowPlanReason.classList.remove('hidden');
                }
                updateProgress();
            }
            
            followedPlanRadios.forEach(radio => {
                radio.addEventListener('change', toggleFollowPlanReason);
            });
            
            // Activity clear instructions logic
            for (let i = 1; i <= 3; i++) {
                const clearInstructionsRadios = document.getElementsByName('activity' + i + '_clear_instructions');
                const noInstructionsReason = document.getElementById('activity' + i + 'NoInstructionsReason');
                
                function toggleInstructionsReason() {
                    const clearInstructions = document.querySelector('input[name="activity' + i + '_clear_instructions"]:checked')?.value === 'Yes';
                    if (clearInstructions) {
                        noInstructionsReason.classList.add('hidden');
                    } else {
                        noInstructionsReason.classList.remove('hidden');
                    }
                    updateProgress();
                }
                
                clearInstructionsRadios.forEach(radio => {
                    radio.addEventListener('change', toggleInstructionsReason);
                });
            }
            
            // Subject levels logic
            const subjectSelect = document.getElementById('subject_observed');
            const languageLevelsDiv = document.getElementById('languageLevelsDiv');
            const numeracyLevelsDiv = document.getElementById('numeracyLevelsDiv');
            
            function toggleSubjectLevels() {
                const selectedSubject = subjectSelect.value;
                
                if (selectedSubject === 'Language') {
                    languageLevelsDiv.classList.remove('hidden');
                    numeracyLevelsDiv.classList.add('hidden');
                } else if (selectedSubject === 'Numeracy') {
                    languageLevelsDiv.classList.add('hidden');
                    numeracyLevelsDiv.classList.remove('hidden');
                } else {
                    languageLevelsDiv.classList.add('hidden');
                    numeracyLevelsDiv.classList.add('hidden');
                }
                
                // Also toggle activity name sections
                toggleActivitySections();
                updateProgress();
            }
            
            subjectSelect.addEventListener('change', toggleSubjectLevels);
            
            // Number of activities logic
            const activitiesSelect = document.getElementById('number_of_activities');
            
            function toggleActivitySections() {
                const numberOfActivities = parseInt(activitiesSelect.value) || 0;
                const selectedSubject = subjectSelect.value;
                
                for (let i = 1; i <= 3; i++) {
                    const activitySection = document.getElementById('activity' + i + 'Section');
                    const languageDiv = document.getElementById('activity' + i + 'LanguageDiv');
                    const numeracyDiv = document.getElementById('activity' + i + 'NumeracyDiv');
                    
                    if (i <= numberOfActivities) {
                        activitySection.classList.remove('hidden');
                        
                        if (selectedSubject === 'Language') {
                            languageDiv.classList.remove('hidden');
                            numeracyDiv.classList.add('hidden');
                        } else if (selectedSubject === 'Numeracy') {
                            languageDiv.classList.add('hidden');
                            numeracyDiv.classList.remove('hidden');
                        } else {
                            languageDiv.classList.add('hidden');
                            numeracyDiv.classList.add('hidden');
                        }
                    } else {
                        activitySection.classList.add('hidden');
                        languageDiv.classList.add('hidden');
                        numeracyDiv.classList.add('hidden');
                    }
                }
                updateProgress();
            }
            
            activitiesSelect.addEventListener('change', toggleActivitySections);
            
            // Progress tracking
            function updateProgress() {
                const requiredFields = document.querySelectorAll('[required]:not([type="hidden"])');
                const filledFields = Array.from(requiredFields).filter(field => {
                    if (field.type === 'checkbox' || field.type === 'radio') {
                        return document.querySelector(`[name="${field.name}"]:checked`);
                    }
                    return field.value.trim() !== '';
                });
                
                const progress = Math.round((filledFields.length / requiredFields.length) * 100);
                document.getElementById('progressPercent').textContent = progress;
                document.getElementById('progressBar').style.width = progress + '%';
            }
            
            // Add progress tracking to all inputs
            document.querySelectorAll('input, select, textarea').forEach(element => {
                element.addEventListener('change', updateProgress);
                element.addEventListener('input', updateProgress);
            });
            
            // Initialize
            filterTeachers();
            toggleClassSections();
            toggleClassSessionSections();
            toggleLateStartReason();
            toggleSessionPlanSections();
            toggleFollowPlanReason();
            toggleSubjectLevels();
            toggleActivitySections();
            updateProgress();
        });
        
    </script>
    @endpush
</x-app-layout>