@extends('Layouts.vuexy')

@section('title', 'Request Leave')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Request Leave Form</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('leaves.request.store') }}" method="POST">
            @csrf
            <div class="row">

                {{-- Requestor (readonly) --}}
                <div class="col-md-6 mb-3">
                    <label for="requestor" class="form-label">Requestor</label>
                    <input type="text" 
                           class="form-control @error('requestor') is-invalid @enderror" 
                           id="requestor" 
                           name="requestor" 
                           value="{{ Auth::user()->fullname }}" 
                           readonly>
                    @error('requestor')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Type of Leave --}}
                <div class="col-md-6 mb-3">
                    <label for="leave_type" class="form-label">Type of Leave</label>
                    <select class="form-select @error('leave_type') is-invalid @enderror" 
                            id="leave_type" 
                            name="leave_type" 
                            required>
                        <option value="">Select Type</option>
                        <option value="Sick Leave" {{ old('leave_type') == 'Sick Leave' ? 'selected' : '' }}>Sick Leave</option>
                        <option value="Vacation Leave" {{ old('leave_type') == 'Vacation Leave' ? 'selected' : '' }}>Vacation Leave</option>
                    </select>
                    @error('leave_type')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Reason --}}
                <div class="col-md-12 mb-3">
                    <label for="reason" class="form-label">Reason</label>
                    <textarea class="form-control @error('reason') is-invalid @enderror" 
                              id="reason" 
                              name="reason" 
                              rows="3" 
                              placeholder="Enter reason for leave" 
                              required>{{ old('reason') }}</textarea>
                    @error('reason')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Duration --}}
                <div class="col-md-6 mb-3">
                    <label for="duration" class="form-label">Duration</label>
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
                    @error('duration')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Date From --}}
                <div class="col-md-3 mb-3">
                    <label for="date_from" class="form-label">Date From</label>
                    <input type="date" class="form-control @error('date_from') is-invalid @enderror" 
                           id="date_from" name="date_from" 
                           value="{{ old('date_from') }}" required>
                    @error('date_from')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Date To --}}
                <div class="col-md-3 mb-3">
                    <label for="date_to" class="form-label">Date To</label>
                    <input type="date" class="form-control @error('date_to') is-invalid @enderror" 
                           id="date_to" name="date_to" 
                           value="{{ old('date_to') }}" required>
                    @error('date_to')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Position (readonly) --}}
                <div class="col-md-6 mb-3">
                    <label for="position" class="form-label">Position</label>
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
                    <input type="text" class="form-control" id="position" name="position" value="{{ $position }}" readonly>
                </div>

                {{-- Leave Credits (readonly) --}}
                <div class="col-md-6 mb-3">
                    <label for="leave_credits" class="form-label">Leave Credits</label>
                    <input type="number" class="form-control" 
                           id="leave_credits" name="leave_credits" 
                           value="{{ old('leave_credits', Auth::user()->leave_credits ?? 5) }}" 
                           readonly>
                </div>

                {{-- Submit --}}
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                    <a href="{{ route('dashboard.index') }}" class="btn btn-secondary">Cancel</a>
                </div>

            </div>
        </form>
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
