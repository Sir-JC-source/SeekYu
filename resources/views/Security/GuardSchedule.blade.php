@extends('layouts.vuexy')

@section('title', 'Guard Schedule - ' . $guard->full_name)

@push('page-styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endpush

@push('page-scripts')
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Schedule for {{ $guard->full_name }} ({{ $guard->employee_number }})</h5>
                    <a href="{{ route('guard-scheduling.assign') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Back to Guards List
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('guard-scheduling.assign.store', ['guard' => $guard->id]) }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Start Month</label>
                                <input type="text" class="form-control" value="{{ $startDate->format('F Y') }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Month</label>
                                <input type="text" class="form-control" value="{{ $endDate->format('F Y') }}" readonly>
                            </div>
                        </div>

                        <div class="calendar-container">
                            @php
                                $currentMonth = $startDate->copy();
                                $currentDate = $currentMonth->copy()->startOfMonth();
                                $endOfMonth = $currentMonth->copy()->endOfMonth();
                                $index = 0;
                            @endphp

                            <div class="calendar-header d-flex justify-content-between align-items-center mb-3">
                                <a href="{{ route('guard-scheduling.assign.guard', ['guard' => $guard->id, 'month' => $currentMonth->copy()->subMonth()->format('Y-m')]) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bx bx-chevron-left"></i> Previous
                                </a>
                                <h4>{{ $currentMonth->format('F Y') }}</h4>
                                <a href="{{ route('guard-scheduling.assign.guard', ['guard' => $guard->id, 'month' => $currentMonth->copy()->addMonth()->format('Y-m')]) }}" class="btn btn-outline-secondary btn-sm">
                                    Next <i class="bx bx-chevron-right"></i>
                                </a>
                            </div>

                            <div class="calendar-grid">
                                <!-- Day headers -->
                                <div class="calendar-day-header">Sun</div>
                                <div class="calendar-day-header">Mon</div>
                                <div class="calendar-day-header">Tue</div>
                                <div class="calendar-day-header">Wed</div>
                                <div class="calendar-day-header">Thu</div>
                                <div class="calendar-day-header">Fri</div>
                                <div class="calendar-day-header">Sat</div>

                                <!-- Empty cells for days before start of month -->
                                @for($i = 0; $i < $currentDate->dayOfWeek; $i++)
                                    <div class="calendar-day empty"></div>
                                @endfor

                                <!-- Calendar days -->
                                @while($currentDate <= $endOfMonth)
                                    @php
                                        $dateKey = $currentDate->format('Y-m-d');
                                        $schedule = $schedules->get($dateKey);
                                        $isInRange = $currentDate >= $startDate && $currentDate <= $endDate;
                                    @endphp
                                    @php
                                        $isPast = $currentDate < now()->startOfDay();
                                    @endphp
                                    <div class="calendar-day {{ $isInRange ? 'active' : 'inactive' }} {{ $isPast ? 'past' : '' }}">
                                        <div class="day-number">{{ $currentDate->format('d') }}</div>
                                        @if($isInRange)
                                            @if($isPast)
                                                @if($schedule)
                                                    <div class="schedule-display">
                                                        <div class="shift-time">In: {{ $schedule->shift_in ? date('g:i A', strtotime($schedule->shift_in)) : 'N/A' }}</div>
                                                        <div class="shift-time">Out: {{ $schedule->shift_out ? date('g:i A', strtotime($schedule->shift_out)) : 'N/A' }}</div>
                                                    </div>
                                                @endif
                                            @else
                                                <input type="hidden" name="schedules[{{ $index }}][date]" value="{{ $dateKey }}">
                                                <div class="schedule-inputs">
                                                    <select class="form-control form-control-sm mb-1 shift-in-input"
                                                            name="schedules[{{ $index }}][shift_in]">
                                                        <option value=""> Select Time </option>
                                                        @for($hour = 0; $hour < 24; $hour++)
                                                            @for($minute = 0; $minute < 60; $minute += 30)
                                                                @php
                                                                    $timeValue = sprintf('%02d:%02d', $hour, $minute);
                                                                    $displayTime = date('g:i A', strtotime($timeValue));
                                                                @endphp
                                                                <option value="{{ $timeValue }}" {{ $schedule && $schedule->shift_in && date('H:i', strtotime($schedule->shift_in)) == $timeValue ? 'selected' : '' }}>
                                                                    {{ $displayTime }}
                                                                </option>
                                                            @endfor
                                                        @endfor
                                                    </select>
                                                    <select class="form-control form-control-sm shift-out-input"
                                                            name="schedules[{{ $index }}][shift_out]">
                                                        <option value=""> Select Time </option>
                                                        @for($hour = 0; $hour < 24; $hour++)
                                                            @for($minute = 0; $minute < 60; $minute += 30)
                                                                @php
                                                                    $timeValue = sprintf('%02d:%02d', $hour, $minute);
                                                                    $displayTime = date('g:i A', strtotime($timeValue));
                                                                @endphp
                                                                <option value="{{ $timeValue }}" {{ $schedule && $schedule->shift_out && date('H:i', strtotime($schedule->shift_out)) == $timeValue ? 'selected' : '' }}>
                                                                    {{ $displayTime }}
                                                                </option>
                                                            @endfor
                                                        @endfor
                                                    </select>
                                                </div>
                                                @if($schedule)
                                                    <button type="button" class="btn btn-xs btn-outline-danger remove-schedule mt-1"
                                                            data-date="{{ $dateKey }}">
                                                        ×
                                                    </button>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                    @php
                                        $currentDate->addDay();
                                        if($isInRange) $index++;
                                    @endphp
                                @endwhile
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-secondary" onclick="clearAllSchedules()">Clear All</button>
                            <button type="submit" class="btn btn-primary">Save Schedules</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.calendar-container {
    max-width: 800px;
    margin: 0 auto;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
}

