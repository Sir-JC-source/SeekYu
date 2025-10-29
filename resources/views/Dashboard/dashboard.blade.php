@extends('Layouts.vuexy')

@section('title', 'Dashboard')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    {{-- KPI Dashboard for Super Admin, Admin, and HR Officer --}}
    @if(isset($kpiData) && count($kpiData) > 0)
      {{-- KPI Overview Cards --}}
      <div class="col-12 mb-4">
        <div class="card">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">Security Guard KPI Overview - {{ date('F Y') }}</h5>
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bx bx-filter-alt me-1"></i>Filter
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="filterKPIs('all')">All Guards</a></li>
                <li><a class="dropdown-item" href="#" onclick="filterKPIs('excellent')">Excellent (90-100)</a></li>
                <li><a class="dropdown-item" href="#" onclick="filterKPIs('good')">Good (70-89)</a></li>
                <li><a class="dropdown-item" href="#" onclick="filterKPIs('needs-improvement')">Needs Improvement (<70)</a></li>
              </ul>
            </div>
          </div>
          <div class="card-body">
            <div class="row g-4 mb-4">
              {{-- Average KPI Score --}}
              <div class="col-md-3">
                <div class="card h-100 border-primary">
                  <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                      <i class="bx bx-trending-up text-white" style="font-size: 24px;"></i>
                    </div>
                    <h4 class="text-primary mb-1">{{ round(collect($kpiData)->avg('kpi_score'), 1) }}</h4>
                    <p class="text-muted mb-0">Average KPI Score</p>
                  </div>
                </div>
              </div>

              {{-- Total Guards --}}
              <div class="col-md-3">
                <div class="card h-100 border-info">
                  <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                      <i class="bx bx-group text-white" style="font-size: 24px;"></i>
                    </div>
                    <h4 class="text-info mb-1">{{ count($kpiData) }}</h4>
                    <p class="text-muted mb-0">Total Guards</p>
                  </div>
                </div>
              </div>

              {{-- High Performers --}}
              <div class="col-md-3">
                <div class="card h-100 border-success">
                  <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                      <i class="bx bx-star text-white" style="font-size: 24px;"></i>
                    </div>
                    <h4 class="text-success mb-1">{{ collect($kpiData)->where('kpi_score', '>=', 80)->count() }}</h4>
                    <p class="text-muted mb-0">High Performers (≥80)</p>
                  </div>
                </div>
              </div>

              {{-- Average Attendance --}}
              <div class="col-md-3">
                <div class="card h-100 border-warning">
                  <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                      <i class="bx bx-calendar-check text-white" style="font-size: 24px;"></i>
                    </div>
                    <h4 class="text-warning mb-1">{{ round(collect($kpiData)->avg('attendance_rate'), 1) }}%</h4>
                    <p class="text-muted mb-0">Avg Attendance Rate</p>
                  </div>
                </div>
              </div>
            </div>

            {{-- KPI Table --}}
            <div class="table-responsive">
              <table class="table table-hover" id="kpiTable">
                <thead class="table-light">
                  <tr>
                    <th>Rank</th>
                    <th>Guard Name</th>
                    <th>Role</th>
                    <th>Attendance Rate</th>
                    <th>Total Hours</th>
                    <th>Avg Hours/Day</th>
                    <th>Leave Days</th>
                    <th>Incidents</th>
                    <th>KPI Score</th>
                    <th>Performance</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($kpiData as $index => $kpi)
                  <tr class="kpi-row" data-performance="{{ strtolower(str_replace(' ', '-', $kpi['performance_rating'])) }}">
                    <td>
                      <span class="badge bg-label-primary rounded-pill">{{ $index + 1 }}</span>
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm me-3">
                          <span class="avatar-initial rounded-circle bg-label-{{ $kpi['performance_rating'] == 'Excellent' ? 'success' : ($kpi['performance_rating'] == 'Very Good' ? 'info' : ($kpi['performance_rating'] == 'Good' ? 'warning' : 'danger')) }}">
                            {{ strtoupper(substr($kpi['guard_name'], 0, 1)) }}
                          </span>
                        </div>
                        <div>
                          <h6 class="mb-0">{{ $kpi['guard_name'] }}</h6>
                        </div>
                      </div>
                    </td>
                    <td>
                      <span class="badge bg-label-secondary">{{ $kpi['guard_role'] }}</span>
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="progress w-100 me-3" style="height: 6px;">
                          <div class="progress-bar bg-{{ $kpi['attendance_rate'] >= 90 ? 'success' : ($kpi['attendance_rate'] >= 80 ? 'info' : ($kpi['attendance_rate'] >= 70 ? 'warning' : 'danger')) }}"
                               style="width: {{ $kpi['attendance_rate'] }}%"></div>
                        </div>
                        <span class="text-heading">{{ $kpi['attendance_rate'] }}%</span>
                      </div>
                    </td>
                    <td>{{ $kpi['total_hours_worked'] }}h</td>
                    <td>{{ $kpi['average_hours_per_day'] }}h</td>
                    <td>
                      <span class="badge bg-label-{{ $kpi['leave_days_taken'] <= 5 ? 'success' : ($kpi['leave_days_taken'] <= 10 ? 'warning' : 'danger') }}">
                        {{ $kpi['leave_days_taken'] }}
                      </span>
                    </td>
                    <td>
                      <span class="badge bg-label-{{ $kpi['incidents_reported'] == 0 ? 'success' : ($kpi['incidents_reported'] <= 2 ? 'warning' : 'danger') }}">
                        {{ $kpi['incidents_reported'] }}
                      </span>
                    </td>
                    <td>
                      <h6 class="mb-0 text-{{ $kpi['kpi_score'] >= 80 ? 'success' : ($kpi['kpi_score'] >= 70 ? 'warning' : 'danger') }}">
                        {{ $kpi['kpi_score'] }}
                      </h6>
                    </td>
                    <td>
                      <span class="badge bg-{{ $kpi['performance_rating'] == 'Excellent' ? 'success' : ($kpi['performance_rating'] == 'Very Good' ? 'info' : ($kpi['performance_rating'] == 'Good' ? 'warning' : 'danger')) }}">
                        {{ $kpi['performance_rating'] }}
                      </span>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    @else
      {{-- Default dashboard content for other roles --}}
      <div class="col-12">
        <div class="card">
          <div class="card-body text-center py-5">
            <div class="avatar mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
              <i class="bx bx-dashboard text-white" style="font-size: 40px;"></i>
            </div>
            <h4>Welcome to your Dashboard</h4>
            <p class="text-muted">Access your features using the sidebar navigation.</p>
          </div>
        </div>
      </div>
    @endif
  </div>
