@extends('Layouts.vuexy')

@section('title', 'My Attendance KPI')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">My Attendance KPI Dashboard</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Total Shifts -->
                        <div class="col-md-4 mb-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title text-white">Total Shifts</h5>
                                            <h3 class="mb-0">{{ $kpiData['total_shifts'] }}</h3>
                                        </div>
                                        <div class="card-icon">
                                            <i class="ti ti-calendar-time ti-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Completed Shifts -->
                        <div class="col-md-4 mb-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title text-white">Completed Shifts</h5>
                                            <h3 class="mb-0">{{ $kpiData['completed_shifts'] }}</h3>
                                        </div>
                                        <div class="card-icon">
                                            <i class="ti ti-check-circle ti-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Late Shifts -->
                        <div class="col-md-4 mb-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title text-white">Late Shifts</h5>
                                            <h3 class="mb-0">{{ $kpiData['late_shifts'] }}</h3>
                                        </div>
                                        <div class="card-icon">
                                            <i class="ti ti-clock-exclamation ti-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Undertime Shifts -->
                        <div class="col-md-4 mb-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title text-white">Undertime Shifts</h5>
                                            <h3 class="mb-0">{{ $kpiData['undertime_shifts'] }}</h3>
                                        </div>
                                        <div class="card-icon">
                                            <i class="ti ti-clock-minus ti-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Average Hours -->
                        <div class="col-md-4 mb-4">
                            <div class="card bg-secondary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title text-white">Average Hours</h5>
                                            <h3 class="mb-0">{{ $kpiData['average_hours'] }}</h3>
                                        </div>
                                        <div class="card-icon">
                                            <i class="ti ti-clock ti-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Analytics charts -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col-md-6"><h6 class="card-title mb-0">My Attendance Trends</h6></div>
                                        <div class="col-md-6">
                                            <div class="d-flex justify-content-end gap-2">
                                                <select id="periodSelect" class="form-select form-select-sm" style="width:auto;">
                                                    <option value="3">Last 3 Months</option>
                                                    <option value="6" selected>Last 6 Months</option>
                                                    <option value="9">Last 9 Months</option>
                                                    <option value="12">Last 12 Months</option>
                                                </select>
                                                <select id="groupingSelect" class="form-select form-select-sm" style="width:auto;">
                                                    <option value="monthly" selected>Monthly</option>
                                                    <option value="quarterly">Quarterly</option>
                                                    <option value="yearly">Yearly</option>
                                                </select>
                                                <button id="applyFilters" class="btn btn-sm btn-primary"><i class="bx bx-refresh me-1"></i>Apply</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ul class="nav nav-tabs nav-fill mb-3" id="attendanceTabs" role="tablist">
                                        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#late-undertime">Late & Undertime</button></li>
                                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#hours">Average Hours</button></li>
                                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#issues">Issues Rate</button></li>
                                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#performance">Performance</button></li>
                                    </ul>

                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="late-undertime"><canvas id="lateUndertimeChart"></canvas></div>
                                        <div class="tab-pane fade" id="hours"><canvas id="hoursChart"></canvas></div>
                                        <div class="tab-pane fade" id="issues"><canvas id="issuesChart"></canvas></div>
                                        <div class="tab-pane fade" id="performance"><canvas id="performanceChart"></canvas></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    let trendsData = [];
    let lateUndertimeChart, hoursChart, issuesChart, performanceChart;

    function fetchTrends() {
        const months = $('#periodSelect').val();
        const grouping = $('#groupingSelect').val();

        $.ajax({
            url: '{{ route("attendance.my-trends") }}',
            method: 'GET',
            data: { months: months, grouping: grouping },
            success: function(data) {
                trendsData = data;
                updateCharts();
            },
            error: function() {
                console.error('Failed to fetch trends data');
            }
        });
    }

    function updateCharts() {
        const labels = trendsData.map(t => t.period);
        const total = trendsData.map(t => t.total_shifts);
        const done = trendsData.map(t => t.completed_shifts);
        const late = trendsData.map(t => t.late_shifts);
        const undertime = trendsData.map(t => t.undertime_shifts);
        const avg = trendsData.map(t => t.avg_hours);
        const issues = trendsData.map(t => t.issues_rate);

        // Destroy existing charts if they exist
        if (lateUndertimeChart) lateUndertimeChart.destroy();
        if (hoursChart) hoursChart.destroy();
        if (issuesChart) issuesChart.destroy();
        if (performanceChart) performanceChart.destroy();

        // Late & Undertime Chart
        const lateUndertimeCtx = document.getElementById('lateUndertimeChart').getContext('2d');
        lateUndertimeChart = new Chart(lateUndertimeCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Late Shifts', data: late, borderColor: '#ff9800', backgroundColor: 'rgba(255,152,0,0.1)', borderWidth: 3, fill: true, tension: 0.4, pointBackgroundColor: '#ff9800', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 6 },
                    { label: 'Undertime Shifts', data: undertime, borderColor: '#f44336', backgroundColor: 'rgba(244,67,54,0.1)', borderWidth: 3, fill: true, tension: 0.4, pointBackgroundColor: '#f44336', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 6 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: 'Late & Undertime Trends', font: { size: 16, weight: 'bold' } },
                    legend: { display: true, position: 'top' },
                    tooltip: { callbacks: { label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y}` } }
                },
                scales: {
                    y: { beginAtZero: true },
                    x: { grid: { color: 'rgba(200,200,200,0.2)' } }
                }
            }
        });

        // Average Hours Chart
        const hoursCtx = document.getElementById('hoursChart').getContext('2d');
        hoursChart = new Chart(hoursCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Average Hours', data: avg, borderColor: '#9c27b0', backgroundColor: 'rgba(156,39,176,0.1)', borderWidth: 3, fill: true, tension: 0.4, pointBackgroundColor: '#9c27b0', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 6 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: 'Average Hours Trends', font: { size: 16, weight: 'bold' } },
                    legend: { display: true, position: 'top' },
                    tooltip: { callbacks: { label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y}` } }
                },
                scales: {
                    y: { beginAtZero: true },
                    x: { grid: { color: 'rgba(200,200,200,0.2)' } }
                }
            }
        });

        // Issues Rate Chart
        const issuesCtx = document.getElementById('issuesChart').getContext('2d');
        issuesChart = new Chart(issuesCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Issues Rate (%)', data: issues, borderColor: '#e91e63', backgroundColor: 'rgba(233,30,99,0.1)', borderWidth: 3, fill: true, tension: 0.4, pointBackgroundColor: '#e91e63', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 6 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: 'Issues Rate Trends', font: { size: 16, weight: 'bold' } },
                    legend: { display: true, position: 'top' },
                    tooltip: { callbacks: { label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y}%` } }
                },
                scales: {
                    y: { beginAtZero: true },
                    x: { grid: { color: 'rgba(200,200,200,0.2)' } }
                }
            }
        });

        // Performance Chart
        const performanceCtx = document.getElementById('performanceChart').getContext('2d');
        performanceChart = new Chart(performanceCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Total Shifts', data: total, backgroundColor: '#2196f3', borderColor: '#2196f3', borderWidth: 1 },
                    { label: 'Completed Shifts', data: done, backgroundColor: '#4caf50', borderColor: '#4caf50', borderWidth: 1 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: 'Performance Trends', font: { size: 16, weight: 'bold' } },
                    legend: { display: true, position: 'top' },
                    tooltip: { callbacks: { label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y}` } }
                },
                scales: {
                    y: { beginAtZero: true },
                    x: { grid: { color: 'rgba(200,200,200,0.2)' } }
                }
            }
        });
    }

    // Initial load
    fetchTrends();

    // Apply filters
    $('#applyFilters').on('click', function() {
        fetchTrends();
    });
});
</script>
@endpush


