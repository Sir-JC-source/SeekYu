@extends('Layouts.vuexy')

@section('title', 'Deploy Guard')

@push('page-styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endpush

@push('page-scripts')
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Deploy Guard</h5>
    </div>
    <div class="card-body">
        <form action="{{ isset($guard) ? route('security.deploy.store', $guard->id) : '#' }}" method="POST">
            @csrf

            <div class="row g-2 mb-2">
                <div class="col-md-3">
                    <label for="employee_number" class="form-label small">Employee No.</label>
                    <input type="text" class="form-control form-control-sm" id="employee_number" 
                           value="{{ $guard->employee_number ?? '' }}" name="employee_number" readonly>
                </div>
                <div class="col-md-5">
                    <label for="full_name" class="form-label small">Full Name</label>
                    <input type="text" class="form-control form-control-sm" id="full_name" 
                           value="{{ $guard->full_name ?? '' }}" name="full_name" readonly>
                </div>
                <div class="col-md-4">
                    <label for="position" class="form-label small">Position</label>
                    <input type="text" class="form-control form-control-sm" id="position" 
                           value="{{ $guard->position ?? '' }}" name="position" readonly>
                </div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-md-4">
                    <label for="deployment_date" class="form-label small">Deployment Date</label>
                    <input type="date" class="form-control form-control-sm" id="deployment_date" name="deployment_date"
                           value="{{ old('deployment_date', date('Y-m-d')) }}" required min="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label for="shift_template" class="form-label small">Shift Template</label>
                    <select class="form-select form-select-sm" id="shift_template">
                        <option value="">Select Template</option>
                        <option value="day">Day Shift (8:00 AM - 8:00 PM)</option>
                        <option value="night">Night Shift (8:00 PM - 8:00 AM)</option>
                        <option value="morning">Morning Shift (6:00 AM - 2:00 PM)</option>
                        <option value="afternoon">Afternoon Shift (2:00 PM - 10:00 PM)</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="shift_in" class="form-label small">Shift In</label>
                    <input type="time" class="form-control form-control-sm" id="shift_in" name="shift_in"
                           value="{{ old('shift_in') }}" required>
                </div>
                <div class="col-md-2">
                    <label for="shift_out" class="form-label small">Shift Out</label>
                    <input type="time" class="form-control form-control-sm" id="shift_out" name="shift_out"
                           value="{{ old('shift_out') }}" required>
                </div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-md-12">
                    <label for="assigned_head_guard_id" class="form-label small">Head Guard</label>
                    <select class="form-select form-select-sm" id="assigned_head_guard_id" name="assigned_head_guard_id" required>
                        @if(isset($guard) && $guard->position === 'Head Guard')
                            <option value="{{ $guard->id }}" selected>{{ $guard->full_name }}</option>
                        @else
                            <option value="">Select Head Guard</option>
                            @foreach($headGuards ?? [] as $head)
                                <option value="{{ $head->id }}">{{ $head->full_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-md-12">
                    <!-- Deploy Guard button -->
                    <button type="submit" class="btn btn-primary btn-sm w-100" {{ isset($guard) ? '' : 'disabled' }}>Deploy Guard</button>
                </div>
            </div>

        </form>
    </div>
</div>

@if(isset($deployments) && $deployments->count() > 0)
<div class="card mt-4">
    <div class="card-header">
        <h5>Existing Deployments</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Shift</th>
                        <th>Head Guard</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deployments as $deployment)
                    <tr>
                        <td>{{ $deployment->deployment_date->format('M d, Y') }}</td>
                        <td>{{ $deployment->shift_in->format('h:i A') }} - {{ $deployment->shift_out->format('h:i A') }}</td>
                        <td>{{ $deployment->headGuard->full_name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $deployment->status === 'active' ? 'success' : ($deployment->status === 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($deployment->status) }}
                            </span>
                        </td>
                        <td>
                            @if($deployment->status === 'pending')
                                <button type="button" class="btn btn-success btn-sm" onclick="confirmAction('{{ route('security.deployment.status', $deployment->id) }}', 'active', 'activate this deployment')">Activate</button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmAction('{{ route('security.deployment.status', $deployment->id) }}', 'cancelled', 'cancel this deployment')">Cancel</button>
                            @elseif($deployment->status === 'active')
                                <button type="button" class="btn btn-primary btn-sm" onclick="confirmAction('{{ route('security.deployment.status', $deployment->id) }}', 'completed', 'complete this deployment')">Complete</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection

@push('page-scripts')
<script>
function confirmAction(url, status, actionText) {
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to ${actionText}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: status === 'active' ? '#28a745' : (status === 'cancelled' ? '#dc3545' : '#007bff'),
        cancelButtonColor: '#6c757d',
        confirmButtonText: status === 'active' ? 'Yes, activate!' : (status === 'cancelled' ? 'Yes, cancel!' : 'Yes, complete!'),
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;

            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken.getAttribute('content');
                form.appendChild(csrfInput);
            }

            // Add method override for PUT
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);

            // Add status
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = status;
            form.appendChild(statusInput);

            document.body.appendChild(form);
            form.submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const shiftTemplateSelect = document.getElementById('shift_template');
    const shiftInInput = document.getElementById('shift_in');
    const shiftOutInput = document.getElementById('shift_out');

    // Shift template functionality
    shiftTemplateSelect.addEventListener('change', function () {
        const template = this.value;
        if (!template) return;

        let shiftIn = '', shiftOut = '';

        switch (template) {
            case 'day':
                shiftIn = '08:00';
                shiftOut = '20:00';
                break;
            case 'night':
                shiftIn = '20:00';
                shiftOut = '08:00';
                break;
            case 'morning':
                shiftIn = '06:00';
                shiftOut = '14:00';
                break;
            case 'afternoon':
                shiftIn = '14:00';
                shiftOut = '22:00';
                break;
            case 'custom':
                // Keep current values
                return;
        }

        shiftInInput.value = shiftIn;
        shiftOutInput.value = shiftOut;
    });

    shiftInInput.addEventListener('change', function () {
        const shiftIn = this.value;
        if (!shiftIn || shiftTemplateSelect.value !== 'custom') return;

        // Auto-calculate shift out (12 hours later) only for custom
        const [hours, minutes] = shiftIn.split(':').map(Number);
        let totalMinutes = hours * 60 + minutes + 720; // 12 hours = 720 minutes
        totalMinutes %= 1440; // Wrap around 24 hours
        const newHours = Math.floor(totalMinutes / 60);
        const newMinutes = totalMinutes % 60;
        const shiftOut = `${newHours.toString().padStart(2, '0')}:${newMinutes.toString().padStart(2, '0')}`;
        shiftOutInput.value = shiftOut;
    });

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const shiftIn = document.getElementById('shift_in').value;
        const shiftOut = document.getElementById('shift_out').value;

        if (shiftIn && shiftOut && shiftIn >= shiftOut) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Shift Times',
                text: 'Shift out time must be after shift in time.',
                confirmButtonText: 'OK'
            });
            return false;
        }
    });
});
</script>
@endpush
