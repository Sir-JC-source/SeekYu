@extends('Layouts.vuexy')

@section('title', 'Request Leave')

@section('content')
<div class="row">
    <!-- Header Card -->
    <div class="col-12 mb-4">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body text-center py-5">
                <div class="avatar avatar-xl mx-auto mb-3">
                    <div class="avatar-initial bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="ti ti-calendar-event text-white" style="font-size: 32px;"></i>
                    </div>
                </div>
                <h3 class="card-title text-white mb-1">Request Leave</h3>
                <p class="card-text opacity-75 mb-0">Submit your leave application for approval</p>
            </div>
        </div>
    </div>

    <!-- Leave Request Form -->
    <div class="col-xl-8 col-lg-7 col-md-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Leave Application Form</h5>
                <small class="text-muted">All fields are required</small>
            </div>
            <div class="card-body">
                <form action="{{ route('leaves.request.store') }}" method="POST" id="leaveForm">
                    @csrf

                    <!-- Personal Information Section -->
                    <div class="row g-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="ti ti-user me-2"></i>Personal Information
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text"
                                       class="form-control bg-light @error('requestor') is-invalid @enderror"
                                       id="requestor"
                                       name="requestor"
                                       value="{{ Auth::user()->employee->full_name ?? Auth::user()->fullname }}"
                                       readonly
                                       placeholder="Requestor">
                                <label for="requestor">Requestor</label>
                                @error('requestor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            @php
                                $role = Auth::user()->role;
                                $position = match($role) {
                                    'admin' => 'Admin',
                                    'hr-officer' => 'HR Officer',
                                    'head-guard' => 'Head Guard',
                                    'security-guard' => 'Security Guard',
                                    default => '',
                                };
                            @endphp
                            <div class="form-floating">
                                <input type="text"
                                       class="form-control bg-light"
                                       id="position"
                                       name="position"
                                       value="{{ $position }}"
                                       readonly
                                       placeholder="Position">
                                <label for="position">Position</label>
                            </div>
                        </div>
                    </div>

                    <!-- Leave Details Section -->
                    <div class="row g-4 mt-2">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="ti ti-calendar-check me-2"></i>Leave Details
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select @error('leave_type') is-invalid @enderror"
                                        id="leave_type"
                                        name="leave_type"
                                        required>
                                    <option value="">Select Type</option>
                                    <option value="Sick Leave" {{ old('leave_type') == 'Sick Leave' ? 'selected' : '' }}>Sick Leave</option>
                                    <option value="Vacation Leave" {{ old('leave_type') == 'Vacation Leave' ? 'selected' : '' }}>Vacation Leave</option>
                                </select>
                                <label for="leave_type">Type of Leave</label>
                                @error('leave_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select @error('duration') is-invalid @enderror"
                                        id="duration"
                                        name="duration"
                                        required>
                                    <option value="">Select Duration</option>
                                    <option value="Whole Shift" {{ old('duration') == 'Whole Shift' ? 'selected' : '' }}>Whole Shift</option>
                                    <option value="Half-Shift Early Out" {{ old('duration') == 'Half-Shift Early Out' ? 'selected' : '' }}>Half-Shift Early Out</option>
                                    <option value="Half-Shift Late In" {{ old('duration') == 'Half-Shift Late In' ? 'selected' : '' }}>Half-Shift Late In</option>
                                    <option value="Multi-Day" {{ old('duration') == 'Multi-Day' ? 'selected' : '' }}>Multi-Day</option>
                                </select>
                                <label for="duration">Duration</label>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date"
                                       class="form-control @error('date_from') is-invalid @enderror"
                                       id="date_from"
                                       name="date_from"
                                       value="{{ old('date_from') }}"
                                       required>
                                <label for="date_from">Date From</label>
                                @error('date_from')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date"
                                       class="form-control @error('date_to') is-invalid @enderror"
                                       id="date_to"
                                       name="date_to"
                                       value="{{ old('date_to') }}"
                                       required>
                                <label for="date_to">Date To</label>
                                @error('date_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control @error('reason') is-invalid @enderror"
                                          id="reason"
                                          name="reason"
                                          rows="4"
                                          placeholder="Please provide detailed reason for your leave request"
                                          required>{{ old('reason') }}</textarea>
                                <label for="reason">Reason for Leave</label>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Section -->
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5 me-3">
                                <i class="ti ti-send me-2"></i>Submit Request
                            </button>
                            <a href="{{ route('dashboard.index') }}" class="btn btn-outline-secondary btn-lg px-5">
                                <i class="ti ti-x me-2"></i>Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Leave Information Sidebar -->
    <div class="col-xl-4 col-lg-5 col-md-5">
        <div class="row g-4">
            <!-- Leave Credits Card -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Leave Credits</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <small class="text-muted">Available Credits</small>
                                <div class="fw-bold text-success fs-4">{{ Auth::user()->leave_credits ?? 10 }}</div>
                            </div>
                            <i class="ti ti-calendar-heart text-success" style="font-size: 32px;"></i>
                        </div>
                        <div class="progress mb-2" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: {{ min((Auth::user()->leave_credits ?? 10) / 10 * 100, 100) }}%"
                                 aria-valuenow="{{ Auth::user()->leave_credits ?? 10 }}"
                                 aria-valuemin="0" aria-valuemax="10"></div>
                        </div>
                        <small class="text-muted">Out of 10 annual credits</small>
                    </div>
                </div>
            </div>

            <!-- Leave Types Info -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Leave Types</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ti ti-heart text-danger me-2"></i>
                                <strong class="me-2">Sick Leave:</strong>
                                <small class="text-muted">Medical reasons</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ti ti-plane text-info me-2"></i>
                                <strong class="me-2">Vacation Leave:</strong>
                                <small class="text-muted">Personal time off</small>
                            </div>
                        </div>
                        <hr>
                        <small class="text-muted">
                            <i class="ti ti-info-circle me-1"></i>
                            All leave requests require approval from your supervisor.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Quick Tips -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Quick Tips</h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline timeline-border-primary">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <small class="text-muted">Plan ahead</small>
                                    <p class="mb-0">Submit requests at least 2 weeks in advance</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <small class="text-muted">Be specific</small>
                                    <p class="mb-0">Provide clear reasons for better approval chances</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <small class="text-muted">Check credits</small>
                                    <p class="mb-0">Ensure you have sufficient leave credits</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toast Notifications --}}
<div class="position-fixed top-0 end-0 p-3" style="z-index:1080">
    @if(session('success'))
        <div id="successToast" class="toast align-items-center text-bg-success border-0 show" role="alert">
            <div class="d-flex">
                <div class="toast-body">{{ session('success') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif
    @if($errors->any())
        <div id="errorToast" class="toast align-items-center text-bg-danger border-0 show" role="alert">
            <div class="d-flex">
                <div class="toast-body"><strong>Validation Error:</strong> {{ $errors->first() }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const successToastEl = document.getElementById('successToast');
    if (successToastEl) new bootstrap.Toast(successToastEl, { delay: 5000 }).show();

    const errorToastEl = document.getElementById('errorToast');
    if (errorToastEl) new bootstrap.Toast(errorToastEl, { delay: 7000 }).show();

    const durationEl = document.getElementById('duration');
    const dateFromEl = document.getElementById('date_from');
    const dateToEl = document.getElementById('date_to');

    durationEl.addEventListener('change', () => {
        const duration = durationEl.value;
        const today = new Date().toISOString().split('T')[0];

        if (duration === 'Whole Shift' || duration === 'Half-Shift Early Out' || duration === 'Half-Shift Late In') {
            dateFromEl.value = today;
            dateToEl.value = today;
            dateToEl.removeAttribute('max'); // remove max for single-day
            dateFromEl.readOnly = true;
            dateToEl.readOnly = true;
        } else if (duration === 'Multi-Day') {
            dateFromEl.value = today;
            dateToEl.value = today;
            const maxDate = new Date();
            maxDate.setDate(maxDate.getDate() + 5);
            dateToEl.max = maxDate.toISOString().split('T')[0];
            dateFromEl.readOnly = false;
            dateToEl.readOnly = false;
        } else {
            dateFromEl.readOnly = false;
            dateToEl.readOnly = false;
        }
    });

    // Trigger on page load in case old value exists
    durationEl.dispatchEvent(new Event('change'));
});
</script>
@endsection
