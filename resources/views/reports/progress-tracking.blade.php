<x-app-layout>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Progress Tracking Report') }}</h3>
                        <a href="{{ route('reports.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            ← {{ __('Back to Reports') }}
                        </a>
                    </div>
                    
                    <!-- Filters -->
                    <form method="GET" action="{{ route('reports.progress-tracking') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="school_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('School') }}</label>
                            <select name="school_id" id="school_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('All Schools') }}</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ $schoolId == $school->id ? 'selected' : '' }}>
                                        {{ $school->school_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Subject') }}</label>
                            <select name="subject" id="subject" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="khmer" {{ $subject == 'khmer' ? 'selected' : '' }}>{{ __('Khmer') }}</option>
                                <option value="math" {{ $subject == 'math' ? 'selected' : '' }}>{{ __('Math') }}</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                {{ __('Apply Filters') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Progress Summary -->
            @if(count($progressData) > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h4 class="text-md font-medium text-gray-900 mb-4">{{ __('Progress Summary') }}</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        @php
                            $totalStudents = count($progressData);
                            $improved = collect($progressData)->where('level_improved', '>', 0)->count();
                            $maintained = collect($progressData)->where('level_improved', '=', 0)->count();
                            $declined = collect($progressData)->where('level_improved', '<', 0)->count();
                            $avgScoreChange = collect($progressData)->avg('score_change');
                        @endphp
                        
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-green-700">{{ $improved }}</div>
                            <div class="text-sm text-green-600">{{ __('Students Improved') }}</div>
                            <div class="text-xs text-green-500">{{ $totalStudents > 0 ? number_format(($improved / $totalStudents) * 100, 1) : 0 }}%</div>
                        </div>
                        
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-700">{{ $maintained }}</div>
                            <div class="text-sm text-blue-600">{{ __('Students Maintained') }}</div>
                            <div class="text-xs text-blue-500">{{ $totalStudents > 0 ? number_format(($maintained / $totalStudents) * 100, 1) : 0 }}%</div>
                        </div>
                        
                        <div class="bg-red-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-red-700">{{ $declined }}</div>
                            <div class="text-sm text-red-600">{{ __('Students Declined') }}</div>
                            <div class="text-xs text-red-500">{{ $totalStudents > 0 ? number_format(($declined / $totalStudents) * 100, 1) : 0 }}%</div>
                        </div>
                        
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-purple-700">{{ number_format($avgScoreChange, 1) }}</div>
                            <div class="text-sm text-purple-600">{{ __('Avg Score Change') }}</div>
                        </div>
                    </div>
                    
                    <!-- Progress Visualization -->
                    <div class="relative" style="height: 300px;">
                        <canvas id="progressChart"></canvas>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Detailed Student Progress -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h4 class="text-md font-medium text-gray-900 mb-4">{{ __('Individual Student Progress') }}</h4>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Student Name') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('School') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Baseline') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Latest') }} ({{ __('Cycle') }})
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Level Change') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Score Change') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($progressData as $data)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $data['student']->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $data['student']->school->school_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ __($data['baseline_level']) }}
                                        </span>
                                        <span class="text-xs text-gray-400">({{ $data['baseline_score'] }})</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ __($data['latest_level']) }}
                                        </span>
                                        <span class="text-xs text-gray-400">({{ $data['latest_score'] }}) - {{ __(ucfirst($data['latest_cycle'])) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($data['level_improved'] > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ↑ +{{ $data['level_improved'] }} {{ __('levels') }}
                                            </span>
                                        @elseif($data['level_improved'] < 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                ↓ {{ $data['level_improved'] }} {{ __('levels') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                = {{ __('Same level') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($data['score_change'] > 0)
                                            <span class="text-green-600 font-medium">+{{ $data['score_change'] }}</span>
                                        @elseif($data['score_change'] < 0)
                                            <span class="text-red-600 font-medium">{{ $data['score_change'] }}</span>
                                        @else
                                            <span class="text-gray-500">0</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        {{ __('No students with multiple assessments found') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        @if(count($progressData) > 0)
        $(document).ready(function() {
            const progressData = @json($progressData);
            const levelChanges = progressData.reduce((acc, curr) => {
                const change = curr.level_improved;
                const key = change > 0 ? 'Improved' : (change < 0 ? 'Declined' : 'Maintained');
                acc[key] = (acc[key] || 0) + 1;
                return acc;
            }, {});
            
            // Create pie chart
            const ctx = document.getElementById('progressChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(levelChanges),
                    datasets: [{
                        data: Object.values(levelChanges),
                        backgroundColor: [
                            '#22c55e', // green for improved
                            '#ef4444', // red for declined
                            '#3b82f6'  // blue for maintained
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: '{{ __("Student Progress Distribution") }}'
                        }
                    }
                }
            });
        });
        @endif
    </script>
    @endpush
</x-app-layout>