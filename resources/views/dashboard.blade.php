<x-app-layout>
    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Filters for Mentors/Admins -->
            @if(in_array(auth()->user()->role, ['mentor', 'admin']))
            <div class="bg-white shadow-sm sm:rounded-lg mb-6 p-4">
                <h3 class="text-sm font-medium text-gray-700 mb-3">{{ trans_db('filter_data') }}:</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Province Filter -->
                    <div>
                        <label for="provinceFilter" class="block text-xs font-medium text-gray-700 mb-1">
                            {{ trans_db('province') }}:
                        </label>
                        <select id="provinceFilter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">{{ trans_db('all_provinces') }}</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province }}">{{ $province }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- District Filter -->
                    <div>
                        <label for="districtFilter" class="block text-xs font-medium text-gray-700 mb-1">
                            {{ trans_db('district') }}:
                        </label>
                        <select id="districtFilter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">{{ trans_db('all_districts') }}</option>
                        </select>
                    </div>
                    
                    <!-- Cluster Filter -->
                    <div>
                        <label for="clusterFilter" class="block text-xs font-medium text-gray-700 mb-1">
                            {{ trans_db('cluster') }}:
                        </label>
                        <select id="clusterFilter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">{{ trans_db('all_clusters') }}</option>
                        </select>
                    </div>
                    
                    <!-- School Filter -->
                    <div>
                        <label for="schoolFilter" class="block text-xs font-medium text-gray-700 mb-1">
                            {{ trans_db('school') }}:
                        </label>
                        <select id="schoolFilter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">{{ trans_db('all_schools') }}</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" 
                                    data-province="{{ $school->province }}" 
                                    data-district="{{ $school->district }}" 
                                    data-cluster="{{ $school->cluster }}">
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Assessment Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-blue-50 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-12 w-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ trans_db('total_students') }}</dt>
                                <dd class="text-3xl font-semibold text-gray-900" id="totalStudents">—</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ trans_db('assessments') }}</dt>
                                <dd class="text-3xl font-semibold text-gray-900" id="totalAssessments">—</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-12 w-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                            </svg>
                        </div>
                        <div class="ml-5">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ trans_db('schools') }}</dt>
                                <dd class="text-3xl font-semibold text-gray-900" id="totalSchools">—</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-50 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-12 w-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ trans_db('mentoring_visits') }}</dt>
                                <dd class="text-3xl font-semibold text-gray-900" id="totalMentoringVisits">—</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assessment Results Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ trans_db('assessment_results') }}</h3>
                    
                    <!-- Subject Toggle -->
                    <div class="flex justify-center gap-2 mb-6">
                        <button class="subject-btn px-4 py-2 rounded transition-all duration-200 bg-blue-500 text-white" data-subject="khmer">
                            {{ trans_db('khmer') }}
                        </button>
                        <button class="subject-btn px-4 py-2 rounded transition-all duration-200 bg-gray-200 text-gray-700 hover:bg-gray-300" data-subject="math">
                            {{ trans_db('math') }}
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Overall Results Chart -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-center font-medium mb-4">{{ trans_db('overall_results') }}</h4>
                            <div class="relative" style="height: 300px;">
                                <canvas id="overallResultsChart"></canvas>
                            </div>
                            <!-- Summary Table -->
                            <table class="mt-4 w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-2 py-1 text-left">{{ trans_db('test_cycle') }}</th>
                                        <th class="px-2 py-1 text-center">{{ trans_db('baseline') }}</th>
                                        <th class="px-2 py-1 text-center">{{ trans_db('midline') }}</th>
                                        <th class="px-2 py-1 text-center">{{ trans_db('endline') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-2 py-1 font-medium">{{ trans_db('students') }}</td>
                                        <td class="px-2 py-1 text-center" id="overallBaseline">—</td>
                                        <td class="px-2 py-1 text-center" id="overallMidline">—</td>
                                        <td class="px-2 py-1 text-center" id="overallEndline">—</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Results by School Chart (for mentors/admins) -->
                        @if(in_array(auth()->user()->role, ['mentor', 'admin']))
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-center font-medium mb-4">{{ trans_db('results_by_school') }}</h4>
                            <!-- Cycle selector -->
                            <div class="flex justify-center gap-2 mb-4">
                                <button class="cycle-btn px-3 py-1 text-sm rounded transition-all duration-200 bg-indigo-500 text-white" data-cycle="baseline">
                                    {{ __('Baseline') }}
                                </button>
                                <button class="cycle-btn px-3 py-1 text-sm rounded transition-all duration-200 bg-gray-200 text-gray-700 hover:bg-gray-300" data-cycle="midline">
                                    {{ __('Midline') }}
                                </button>
                                <button class="cycle-btn px-3 py-1 text-sm rounded transition-all duration-200 bg-gray-200 text-gray-700 hover:bg-gray-300" data-cycle="endline">
                                    {{ __('Endline') }}
                                </button>
                            </div>
                            <div class="relative overflow-y-auto" style="height: 400px;">
                                <div style="min-height: 300px;">
                                    <canvas id="schoolResultsChart"></canvas>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ trans_db('quick_actions') }}</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if(in_array(auth()->user()->role, ['admin', 'teacher']))
                        <a href="{{ route('students.create') }}" class="text-center p-4 border rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-8 h-8 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            <span class="text-sm">{{ trans_db('add_student') }}</span>
                        </a>
                        @endif
                        
                        @if(in_array(auth()->user()->role, ['admin', 'teacher', 'mentor']))
                        <a href="{{ route('assessments.create') }}" class="text-center p-4 border rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-8 h-8 mx-auto mb-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <span class="text-sm">{{ trans_db('new_assessment') }}</span>
                        </a>
                        @endif
                        
                        @if(in_array(auth()->user()->role, ['admin', 'mentor']))
                        <a href="{{ route('mentoring.create') }}" class="text-center p-4 border rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-8 h-8 mx-auto mb-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span class="text-sm">{{ trans_db('log_visit') }}</span>
                        </a>
                        @endif
                        
                        <a href="{{ route('reports.index') }}" class="text-center p-4 border rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-8 h-8 mx-auto mb-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm">{{ trans_db('view_reports') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script>
        $(document).ready(function() {
            let overallChart = null;
            let schoolChart = null;
            let currentSubject = 'khmer';
            let currentCycle = 'baseline';
            let selectedSchoolId = '';
            let selectedProvince = '';
            let selectedDistrict = '';
            let selectedCluster = '';
            
            // Initialize filters data
            const allSchools = @json($schools);
            const districts = @json($districts ?? []);
            const clusters = @json($clusters ?? []);
            
            // Initialize
            loadDashboardStats();
            loadOverallResults();
            @if(in_array(auth()->user()->role, ['mentor', 'admin']))
            loadSchoolResults();
            @endif
            
            // Province filter change
            $('#provinceFilter').change(function() {
                selectedProvince = $(this).val();
                selectedDistrict = '';
                selectedCluster = '';
                selectedSchoolId = '';
                
                // Reset dependent filters
                $('#districtFilter').html('<option value="">{{ trans_db('all_districts') }}</option>');
                $('#clusterFilter').html('<option value="">{{ trans_db('all_clusters') }}</option>');
                $('#schoolFilter').val('');
                
                // Populate districts for selected province
                if (selectedProvince) {
                    const provinceDistricts = [...new Set(allSchools
                        .filter(s => s.province === selectedProvince)
                        .map(s => s.district))].sort();
                    
                    provinceDistricts.forEach(district => {
                        $('#districtFilter').append(`<option value="${district}">${district}</option>`);
                    });
                }
                
                filterSchools();
                reloadData();
            });
            
            // District filter change
            $('#districtFilter').change(function() {
                selectedDistrict = $(this).val();
                selectedCluster = '';
                selectedSchoolId = '';
                
                // Reset dependent filters
                $('#clusterFilter').html('<option value="">{{ trans_db('all_clusters') }}</option>');
                $('#schoolFilter').val('');
                
                // Populate clusters for selected district
                if (selectedDistrict) {
                    const districtClusters = [...new Set(allSchools
                        .filter(s => s.province === selectedProvince && s.district === selectedDistrict && s.cluster)
                        .map(s => s.cluster))].sort();
                    
                    districtClusters.forEach(cluster => {
                        $('#clusterFilter').append(`<option value="${cluster}">${cluster}</option>`);
                    });
                }
                
                filterSchools();
                reloadData();
            });
            
            // Cluster filter change
            $('#clusterFilter').change(function() {
                selectedCluster = $(this).val();
                selectedSchoolId = '';
                $('#schoolFilter').val('');
                
                filterSchools();
                reloadData();
            });
            
            // School filter change
            $('#schoolFilter').change(function() {
                selectedSchoolId = $(this).val();
                
                // Auto-select province, district, cluster if a school is selected
                if (selectedSchoolId) {
                    const selectedOption = $(this).find(':selected');
                    const province = selectedOption.data('province');
                    const district = selectedOption.data('district');
                    const cluster = selectedOption.data('cluster');
                    
                    if (province && province !== selectedProvince) {
                        $('#provinceFilter').val(province).trigger('change');
                        $('#districtFilter').val(district);
                        $('#clusterFilter').val(cluster);
                        selectedProvince = province;
                        selectedDistrict = district;
                        selectedCluster = cluster;
                    }
                }
                
                reloadData();
            });
            
            // Filter schools based on selected province, district, cluster
            function filterSchools() {
                $('#schoolFilter option:not(:first)').each(function() {
                    const $option = $(this);
                    const province = $option.data('province');
                    const district = $option.data('district');
                    const cluster = $option.data('cluster');
                    
                    let show = true;
                    
                    if (selectedProvince && province !== selectedProvince) show = false;
                    if (selectedDistrict && district !== selectedDistrict) show = false;
                    if (selectedCluster && cluster !== selectedCluster) show = false;
                    
                    if (show) {
                        $option.show();
                    } else {
                        $option.hide();
                    }
                });
            }
            
            // Reload all data
            function reloadData() {
                loadDashboardStats();
                loadOverallResults();
                @if(in_array(auth()->user()->role, ['mentor', 'admin']))
                loadSchoolResults();
                @endif
            }
            
            // Subject toggle
            $('.subject-btn').click(function() {
                $('.subject-btn').removeClass('bg-blue-500 text-white').addClass('bg-gray-200 text-gray-700 hover:bg-gray-300');
                $(this).removeClass('bg-gray-200 text-gray-700 hover:bg-gray-300').addClass('bg-blue-500 text-white');
                currentSubject = $(this).data('subject');
                loadOverallResults();
                @if(in_array(auth()->user()->role, ['mentor', 'admin']))
                loadSchoolResults();
                @endif
            });
            
            // Cycle toggle (for school results)
            $('.cycle-btn').click(function() {
                $('.cycle-btn').removeClass('bg-indigo-500 text-white').addClass('bg-gray-200 text-gray-700 hover:bg-gray-300');
                $(this).removeClass('bg-gray-200 text-gray-700 hover:bg-gray-300').addClass('bg-indigo-500 text-white');
                currentCycle = $(this).data('cycle');
                loadSchoolResults();
            });
            
            // Load dashboard statistics
            function loadDashboardStats() {
                showLoading('{{ trans_db("loading_statistics") }}');
                $.ajax({
                    url: '{{ route("api.dashboard.stats") }}',
                    data: { 
                        school_id: selectedSchoolId,
                        province: selectedProvince,
                        district: selectedDistrict,
                        cluster: selectedCluster
                    },
                    success: function(data) {
                        $('#totalStudents').text(data.total_students);
                        $('#totalAssessments').text(data.total_assessments);
                        $('#totalSchools').text(data.total_schools);
                        $('#totalMentoringVisits').text(data.total_mentoring_visits);
                    },
                    complete: function() {
                        hideLoading();
                    }
                });
            }
            
            // Load overall results
            function loadOverallResults() {
                $('#overallResultsChart').parent().addClass('loading');
                $.ajax({
                    url: '{{ route("api.dashboard.overall-results") }}',
                    data: { 
                        subject: currentSubject,
                        school_id: selectedSchoolId,
                        province: selectedProvince,
                        district: selectedDistrict,
                        cluster: selectedCluster
                    },
                    success: function(response) {
                        // Update chart
                        updateOverallChart(response.chartData);
                        
                        // Update table
                        $('#overallBaseline').text(response.totals.baseline || '—');
                        $('#overallMidline').text(response.totals.midline || '—');
                        $('#overallEndline').text(response.totals.endline || '—');
                    },
                    complete: function() {
                        $('#overallResultsChart').parent().removeClass('loading');
                    }
                });
            }
            
            // Load school results (for mentors/admins)
            @if(in_array(auth()->user()->role, ['mentor', 'admin']))
            function loadSchoolResults() {
                $('#schoolResultsChart').parent().addClass('loading');
                $.ajax({
                    url: '{{ route("api.dashboard.results-by-school") }}',
                    data: { 
                        subject: currentSubject,
                        cycle: currentCycle,
                        school_id: selectedSchoolId,
                        province: selectedProvince,
                        district: selectedDistrict,
                        cluster: selectedCluster
                    },
                    success: function(response) {
                        updateSchoolChart(response.chartData);
                    },
                    complete: function() {
                        $('#schoolResultsChart').parent().removeClass('loading');
                    }
                });
            }
            @endif
            
            // Update overall chart
            function updateOverallChart(data) {
                const ctx = document.getElementById('overallResultsChart').getContext('2d');
                
                if (overallChart) {
                    overallChart.destroy();
                }
                
                overallChart = new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                stacked: true,
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: '{{ trans_db("number_of_students") }}'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 15,
                                    padding: 10
                                }
                            },
                            datalabels: {
                                display: function(context) {
                                    return context.dataset.data[context.dataIndex] > 0; // Show labels for all non-zero values
                                },
                                color: 'white',
                                font: {
                                    weight: 'bold',
                                    size: 11
                                },
                                formatter: function(value, context) {
                                    // Calculate percentage for this segment
                                    const dataArray = context.chart.data.datasets.map(dataset => dataset.data[context.dataIndex]);
                                    const total = dataArray.reduce((sum, val) => sum + val, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return percentage > 0 ? percentage + '%' : '';
                                },
                                anchor: 'center',
                                align: 'center'
                            },
                            tooltip: {
                                callbacks: {
                                    afterTitle: function() {
                                        return '{{ trans_db("students") }}';
                                    }
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });
            }
            
            // Update school chart
            @if(in_array(auth()->user()->role, ['mentor', 'admin']))
            function updateSchoolChart(data) {
                const ctx = document.getElementById('schoolResultsChart').getContext('2d');
                
                if (schoolChart) {
                    schoolChart.destroy();
                }
                
                // Calculate dynamic height based on number of schools
                const numSchools = data.labels ? data.labels.length : 0;
                const barHeight = 50; // Height per school bar
                const minHeight = 300;
                const calculatedHeight = Math.max(minHeight, numSchools * barHeight);
                
                // Set canvas container height
                ctx.canvas.parentElement.style.minHeight = calculatedHeight + 'px';
                ctx.canvas.style.height = calculatedHeight + 'px';
                
                schoolChart = new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: {
                        indexAxis: 'y', // This makes it a horizontal bar chart
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                stacked: true,
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: '{{ trans_db("percentage") }}'
                                },
                                max: 100
                            },
                            y: {
                                stacked: true,
                                ticks: {
                                    autoSkip: false,
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 15,
                                    padding: 10,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            datalabels: {
                                display: function(context) {
                                    return context.dataset.data[context.dataIndex] > 5; // Only show labels for segments > 5%
                                },
                                color: 'white',
                                font: {
                                    weight: 'bold',
                                    size: 10
                                },
                                formatter: function(value) {
                                    return value + '%';
                                },
                                anchor: 'center',
                                align: 'center'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.x !== null) {
                                            label += context.parsed.x + '%';
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });
            }
            @endif
        });
    </script>
    @endpush
</x-app-layout>