<div class="max-w-4xl mx-auto mt-10 p-6 border shadow rounded-lg bg-white">
    <!-- Header -->
    <h2 class="text-xl font-semibold text-center mb-4">{{ __('Assessment Results') }}</h2>
    
    <!-- Subject Selector -->
    <div class="flex justify-center gap-2 mb-6">
        <button wire:click="$set('selectedSubject', 'khmer')" 
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
                class="px-3 py-1 rounded transition-all duration-200 {{ $selectedSubject === 'khmer' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            {{ __('Khmer') }}
        </button>
        <button wire:click="$set('selectedSubject', 'math')" 
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
                class="px-3 py-1 rounded transition-all duration-200 {{ $selectedSubject === 'math' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            {{ __('Math') }}
        </button>
    </div>
    
    <!-- Chart Container -->
    <div class="mb-8 relative" style="height: 300px;">
        <!-- Loading Spinner -->
        <div wire:loading wire:target="selectedSubject" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10 rounded">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
        </div>
        <canvas id="assessmentChart" wire:ignore.self></canvas>
    </div>
    
    <!-- Hidden data for JavaScript -->
    <div id="chartDataContainer" style="display: none;">
        <span id="currentChartData">@json($chartData)</span>
        <span id="currentSubject">{{ $selectedSubject }}</span>
    </div>
    
    <!-- Test Cycle Table -->
    <div class="relative">
        <div wire:loading wire:target="selectedSubject" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
        </div>
        <table class="table-auto mt-4 border mx-auto text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">{{ __('Test Cycle') }}</th>
                    <th class="border px-4 py-2">{{ __('Baseline') }}</th>
                    <th class="border px-4 py-2">{{ __('Midline') }}</th>
                    <th class="border px-4 py-2">{{ __('Endline') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border px-4 py-2 font-medium">{{ __('Students') }}</td>
                    <td class="border px-4 py-2 text-center">{{ $cycleData['baseline'] ?? '—' }}</td>
                    <td class="border px-4 py-2 text-center">{{ $cycleData['midline'] ?? '—' }}</td>
                    <td class="border px-4 py-2 text-center">{{ $cycleData['endline'] ?? '—' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Total Students -->
    <p class="text-center mt-4 text-sm text-gray-600">
        {{ __('Total Students Assessed') }}: <span class="font-semibold">{{ $cycleData['total'] ?? 0 }}</span>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
    let assessmentChart = null;
    
    function createChart() {
        const canvas = document.getElementById('assessmentChart');
        if (!canvas) return;
        
        const dataElement = document.getElementById('currentChartData');
        if (!dataElement) return;
        
        let chartData;
        try {
            chartData = JSON.parse(dataElement.textContent);
        } catch (e) {
            console.error('Failed to parse chart data:', e);
            return;
        }
        
        // Destroy existing chart
        if (assessmentChart) {
            assessmentChart.destroy();
            assessmentChart = null;
        }
        
        // Create new chart
        assessmentChart = new Chart(canvas, {
            type: 'bar',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: '{{ __("Number of Students") }}'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: '{{ __("Reading Level") }}'
                        }
                    }
                }
            }
        });
    }
    
    // Create chart on load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', createChart);
    } else {
        createChart();
    }
    
    // Update chart on Livewire updates
    document.addEventListener('livewire:initialized', () => {
        Livewire.hook('message.processed', (message, component) => {
            if (component.fingerprint && component.fingerprint.name === 'public.assessment-results') {
                // Wait for DOM update
                setTimeout(createChart, 100);
            }
        });
    });
})();
</script>