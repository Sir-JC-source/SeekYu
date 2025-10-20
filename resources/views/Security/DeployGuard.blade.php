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

            <div class="row g-2   y     gf  mmb-2">
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
                <div class="col-md-3">
                    <label for="time_in" class="form-label small">Shift In</label>
                    <select class="form-select form-select-sm" id="time_in" name="time_in" required>
                        <option value="">Select Shift In</option>
                        @for($h=0; $h<24; $h++)
                            @for($m=0; $m<60; $m+=30)
                                @php
                                    $time = trim(sprintf('%02d:%02d', $h, $m));
                                    try {
                                        $ampm = \Carbon\Carbon::createFromFormat('H:i', $time)->format('h:i A');
                                    } catch (\Exception $e) {
                                        $ampm = $time;
                                    }
                                @endphp
                                <option value="{{ $time }}">{{ $ampm }}</option>
                            @endfor
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="time_out" class="form-label small">Shift Out</label>
                    <select class="form-select form-select-sm" id="time_out" name="time_out" required>
                        <option value="">Shift Out auto</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="designation" class="form-label small">Designation</label>
                    <select class="form-select form-select-sm" id="designation" name="designation" required>
                        <option value="In-House" {{ (isset($guard) && $guard->designation == 'In-House') ? 'selected' : '' }}>In-House</option>
                    </select>
                </div>
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <label for="status" class="form-label small">Status</label>
                    <input type="text" class="form-control form-control-sm" id="status" value="Pending" readonly>
                    <!-- Deploy Guard button under the status input -->
                    <button type="submit" class="btn btn-primary btn-sm mt-2 w-100" {{ isset($guard) ? '' : 'disabled' }}>Deploy Guard</button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection

@push('page-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const timeInSelect = document.getElementById('time_in');
    const timeOutSelect = document.getElementById('time_out');

    timeInSelect.addEventListener('change', function () {
        const timeIn = this.value;
        if (!timeIn) return;

        const [h, m] = timeIn.split(':').map(Number);
        timeOutSelect.innerHTML = '';

        let totalMinutes = h * 60 + m + 720; // 12 hours
        totalMinutes %= 1440;
        const hour = Math.floor(totalMinutes / 60);
        const minute = totalMinutes % 60;
        const t = `${hour.toString().padStart(2,'0')}:${minute.toString().padStart(2,'0')}`;
        const display = moment(t, "HH:mm").format('hh:mm A');

        const option = document.createElement('option');
        option.value = t;
        option.textContent = display;
        option.selected = true;
        timeOutSelect.appendChild(option);
    });
});
</script>
@endpush
