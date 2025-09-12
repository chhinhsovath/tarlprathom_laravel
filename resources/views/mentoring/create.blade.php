<x-app-layout>
    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">ទម្រង់សង្កេតការណ៍ការចុះណែនាំ</h3>
                    
                    <!-- Global Error Display -->
                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <h4 class="text-red-800 font-medium">បញ្ហាក្នុងការបំពេញទម្រង់:</h4>
                            </div>
                            <ul class="text-red-700 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li class="flex items-start">
                                        <span class="text-red-500 mr-2">•</span>
                                        <span>{{ $error }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <p class="text-red-600 text-sm mt-3">
                                <strong>សូមពិនិត្យ និងកែតម្រូវកន្លែងដែលមានបញ្ហា រួចបញ្ជូនម្តងទៀត។</strong>
                            </p>
                        </div>
                    @endif
                    
                    <form id="mentoringForm" method="POST" action="{{ route('mentoring.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Progress Indicator -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">វឌ្ឍនភាព</span>
                                <span class="text-sm text-gray-600"><span id="progressPercent">0</span>%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                        </div>

                        <!-- Section 1: Visit Details -->
                        <div class="questionnaire-section mb-8" data-section="visit_details">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">ព័ត៌មានលម្អិតការចុះអង្កេត</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- កាលបរិច្ឆេទនៃការចុះ (Visit Date) -->
                                <div>
                                    <label for="visit_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('កាលបរិច្ឆេទនៃការចុះ') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" 
                                           id="visit_date" 
                                           name="visit_date" 
                                           value="{{ old('visit_date', date('Y-m-d')) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           required>
                                    @error('visit_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                
                                
                                <!-- ឈ្មោះទីប្រឹក្សាគរុកោសល្យ (Mentor Name) -->
                                <div>
                                    <label for="mentor_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('ឈ្មោះទីប្រឹក្សាគរុកោសល្យ') }}
                                    </label>
                                    <input type="text" 
                                           id="mentor_name" 
                                           name="mentor_name" 
                                           value="{{ old('mentor_name', auth()->user()->name) }}"
                                           class="w-full rounded-md border-gray-300 bg-gray-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           readonly>
                                    <small class="text-gray-500">បំពេញដោយស្វ័យប្រវត្តិ - ផ្អែកលើព័ត៌មានលម្អិតនៃគណនីអ្នកប្រើប្រាស់</small>
                                    <input type="hidden" name="mentor_id" value="{{ auth()->user()->id }}">
                                </div>
                                
                                <!-- ឈ្មោះសាលា (School Name) -->
                                <div>
                                    <label for="school_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('ឈ្មោះសាលា') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select id="pilot_school_id" 
                                            name="pilot_school_id" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            required>
                                        <option value="">ជ្រើសរើសសាលា</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ old('pilot_school_id', $selectedSchoolId ?? '') == $school->id ? 'selected' : '' }}>
                                                {{ $school->school_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pilot_school_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- ឈ្មោះគ្រូបង្រៀន (Teacher Name) -->
                                <div>
                                    <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('ឈ្មោះគ្រូបង្រៀន') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select id="teacher_id" 
                                            name="teacher_id" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            required>
                                        <option value="">ជ្រើសរើសគ្រូ</option>
                                    </select>
                                    @error('teacher_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- TaRL Class Status -->
                        <div class="questionnaire-section mb-8" data-section="class_status">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">ស្ថានភាពថ្នាក់រៀន</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- តើថ្នាក់រៀន TaRL មានដំណើរការនៅថ្ងៃចុះអង្កេតដែរឬទេ? -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('តើថ្នាក់រៀន TaRL មានដំណើរការនៅថ្ងៃចុះអង្កេតដែរឬទេ?') }} <span class="text-red-500">*</span>
                                    </label>
                                    <div class="space-y-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="class_in_session" value="1" 
                                                   class="form-radio" required
                                                   {{ old('class_in_session') == '1' ? 'checked' : '' }}>
                                            <span class="ml-2">{{ __('បាទ/ចាស') }}</span>
                                        </label>
                                        <label class="inline-flex items-center ml-6">
                                            <input type="radio" name="class_in_session" value="0" 
                                                   class="form-radio" required
                                                   {{ old('class_in_session') == '0' ? 'checked' : '' }}>
                                            <span class="ml-2">{{ __('ទេ') }}</span>
                                        </label>
                                    </div>
                                    @error('class_in_session')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- ហេតុអ្វីបានជាថ្នាក់រៀន TaRL មិនដំណើរការ? -->
                                <div id="class_not_in_session_reason_container" style="display: none;">
                                    <label for="class_not_in_session_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('ហេតុអ្វីបានជាថ្នាក់រៀន TaRL មិនដំណើរការ?') }}
                                    </label>
                                    <textarea id="class_not_in_session_reason" 
                                              name="class_not_in_session_reason" 
                                              rows="3"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('class_not_in_session_reason') }}</textarea>
                                    @error('class_not_in_session_reason')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6" id="class_details_section">
                                <!-- តើអ្នកបានអង្កេតការបង្រៀនពេញមួយវគ្គដែរឬទេ? -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('តើអ្នកបានអង្កេតការបង្រៀនពេញមួយវគ្គដែរឬទេ?') }} <span class="text-red-500">*</span>
                                    </label>
                                    <div class="space-y-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="full_session_observed" value="1" 
                                                   class="form-radio"
                                                   {{ old('full_session_observed') == '1' ? 'checked' : '' }}>
                                            <span class="ml-2">{{ __('បាទ/ចាស') }}</span>
                                        </label>
                                        <label class="inline-flex items-center ml-6">
                                            <input type="radio" name="full_session_observed" value="0" 
                                                   class="form-radio"
                                                   {{ old('full_session_observed') == '0' ? 'checked' : '' }}>
                                            <span class="ml-2">{{ __('ទេ') }}</span>
                                        </label>
                                    </div>
                                    @error('full_session_observed')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- កម្រិតថ្នាក់ដែលបានអង្កេត -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('កម្រិតថ្នាក់ដែលបានអង្កេត') }} <span class="text-red-500">*</span>
                                    </label>
                                    <div class="space-y-2">
                                        @foreach(['ទី៤', 'ទី៥', 'ទី៦'] as $grade)
                                        <label class="inline-flex items-center mr-4">
                                            <input type="checkbox" name="grades_observed[]" value="{{ $grade }}" 
                                                   class="form-checkbox"
                                                   {{ in_array($grade, old('grades_observed', [])) ? 'checked' : '' }}>
                                            <span class="ml-2">{{ $grade }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                    @error('grades_observed')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- មុខវិជ្ជាដែលបានអង្កេត -->
                                <div>
                                    <label for="subject_observed" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('មុខវិជ្ជាដែលបានអង្កេត') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select id="subject_observed" 
                                            name="subject_observed" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">ជ្រើសរើសមុខវិជ្ជា</option>
                                        <option value="ភាសាខ្មែរ" {{ old('subject_observed') == 'ភាសាខ្មែរ' ? 'selected' : '' }}>{{ __('ភាសាខ្មែរ') }}</option>
                                        <option value="គណិតវិទ្យា" {{ old('subject_observed') == 'គណិតវិទ្យា' ? 'selected' : '' }}>{{ __('គណិតវិទ្យា') }}</option>
                                    </select>
                                    @error('subject_observed')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- កម្រិតសមត្ថភាព TaRL (ភាសាខ្មែរ) -->
                                <div id="language_levels_container" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('កម្រិតសមត្ថភាព TaRL (ភាសាខ្មែរ) ដែលបានអង្កេត') }}
                                    </label>
                                    <div class="space-y-2">
                                        @foreach(['កម្រិតដំបូង', 'តួអក្សរ', 'ពាក្យ', 'កថាខណ្ឌ', 'អត្ថបទខ្លី', 'ការយល់ន័យទី១', 'ការយល់ន័យទី២'] as $level)
                                        <label class="inline-flex items-center mr-4">
                                            <input type="checkbox" name="language_levels_observed[]" value="{{ $level }}" 
                                                   class="form-checkbox"
                                                   {{ in_array($level, old('language_levels_observed', [])) ? 'checked' : '' }}>
                                            <span class="ml-2">{{ $level }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- កម្រិតសមត្ថភាព (គណិតវិទ្យា) -->
                                <div id="numeracy_levels_container" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('កម្រិតសមត្ថភាព (គណិតវិទ្យា) ដែលបានអង្កេត') }}
                                    </label>
                                    <div class="space-y-2">
                                        @foreach(['កម្រិតដំបូង', 'លេខ១ខ្ទង់', 'លេខ២ខ្ទង់', 'ប្រមាណវិធីដក', 'ប្រមាណវិធីចែក', 'ចំណោទ'] as $level)
                                        <label class="inline-flex items-center mr-4">
                                            <input type="checkbox" name="numeracy_levels_observed[]" value="{{ $level }}" 
                                                   class="form-checkbox"
                                                   {{ in_array($level, old('numeracy_levels_observed', [])) ? 'checked' : '' }}>
                                            <span class="ml-2">{{ $level }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6" id="student_stats_section">
                                <!-- ចំនួនសិស្សសរុបក្នុងថ្នាក់ TaRL -->
                                <div>
                                    <label for="total_students_enrolled" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('ចំនួនសិស្សសរុបក្នុងថ្នាក់ TaRL') }}
                                    </label>
                                    <input type="number" 
                                           id="total_students_enrolled" 
                                           name="total_students_enrolled" 
                                           value="{{ old('total_students_enrolled') }}"
                                           max="20"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('total_students_enrolled')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- ចំនួនសិស្សមានវត្តមាននៅថ្ងៃចុះអង្កេត -->
                                <div>
                                    <label for="students_present" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('ចំនួនសិស្សមានវត្តមាននៅថ្ងៃចុះអង្កេត') }}
                                    </label>
                                    <input type="number" 
                                           id="students_present" 
                                           name="students_present" 
                                           value="{{ old('students_present') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('students_present')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- ចំនួនសិស្សដែលមានការកើនឡើងសមត្ថភាពធៀបនឹងសប្តាហ៍មុន -->
                                <div>
                                    <label for="students_improved_from_last_week" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('ចំនួនសិស្សដែលមានការកើនឡើងសមត្ថភាពធៀបនឹងសប្តាហ៍មុន') }}
                                    </label>
                                    <input type="number" 
                                           id="students_improved_from_last_week" 
                                           name="students_improved_from_last_week" 
                                           value="{{ old('students_improved_from_last_week') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('students_improved_from_last_week')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- តើមានថ្នាក់រៀនប៉ុន្មានបានដំណើរការមុនថ្ងៃចុះអង្កេតនេះ? -->
                                <div>
                                    <label for="classes_conducted_before_visit" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('តើមានថ្នាក់រៀនប៉ុន្មានបានដំណើរការមុនថ្ងៃចុះអង្កេតនេះ?') }}
                                    </label>
                                    <input type="number" 
                                           id="classes_conducted_before_visit" 
                                           name="classes_conducted_before_visit" 
                                           value="{{ old('classes_conducted_before_visit') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('classes_conducted_before_visit')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Questions -->
                        <div class="questionnaire-section mb-8" data-section="delivery_questions" id="delivery_section">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">សំណួរអំពីការបង្រៀន</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- តើថ្នាក់រៀនបានចាប់ផ្តើមទាន់ពេលដែរឬទេ (ក្នុងរយៈពេល ៥ នាទី)? -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('តើថ្នាក់រៀនបានចាប់ផ្តើមទាន់ពេលដែរឬទេ (ក្នុងរយៈពេល ៥ នាទី)?') }}
                                    </label>
                                    <div class="space-y-2">
                                        @foreach(['បាទ/ចាស' => 1, 'ទេ' => 0, 'មិនដឹង' => -1] as $label => $value)
                                        <label class="inline-flex items-center mr-4">
                                            <input type="radio" name="class_started_on_time" value="{{ $value }}" 
                                                   class="form-radio"
                                                   {{ old('class_started_on_time') == $value ? 'checked' : '' }}>
                                            <span class="ml-2">{{ __($label) }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                    @error('class_started_on_time')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- ហេតុអ្វីបានជាថ្នាក់រៀនមិនចាប់ផ្តើមទាន់ពេល? -->
                                <div id="late_start_reason_container" style="display: none;">
                                    <label for="late_start_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('ហេតុអ្វីបានជាថ្នាក់រៀនមិនចាប់ផ្តើមទាន់ពេល?') }} <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="late_start_reason" 
                                              name="late_start_reason" 
                                              rows="3"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('late_start_reason') }}</textarea>
                                    @error('late_start_reason')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Classroom Related Questions -->
                        <div class="questionnaire-section mb-8" data-section="classroom_questions" id="classroom_section">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">សំណួរទាក់ទងនឹងថ្នាក់រៀន</h4>
                            
                            <div class="space-y-6">
                                <!-- តើសម្ភារឧបទេសអ្វីខ្លះដែលអ្នកបានឃើញមាននៅក្នុងថ្នាក់រៀន? -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('តើសម្ភារឧបទេសអ្វីខ្លះដែលអ្នកបានឃើញមាននៅក្នុងថ្នាក់រៀន?') }}
                                    </label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        @php
                                        $materials = [
                                            'តារាងលេខ ០-៩៩',
                                            'តារាងតម្លៃលេខតាមខ្ទង់',
                                            'បណ្ណតម្លៃលេខតាមខ្ទង់',
                                            'តារាងបូកលេខដោយផ្ទាល់មាត់',
                                            'តារាងដកលេខដោយផ្ទាល់មាត់',
                                            'តារាងគុណលេខដោយផ្ទាល់មាត់',
                                            'ប្រាក់លេង',
                                            'សៀវភៅគ្រូមុខវិទ្យាគណិតវិទ្យា',
                                            'ទុយោ',
                                            'កូនសៀវភៅចំណោទ (បូក ដក គុណ ចែក)',
                                            'តារាងព្យាង្គ',
                                            'បណ្ណរូបភាព',
                                            'ការតម្រៀបល្បះ និងកូនសៀវភៅការកែតម្រូវកំហុស',
                                            'បណ្ណពាក្យ/បណ្ណព្យាង្គ',
                                            'បណ្ណរឿង/អត្ថបទ',
                                            'សៀវភៅគ្រូមុខវិជ្ជាភាសាខ្មែរ'
                                        ];
                                        @endphp
                                        @foreach($materials as $material)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="materials_present[]" value="{{ $material }}" 
                                                   class="form-checkbox"
                                                   {{ in_array($material, old('materials_present', [])) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm">{{ $material }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- តើសិស្សត្រូវបានបែងចែកជាក្រុមសមស្របតាមកម្រិតសមត្ថភាពដែរឬទេ? -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('តើសិស្សត្រូវបានបែងចែកជាក្រុមសមស្របតាមកម្រិតសមត្ថភាពដែរឬទេ?') }}
                                    </label>
                                    <div class="space-y-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="children_grouped_appropriately" value="1" 
                                                   class="form-radio"
                                                   {{ old('children_grouped_appropriately') == '1' ? 'checked' : '' }}>
                                            <span class="ml-2">{{ __('បាទ/ចាស') }}</span>
                                        </label>
                                        <label class="inline-flex items-center ml-6">
                                            <input type="radio" name="children_grouped_appropriately" value="0" 
                                                   class="form-radio"
                                                   {{ old('children_grouped_appropriately') == '0' ? 'checked' : '' }}>
                                            <span class="ml-2">{{ __('ទេ') }}</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- តើសិស្សបានចូលរួមយ៉ាងសកម្មក្នុងសកម្មភាពដែរឬទេ? -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('តើសិស្សបានចូលរួមយ៉ាងសកម្មក្នុងសកម្មភាពដែរឬទេ?') }}
                                    </label>
                                    <div class="space-y-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="students_fully_involved" value="1" 
                                                   class="form-radio"
                                                   {{ old('students_fully_involved') == '1' ? 'checked' : '' }}>
                                            <span class="ml-2">{{ __('បាទ/ចាស') }}</span>
                                        </label>
                                        <label class="inline-flex items-center ml-6">
                                            <input type="radio" name="students_fully_involved" value="0" 
                                                   class="form-radio"
                                                   {{ old('students_fully_involved') == '0' ? 'checked' : '' }}>
                                            <span class="ml-2">{{ __('ទេ') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Teacher Related Questions -->
                        <div class="questionnaire-section mb-8" data-section="teacher_questions" id="teacher_section">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">សំណួរទាក់ទងនឹងគ្រូបង្រៀន</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- តើគ្រូមានកិច្ចតែងការបង្រៀន (ផែនការមេរៀន) ដែរឬទេ? -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('តើគ្រូមានកិច្ចតែងការបង្រៀន (ផែនការមេរៀន) ដែរឬទេ?') }}
                                    </label>
                                    <div class="space-y-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="has_session_plan" value="1" 
                                                   class="form-radio"
                                                   {{ old('has_session_plan') == '1' ? 'checked' : '' }}>
                                            <span class="ml-2">{{ __('បាទ/ចាស') }}</span>
                                        </label>
                                        <label class="inline-flex items-center ml-6">
                                            <input type="radio" name="has_session_plan" value="0" 
                                                   class="form-radio"
                                                   {{ old('has_session_plan') == '0' ? 'checked' : '' }}>
                                            <span class="ml-2">{{ __('ទេ') }}</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- ហេតុអ្វីបានជាគ្រូមិនមានកិច្ចតែងការបង្រៀន? -->
                                <div id="no_session_plan_reason_container" style="display: none;">
                                    <label for="no_session_plan_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('ហេតុអ្វីបានជាគ្រូមិនមានកិច្ចតែងការបង្រៀន?') }}
                                    </label>
                                    <textarea id="no_session_plan_reason" 
                                              name="no_session_plan_reason" 
                                              rows="3"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('no_session_plan_reason') }}</textarea>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6" id="session_plan_details" style="display: none;">
                                <!-- តើគ្រូបានបង្រៀនតាមកិច្ចតែងការបង្រៀនដែរឬទេ? -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('តើគ្រូបានបង្រៀនតាមកិច្ចតែងការបង្រៀនដែរឬទេ?') }}
                                    </label>
                                    <div class="space-y-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="followed_session_plan" value="1" 
                                                   class="form-radio"
                                                   {{ old('followed_session_plan') == '1' ? 'checked' : '' }}>
                                            <span class="ml-2">{{ __('បាទ/ចាស') }}</span>
                                        </label>
                                        <label class="inline-flex items-center ml-6">
                                            <input type="radio" name="followed_session_plan" value="0" 
                                                   class="form-radio"
                                                   {{ old('followed_session_plan') == '0' ? 'checked' : '' }}>
                                            <span class="ml-2">{{ __('ទេ') }}</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- ហេតុអ្វីបានជាគ្រូមិនបានបង្រៀនតាមកិច្ចតែងការបង្រៀន? -->
                                <div id="no_follow_plan_reason_container" style="display: none;">
                                    <label for="no_follow_plan_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('ហេតុអ្វីបានជាគ្រូមិនបានបង្រៀនតាមកិច្ចតែងការបង្រៀន?') }}
                                    </label>
                                    <textarea id="no_follow_plan_reason" 
                                              name="no_follow_plan_reason" 
                                              rows="3"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('no_follow_plan_reason') }}</textarea>
                                </div>
                                
                                <!-- តើកិច្ចតែងការបង្រៀនសមស្របនឹងកម្រិតសមត្ថភាពរបស់សិស្សដែរឬទេ? -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('តើកិច្ចតែងការបង្រៀនសមស្របនឹងកម្រិតសមត្ថភាពរបស់សិស្សដែរឬទេ?') }}
                                    </label>
                                    <div class="space-y-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="session_plan_appropriate" value="1" 
                                                   class="form-radio"
                                                   {{ old('session_plan_appropriate') == '1' ? 'checked' : '' }}>
                                            <span class="ml-2">{{ __('បាទ/ចាស') }}</span>
                                        </label>
                                        <label class="inline-flex items-center ml-6">
                                            <input type="radio" name="session_plan_appropriate" value="0" 
                                                   class="form-radio"
                                                   {{ old('session_plan_appropriate') == '0' ? 'checked' : '' }}>
                                            <span class="ml-2">{{ __('ទេ') }}</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- កំណត់ចំណាំ/មតិកែលម្អដែលបានផ្តល់លើកិច្ចតែងការបង្រៀន -->
                                <div>
                                    <label for="session_plan_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('កំណត់ចំណាំ/មតិកែលម្អដែលបានផ្តល់លើកិច្ចតែងការបង្រៀន') }}
                                    </label>
                                    <textarea id="session_plan_notes" 
                                              name="session_plan_notes" 
                                              rows="3"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('session_plan_notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Related Questions -->
                        <div class="questionnaire-section mb-8" data-section="activities" id="activities_section">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">សំណួរទាក់ទងនឹងសកម្មភាព</h4>
                            
                            <!-- តើមានសកម្មភាពប៉ុន្មានត្រូវបានអង្កេត? -->
                            <div class="mb-6">
                                <label for="number_of_activities" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('តើមានសកម្មភាពប៉ុន្មានត្រូវបានអង្កេត?') }}
                                </label>
                                <select id="number_of_activities" 
                                        name="number_of_activities" 
                                        class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="0">0</option>
                                    <option value="1" {{ old('number_of_activities') == 1 ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ old('number_of_activities') == 2 ? 'selected' : '' }}>2</option>
                                    <option value="3" {{ old('number_of_activities') == 3 ? 'selected' : '' }}>3</option>
                                    <option value="4" {{ old('number_of_activities') == 4 ? 'selected' : '' }}>4</option>
                                    <option value="5" {{ old('number_of_activities') == 5 ? 'selected' : '' }}>5</option>
                                </select>
                            </div>
                            
                            <!-- Activity 1 -->
                            <div id="activity1_section" style="display: none;" class="border-l-4 border-blue-500 pl-4 mb-6">
                                <h5 class="font-semibold text-gray-700 mb-4">សកម្មភាពទី១</h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Activity name for Khmer -->
                                    <div id="activity1_khmer_container" style="display: none;">
                                        <label for="activity1_name_language" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('តើសកម្មភាពទីមួយណាដែលត្រូវបានអនុវត្ត? (ភាសាខ្មែរ)') }}
                                        </label>
                                        <select id="activity1_name_language" 
                                                name="activity1_name_language" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">ជ្រើសរើសសកម្មភាព</option>
                                            @php
                                            $khmerActivities = [
                                                'ការសន្ទនាសេរី',
                                                'ការពណ៌នារូបភាព',
                                                'ការអានកថាខណ្ឌ',
                                                'ផែនទីគំនិត',
                                                'ការចម្លងនិងសរសេរតាមអាន',
                                                'ល្បែងបោះចូលកន្ត្រក (បោះព្យញ្ជនៈ, ស្រៈ , ពាក្យ ចូលកន្ត្រក)',
                                                'ល្បែងត្រឡប់បណ្ណពាក្យ',
                                                'ល្បែងលោតលើតួអក្សរ',
                                                'ការអានតារាងព្យាង្គ',
                                                'ការកំណត់ចំនួនព្យាង្គក្នុងពាក្យ',
                                                'ការបង្កើតពាក្យនិងប្រយោគ',
                                                'ពាក្យចួន',
                                                'ល្បែងគ្រ័រ',
                                                'ការបង្កើតពាក្យតាមសូរចុង',
                                                'ការកែតម្រូវកំហុស',
                                                'ការបង្កើតសាច់រឿងបន្ត',
                                                'សកម្មភាពផ្អែកលើមេរៀន (៨ ជំហាន)'
                                            ];
                                            @endphp
                                            @foreach($khmerActivities as $activity)
                                            <option value="{{ $activity }}" {{ old('activity1_name_language') == $activity ? 'selected' : '' }}>
                                                {{ $activity }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Activity name for Math -->
                                    <div id="activity1_math_container" style="display: none;">
                                        <label for="activity1_name_numeracy" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('តើសកម្មភាពទីមួយណាដែលត្រូវបានអនុវត្ត? (គណិតវិទ្យា)') }}
                                        </label>
                                        <select id="activity1_name_numeracy" 
                                                name="activity1_name_numeracy" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">ជ្រើសរើសសកម្មភាព</option>
                                            @php
                                            $mathActivities = [
                                                'ចំនួនដោយប្រើបាច់ឈើនិងឈើ',
                                                'ការអានតារាងលេខ',
                                                'ល្បែងកង់លេខ',
                                                'ល្បែងលោតលើលេខ',
                                                'ការទះដៃនិងផ្ទាត់ម្រាមដៃ',
                                                'តារាងតម្លៃលេខតាមខ្ទង់',
                                                'បណ្ណតម្លៃលេខតាមខ្ទង់',
                                                'សៀវភៅកំណត់ត្រាលេខ',
                                                'ចំនួនជាមួយប្រាក់លេង',
                                                'ការដោះស្រាយចំណោទ',
                                                'រកផលគុណជាមួយលេខប្រាំបួន',
                                                'ប្រមាណវិធីបូកដោយប្រើបាច់ឈើនិងឈើ',
                                                'ប្រមាណវិធីដកដោយប្រើបាច់ឈើនិងឈើ',
                                                'ប្រមាណវិធីបូកដកដោយផ្ទាល់មាត់',
                                                'ប្រមាណវិធីបូកដោយប្រើប្រាក់លេង',
                                                'ប្រមាណវិធីដកដោយប្រើប្រាក់លេង',
                                                'ប្រមាណវិធីគុណដោយប្រើឈើ',
                                                'ប្រមាណវិធីគុណដោយបំបែកលេខ',
                                                'ការសូត្រតារាងមេគុណ',
                                                'ប្រមាណវិធីគុណក្នុងប្រអប់',
                                                'ប្រមាណវិធីគុណដោយប្រើតារាងតម្លៃលេខតាមខ្ទង់',
                                                'ប្រមាណវិធីចែកដោយប្រើឈើ',
                                                'ប្រមាណវិធីចែកដោយប្រើប្រាក់លេង',
                                                'ប្រមាណវិធីចែកដោយប្រើតារាងមេគុណ'
                                            ];
                                            @endphp
                                            @foreach($mathActivities as $activity)
                                            <option value="{{ $activity }}" {{ old('activity1_name_numeracy') == $activity ? 'selected' : '' }}>
                                                {{ $activity }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Duration -->
                                    <div>
                                        <label for="activity1_duration" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('តើសកម្មភាពទីមួយប្រើរយៈពេលប៉ុន្មាន? (នាទី)') }}
                                        </label>
                                        <input type="number" 
                                               id="activity1_duration" 
                                               name="activity1_duration" 
                                               value="{{ old('activity1_duration') }}"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    
                                    <!-- Clear instructions -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('តើគ្រូបានណែនាំពីសកម្មភាពទីមួយបានច្បាស់លាស់ដែរឬទេ?') }}
                                        </label>
                                        <div class="space-y-2">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="activity1_clear_instructions" value="1" 
                                                       class="form-radio"
                                                       {{ old('activity1_clear_instructions') == '1' ? 'checked' : '' }}>
                                                <span class="ml-2">{{ __('បាទ/ចាស') }}</span>
                                            </label>
                                            <label class="inline-flex items-center ml-6">
                                                <input type="radio" name="activity1_clear_instructions" value="0" 
                                                       class="form-radio"
                                                       {{ old('activity1_clear_instructions') == '0' ? 'checked' : '' }}>
                                                <span class="ml-2">{{ __('ទេ') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <!-- No clear instructions reason -->
                                    <div id="activity1_no_clear_instructions_container" style="display: none;">
                                        <label for="activity1_no_clear_instructions_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('ហេតុអ្វីបានជាគ្រូមិនបានណែនាំពីសកម្មភាពទីមួយបានច្បាស់លាស់?') }}
                                        </label>
                                        <textarea id="activity1_no_clear_instructions_reason" 
                                                  name="activity1_no_clear_instructions_reason" 
                                                  rows="2"
                                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('activity1_no_clear_instructions_reason') }}</textarea>
                                    </div>
                                    
                                    <!-- Followed process -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('តើគ្រូបានអនុវត្តតាមដំណើរការនៃសកម្មភាពដូចមានក្នុងកិច្ចតែងការបង្រៀនដែរឬទេ?') }}
                                        </label>
                                        <div class="space-y-2">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="activity1_followed_process" value="1" 
                                                       class="form-radio"
                                                       {{ old('activity1_followed_process') == '1' ? 'checked' : '' }}>
                                                <span class="ml-2">{{ __('បាទ/ចាស') }}</span>
                                            </label>
                                            <label class="inline-flex items-center ml-6">
                                                <input type="radio" name="activity1_followed_process" value="0" 
                                                       class="form-radio"
                                                       {{ old('activity1_followed_process') == '0' ? 'checked' : '' }}>
                                                <span class="ml-2">{{ __('ទេ') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <!-- Not followed reason -->
                                    <div id="activity1_not_followed_reason_container" style="display: none;">
                                        <label for="activity1_not_followed_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('បើមិនបានអនុវត្តតាម, ហេតុអ្វី?') }}
                                        </label>
                                        <textarea id="activity1_not_followed_reason" 
                                                  name="activity1_not_followed_reason" 
                                                  rows="2"
                                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('activity1_not_followed_reason') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Activity 2 -->
                            <div id="activity2_section" style="display: none;" class="border-l-4 border-green-500 pl-4 mb-6">
                                <h5 class="font-semibold text-gray-700 mb-4">សកម្មភាពទី២</h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Activity name for Khmer -->
                                    <div id="activity2_khmer_container" style="display: none;">
                                        <label for="activity2_name_language" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('តើសកម្មភាពទីពីរណាដែលត្រូវបានអនុវត្ត? (ភាសាខ្មែរ)') }}
                                        </label>
                                        <select id="activity2_name_language" 
                                                name="activity2_name_language" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">ជ្រើសរើសសកម្មភាព</option>
                                            @foreach($khmerActivities as $activity)
                                            <option value="{{ $activity }}" {{ old('activity2_name_language') == $activity ? 'selected' : '' }}>
                                                {{ $activity }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Activity name for Math -->
                                    <div id="activity2_math_container" style="display: none;">
                                        <label for="activity2_name_numeracy" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('តើសកម្មភាពទីពីរណាដែលត្រូវបានអនុវត្ត? (គណិតវិទ្យា)') }}
                                        </label>
                                        <select id="activity2_name_numeracy" 
                                                name="activity2_name_numeracy" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">ជ្រើសរើសសកម្មភាព</option>
                                            @foreach($mathActivities as $activity)
                                            <option value="{{ $activity }}" {{ old('activity2_name_numeracy') == $activity ? 'selected' : '' }}>
                                                {{ $activity }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Duration -->
                                    <div>
                                        <label for="activity2_duration" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('តើសកម្មភាពទីពីរប្រើរយៈពេលប៉ុន្មាន? (នាទី)') }}
                                        </label>
                                        <input type="number" 
                                               id="activity2_duration" 
                                               name="activity2_duration" 
                                               value="{{ old('activity2_duration') }}"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    
                                    <!-- Clear instructions -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('តើគ្រូបានណែនាំពីសកម្មភាពទីពីរបានច្បាស់លាស់ដែរឬទេ?') }}
                                        </label>
                                        <div class="space-y-2">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="activity2_clear_instructions" value="1" 
                                                       class="form-radio"
                                                       {{ old('activity2_clear_instructions') == '1' ? 'checked' : '' }}>
                                                <span class="ml-2">{{ __('បាទ/ចាស') }}</span>
                                            </label>
                                            <label class="inline-flex items-center ml-6">
                                                <input type="radio" name="activity2_clear_instructions" value="0" 
                                                       class="form-radio"
                                                       {{ old('activity2_clear_instructions') == '0' ? 'checked' : '' }}>
                                                <span class="ml-2">{{ __('ទេ') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <!-- No clear instructions reason -->
                                    <div id="activity2_no_clear_instructions_container" style="display: none;">
                                        <label for="activity2_no_clear_instructions_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('ហេតុអ្វីបានជាគ្រូមិនបានណែនាំពីសកម្មភាពទីពីរបានច្បាស់លាស់?') }}
                                        </label>
                                        <textarea id="activity2_no_clear_instructions_reason" 
                                                  name="activity2_no_clear_instructions_reason" 
                                                  rows="2"
                                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('activity2_no_clear_instructions_reason') }}</textarea>
                                    </div>
                                    
                                    <!-- Followed process -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('តើគ្រូបានអនុវត្តតាមដំណើរនៃសកម្មភាពដូចមានក្នុងកិច្ចតែងការបង្រៀនដែរឬទេ?') }}
                                        </label>
                                        <div class="space-y-2">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="activity2_followed_process" value="1" 
                                                       class="form-radio"
                                                       {{ old('activity2_followed_process') == '1' ? 'checked' : '' }}>
                                                <span class="ml-2">{{ __('បាទ/ចាស') }}</span>
                                            </label>
                                            <label class="inline-flex items-center ml-6">
                                                <input type="radio" name="activity2_followed_process" value="0" 
                                                       class="form-radio"
                                                       {{ old('activity2_followed_process') == '0' ? 'checked' : '' }}>
                                                <span class="ml-2">{{ __('ទេ') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <!-- Not followed reason -->
                                    <div id="activity2_not_followed_reason_container" style="display: none;">
                                        <label for="activity2_not_followed_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('បើមិនបានអនុវត្តតាម, ហេតុអ្វី?') }}
                                        </label>
                                        <textarea id="activity2_not_followed_reason" 
                                                  name="activity2_not_followed_reason" 
                                                  rows="2"
                                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('activity2_not_followed_reason') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Activity 3 -->
                            <div id="activity3_section" style="display: none;" class="border-l-4 border-purple-500 pl-4 mb-6">
                                <h5 class="font-semibold text-gray-700 mb-4">សកម្មភាពទី៣</h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Activity name for Khmer -->
                                    <div id="activity3_khmer_container" style="display: none;">
                                        <label for="activity3_name_language" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('តើសកម្មភាពទីបីណាដែលត្រូវបានអនុវត្ត? (ភាសាខ្មែរ)') }}
                                        </label>
                                        <select id="activity3_name_language" 
                                                name="activity3_name_language" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">ជ្រើសរើសសកម្មភាព</option>
                                            @foreach($khmerActivities as $activity)
                                            <option value="{{ $activity }}" {{ old('activity3_name_language') == $activity ? 'selected' : '' }}>
                                                {{ $activity }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Activity name for Math -->
                                    <div id="activity3_math_container" style="display: none;">
                                        <label for="activity3_name_numeracy" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('តើសកម្មភាពទីបីណាដែលត្រូវបានអនុវត្ត? (គណិតវិទ្យា)') }}
                                        </label>
                                        <select id="activity3_name_numeracy" 
                                                name="activity3_name_numeracy" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">ជ្រើសរើសសកម្មភាព</option>
                                            @foreach($mathActivities as $activity)
                                            <option value="{{ $activity }}" {{ old('activity3_name_numeracy') == $activity ? 'selected' : '' }}>
                                                {{ $activity }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Duration -->
                                    <div>
                                        <label for="activity3_duration" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('តើសកម្មភាពទីបីប្រើរយៈពេលប៉ុន្មាន? (នាទី)') }}
                                        </label>
                                        <input type="number" 
                                               id="activity3_duration" 
                                               name="activity3_duration" 
                                               value="{{ old('activity3_duration') }}"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    
                                    <!-- Clear instructions -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('តើគ្រូបានណែនាំពីសកម្មភាពទីបីបានច្បាស់លាស់ដែរឬទេ?') }}
                                        </label>
                                        <div class="space-y-2">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="activity3_clear_instructions" value="1" 
                                                       class="form-radio"
                                                       {{ old('activity3_clear_instructions') == '1' ? 'checked' : '' }}>
                                                <span class="ml-2">{{ __('បាទ/ចាស') }}</span>
                                            </label>
                                            <label class="inline-flex items-center ml-6">
                                                <input type="radio" name="activity3_clear_instructions" value="0" 
                                                       class="form-radio"
                                                       {{ old('activity3_clear_instructions') == '0' ? 'checked' : '' }}>
                                                <span class="ml-2">{{ __('ទេ') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <!-- No clear instructions reason -->
                                    <div id="activity3_no_clear_instructions_container" style="display: none;">
                                        <label for="activity3_no_clear_instructions_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('ហេតុអ្វីបានជាគ្រូមិនបានណែនាំពីសកម្មភាពទីបីបានច្បាស់លាស់?') }}
                                        </label>
                                        <textarea id="activity3_no_clear_instructions_reason" 
                                                  name="activity3_no_clear_instructions_reason" 
                                                  rows="2"
                                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('activity3_no_clear_instructions_reason') }}</textarea>
                                    </div>
                                    
                                    <!-- Followed process -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('តើគ្រូបានអនុវត្តតាមដំណើរការនៃសកម្មភាពដូចមានក្នុងកិច្ចតែងការបង្រៀនដែរឬទេ?') }}
                                        </label>
                                        <div class="space-y-2">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="activity3_followed_process" value="1" 
                                                       class="form-radio"
                                                       {{ old('activity3_followed_process') == '1' ? 'checked' : '' }}>
                                                <span class="ml-2">{{ __('បាទ/ចាស') }}</span>
                                            </label>
                                            <label class="inline-flex items-center ml-6">
                                                <input type="radio" name="activity3_followed_process" value="0" 
                                                       class="form-radio"
                                                       {{ old('activity3_followed_process') == '0' ? 'checked' : '' }}>
                                                <span class="ml-2">{{ __('ទេ') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <!-- Not followed reason -->
                                    <div id="activity3_not_followed_reason_container" style="display: none;">
                                        <label for="activity3_not_followed_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('បើមិនបានអនុវត្តតាម, ហេតុអ្វី?') }}
                                        </label>
                                        <textarea id="activity3_not_followed_reason" 
                                                  name="activity3_not_followed_reason" 
                                                  rows="2"
                                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('activity3_not_followed_reason') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Miscellaneous -->
                        <div class="questionnaire-section mb-8" data-section="miscellaneous">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">ផ្សេងៗ</h4>
                            
                            <!-- មតិយោបល់សម្រាប់គ្រូបង្រៀន -->
                            <div>
                                <label for="teacher_feedback" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('មតិយោបល់សម្រាប់គ្រូបង្រៀន (ប្រសិនបើមាន) (១០០-១២០ ពាក្យ)') }}
                                </label>
                                <textarea id="teacher_feedback" 
                                          name="teacher_feedback" 
                                          rows="4"
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                          placeholder="សូមបញ្ចូលមតិយោបល់សម្រាប់គ្រូបង្រៀន (១០០-១២០ ពាក្យ)">{{ old('teacher_feedback') }}</textarea>
                                @error('teacher_feedback')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-between">
                            <a href="{{ route('mentoring.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg">
                                បោះបង់
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                បញ្ជូន
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
            
            // Update progress bar
            function updateProgress() {
                const form = document.getElementById('mentoringForm');
                const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
                const filledInputs = Array.from(inputs).filter(input => {
                    if (input.type === 'radio') {
                        return form.querySelector(`input[name="${input.name}"]:checked`) !== null;
                    } else if (input.type === 'checkbox') {
                        return form.querySelector(`input[name="${input.name}"]:checked`) !== null;
                    }
                    return input.value.trim() !== '';
                });
                
                const progress = Math.round((filledInputs.length / inputs.length) * 100);
                document.getElementById('progressPercent').textContent = progress;
                document.getElementById('progressBar').style.width = progress + '%';
            }
            
            // Dynamic form fields based on conditions
            
            // School-Teacher relationship
            const schoolSelect = document.getElementById('pilot_school_id');
            const teacherSelect = document.getElementById('teacher_id');
            
            if (schoolSelect) {
                // Function to populate teachers for a given school
                function populateTeachers(schoolId, selectedTeacherId = null) {
                    teacherSelect.innerHTML = '<option value="">ជ្រើសរើសគ្រូ</option>';
                    
                    if (schoolId) {
                        fetch(`/api/school/${schoolId}/teachers`)
                            .then(response => response.json())
                            .then(data => {
                                data.forEach(teacher => {
                                    const option = document.createElement('option');
                                    option.value = teacher.id;
                                    option.textContent = teacher.name;
                                    // Preserve previously selected teacher
                                    if (selectedTeacherId && teacher.id == selectedTeacherId) {
                                        option.selected = true;
                                    }
                                    teacherSelect.appendChild(option);
                                });
                                updateProgress();
                            });
                    }
                }
                
                // Handle school change event
                schoolSelect.addEventListener('change', function() {
                    populateTeachers(this.value);
                });
                
                // Preserve form state after validation errors
                const oldSchoolId = '{{ old('pilot_school_id') }}';
                const oldTeacherId = '{{ old('teacher_id') }}';
                
                if (oldSchoolId) {
                    schoolSelect.value = oldSchoolId;
                    populateTeachers(oldSchoolId, oldTeacherId);
                }
            }
            
            // Class in session logic
            const classInSessionRadios = document.querySelectorAll('input[name="class_in_session"]');
            const classNotInSessionReasonContainer = document.getElementById('class_not_in_session_reason_container');
            const classDetailsSections = ['class_details_section', 'student_stats_section', 'delivery_section', 
                                         'classroom_section', 'teacher_section', 'activities_section'];
            
            classInSessionRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === '0') {
                        classNotInSessionReasonContainer.style.display = 'block';
                        classDetailsSections.forEach(id => {
                            const element = document.getElementById(id);
                            if (element) element.style.display = 'none';
                        });
                    } else {
                        classNotInSessionReasonContainer.style.display = 'none';
                        classDetailsSections.forEach(id => {
                            const element = document.getElementById(id);
                            if (element) element.style.display = 'block';
                        });
                    }
                    updateProgress();
                });
            });
            
            // Subject observed logic
            const subjectObserved = document.getElementById('subject_observed');
            const languageLevelsContainer = document.getElementById('language_levels_container');
            const numeracyLevelsContainer = document.getElementById('numeracy_levels_container');
            
            if (subjectObserved) {
                subjectObserved.addEventListener('change', function() {
                    if (this.value === 'ភាសាខ្មែរ') {
                        languageLevelsContainer.style.display = 'block';
                        numeracyLevelsContainer.style.display = 'none';
                        // Show/hide activity fields for activities 1-3
                        document.querySelectorAll('[id*="_khmer_container"]').forEach(el => el.style.display = 'block');
                        document.querySelectorAll('[id*="_math_container"]').forEach(el => el.style.display = 'none');
                    } else if (this.value === 'គណិតវិទ្យា') {
                        languageLevelsContainer.style.display = 'none';
                        numeracyLevelsContainer.style.display = 'block';
                        // Show/hide activity fields for activities 1-3
                        document.querySelectorAll('[id*="_khmer_container"]').forEach(el => el.style.display = 'none');
                        document.querySelectorAll('[id*="_math_container"]').forEach(el => el.style.display = 'block');
                    } else {
                        languageLevelsContainer.style.display = 'none';
                        numeracyLevelsContainer.style.display = 'none';
                        document.querySelectorAll('[id*="_khmer_container"]').forEach(el => el.style.display = 'none');
                        document.querySelectorAll('[id*="_math_container"]').forEach(el => el.style.display = 'none');
                    }
                });
            }
            
            // Class started on time logic
            const classStartedOnTimeRadios = document.querySelectorAll('input[name="class_started_on_time"]');
            const lateStartReasonContainer = document.getElementById('late_start_reason_container');
            
            classStartedOnTimeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const lateStartReasonTextarea = document.getElementById('late_start_reason');
                    if (this.value === '0') {
                        lateStartReasonContainer.style.display = 'block';
                        lateStartReasonTextarea.required = true;
                    } else {
                        lateStartReasonContainer.style.display = 'none';
                        lateStartReasonTextarea.required = false;
                    }
                });
            });
            
            // Initialize late start reason visibility on page load
            const checkedClassStartedOnTime = document.querySelector('input[name="class_started_on_time"]:checked');
            if (checkedClassStartedOnTime && checkedClassStartedOnTime.value === '0') {
                lateStartReasonContainer.style.display = 'block';
                document.getElementById('late_start_reason').required = true;
            }
            
            // Has session plan logic
            const hasSessionPlanRadios = document.querySelectorAll('input[name="has_session_plan"]');
            const noSessionPlanReasonContainer = document.getElementById('no_session_plan_reason_container');
            const sessionPlanDetails = document.getElementById('session_plan_details');
            
            hasSessionPlanRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === '1') {
                        noSessionPlanReasonContainer.style.display = 'none';
                        sessionPlanDetails.style.display = 'block';
                    } else {
                        noSessionPlanReasonContainer.style.display = 'block';
                        sessionPlanDetails.style.display = 'none';
                    }
                });
            });
            
            // Followed session plan logic
            const followedSessionPlanRadios = document.querySelectorAll('input[name="followed_session_plan"]');
            const noFollowPlanReasonContainer = document.getElementById('no_follow_plan_reason_container');
            
            followedSessionPlanRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    noFollowPlanReasonContainer.style.display = this.value === '0' ? 'block' : 'none';
                });
            });
            
            // Number of activities logic
            const numberOfActivities = document.getElementById('number_of_activities');
            console.log('Found numberOfActivities element:', numberOfActivities);
            
            if (numberOfActivities) {
                console.log('Adding event listener to numberOfActivities');
                numberOfActivities.addEventListener('change', function() {
                    const num = parseInt(this.value);
                    console.log('Selected number of activities:', num);
                    
                    // Show/hide activity sections (max 3 activities even if user selects 4 or 5)
                    const maxSections = Math.min(num, 3); // Cap at 3 sections
                    for (let i = 1; i <= 3; i++) {
                        const section = document.getElementById(`activity${i}_section`);
                        console.log(`Looking for activity${i}_section:`, section);
                        if (section) {
                            const shouldShow = i <= maxSections;
                            section.style.display = shouldShow ? 'block' : 'none';
                            console.log(`Activity ${i} display set to:`, shouldShow ? 'block' : 'none');
                        }
                    }
                });
                
                // Trigger on page load if there's already a value (from old() after validation error)
                if (numberOfActivities.value) {
                    numberOfActivities.dispatchEvent(new Event('change'));
                }
            } else {
                console.error('numberOfActivities element not found!');
            }
            
            // Activity clear instructions logic
            for (let i = 1; i <= 3; i++) {
                const clearInstructionsRadios = document.querySelectorAll(`input[name="activity${i}_clear_instructions"]`);
                const noClearInstructionsContainer = document.getElementById(`activity${i}_no_clear_instructions_container`);
                
                clearInstructionsRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        noClearInstructionsContainer.style.display = this.value === '0' ? 'block' : 'none';
                    });
                });
                
                // Activity followed process logic
                const followedProcessRadios = document.querySelectorAll(`input[name="activity${i}_followed_process"]`);
                const notFollowedReasonContainer = document.getElementById(`activity${i}_not_followed_reason_container`);
                
                followedProcessRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        notFollowedReasonContainer.style.display = this.value === '0' ? 'block' : 'none';
                    });
                });
            }
            
            // Validation for student numbers
            const totalStudents = document.getElementById('total_students_enrolled');
            const studentsPresent = document.getElementById('students_present');
            const studentsImproved = document.getElementById('students_improved_from_last_week');
            
            if (totalStudents && studentsPresent) {
                studentsPresent.addEventListener('input', function() {
                    const total = parseInt(totalStudents.value) || 0;
                    const present = parseInt(this.value) || 0;
                    
                    if (present > total) {
                        this.setCustomValidity('ចំនួនសិស្សមានវត្តមានមិនអាចលើសពីចំនួនសិស្សសរុបបានទេ');
                    } else {
                        this.setCustomValidity('');
                    }
                });
            }
            
            if (totalStudents && studentsImproved) {
                studentsImproved.addEventListener('input', function() {
                    const total = parseInt(totalStudents.value) || 0;
                    const improved = parseInt(this.value) || 0;
                    
                    if (improved > total) {
                        this.setCustomValidity('ចំនួនសិស្សដែលមានការកើនឡើងមិនអាចលើសពីចំនួនសិស្សសរុបបានទេ');
                    } else {
                        this.setCustomValidity('');
                    }
                });
            }
            
            // Validation for total students limit (max 20 as per TaRL requirements)
            if (totalStudents) {
                totalStudents.addEventListener('input', function() {
                    const total = parseInt(this.value) || 0;
                    
                    if (total > 20) {
                        this.setCustomValidity('ចំនួនសិស្សសរុបក្នុងថ្នាក់ TaRL មិនអាចលើសពី ២០ រូបបានទេ');
                    } else {
                        this.setCustomValidity('');
                    }
                });
            }
            
            // Update progress on input change
            const formInputs = document.querySelectorAll('#mentoringForm input, #mentoringForm select, #mentoringForm textarea');
            formInputs.forEach(input => {
                input.addEventListener('change', updateProgress);
                input.addEventListener('input', updateProgress);
            });
            
            // Initial progress update
            updateProgress();
            
            // Trigger initial state for preserved form values
            const checkedClassInSession = document.querySelector('input[name="class_in_session"]:checked');
            if (checkedClassInSession) {
                checkedClassInSession.dispatchEvent(new Event('change'));
            }
            
            // Trigger subject observed logic for preserved values
            const subjectObservedSelect = document.getElementById('subject_observed');
            if (subjectObservedSelect && subjectObservedSelect.value) {
                subjectObservedSelect.dispatchEvent(new Event('change'));
            }
            
            // Trigger number of activities logic for preserved values
            const numberOfActivitiesSelect = document.getElementById('number_of_activities');
            if (numberOfActivitiesSelect && numberOfActivitiesSelect.value) {
                console.log('Triggering numberOfActivities from initialization with value:', numberOfActivitiesSelect.value);
                numberOfActivitiesSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
    @endpush
</x-app-layout>