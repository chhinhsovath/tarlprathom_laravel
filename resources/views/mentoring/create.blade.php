<x-app-layout>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">{{ __('Log Mentoring Visit') }}</h3>
                    
                    <form id="mentoringForm" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Visit Date -->
                            <div>
                                <label for="visit_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Visit Date') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       id="visit_date" 
                                       name="visit_date" 
                                       value="{{ date('Y-m-d') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                                <p class="mt-1 text-sm text-red-600 hidden" id="visit_date_error"></p>
                            </div>
                            
                            <!-- School -->
                            <div>
                                <label for="school_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('School') }} <span class="text-red-500">*</span>
                                </label>
                                <select id="school_id" 
                                        name="school_id" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required>
                                    <option value="">{{ __('Select School') }}</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ $selectedSchoolId == $school->id ? 'selected' : '' }}>
                                            {{ $school->school_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-sm text-red-600 hidden" id="school_id_error"></p>
                            </div>
                            
                            <!-- Teacher -->
                            <div>
                                <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Teacher') }} <span class="text-red-500">*</span>
                                </label>
                                <select id="teacher_id" 
                                        name="teacher_id" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required>
                                    <option value="">{{ __('Select Teacher') }}</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" 
                                                data-school-id="{{ $teacher->school_id }}"
                                                {{ $selectedTeacherId == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-sm text-red-600 hidden" id="teacher_id_error"></p>
                            </div>
                            
                            <!-- Score -->
                            <div>
                                <label for="score" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Score') }} (0-100) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       id="score" 
                                       name="score" 
                                       min="0" 
                                       max="100"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                                <p class="mt-1 text-sm text-red-600 hidden" id="score_error"></p>
                            </div>
                        </div>
                        
                        <!-- Observation -->
                        <div class="mt-6">
                            <label for="observation" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Observation Notes') }} <span class="text-red-500">*</span>
                            </label>
                            <textarea id="observation" 
                                      name="observation" 
                                      rows="4"
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      required
                                      placeholder="{{ __('Describe your observations during the visit...') }}"></textarea>
                            <p class="mt-1 text-sm text-red-600 hidden" id="observation_error"></p>
                        </div>
                        
                        <!-- Photo Upload -->
                        <div class="mt-6">
                            <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Photo') }} ({{ __('Optional') }})
                            </label>
                            <input type="file" 
                                   id="photo" 
                                   name="photo" 
                                   accept="image/*"
                                   class="w-full">
                            <p class="mt-1 text-sm text-gray-500">{{ __('Upload a photo from the visit (max 5MB)') }}</p>
                            <p class="mt-1 text-sm text-red-600 hidden" id="photo_error"></p>
                        </div>
                        
                        <!-- Photo Preview -->
                        <div id="photoPreview" class="mt-4 hidden">
                            <img id="previewImage" class="h-32 w-auto rounded-lg shadow-md" alt="Photo preview">
                        </div>
                        
                        <!-- Submit Buttons -->
                        <div class="mt-6 flex items-center justify-end space-x-3">
                            <a href="{{ route('mentoring.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-200 disabled:opacity-25 transition">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" 
                                    id="submitBtn"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition">
                                {{ __('Save Visit') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Filter teachers based on selected school
            $('#school_id').change(function() {
                const selectedSchoolId = $(this).val();
                const teacherSelect = $('#teacher_id');
                
                // Reset teacher selection
                teacherSelect.val('');
                
                // Show/hide teachers based on school
                teacherSelect.find('option').each(function() {
                    if ($(this).val() === '') {
                        // Keep the default option
                        $(this).show();
                    } else {
                        const teacherSchoolId = $(this).data('school-id');
                        if (selectedSchoolId && teacherSchoolId == selectedSchoolId) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    }
                });
            });
            
            // Trigger school change on load if a school is selected
            if ($('#school_id').val()) {
                $('#school_id').trigger('change');
            }
            
            // Photo preview
            $('#photo').change(function() {
                const file = this.files[0];
                if (file) {
                    // Check file size (5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        $('#photo_error').text('{{ __("File size must be less than 5MB") }}').removeClass('hidden');
                        this.value = '';
                        $('#photoPreview').addClass('hidden');
                        return;
                    }
                    
                    $('#photo_error').addClass('hidden');
                    
                    // Show preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#previewImage').attr('src', e.target.result);
                        $('#photoPreview').removeClass('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#photoPreview').addClass('hidden');
                }
            });
            
            // Form submission
            $('#mentoringForm').submit(function(e) {
                e.preventDefault();
                
                // Clear previous errors
                $('.text-red-600').addClass('hidden').text('');
                
                // Show loading
                const submitBtn = $('#submitBtn');
                setButtonLoading(submitBtn, true);
                
                // Create FormData for file upload
                const formData = new FormData(this);
                
                $.ajax({
                    url: '{{ route("mentoring.store") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: '{{ __("Success") }}',
                            text: '{{ __("Mentoring visit recorded successfully") }}',
                            confirmButtonText: '{{ __("OK") }}'
                        }).then(() => {
                            window.location.href = '{{ route("mentoring.index") }}';
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // Validation errors
                            const errors = xhr.responseJSON.errors;
                            for (const field in errors) {
                                $(`#${field}_error`).text(errors[field][0]).removeClass('hidden');
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: '{{ __("Error") }}',
                                text: xhr.responseJSON?.message || '{{ __("Failed to save mentoring visit") }}'
                            });
                        }
                    },
                    complete: function() {
                        setButtonLoading(submitBtn, false);
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>