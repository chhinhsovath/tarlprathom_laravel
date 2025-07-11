<div>
    <div class="bg-white p-6 rounded-lg shadow">
        <!-- Chart Controls -->
        <div class="mb-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chart Type</label>
                    <select wire:model.live="chartType" class="w-full rounded-md border-gray-300 shadow-sm">
                        <option value="studentsBySchool">Students by School</option>
                        <option value="assessmentsByLevel">Assessments by Level</option>
                        <option value="mentoringTrend">Mentoring Trend</option>
                        <option value="studentProgress">Student Progress</option>
                    </select>
                </div>
                
                @if($chartType === 'assessmentsByLevel')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by School</label>
                    <select wire:model.live="filterSchool" class="w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">All Schools</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                @if($chartType === 'mentoringTrend')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                    <input type="date" wire:model.live="filterDateFrom" class="w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                    <input type="date" wire:model.live="filterDateTo" class="w-full rounded-md border-gray-300 shadow-sm">
                </div>
                @endif
            </div>
        </div>
        
        <!-- Chart Container -->
        <div class="relative" style="height: 400px;">
            <canvas id="chartCanvas-{{ $chartType }}" wire:ignore></canvas>
        </div>
    </div>
    
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            let chart = null;
            
            function renderChart() {
                const ctx = document.getElementById('chartCanvas-{{ $chartType }}');
                if (!ctx) return;
                
                if (chart) {
                    chart.destroy();
                }
                
                const chartData = @json($chartData);
                const chartType = @json($chartType);
                
                let type = 'bar';
                if (chartType === 'mentoringTrend' || chartType === 'studentProgress') {
                    type = 'line';
                }
                if (chartType === 'assessmentsByLevel') {
                    type = 'pie';
                }
                
                chart = new Chart(ctx, {
                    type: type,
                    data: chartData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: false
                            }
                        },
                        scales: type !== 'pie' ? {
                            y: {
                                beginAtZero: true
                            }
                        } : {}
                    }
                });
            }
            
            renderChart();
            
            Livewire.on('chartDataUpdated', () => {
                setTimeout(() => renderChart(), 100);
            });
        });
    </script>
    @endpush
</div>