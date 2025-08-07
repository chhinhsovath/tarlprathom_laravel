<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ trans_db('TaRL Project') }} - {{ trans_db('Assessment Results') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanuman:wght@100;300;400;700;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css'])
    
    <style>
        .loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }
        
        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 40px;
            border: 4px solid rgba(0, 0, 0, .1);
            border-radius: 50%;
            border-top-color: #3B82F6;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow">
            <div class="w-full px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold">{{ trans_db('TaRL Project') }}</h1>
                    </div>
                    <div class="flex items-center space-x-4">

                                    <a 
           href="https://plp.moeys.gov.kh" class="inline-flex 
           items-center px-1 pt-1 border-b-2 border-transparent 
           text-sm font-medium leading-5 text-gray-500 
           hover:text-gray-700 hover:border-gray-300 
           focus:outline-none focus:text-gray-700 
           focus:border-gray-300 transition duration-150 
           ease-in-out">
                                 {{ trans_db('PLP') }}
                             </a> 
                             
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 hover:text-gray-900">{{ trans_db('Dashboard') }}</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">{{ trans_db('Log in') }}</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-gray-900">{{ trans_db('Register') }}</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            <div class="max-w-4xl mx-auto mt-10 p-6 border shadow rounded-lg bg-white">
                <!-- Header -->
                <h2 class="text-xl font-semibold text-center mb-4">{{ trans_db('Assessment Results') }}</h2>
                
                <!-- Subject Selector -->
                <div class="flex justify-center gap-2 mb-6">
                    <button id="khmerBtn" data-subject="khmer" 
                            class="subject-btn px-3 py-1 rounded transition-all duration-200 bg-blue-500 text-white">
                        {{ trans_db('Khmer') }}
                    </button>
                    <button id="mathBtn" data-subject="math" 
                            class="subject-btn px-3 py-1 rounded transition-all duration-200 bg-gray-200 text-gray-700 hover:bg-gray-300">
                        {{ trans_db('Math') }}
                    </button>
                </div>
                
                <!-- Chart Container -->
                <div class="mb-8 relative" style="height: 300px;" id="chartContainer">
                    <div class="loading-spinner" style="display: none;">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                    </div>
                    <canvas id="assessmentChart"></canvas>
                </div>
                
                <!-- Test Cycle Table -->
                <table class="table-auto mt-4 border mx-auto text-sm">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2">{{ trans_db('Test Cycle') }}</th>
                            <th class="border px-4 py-2">{{ trans_db('Baseline') }}</th>
                            <th class="border px-4 py-2">{{ trans_db('Midline') }}</th>
                            <th class="border px-4 py-2">{{ trans_db('Endline') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-4 py-2 font-medium">{{ trans_db('Students') }}</td>
                            <td class="border px-4 py-2 text-center" id="baseline">—</td>
                            <td class="border px-4 py-2 text-center" id="midline">—</td>
                            <td class="border px-4 py-2 text-center" id="endline">—</td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- Total Students -->
                <p class="text-center mt-4 text-sm text-gray-600">
                    {{ trans_db('Total Students Assessed') }}: <span class="font-semibold" id="totalStudents">0</span>
                </p>
            </div>
        </main>
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script>
        $(document).ready(function() {
            let assessmentChart = null;
            let currentSubject = 'khmer';
            
            // Setup AJAX to include CSRF token and credentials
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                xhrFields: {
                    withCredentials: true
                }
            });
            
            // Function to load data and update chart
            function loadAssessmentData(subject) {
                $('#chartContainer').addClass('loading');
                
                $.ajax({
                    url: '{{ route("api.assessment-data") }}',
                    type: 'GET',
                    data: { subject: subject },
                    success: function(response) {
                        // Update chart
                        updateChart(response.chartData);
                        
                        // Update table
                        $('#baseline').text(response.cycleData.baseline || '—');
                        $('#midline').text(response.cycleData.midline || '—');
                        $('#endline').text(response.cycleData.endline || '—');
                        $('#totalStudents').text(response.cycleData.total || 0);
                    },
                    error: function(xhr, status, error) {
                        alert('{{ trans_db("Error loading assessment data") }}');
                    },
                    complete: function() {
                        $('#chartContainer').removeClass('loading');
                    }
                });
            }
            
            // Function to update chart
            function updateChart(data) {
                const ctx = document.getElementById('assessmentChart').getContext('2d');
                
                if (assessmentChart) {
                    assessmentChart.destroy();
                }
                
                assessmentChart = new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        plugins: {
                            legend: { display: false },
                            datalabels: {
                                display: function(context) {
                                    return context.dataset.data[context.dataIndex] > 0;
                                },
                                color: 'black',
                                font: {
                                    weight: 'bold',
                                    size: 11
                                },
                                formatter: function(value) {
                                    return value;
                                },
                                anchor: 'end',
                                align: 'right'
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: '{{ trans_db("Number of Students") }}'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: '{{ trans_db("Reading Level") }}'
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });
            }
            
            // Subject button click handlers
            $('.subject-btn').click(function() {
                const subject = $(this).data('subject');
                
                // Update button styles
                $('.subject-btn').removeClass('bg-blue-500 text-white')
                    .addClass('bg-gray-200 text-gray-700 hover:bg-gray-300');
                $(this).removeClass('bg-gray-200 text-gray-700 hover:bg-gray-300')
                    .addClass('bg-blue-500 text-white');
                
                // Load new data
                currentSubject = subject;
                loadAssessmentData(subject);
            });
            
            // Load initial data
            loadAssessmentData(currentSubject);
        });
    </script>
</body>
</html>