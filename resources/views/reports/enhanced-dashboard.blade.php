@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ __('Analytics Dashboard') }}</h2>
                        <p class="text-gray-600 mt-2">{{ __('Comprehensive overview of TaRL program performance') }}</p>
                    </div>
                    <div class="flex space-x-4">
                        <select id="schoolFilter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            <option value="">{{ __('All Schools') }}</option>
                            <!-- Populated by JavaScript -->
                        </select>
                        <select id="dateRange" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            <option value="week">{{ __('Last Week') }}</option>
                            <option value="month" selected>{{ __('Last Month') }}</option>
                            <option value="quarter">{{ __('Last Quarter') }}</option>
                            <option value="year">{{ __('Last Year') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Total Students') }}</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ number_format($data['summary_stats']['total_students'] ?? 0) }}</dd>
                                <dd class="text-sm text-green-600">{{ number_format($data['summary_stats']['active_students'] ?? 0) }} {{ __('active') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Average Attendance') }}</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ number_format($data['summary_stats']['average_attendance'] ?? 0, 1) }}%</dd>
                                <dd class="text-sm text-gray-600">{{ __('Last 30 days') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Assessments') }}</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ number_format($data['summary_stats']['assessments_completed'] ?? 0) }}</dd>
                                <dd class="text-sm text-gray-600">{{ __('Completed') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('At Risk') }}</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ number_format($data['summary_stats']['students_needing_intervention'] ?? 0) }}</dd>
                                <dd class="text-sm text-red-600">{{ __('Need intervention') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 1 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Enrollment Trends Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Enrollment Trends') }}</h3>
                    <canvas id="enrollmentChart" height="150"></canvas>
                </div>
            </div>

            <!-- TaRL Level Distribution -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('TaRL Level Distribution') }}</h3>
                    <canvas id="levelDistributionChart" height="150"></canvas>
                </div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Assessment Performance -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Assessment Performance') }}</h3>
                    <canvas id="assessmentChart" height="200"></canvas>
                </div>
            </div>

            <!-- Attendance Overview -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Attendance Patterns') }}</h3>
                    <canvas id="attendanceChart" height="200"></canvas>
                </div>
            </div>

            <!-- Intervention Effectiveness -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Intervention Success') }}</h3>
                    <canvas id="interventionChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Geographic Distribution -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Geographic Distribution') }}</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <canvas id="geographicChart" height="150"></canvas>
                    </div>
                    <div>
                        <h4 class="text-md font-semibold text-gray-700 mb-3">{{ __('Distribution by Province') }}</h4>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($data['geographic_distribution']['by_province'] ?? [] as $province => $count)
                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                <span class="text-sm text-gray-600">{{ $province }}</span>
                                <span class="text-sm font-semibold text-gray-800">{{ number_format($count) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- At-Risk Students Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">{{ __('Students Requiring Attention') }}</h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('reports.export', ['type' => 'at_risk', 'format' => 'pdf']) }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                            {{ __('Export PDF') }}
                        </a>
                        <a href="{{ route('reports.export', ['type' => 'at_risk', 'format' => 'excel']) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                            {{ __('Export Excel') }}
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Student') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Grade') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Attendance') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Performance') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Risk Factors') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($data['at_risk_students'] ?? [] as $student)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $student['student_name'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $student['grade'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="@if($student['attendance_rate'] < 75) text-red-600 @else text-gray-600 @endif">
                                        {{ number_format($student['attendance_rate'], 1) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="@if($student['academic_performance'] < 50) text-red-600 @else text-gray-600 @endif">
                                        {{ number_format($student['academic_performance'], 1) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    @foreach($student['risk_factors'] ?? [] as $factor)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 mr-1 mb-1">
                                            {{ __($factor) }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('reports.student-progress', ['student_id' => $student['student_id']]) }}" class="text-indigo-600 hover:text-indigo-900">
                                        {{ __('View Details') }}
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Translation strings for JavaScript
const translations = {
    'Active Students': @json(__('Active Students')),
    'Dropped Out': @json(__('Dropped Out')),
    'Khmer': @json(__('Khmer')),
    'Math': @json(__('Math')),
    'Present': @json(__('Present')),
    'Absent': @json(__('Absent')),
    'Late': @json(__('Late')),
    'Excused': @json(__('Excused')),
    'Average Score': @json(__('Average Score')),
    'Success Rate (%)': @json(__('Success Rate (%)')),
    'Baseline': @json(__('baseline')),
    'Midline': @json(__('midline')),
    'Endline': @json(__('endline')),
    'Beginner': @json(__('Beginner')),
    'Letter': @json(__('Letter')),
    'Word': @json(__('Word')),
    'Paragraph': @json(__('Paragraph')),
    'Story': @json(__('Story'))
};

document.addEventListener('DOMContentLoaded', function() {
    // Enrollment Trends Chart
    const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
    new Chart(enrollmentCtx, {
        type: 'line',
        data: {
            labels: Object.keys(@json($data['enrollment_trends'] ?? [])),
            datasets: [{
                label: translations['Active Students'],
                data: Object.values(@json($data['enrollment_trends'] ?? [])).map(d => d.active || 0),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.1
            }, {
                label: translations['Dropped Out'],
                data: Object.values(@json($data['enrollment_trends'] ?? [])).map(d => d.dropped_out || 0),
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // TaRL Level Distribution Chart
    const levelCtx = document.getElementById('levelDistributionChart').getContext('2d');
    const levelData = @json($data['tarl_level_distribution'] ?? []);
    
    new Chart(levelCtx, {
        type: 'bar',
        data: {
            labels: [
                translations['Beginner'], 
                translations['Letter'], 
                translations['Word'], 
                translations['Paragraph'], 
                translations['Story']
            ],
            datasets: [{
                label: translations['Khmer'],
                data: levelData.khmer ? Object.values(levelData.khmer) : [],
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
            }, {
                label: translations['Math'],
                data: levelData.math ? Object.values(levelData.math) : [],
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Assessment Performance Chart
    const assessmentCtx = document.getElementById('assessmentChart').getContext('2d');
    const assessmentData = @json($data['assessment_performance'] ?? []);
    
    new Chart(assessmentCtx, {
        type: 'radar',
        data: {
            labels: [translations['Baseline'], translations['Midline'], translations['Endline']],
            datasets: [{
                label: translations['Average Score'],
                data: ['baseline', 'midline', 'endline'].map(cycle => 
                    assessmentData.by_cycle && assessmentData.by_cycle[cycle] ? 
                    assessmentData.by_cycle[cycle].average_score : 0
                ),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });

    // Attendance Patterns Chart
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    const attendanceData = @json($data['attendance_overview'] ?? []);
    
    new Chart(attendanceCtx, {
        type: 'doughnut',
        data: {
            labels: [
                translations['Present'], 
                translations['Absent'], 
                translations['Late'], 
                translations['Excused']
            ],
            datasets: [{
                data: [
                    Object.values(attendanceData).reduce((sum, d) => sum + (d.present || 0), 0),
                    Object.values(attendanceData).reduce((sum, d) => sum + (d.absent || 0), 0),
                    Object.values(attendanceData).reduce((sum, d) => sum + (d.late || 0), 0),
                    Object.values(attendanceData).reduce((sum, d) => sum + (d.excused || 0), 0)
                ],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(156, 163, 175, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Intervention Effectiveness Chart
    const interventionCtx = document.getElementById('interventionChart').getContext('2d');
    const interventionData = @json($data['intervention_effectiveness'] ?? []);
    
    new Chart(interventionCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(interventionData.by_type || {}),
            datasets: [{
                label: translations['Success Rate (%)'],
                data: Object.values(interventionData.by_type || {}).map(d => d.success_rate),
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
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
                    max: 100
                }
            }
        }
    });

    // Geographic Distribution Chart
    const geographicCtx = document.getElementById('geographicChart').getContext('2d');
    const geographicData = @json($data['geographic_distribution'] ?? []);
    
    new Chart(geographicCtx, {
        type: 'pie',
        data: {
            labels: Object.keys(geographicData.by_province || {}).slice(0, 5),
            datasets: [{
                data: Object.values(geographicData.by_province || {}).slice(0, 5),
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(139, 92, 246, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });

    // Filter functionality
    document.getElementById('schoolFilter').addEventListener('change', function() {
        // Refresh dashboard with school filter
        refreshDashboard();
    });

    document.getElementById('dateRange').addEventListener('change', function() {
        // Refresh dashboard with date range filter
        refreshDashboard();
    });

    function refreshDashboard() {
        const schoolId = document.getElementById('schoolFilter').value;
        const dateRange = document.getElementById('dateRange').value;
        
        const params = new URLSearchParams({
            school_id: schoolId,
            date_range: dateRange
        });
        
        window.location.href = `{{ route('reports.dashboard') }}?${params}`;
    }
});
</script>
@endpush
@endsection