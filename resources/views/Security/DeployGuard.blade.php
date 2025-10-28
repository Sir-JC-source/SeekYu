@extends('Layouts.vuexy')

@section('title', 'Deploy Guard')

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
                    <label for="shift_in" class="form-label small">Shift In</label>
                    <input type="time" class="form-control form-control-sm" id="shift_in" name="shift_in" 
                           value="{{ old('shift_in') }}" required>
                </div>
                <div class="col-md-4">
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
                                <form action="{{ route('security.deployment.status', $deployment->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="active">
                                    <button type="submit" class="btn btn-success btn-sm">Activate</button>
                                </form>
                                <form action="{{ route('security.deployment.status', $deployment->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                </form>
                            @elseif($deployment->status === 'active')
                                <form action="{{ route('security.deployment.status', $deployment->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="btn btn-primary btn-sm">Complete</button>
                                </form>
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
document.addEventListener('DOMContentLoaded', function () {
    const shiftInInput = document.getElementById('shift_in');
    const shiftOutInput = document.getElementById('shift_out');

    shiftInInput.addEventListener('change', function () {
        const shiftIn = this.value;
        if (!shiftIn) return;

        // Auto-calculate shift out (12 hours later)
        const [hours, minutes] = shiftIn.split(':').map(Number);
        let totalMinutes = hours * 60 + minutes + 720; // 12 hours = 720 minutes
        totalMinutes %= 1440; // Wrap around 24 hours
        const newHours = Math.floor(totalMinutes / 60);
        const newMinutes = totalMinutes % 60;
        const shiftOut = `${newHours.toString().padStart(2, '0')}:${newMinutes.toString().padStart(2, '0')}`;
        shiftOutInput.value = shiftOut;
    });
});
</script>
@endpush
