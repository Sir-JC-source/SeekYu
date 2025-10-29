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
            <div class="row g-4 mb-4">
              {{-- Total Guards --}}
              <div class="col-md-3">
                <div class="card h-100 border-info">
                  <div class="card-body text-center">
                    
                    <h4 class="text-info mb-1">{{ $attendanceKpiData['total_guards'] }}</h4>
                    <p class="text-muted mb-0">Total Guards</p>
                  </div>
                </div>
              </div>

              {{-- Total Shifts --}}
              <div class="col-md-3">
                <div class="card h-100 border-info">
                  <div class="card-body text-center">
                    <h4 class="text-primary mb-1">{{ $attendanceKpiData['total_shifts'] }}</h4>
                    <p class="text-muted mb-0">Total Shifts</p>
                  </div>
                </div>
              </div>

              {{-- Completed Shifts --}}
              <div class="col-md-3">
                <div class="card h-100 border-info">
                  <div class="card-body text-center">
                    <h4 class="text-success mb-1">{{ $attendanceKpiData['completed_shifts'] }}</h4>
                    <p class="text-muted mb-0">Completed Shifts</p>
                  </div>
                </div>
              </div>

              {{-- Average Hours --}}
              <div class="col-md-3">
                <div class="card h-100 border-info">
                  <div class="card-body text-center">
                    <h4 class="text-warning mb-1">{{ $attendanceKpiData['average_hours'] }}h</h4>
                    <p class="text-muted mb-0">Avg Hours/Shift</p>
                  </div>
                </div>
              </div>
            </div>

            {{-- Attendance KPI Summary --}}
            <div class="row g-4">
              <div class="col-md-6">
                <div class="card h-100">
                  <div class="card-header">
                    <h6 class="card-title mb-0">Shift Completion Summary</h6>
                  </div>
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <span>Completed Shifts</span>
                      <span class="badge bg-success">{{ $attendanceKpiData['completed_shifts'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <span>Total Scheduled Shifts</span>
                      <span class="badge bg-primary">{{ $attendanceKpiData['total_shifts'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                      <span>Completion Rate</span>
                      <span class="badge bg-info">
                        @if($attendanceKpiData['total_shifts'] > 0)
                          {{ round(($attendanceKpiData['completed_shifts'] / $attendanceKpiData['total_shifts']) * 100, 1) }}%
                        @else
                          0%
                        @endif
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="card h-100">
                  <div class="card-header">
                    <h6 class="card-title mb-0">Attendance Issues</h6>
                  </div>
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <span>Late Arrivals</span>
                      <span class="badge bg-warning">{{ $attendanceKpiData['late_shifts'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <span>Undertime</span>
                      <span class="badge bg-danger">{{ $attendanceKpiData['undertime_shifts'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                      <span>Average Hours per Shift</span>
                      <span class="badge bg-secondary">{{ $attendanceKpiData['average_hours'] }}h</span>
                    </div>
                  </div>
                </div>
              </div>
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

{{-- Attendance KPI Script --}}
@if(isset($attendanceKpiData))
<script>
// Optional: Add any interactive features for attendance KPIs here
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
