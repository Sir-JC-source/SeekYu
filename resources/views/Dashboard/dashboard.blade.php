@extends('Layouts.vuexy')

@section('title', 'Dashboard')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    {{-- KPI Dashboard for Super Admin, Admin, and HR Officer --}}
    @if(isset($attendanceKpiData) && count($attendanceKpiData) > 0)
      {{-- KPI Overview Cards --}}
      <div class="col-12 mb-4">
        <div class="card">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">Security Guards and Head Guards Attendance KPI Overview</h5>
          </div>
          <div class="card-body">
            {{-- KPI cards --}}
            <div class="row g-4 mb-4">
              <div class="col-md-3">
                <div class="card h-100 border-info text-center p-3">
                  <h4 class="text-info mb-1">{{ $attendanceKpiData['total_guards'] }}</h4>
                  <p class="text-muted mb-0">Total Guards</p>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card h-100 border-primary text-center p-3">
                  <h4 class="text-primary mb-1">{{ $attendanceKpiData['total_shifts'] }}</h4>
                  <p class="text-muted mb-0">Total Shifts</p>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card h-100 border-success text-center p-3">
                  <h4 class="text-success mb-1">{{ $attendanceKpiData['completed_shifts'] }}</h4>
                  <p class="text-muted mb-0">Completed Shifts</p>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card h-100 border-warning text-center p-3">
                  <h4 class="text-warning mb-1">{{ $attendanceKpiData['average_hours'] }}h</h4>
                  <p class="text-muted mb-0">Avg Hours/Shift</p>
                </div>
              </div>
            </div>

            {{-- KPI summary --}}
            <div class="row g-4">
              <div class="col-md-6">
                <div class="card h-100">
                  <div class="card-header"><h6 class="card-title mb-0">Shift Completion Summary</h6></div>
                  <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                      <span>Completed Shifts</span><span class="badge bg-success">{{ $attendanceKpiData['completed_shifts'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                      <span>Total Scheduled Shifts</span><span class="badge bg-primary">{{ $attendanceKpiData['total_shifts'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                      <span>Completion Rate</span>
                      <span class="badge bg-info">
                        @if($attendanceKpiData['total_shifts'] > 0)
                          {{ round(($attendanceKpiData['completed_shifts'] / $attendanceKpiData['total_shifts']) * 100, 1) }}%
                        @else 0% @endif
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card h-100">
                  <div class="card-header"><h6 class="card-title mb-0">Attendance Issues</h6></div>
                  <div class="card-body">
                    <div class="d-flex justify-content-between mb-2"><span>Late Arrivals</span><span class="badge bg-warning">{{ $attendanceKpiData['late_shifts'] }}</span></div>
                    <div class="d-flex justify-content-between mb-2"><span>Undertime</span><span class="badge bg-danger">{{ $attendanceKpiData['undertime_shifts'] }}</span></div>
                    <div class="d-flex justify-content-between"><span>Average Hours per Shift</span><span class="badge bg-secondary">{{ $attendanceKpiData['average_hours'] }}h</span></div>
                  </div>
                </div>
              </div>
            </div>

            {{-- Analytics charts --}}
            <div class="row mt-4">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <div class="row align-items-center">
                      <div class="col-md-6"><h6 class="card-title mb-0">Attendance Analytics Trends</h6></div>
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
                      <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#productivity">Productivity</button></li>
                    </ul>

                    <div class="tab-content">
                      <div class="tab-pane fade show active" id="late-undertime"><canvas id="lateUndertimeChart"></canvas></div>
                      <div class="tab-pane fade" id="hours"><canvas id="hoursChart"></canvas></div>
                      <div class="tab-pane fade" id="issues"><canvas id="issuesChart"></canvas></div>
                      <div class="tab-pane fade" id="performance"><canvas id="performanceChart"></canvas></div>
                      <div class="tab-pane fade" id="productivity"><canvas id="productivityChart"></canvas></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @else
      <div class="col-12">
        <div class="card">
          <div class="card-body text-center py-5">
            <div class="avatar mx-auto mb-3" style="width:80px;height:80px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:50%;display:flex;align-items:center;justify-content:center;">
              <i class="bx bx-dashboard text-white" style="font-size:40px;"></i>
            </div>
            <h4>Welcome to your Dashboard</h4>
            <p class="text-muted">Access your features using the sidebar navigation.</p>
          </div>
        </div>
      </div>
    @endif
  </div>
</div>

@if(isset($attendanceKpiData))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentMonths = 6, currentGrouping = 'monthly';
    let charts = {};
    const safeY = (context) => context?.parsed?.y ?? 0;

    initializeCharts();

    document.getElementById('applyFilters').addEventListener('click', () => {
        currentMonths = parseInt(document.getElementById('periodSelect').value);
        currentGrouping = document.getElementById('groupingSelect').value;
        loadChartData();
    });

    function initializeCharts() {
        Object.values(charts).forEach(chart => chart.destroy());

        const chartIds = {
            lateUndertime: 'lateUndertimeChart',
            hours: 'hoursChart',
            issues: 'issuesChart',
            performance: 'performanceChart',
            productivity: 'productivityChart'
        };

        charts.lateUndertime = new Chart(document.getElementById(chartIds.lateUndertime), { type: 'line', data: { labels: [], datasets: [] }, options: getChartOptions('Late & Undertime Trends', 'Count') });
        charts.hours = new Chart(document.getElementById(chartIds.hours), { type: 'line', data: { labels: [], datasets: [] }, options: getChartOptions('Average Hours Worked', 'Hours') });
        charts.issues = new Chart(document.getElementById(chartIds.issues), { type: 'line', data: { labels: [], datasets: [] }, options: getChartOptions('Attendance Issues Rate', '%') });
        charts.performance = new Chart(document.getElementById(chartIds.performance), { type: 'bar', data: { labels: [], datasets: [] }, options: getBarChartOptions('Shift Performance Comparison') });
        charts.productivity = new Chart(document.getElementById(chartIds.productivity), { type: 'line', data: { labels: [], datasets: [] }, options: getChartOptions('Guard Productivity', 'Hours') });

        loadChartData();
    }

    function loadChartData() {
        const btn = document.getElementById('applyFilters');
        const oldText = btn.innerHTML;
        btn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Loading...';
        btn.disabled = true;

        fetch(`/dashboard/attendance-trends?months=${currentMonths}&grouping=${currentGrouping}`)
            .then(r => r.json())
            .then(updateCharts)
            .catch(err => {
                console.error('Error loading chart data:', err);
                alert('Error loading chart data. Please try again.');
            })
            .finally(() => {
                btn.innerHTML = oldText;
                btn.disabled = false;
            });
    }

    function updateCharts(data) {
        const labels = data.map(i => i.period);
        const late = data.map(i => i.late_shifts ?? 0);
        const under = data.map(i => i.undertime_shifts ?? 0);
        const avg = data.map(i => i.avg_hours ?? 0);
        const issues = data.map(i => i.issues_rate ?? 0);
        const total = data.map(i => i.total_shifts ?? 0);
        const done = data.map(i => i.completed_shifts ?? 0);
        const prod = data.map(i => i.productivity ?? 0);

        charts.lateUndertime.data = { labels, datasets: [
            { label: 'Late Arrivals', data: late, borderColor: '#ff5722', backgroundColor: 'rgba(255,87,34,0.1)', borderWidth: 3, tension: 0.4, pointBackgroundColor: '#ff5722', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 6, fill: false },
            { label: 'Undertime', data: under, borderColor: '#ff9800', backgroundColor: 'rgba(255,152,0,0.1)', borderWidth: 3, tension: 0.4, pointBackgroundColor: '#ff9800', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 6, fill: false }
        ]}; charts.lateUndertime.update();

        charts.hours.data = { labels, datasets: [{ label: 'Average Hours', data: avg, borderColor: '#2196f3', backgroundColor: 'rgba(33,150,243,0.1)', borderWidth: 3, fill: true, tension: 0.4, pointBackgroundColor: '#2196f3', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 6 }]}; charts.hours.update();

        charts.issues.data = { labels, datasets: [{
            label: 'Issues Rate (%)',
            data: issues,
            borderColor: ctx => { const v = safeY(ctx); return v > 20 ? '#f44336' : v > 10 ? '#ff9800' : '#4caf50'; },
            backgroundColor: 'rgba(76,175,80,0.1)', borderWidth: 3, fill: true, tension: 0.4,
            pointBackgroundColor: '#4caf50', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 6
        }]}; charts.issues.update();

        charts.performance.data = { labels, datasets: [
            { label: 'Scheduled Shifts', data: total, backgroundColor: 'rgba(33,150,243,0.8)', borderColor: '#2196f3', borderWidth: 1 },
            { label: 'Completed Shifts', data: done, backgroundColor: 'rgba(76,175,80,0.8)', borderColor: '#4caf50', borderWidth: 1 }
        ]}; charts.performance.update();

        charts.productivity.data = { labels, datasets: [{ label: 'Avg Hours per Guard', data: prod, borderColor: '#9c27b0', backgroundColor: 'rgba(156,39,176,0.1)', borderWidth: 3, fill: true, tension: 0.4, pointBackgroundColor: '#9c27b0', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 6 }]}; charts.productivity.update();
    }

    function getChartOptions(title, yLabel) {
        return {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                title: { display: true, text: title, font: { size: 16, weight: 'bold' } },
                legend: { display: true, position: 'top' },
                tooltip: { callbacks: { label: ctx => `${ctx.dataset.label}: ${safeY(ctx)} ${yLabel}` } }
            },
            scales: {
                y: { beginAtZero: true, ticks: { callback: v => v + (yLabel === '%' ? '%' : '') } },
                x: { grid: { color: 'rgba(200,200,200,0.2)' } }
            }
        };
    }

    function getBarChartOptions(title) {
        return {
            responsive: true, maintainAspectRatio: false,
            plugins: { title: { display: true, text: title, font: { size: 16, weight: 'bold' } }, legend: { display: true, position: 'top' } },
            scales: { y: { beginAtZero: true }, x: { grid: { color: 'rgba(200,200,200,0.2)' } } }
        };
    }
});
</script>
@endif

@endsection