.calendar-day-header {
    background: #e9ecef;
    padding: 10px;
    text-align: center;
    font-weight: bold;
    color: #495057;
    border-bottom: 1px solid #dee2e6;
}

.calendar-day {
    background: white;
    min-height: 120px;
    padding: 8px;
    border: 1px solid #dee2e6;
    position: relative;
}

.calendar-day.empty {
    background: #f8f9fa;
}

.calendar-day.inactive {
    background: #f8f9fa;
    opacity: 0.6;
}

.calendar-day.active {
    background: white;
}

.day-number {
    font-weight: bold;
    margin-bottom: 5px;
    color: #495057;
}

.schedule-inputs {
    font-size: 0.75rem;
}

.schedule-inputs input {
    padding: 2px 4px;
    font-size: 0.75rem;
    height: 24px;
}

.shift-in-input {
    background-color: #28a745 !important;
    border-color: #28a745 !important;
    color: white !important;
}

.shift-out-input {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
    color: white !important;
}

.btn-xs {
    padding: 2px 6px;
    font-size: 0.75rem;
    line-height: 1;
}

.calendar-day.past {
    background: #e9ecef !important;
    opacity: 0.7;
}

.schedule-display {
    font-size: 0.75rem;
    color: #495057;
}

.shift-time {
    margin-bottom: 2px;
    font-weight: 500;
}
</style>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: '{{ session('success') }}',
    timer: 3000,
    showConfirmButton: false
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '{{ session('error') }}',
    timer: 3000,
    showConfirmButton: false
});
</script>
@endif

<script>
function clearAllSchedules() {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Are you sure you want to clear all schedules for this guard?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, clear all!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.querySelectorAll('select.shift-in-input').forEach(select => {
                select.value = '';
            });
            document.querySelectorAll('select.shift-out-input').forEach(select => {
                select.value = '';
            });
            // Submit the form to save changes
            document.querySelector('form').submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Handle remove schedule buttons
    document.querySelectorAll('.remove-schedule').forEach(button => {
        button.addEventListener('click', function() {
            const date = this.getAttribute('data-date');
            const dayElement = this.closest('.calendar-day');
            const shiftInInput = dayElement.querySelector('.shift-in-input');
            const shiftOutInput = dayElement.querySelector('.shift-out-input');

            Swal.fire({
                title: 'Are you sure?',
                text: 'Remove schedule for ' + date + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (shiftInInput) shiftInInput.value = '';
                    if (shiftOutInput) shiftOutInput.value = '';
                    // Submit the form to save changes
                    dayElement.closest('form').submit();
                }
            });
        });
    });

    // Auto-generate shift out when shift in is selected
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('shift-in-input')) {
            const shiftInValue = e.target.value;
            if (shiftInValue) {
                const [hours, minutes] = shiftInValue.split(':').map(Number);
                const shiftInDate = new Date();
                shiftInDate.setHours(hours, minutes, 0, 0);

                // Add 12 hours
                shiftInDate.setHours(shiftInDate.getHours() + 12);

                const shiftOutHours = shiftInDate.getHours().toString().padStart(2, '0');
                const shiftOutMinutes = shiftInDate.getMinutes().toString().padStart(2, '0');
                const shiftOutValue = `${shiftOutHours}:${shiftOutMinutes}`;

                const dayElement = e.target.closest('.calendar-day');
                const shiftOutInput = dayElement.querySelector('.shift-out-input');
                if (shiftOutInput) {
                    shiftOutInput.value = shiftOutValue;
                }
            }
        }
    });

    // Month navigation is now handled via links above
});
</script>
@endsection
