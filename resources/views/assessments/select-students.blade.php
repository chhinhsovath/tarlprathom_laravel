<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Select Students for :type Assessment', ['type' => ucfirst($assessmentType)]) }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('assessments.select-students', ['type' => 'midline']) }}" 
                   class="px-4 py-2 rounded-md text-sm font-medium {{ $assessmentType === 'midline' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    {{ __('Midline') }}
                </a>
                <a href="{{ route('assessments.select-students', ['type' => 'endline']) }}" 
                   class="px-4 py-2 rounded-md text-sm font-medium {{ $assessmentType === 'endline' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    {{ __('Endline') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('assessments.select-students') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <input type="hidden" name="type" value="{{ $assessmentType }}">
                        
                        <!-- Search -->
                        <div>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="{{ __('Search by name...') }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        @if(auth()->user()->isAdmin())
                        <!-- School Filter (Admin only) -->
                        <div>
                            <select name="school_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ __('All Schools') }}</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                        {{ $school->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Grade Filter -->
                        <div>
                            <select name="grade" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ __('All Grades') }}</option>
                                <option value="4" {{ request('grade') == '4' ? 'selected' : '' }}>
                                    {{ __('Grade') }} 4
                                </option>
                                <option value="5" {{ request('grade') == '5' ? 'selected' : '' }}>
                                    {{ __('Grade') }} 5
                                </option>
                            </select>
                        </div>

                        <!-- Baseline Khmer Filter -->
                        <div>
                            <select name="baseline_khmer" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ __('Baseline Khmer') }}</option>
                                <option value="letter" {{ request('baseline_khmer') == 'letter' ? 'selected' : '' }}>
                                    {{ __('Letter') }}
                                </option>
                                <option value="word" {{ request('baseline_khmer') == 'word' ? 'selected' : '' }}>
                                    {{ __('Word') }}
                                </option>
                                <option value="sentence" {{ request('baseline_khmer') == 'sentence' ? 'selected' : '' }}>
                                    {{ __('Sentence') }}
                                </option>
                                <option value="paragraph" {{ request('baseline_khmer') == 'paragraph' ? 'selected' : '' }}>
                                    {{ __('Paragraph') }}
                                </option>
                                <option value="not_assessed" {{ request('baseline_khmer') == 'not_assessed' ? 'selected' : '' }}>
                                    {{ __('Not Assessed') }}
                                </option>
                            </select>
                        </div>

                        <!-- Baseline Math Filter -->
                        <div>
                            <select name="baseline_math" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ __('Baseline Math') }}</option>
                                <option value="beginner" {{ request('baseline_math') == 'beginner' ? 'selected' : '' }}>
                                    {{ __('Beginner') }}
                                </option>
                                <option value="basic_operations" {{ request('baseline_math') == 'basic_operations' ? 'selected' : '' }}>
                                    {{ __('Basic Operations') }}
                                </option>
                                <option value="subtraction" {{ request('baseline_math') == 'subtraction' ? 'selected' : '' }}>
                                    {{ __('Subtraction') }}
                                </option>
                                <option value="multiplication_division" {{ request('baseline_math') == 'multiplication_division' ? 'selected' : '' }}>
                                    {{ __('Multiplication/Division') }}
                                </option>
                                <option value="not_assessed" {{ request('baseline_math') == 'not_assessed' ? 'selected' : '' }}>
                                    {{ __('Not Assessed') }}
                                </option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Filter') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Student Selection Form -->
            <form method="POST" action="{{ route('assessments.update-selected-students') }}">
                @csrf
                <input type="hidden" name="assessment_type" value="{{ $assessmentType }}">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-gray-600">
                                        {{ __('Select which students should be assessed for :type. Only selected students will appear in the assessment list.', ['type' => $assessmentType]) }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-2">
                                        <strong>{{ __('Note') }}:</strong> {{ __('All students are automatically eligible for baseline assessments.') }}
                                    </p>
                                </div>
                                <div>
                                    <button type="button" id="selectAll" class="text-sm text-blue-600 hover:text-blue-800 mr-4">
                                        {{ __('Select All') }}
                                    </button>
                                    <button type="button" id="deselectAll" class="text-sm text-blue-600 hover:text-blue-800">
                                        {{ __('Deselect All') }}
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Baseline Assessment Legend -->
                            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs font-medium text-gray-700 mb-2">{{ __('Baseline Assessment Levels') }}:</p>
                                <div class="flex flex-wrap gap-4 text-xs">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-600">{{ __('Khmer') }}:</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-red-100 text-red-800">{{ __('Letter') }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800">{{ __('Word') }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">{{ __('Sentence') }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-100 text-green-800">{{ __('Paragraph') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-600">{{ __('Math') }}:</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-red-100 text-red-800">{{ __('Beginner') }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800">{{ __('Basic Operations') }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">{{ __('Subtraction') }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-100 text-green-800">{{ __('Multiplication/Division') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($students->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Select') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Name') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Grade') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('School') }}
                                            </th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" colspan="2">
                                                {{ __('Baseline Assessment') }}
                                            </th>
                                        </tr>
                                        <tr class="bg-gray-50">
                                            <th colspan="4"></th>
                                            <th class="px-6 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Khmer') }}
                                            </th>
                                            <th class="px-6 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Math') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($students as $student)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="checkbox" 
                                                           name="students[]" 
                                                           value="{{ $student->id }}"
                                                           class="student-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                           {{ in_array($student->id, $eligibleStudentIds) ? 'checked' : '' }}>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $student->student_id }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ __('Grade') }} {{ $student->grade }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $student->school->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    @php
                                                        $khmerBaseline = $student->assessments->where('subject', 'khmer')->first();
                                                    @endphp
                                                    @if($khmerBaseline)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                            @if($khmerBaseline->level == 'letter') bg-red-100 text-red-800
                                                            @elseif($khmerBaseline->level == 'word') bg-yellow-100 text-yellow-800
                                                            @elseif($khmerBaseline->level == 'sentence') bg-blue-100 text-blue-800
                                                            @elseif($khmerBaseline->level == 'paragraph') bg-green-100 text-green-800
                                                            @endif">
                                                            {{ ucfirst($khmerBaseline->level) }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400">{{ __('Not Assessed') }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    @php
                                                        $mathBaseline = $student->assessments->where('subject', 'math')->first();
                                                    @endphp
                                                    @if($mathBaseline)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                            @if($mathBaseline->level == 'beginner') bg-red-100 text-red-800
                                                            @elseif($mathBaseline->level == 'basic_operations') bg-yellow-100 text-yellow-800
                                                            @elseif($mathBaseline->level == 'subtraction') bg-blue-100 text-blue-800
                                                            @elseif($mathBaseline->level == 'multiplication_division') bg-green-100 text-green-800
                                                            @endif">
                                                            {{ str_replace('_', ' ', ucfirst($mathBaseline->level)) }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400">{{ __('Not Assessed') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-4">
                                {{ $students->links() }}
                            </div>

                            <!-- Submit Button -->
                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                    {{ __('Save Selection') }}
                                </button>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">{{ __('No students found.') }}</p>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('selectAll').addEventListener('click', function() {
            document.querySelectorAll('.student-checkbox').forEach(function(checkbox) {
                checkbox.checked = true;
            });
        });

        document.getElementById('deselectAll').addEventListener('click', function() {
            document.querySelectorAll('.student-checkbox').forEach(function(checkbox) {
                checkbox.checked = false;
            });
        });
    </script>
    @endpush
</x-app-layout>