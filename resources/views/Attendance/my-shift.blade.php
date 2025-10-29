@extends('Layouts.vuexy')

@section('title', 'My Shift')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <h5 class="mb-0"><i class="ti ti-clock me-2"></i>My Shift</h5>
                    <div>
                        <a href="{{ route('attendance.index', ['week' => $weekStart->copy()->subWeek()->format('Y-m-d')]) }}"
                           class="btn btn-sm btn-outline-primary me-2">
                            <i class="ti ti-chevron-left"></i> Previous
                        </a>
                        <a href="{{ route('attendance.index', ['week' => $weekStart->copy()->addWeek()->format('Y-m-d')]) }}"
                           class="btn btn-sm btn-outline-primary">
                            Next <i class="ti ti-chevron-right"></i>
                        </a>
                    </div>
                </div>

                <div class="card-body">

                    <!-- Today's Shift -->
                    <div class="mb-4">
                        <div class="card border-primary shadow-sm">
                            <div class="card-header text-white">
                                <h6 class="mb-0">
                                    <i class="ti ti-calendar me-1"></i>
                                    Today's Shift ({{ \Carbon\Carbon::today('Asia/Manila')->format('l, M j, Y') }})
                                </h6>
                            </div>

                            <div class="card-body">
                                <div class="row align-items-center gy-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Scheduled Shift</label>
                                        <p class="mb-0">{{ $todayData['schedule'] ?? 'No Shift' }}</p>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">Actual Shift In</label>
                                        <p class="mb-0">
                                            {{ $todayData['shift_in'] ?? '—' }}
                                            @if(!empty($todayData['status_label']) && $todayData['status_label'] == 'Late')
                                                <span class="badge bg-danger ms-1">Late</span>
                                            @endif
                                        </p>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">Actual Shift Out</label>
                                        <p class="mb-0">
                                            {{ $todayData['shift_out'] ?? '—' }}
                                            @if(!empty($todayData['status_label']) && $todayData['status_label'] == 'Undertime')
                                                <span class="badge bg-warning text-dark ms-1">Undertime</span>
                                            @endif
                                        </p>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">Total Hours</label>
                                        <p class="mb-0 text-success fw-semibold">
                                            {{ $todayData['total_hours_display'] ?? '—' }}
                                        </p>
                                    </div>

                                    <div class="col-md-3 text-md-end">
                                        <label class="form-label fw-bold d-block">Actions</label>
                                        <div class="d-flex gap-2 justify-content-md-end">
                                            @if($todayData['can_shift_in'])
                                                <button id="shiftInBtn" class="btn btn-success btn-sm">
                                                    <i class="ti ti-login"></i> Shift In
                                                </button>
                                            @endif
                                            @if($todayData['can_shift_out'])
                                                <button id="shiftOutBtn" class="btn btn-warning btn-sm">
                                                    <i class="ti ti-logout"></i> Shift Out
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Weekly Overview -->
                    <h6 class="mb-3"><i class="ti ti-calendar-stats me-1"></i>Weekly Schedule Overview</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Scheduled Shift</th>
                                    <th>Actual In</th>
                                    <th>Actual Out</th>
                                    <th>Total Hours</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($weeklyData as $day)
                                    <tr class="{{ $day['is_today'] ? 'table-primary' : '' }}">
                                        <td>
                                            {{ $day['date'] }}
                                            @if($day['is_today'])
                                                <span class="badge bg-primary ms-1">Today</span>
                                            @endif
                                        </td>
                                        <td>{{ $day['schedule'] ?? 'No Shift' }}</td>
                                        <td>
                                            {{ $day['shift_in'] ?? '—' }}
                                            @if(!empty($day['status_label']) && $day['status_label'] == 'Late')
                                                <span class="badge bg-danger ms-1">Late</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $day['shift_out'] ?? '—' }}
                                            @if(!empty($day['status_label']) && $day['status_label'] == 'Undertime')
                                                <span class="badge bg-warning text-dark ms-1">Undertime</span>
                                            @endif
                                        </td>
                                        <td class="fw-semibold text-success">{{ $day['total_hours_display'] ?? '—' }}</td>
                                        <td>
                                            @if(isset($day['status_label'], $day['status_class']))
                                                <span class="badge bg-{{ $day['status_class'] }}">
                                                    {{ $day['status_label'] }}
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No schedule data available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const shiftInBtn = document.getElementById('shiftInBtn');
    const shiftOutBtn = document.getElementById('shiftOutBtn');

    // Shift In
    if (shiftInBtn) {
        shiftInBtn.addEventListener('click', () => {
            Swal.fire({
                title: 'Confirm Shift In?',
                text: "This will record your shift-in time.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, record it!'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch('{{ route("attendance.shift-in") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(res => res.json())
                    .then(data => Swal.fire('Success!', data.message, 'success').then(() => location.reload()))
                    .catch(() => Swal.fire('Error!', 'Something went wrong.', 'error'));
                }
            });
        });
    }

    // Shift Out
    if (shiftOutBtn) {
        shiftOutBtn.addEventListener('click', () => {
            Swal.fire({
                title: 'Confirm Shift Out?',
                text: "This will record your shift-out time.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, record it!'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch('{{ route("attendance.shift-out") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({})
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.warning && data.allow_force) {
                            Swal.fire({
                                title: 'Early Shift Out!',
                                text: data.message,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Force Shift Out',
                                cancelButtonText: 'Cancel',
                                confirmButtonColor: '#d33'
                            }).then(confirm => {
                                if (confirm.isConfirmed) {
                                    fetch('{{ route("attendance.force-shift-out") }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({})
                                    })
                                    .then(res => res.json())
                                    .then(resp => Swal.fire('Shift Out Recorded!', resp.message, 'success').then(() => location.reload()))
                                    .catch(() => Swal.fire('Error!', 'Something went wrong.', 'error'));
                                }
                            });
                        } else if (data.success) {
                            Swal.fire('Shift Out Recorded!', data.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error!', data.message, 'error');
                        }
                    })
                    .catch(() => Swal.fire('Error!', 'Something went wrong.', 'error'));
                }
            });
        });
    }
});
</script>
@endsection
