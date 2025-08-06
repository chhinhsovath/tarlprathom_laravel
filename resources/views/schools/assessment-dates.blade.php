<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Assessment Dates') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Instructions -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            {{ __('Set assessment periods for schools. Select one or more schools and define the date ranges for Baseline, Midline, and Endline assessments.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Date Setting Form -->
            <form method="POST" action="{{ route('schools.update-assessment-dates') }}" class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                @csrf
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Set Assessment Dates') }}</h3>
                    
                    <!-- Assessment Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- Baseline -->
                        <div class="space-y-4">
                            <h4 class="text-md font-medium text-gray-700">{{ __('Baseline Assessment') }}</h4>
                            <div>
                                <x-input-label for="baseline_start_date" :value="__('Start Date')" />
                                <x-text-input id="baseline_start_date" name="baseline_start_date" type="date" class="mt-1 block w-full" />
                                <x-input-error class="mt-2" :messages="$errors->get('baseline_start_date')" />
                            </div>
                            <div>
                                <x-input-label for="baseline_end_date" :value="__('End Date')" />
                                <x-text-input id="baseline_end_date" name="baseline_end_date" type="date" class="mt-1 block w-full" />
                                <x-input-error class="mt-2" :messages="$errors->get('baseline_end_date')" />
                            </div>
                        </div>
                        
                        <!-- Midline -->
                        <div class="space-y-4">
                            <h4 class="text-md font-medium text-gray-700">{{ __('Midline Assessment') }}</h4>
                            <div>
                                <x-input-label for="midline_start_date" :value="__('Start Date')" />
                                <x-text-input id="midline_start_date" name="midline_start_date" type="date" class="mt-1 block w-full" />
                                <x-input-error class="mt-2" :messages="$errors->get('midline_start_date')" />
                            </div>
                            <div>
                                <x-input-label for="midline_end_date" :value="__('End Date')" />
                                <x-text-input id="midline_end_date" name="midline_end_date" type="date" class="mt-1 block w-full" />
                                <x-input-error class="mt-2" :messages="$errors->get('midline_end_date')" />
                            </div>
                        </div>
                        
                        <!-- Endline -->
                        <div class="space-y-4">
                            <h4 class="text-md font-medium text-gray-700">{{ __('Endline Assessment') }}</h4>
                            <div>
                                <x-input-label for="endline_start_date" :value="__('Start Date')" />
                                <x-text-input id="endline_start_date" name="endline_start_date" type="date" class="mt-1 block w-full" />
                                <x-input-error class="mt-2" :messages="$errors->get('endline_start_date')" />
                            </div>
                            <div>
                                <x-input-label for="endline_end_date" :value="__('End Date')" />
                                <x-text-input id="endline_end_date" name="endline_end_date" type="date" class="mt-1 block w-full" />
                                <x-input-error class="mt-2" :messages="$errors->get('endline_end_date')" />
                            </div>
                        </div>
                    </div>
                    
                    <!-- School Selection -->
                    <div class="border-t pt-6">
                        <h4 class="text-md font-medium text-gray-700 mb-4">{{ __('Select Schools to Update') }}</h4>
                        
                        <div class="mb-4">
                            <div class="flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2">{{ __('Select All') }}</span>
                                </label>
                                <span class="text-sm text-gray-500">
                                    <span id="selectedCount">0</span> {{ __('schools selected') }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Schools Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Select') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('School Name') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Current Baseline') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Current Midline') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Current Endline') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($schools as $school)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" name="school_ids[]" value="{{ $school->id }}" class="school-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $school->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $school->school_code }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($school->baseline_start_date && $school->baseline_end_date)
                                                {{ $school->baseline_start_date->format('d/m/Y') }} - {{ $school->baseline_end_date->format('d/m/Y') }}
                                                <span class="ml-1 text-xs {{ $school->getAssessmentPeriodStatus('baseline') === 'active' ? 'text-green-600' : ($school->getAssessmentPeriodStatus('baseline') === 'upcoming' ? 'text-blue-600' : 'text-gray-400') }}">
                                                    ({{ ucfirst($school->getAssessmentPeriodStatus('baseline')) }})
                                                </span>
                                            @else
                                                <span class="text-gray-400">{{ __('Not set') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($school->midline_start_date && $school->midline_end_date)
                                                {{ $school->midline_start_date->format('d/m/Y') }} - {{ $school->midline_end_date->format('d/m/Y') }}
                                                <span class="ml-1 text-xs {{ $school->getAssessmentPeriodStatus('midline') === 'active' ? 'text-green-600' : ($school->getAssessmentPeriodStatus('midline') === 'upcoming' ? 'text-blue-600' : 'text-gray-400') }}">
                                                    ({{ ucfirst($school->getAssessmentPeriodStatus('midline')) }})
                                                </span>
                                            @else
                                                <span class="text-gray-400">{{ __('Not set') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($school->endline_start_date && $school->endline_end_date)
                                                {{ $school->endline_start_date->format('d/m/Y') }} - {{ $school->endline_end_date->format('d/m/Y') }}
                                                <span class="ml-1 text-xs {{ $school->getAssessmentPeriodStatus('endline') === 'active' ? 'text-green-600' : ($school->getAssessmentPeriodStatus('endline') === 'upcoming' ? 'text-blue-600' : 'text-gray-400') }}">
                                                    ({{ ucfirst($school->getAssessmentPeriodStatus('endline')) }})
                                                </span>
                                            @else
                                                <span class="text-gray-400">{{ __('Not set') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="mt-6 flex items-center gap-4">
                        <x-primary-button>{{ __('Update Selected Schools') }}</x-primary-button>
                        <a href="{{ route('schools.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </div>
            </form>

            <!-- Note -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>{{ __('Note:') }}</strong> {{ __('Only the date fields you fill in will be updated. Leave fields empty to keep existing dates unchanged.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Select all functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.school-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedCount();
        });

        // Update selected count
        document.querySelectorAll('.school-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });

        function updateSelectedCount() {
            const count = document.querySelectorAll('.school-checkbox:checked').length;
            document.getElementById('selectedCount').textContent = count;
        }

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const selectedSchools = document.querySelectorAll('.school-checkbox:checked');
            if (selectedSchools.length === 0) {
                e.preventDefault();
                alert('{{ __("Please select at least one school to update.") }}');
                return;
            }

            // Check if at least one date field is filled
            const dateFields = [
                'baseline_start_date', 'baseline_end_date',
                'midline_start_date', 'midline_end_date',
                'endline_start_date', 'endline_end_date'
            ];
            
            const hasDateInput = dateFields.some(field => {
                return document.getElementById(field).value !== '';
            });

            if (!hasDateInput) {
                e.preventDefault();
                alert('{{ __("Please enter at least one assessment date to update.") }}');
            }
        });
    </script>
    @endpush
</x-app-layout>