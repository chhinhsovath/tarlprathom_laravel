<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Students Performance Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('reports.my-students') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Class Filter -->
                        <div>
                            <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Class') }}
                            </label>
                            <select name="class_id" id="class_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('All Classes') }}</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Subject Filter -->
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Subject') }}
                            </label>
                            <select name="subject" id="subject" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="all" {{ request('subject', 'all') == 'all' ? 'selected' : '' }}>
                                    {{ __('All Subjects') }}
                                </option>
                                <option value="khmer" {{ request('subject') == 'khmer' ? 'selected' : '' }}>
                                    {{ __('Khmer') }}
                                </option>
                                <option value="math" {{ request('subject') == 'math' ? 'selected' : '' }}>
                                    {{ __('Math') }}
                                </option>
                            </select>
                        </div>

                        <!-- Cycle Filter -->
                        <div>
                            <label for="cycle" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Assessment Cycle') }}
                            </label>
                            <select name="cycle" id="cycle" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="all" {{ request('cycle', 'all') == 'all' ? 'selected' : '' }}>
                                    {{ __('All Cycles') }}
                                </option>
                                <option value="baseline" {{ request('cycle') == 'baseline' ? 'selected' : '' }}>
                                    {{ __('Baseline') }}
                                </option>
                                <option value="midline" {{ request('cycle') == 'midline' ? 'selected' : '' }}>
                                    {{ __('Midline') }}
                                </option>
                                <option value="endline" {{ request('cycle') == 'endline' ? 'selected' : '' }}>
                                    {{ __('Endline') }}
                                </option>
                            </select>
                        </div>

                        <!-- Apply Filters Button -->
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out">
                                {{ __('Apply Filters') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-3xl font-bold text-blue-600">{{ $students->count() }}</div>
                        <div class="text-sm text-gray-600 mt-2">{{ __('Total Students') }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-3xl font-bold text-green-600">{{ $assessments->count() }}</div>
                        <div class="text-sm text-gray-600 mt-2">{{ __('Total Assessments') }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-3xl font-bold text-purple-600">{{ $performanceByLevel->count() }}</div>
                        <div class="text-sm text-gray-600 mt-2">{{ __('Performance Levels') }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-3xl font-bold text-orange-600">{{ round($assessments->avg('score') ?? 0) }}%</div>
                        <div class="text-sm text-gray-600 mt-2">{{ __('Average Score') }}</div>
                    </div>
                </div>
            </div>

            <!-- Performance by Level Chart -->
            @if($performanceByLevel->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Performance by Level') }}</h3>
                    <div class="relative" style="height: 300px;">
                        <canvas id="levelChart"></canvas>
                    </div>
                </div>
            </div>
            @endif

            <!-- Students Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Student List') }}</h3>
                    
                    @if($students->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Student Code') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Name') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Class') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Grade') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Assessments') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Latest Level') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($students as $student)
                                        @php
                                            $studentAssessments = $assessments->where('student_id', $student->id);
                                            $latestAssessment = $studentAssessments->sortByDesc('assessed_at')->first();
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $student->student_code }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $student->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $student->class ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $student->grade }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $studentAssessments->count() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($latestAssessment)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @if(in_array($latestAssessment->level, ['Story', 'Division', 'Word Problem', 'Comp. 2']))
                                                            bg-green-100 text-green-800
                                                        @elseif(in_array($latestAssessment->level, ['Paragraph', 'Subtraction', 'Comp. 1']))
                                                            bg-blue-100 text-blue-800
                                                        @elseif(in_array($latestAssessment->level, ['Word', '2-Digit']))
                                                            bg-yellow-100 text-yellow-800
                                                        @elseif(in_array($latestAssessment->level, ['Letter', '1-Digit']))
                                                            bg-orange-100 text-orange-800
                                                        @else
                                                            bg-red-100 text-red-800
                                                        @endif">
                                                        {{ $latestAssessment->level }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">{{ __('No Assessment') }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('students.show', $student) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    {{ __('View') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">{{ __('No students found') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        @if($performanceByLevel->count() > 0)
        // Performance by Level Chart
        const levelCtx = document.getElementById('levelChart').getContext('2d');
        new Chart(levelCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($performanceByLevel->keys()) !!},
                datasets: [{
                    label: '{{ __("Number of Students") }}',
                    data: {!! json_encode($performanceByLevel->values()) !!},
                    backgroundColor: [
                        '#EF4444', // Red for Beginner
                        '#F97316', // Orange for Letter/1-Digit
                        '#EAB308', // Yellow for Word/2-Digit
                        '#3B82F6', // Blue for Paragraph/Subtraction
                        '#10B981', // Green for Story/Division
                        '#8B5CF6', // Purple for Comp.1/Word Problem
                        '#EC4899', // Pink for Comp.2
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
        @endif
    </script>
    @endpush
</x-app-layout>