</div>

{{-- KPI Filter Script --}}
@if(isset($kpiData))
<script>
function filterKPIs(filter) {
  const rows = document.querySelectorAll('.kpi-row');

  rows.forEach(row => {
    const performance = row.getAttribute('data-performance');

    if (filter === 'all') {
      row.style.display = '';
    } else if (filter === 'excellent' && performance === 'excellent') {
      row.style.display = '';
    } else if (filter === 'good' && ['very-good', 'good'].includes(performance)) {
      row.style.display = '';
    } else if (filter === 'needs-improvement' && ['satisfactory', 'needs-improvement'].includes(performance)) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
}
</script>
@endif

{{-- Force Password Change Modal --}}
@if(session('force_password_change') && (auth()->user()->hasRole('student') || auth()->user()->hasRole('faculty')))
<div class="modal fade show" id="forceChangePasswordModal" tabindex="-1" aria-modal="true" role="dialog" style="display:block; background: rgba(0,0,0,0.6);">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('password.forceChange') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Change Your Password</h5>
        </div>
        <div class="modal-body">
          <p>You must change your password before continuing.</p>
          <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="new_password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          {{-- Update Password Button --}}
          <button type="submit" class="btn btn-primary">Update Password</button>

          {{-- Logout Button --}}
          <a href="{{ route('logout') }}" 
             onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
             class="btn btn-danger">
             Logout
          </a>
        </div>
      </form>

      {{-- Hidden Logout Form --}}
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
      </form>
    </div>
  </div>
</div>

{{-- Prevent closing modal --}}
<script>
  document.addEventListener("DOMContentLoaded", function() {
      let modal = document.getElementById('forceChangePasswordModal');
      modal.classList.add('show');
      modal.style.display = 'block';
      modal.setAttribute('data-bs-backdrop', 'static');
      modal.setAttribute('data-bs-keyboard', 'false');
  });
</script>
@endif

@endsection
