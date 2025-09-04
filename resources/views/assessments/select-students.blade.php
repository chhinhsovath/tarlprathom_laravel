<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ trans_db('select_students_for_assessment', ['type' => trans_db($assessmentType)]) }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('assessments.select-students', ['type' => 'midline']) }}" 
                   class="px-4 py-2 rounded-md text-sm font-medium {{ $assessmentType === 'midline' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    {{ trans_db('midline') }}
                </a>
                <a href="{{ route('assessments.select-students', ['type' => 'endline']) }}" 
                   class="px-4 py-2 rounded-md text-sm font-medium {{ $assessmentType === 'endline' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    {{ trans_db('endline') }}
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
                                   placeholder="{{ trans_db('search_by_name') }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        @if(auth()->user()->isAdmin())
                        <!-- School Filter (Admin only) -->
                        <div>
                            <select name="school_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ trans_db('all_schools') }}</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                        {{ $school->school_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Grade Filter -->
                        <div>
                            <select name="grade" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ trans_db('all_grades') }}</option>
                                <option value="4" {{ request('grade') == '4' ? 'selected' : '' }}>
                                    {{ trans_db('grade') }} 4
                                </option>
                                <option value="5" {{ request('grade') == '5' ? 'selected' : '' }}>
                                    {{ trans_db('grade') }} 5
                                </option>
                                <option value="6" {{ request('grade') == '6' ? 'selected' : '' }}>
                                    {{ trans_db('grade') }} 6
                                </option>
                            </select>
                        </div>

                        <!-- Baseline Khmer Filter -->
                        <div>
                            <select name="baseline_khmer" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ trans_db('baseline_khmer') }}</option>
                                <option value="letter" {{ request('baseline_khmer') == 'letter' ? 'selected' : '' }}>
                                    {{ trans_db('letter') }}
                                </option>
                                <option value="word" {{ request('baseline_khmer') == 'word' ? 'selected' : '' }}>
                                    {{ trans_db('word') }}
                                </option>
                                <option value="sentence" {{ request('baseline_khmer') == 'sentence' ? 'selected' : '' }}>
                                    {{ trans_db('sentence') }}
                                </option>
                                <option value="paragraph" {{ request('baseline_khmer') == 'paragraph' ? 'selected' : '' }}>
                                    {{ trans_db('paragraph') }}
                                </option>
                                <option value="not_assessed" {{ request('baseline_khmer') == 'not_assessed' ? 'selected' : '' }}>
                                    {{ trans_db('not_assessed') }}
                                </option>
                            </select>
                        </div>

                        <!-- Baseline Math Filter -->
                        <div>
                            <select name="baseline_math" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ trans_db('baseline_math') }}</option>
                                <option value="beginner" {{ request('baseline_math') == 'beginner' ? 'selected' : '' }}>
                                    {{ trans_db('beginner') }}
                                </option>
                                <option value="basic_operations" {{ request('baseline_math') == 'basic_operations' ? 'selected' : '' }}>
                                    {{ trans_db('basic_operations') }}
                                </option>
                                <option value="subtraction" {{ request('baseline_math') == 'subtraction' ? 'selected' : '' }}>
                                    {{ trans_db('subtraction') }}
                                </option>
                                <option value="multiplication_division" {{ request('baseline_math') == 'multiplication_division' ? 'selected' : '' }}>
                                    {{ trans_db('multiplication_division') }}
                                </option>
                                <option value="not_assessed" {{ request('baseline_math') == 'not_assessed' ? 'selected' : '' }}>
                                    {{ trans_db('not_assessed') }}
                                </option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ trans_db('filter') }}
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
                                        {{ trans_db('select_students_note', ['type' => $assessmentType]) }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-2">
                                        <strong>{{ trans_db('note') }}:</strong> {{ trans_db('baseline_automatic_note') }}
                                    </p>
                                </div>
                                <div>
                                    <button type="button" id="selectAll" class="text-sm text-blue-600 hover:text-blue-800 mr-4">
                                        {{ trans_db('select_all') }}
                                    </button>
                                    <button type="button" id="deselectAll" class="text-sm text-blue-600 hover:text-blue-800">
                                        {{ trans_db('deselect_all') }}
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Baseline Assessment Legend -->
                            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs font-medium text-gray-700 mb-2">{{ trans_db('baseline_assessment_levels') }}:</p>
                                <div class="flex flex-wrap gap-4 text-xs">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-600">{{ trans_db('khmer') }}:</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-red-100 text-red-800">{{ trans_db('letter') }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800">{{ trans_db('word') }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">{{ trans_db('sentence') }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-100 text-green-800">{{ trans_db('paragraph') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-600">{{ trans_db('math') }}:</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-red-100 text-red-800">{{ trans_db('beginner') }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800">{{ trans_db('basic_operations') }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">{{ trans_db('subtraction') }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-100 text-green-800">{{ trans_db('multiplication_division') }}</span>
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
                                                {{ trans_db('select') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ trans_db('name') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ trans_db('grade') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ trans_db('school') }}
                                            </th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" colspan="2">
                                                {{ trans_db('baseline_assessment') }}
                                            </th>
                                        </tr>
                                        <tr class="bg-gray-50">
                                            <th colspan="4"></th>
                                            <th class="px-6 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ trans_db('khmer') }}
                                            </th>
                                            <th class="px-6 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ trans_db('math') }}
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
                                                    {{ $student->class }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $student->pilotSchool ? $student->pilotSchool->school_name : 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    @php
                                                        $khmerBaseline = $student->assessments->where('subject', 'khmer')->first();
                                                    @endphp
                                                    @if($khmerBaseline)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                            @if($khmerBaseline->level == 'Beginner') bg-gray-100 text-gray-800
                                                            @elseif($khmerBaseline->level == 'Letter') bg-red-100 text-red-800
                                                            @elseif($khmerBaseline->level == 'Word') bg-yellow-100 text-yellow-800
                                                            @elseif($khmerBaseline->level == 'Story') bg-blue-100 text-blue-800
                                                            @elseif($khmerBaseline->level == 'Paragraph') bg-green-100 text-green-800
                                                            @endif">
                                                            {{ trans_db(str_replace('-', '-', strtolower($khmerBaseline->level))) }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400">{{ trans_db('not_assessed') }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    @php
                                                        $mathBaseline = $student->assessments->where('subject', 'math')->first();
                                                    @endphp
                                                    @if($mathBaseline)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                            @if($mathBaseline->level == 'Beginner') bg-gray-100 text-gray-800
                                                            @elseif($mathBaseline->level == '1-Digit') bg-red-100 text-red-800
                                                            @elseif($mathBaseline->level == '2-Digit') bg-yellow-100 text-yellow-800
                                                            @elseif($mathBaseline->level == 'Subtraction') bg-blue-100 text-blue-800
                                                            @elseif($mathBaseline->level == 'Division') bg-green-100 text-green-800
                                                            @endif">
                                                            {{ trans_db(strtolower($mathBaseline->level)) }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400">{{ trans_db('not_assessed') }}</span>
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
                                    {{ trans_db('save_selection') }}
                                </button>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">{{ trans_db('no_students_found') }}</p>
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