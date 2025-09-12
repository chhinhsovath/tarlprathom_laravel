<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ trans_db('Class Progress Report') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('reports.class-progress') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Class Selection -->
                        <div>
                            <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ trans_db('Select Class') }}
                            </label>
                            <select name="class_id" id="class_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">{{ trans_db('Choose a class...') }}</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Subject Selection -->
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ trans_db('Subject') }}
                            </label>
                            <select name="subject" id="subject" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="khmer" {{ request('subject', 'khmer') == 'khmer' ? 'selected' : '' }}>
                                    {{ trans_db('Khmer') }}
                                </option>
                                <option value="math" {{ request('subject') == 'math' ? 'selected' : '' }}>
                                    {{ trans_db('Math') }}
                                </option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out">
                                {{ trans_db('View Progress') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($classId && count($progressData) > 0)
                <!-- Progress Summary -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    @php
                        $totalStudents = count($progressData);
                        $hasBaseline = collect($progressData)->filter(function($item) { return $item['baseline'] != null; })->count();
                        $hasMidline = collect($progressData)->filter(function($item) { return $item['midline'] != null; })->count();
                        $hasEndline = collect($progressData)->filter(function($item) { return $item['endline'] != null; })->count();
                        
                        // Calculate improvement rate
                        $improvedCount = 0;
                        foreach($progressData as $data) {
                            if($data['baseline'] && $data['latest'] && $data['baseline']->id != $data['latest']->id) {
                                $baselineIndex = array_search($data['baseline']->level, 
                                    $subject == 'khmer' 
                                        ? ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2']
                                        : ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem']
                                );
                                $latestIndex = array_search($data['latest']->level,
                                    $subject == 'khmer'
                                        ? ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2']
                                        : ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem']
                                );
                                if($latestIndex > $baselineIndex) {
                                    $improvedCount++;
                                }
                            }
                        }
                    @endphp

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-3xl font-bold text-blue-600">{{ $hasBaseline }}/{{ $totalStudents }}</div>
                            <div class="text-sm text-gray-600 mt-2">{{ trans_db('Baseline Completed') }}</div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-3xl font-bold text-green-600">{{ $hasMidline }}/{{ $totalStudents }}</div>
                            <div class="text-sm text-gray-600 mt-2">{{ trans_db('Midline Completed') }}</div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-3xl font-bold text-purple-600">{{ $hasEndline }}/{{ $totalStudents }}</div>
                            <div class="text-sm text-gray-600 mt-2">{{ trans_db('Endline Completed') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Progress Visualization -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ trans_db('Class Progress Overview') }}</h3>
                        <div class="relative" style="height: 400px;">
                            <canvas id="progressChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Student Progress Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ trans_db('Individual Student Progress') }}</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ trans_db('Student') }}
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ trans_db('Baseline') }}
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ trans_db('Midline') }}
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ trans_db('Endline') }}
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ trans_db('Progress') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($progressData as $data)
                                        @php
                                            $levels = $subject == 'khmer' 
                                                ? ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2']
                                                : ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'];
                                            
                                            $baselineIndex = $data['baseline'] ? array_search($data['baseline']->level, $levels) : -1;
                                            $latestIndex = $data['latest'] ? array_search($data['latest']->level, $levels) : -1;
                                            $progress = 'No Change';
                                            $progressColor = 'gray';
                                            
                                            if($baselineIndex >= 0 && $latestIndex >= 0) {
                                                if($latestIndex > $baselineIndex) {
                                                    $progress = '+' . ($latestIndex - $baselineIndex) . ' levels';
                                                    $progressColor = 'green';
                                                } elseif($latestIndex < $baselineIndex) {
                                                    $progress = ($latestIndex - $baselineIndex) . ' levels';
                                                    $progressColor = 'red';
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $data['student']->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $data['student']->student_code }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($data['baseline'])
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        {{ $data['baseline']->level }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($data['midline'])
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        {{ $data['midline']->level }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($data['endline'])
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        {{ $data['endline']->level }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="text-sm font-medium text-{{ $progressColor }}-600">
                                                    {{ $progress }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @elseif($classId)
                <!-- No Data Message -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500">{{ trans_db('No assessment data found for this class') }}</p>
                    </div>
                </div>
            @else
                <!-- Instructions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ trans_db('Select a Class to View Progress') }}</h3>
                        <p class="text-gray-500">{{ trans_db('Choose a class from the dropdown above to see student progress data') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @if($classId && count($progressData) > 0)
    <script>
        // Prepare data for the chart
        const students = {!! json_encode(collect($progressData)->pluck('student.name')) !!};
        const levels = {!! json_encode($subject == 'khmer' 
            ? ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2']
            : ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem']) !!};
        
        const baselineData = [];
        const midlineData = [];
        const endlineData = [];
        
        @foreach($progressData as $data)
            @php
                $baselineIndex = $data['baseline'] ? array_search($data['baseline']->level, 
                    $subject == 'khmer' 
                        ? ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2']
                        : ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem']
                ) : null;
                $midlineIndex = $data['midline'] ? array_search($data['midline']->level,
                    $subject == 'khmer'
                        ? ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2']
                        : ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem']
                ) : null;
                $endlineIndex = $data['endline'] ? array_search($data['endline']->level,
                    $subject == 'khmer'
                        ? ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2']
                        : ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem']
                ) : null;
            @endphp
            baselineData.push({{ $baselineIndex !== null ? $baselineIndex : 'null' }});
            midlineData.push({{ $midlineIndex !== null ? $midlineIndex : 'null' }});
            endlineData.push({{ $endlineIndex !== null ? $endlineIndex : 'null' }});
        @endforeach
        
        // Create the chart
        const ctx = document.getElementById('progressChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: students,
                datasets: [
                    {
                        label: '{{ trans_db("Baseline") }}',
                        data: baselineData,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.1
                    },
                    {
                        label: '{{ trans_db("Midline") }}',
                        data: midlineData,
                        borderColor: 'rgb(234, 179, 8)',
                        backgroundColor: 'rgba(234, 179, 8, 0.1)',
                        tension: 0.1
                    },
                    {
                        label: '{{ trans_db("Endline") }}',
                        data: endlineData,
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const levelIndex = context.parsed.y;
                                if (levelIndex !== null && levels[levelIndex]) {
                                    return context.dataset.label + ': ' + levels[levelIndex];
                                }
                                return context.dataset.label + ': No data';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: levels.length - 1,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return levels[value] || '';
                            }
                        },
                        title: {
                            display: true,
                            text: '{{ trans_db("Performance Level") }}'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: '{{ trans_db("Students") }}'
                        }
                    }
                }
            }
        });
    </script>
    @endif
    @endpush
</x-app-layout>