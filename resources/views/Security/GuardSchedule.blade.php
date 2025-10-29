@extends('layouts.vuexy')

@section('title', 'Guard Schedule - ' . $guard->full_name)

@push('page-styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endpush

@push('page-scripts')
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<script>
$(document).ready(function() {
    // Auto-set shift-out time when shift-in changes
    $('.shift-in-input').on('change', function() {
        const shiftInValue = $(this).val();
        const shiftOutSelect = $(this).closest('.schedule-inputs').find('.shift-out-input');

        if (shiftInValue) {
            // Parse the time and add 12 hours
            const [hours, minutes] = shiftInValue.split(':').map(Number);
            let newHours = hours + 12;
            if (newHours >= 24) {
                newHours -= 24;
            }
            const newTime = `${newHours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;

            // Set the shift-out value
            shiftOutSelect.val(newTime);
        } else {
            // Clear shift-out if shift-in is cleared
            shiftOutSelect.val('');
        }
    });

    // Trigger change event on page load for existing schedules
    $('.shift-in-input').each(function() {
        if ($(this).val()) {
            $(this).trigger('change');
        }
    });

    // Enable shift-out fields before form submission
    $('form').on('submit', function() {
        $('.shift-out-input').prop('disabled', false);
    });

    $('.remove-schedule-btn').on('click', function() {
        const date = $(this).data('date');
        const guardId = {{ $guard->id }};

        Swal.fire({
            title: 'Remove Schedule?',
            text: 'Are you sure you want to remove the schedule for ' + date + '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create a form to submit the removal
                const form = $('<form>', {
                    method: 'POST',
                    action: '{{ route("guard-scheduling.assign.store", $guard->id) }}'
                });

                form.append('<input type="hidden" name="_token" value="{{ csrf_token() }}">');
                form.append('<input type="hidden" name="remove_date" value="' + date + '">');

                $('body').append(form);
                form.submit();
            }
        });
    });
});
</script>
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
                    @php
                        $canEdit = Auth::user()->hasRole(['admin', 'hr-officer']);
                    @endphp

                    @if($canEdit)
                        <form method="POST" action="{{ route('guard-scheduling.assign.store', $guard->id) }}">
                            @csrf
                    @endif



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
                                                @if($canEdit)
                                                    <div class="schedule-inputs">
                                                        <input type="hidden" name="schedules[{{ $index }}][date]" value="{{ $dateKey }}">
                                                        <select name="schedules[{{ $index }}][shift_in]" class="form-control form-control-sm shift-in-input mb-1">
                                                            <option value="">Select Shift In</option>
                                                            @for($hour = 0; $hour < 24; $hour++)
                                                                @for($minute = 0; $minute < 60; $minute += 30)
                                                                    @php
                                                                        $timeValue = sprintf('%02d:%02d', $hour, $minute);
                                                                        $timeDisplay = date('g:i A', strtotime($timeValue));
                                                                    @endphp
                                                                    <option value="{{ $timeValue }}" {{ $schedule && date('H:i', strtotime($schedule->shift_in)) == $timeValue ? 'selected' : '' }}>
                                                                        {{ $timeDisplay }}
                                                                    </option>
                                                                @endfor
                                                            @endfor
                                                        </select>
                                                        <select name="schedules[{{ $index }}][shift_out]" class="form-control form-control-sm shift-out-input mb-1" disabled>
                                                            <option value="">Select Shift Out</option>
                                                            @for($hour = 0; $hour < 24; $hour++)
                                                                @for($minute = 0; $minute < 60; $minute += 30)
                                                                    @php
                                                                        $timeValue = sprintf('%02d:%02d', $hour, $minute);
                                                                        $timeDisplay = date('g:i A', strtotime($timeValue));
                                                                    @endphp
                                                                    <option value="{{ $timeValue }}" {{ $schedule && date('H:i', strtotime($schedule->shift_out)) == $timeValue ? 'selected' : '' }}>
                                                                        {{ $timeDisplay }}
                                                                    </option>
                                                                @endfor
                                                            @endfor
                                                        </select>
                                                        @if($schedule)
                                                            <button type="button" class="btn btn-sm btn-outline-danger remove-schedule-btn" data-date="{{ $dateKey }}">
                                                                <i class="bx bx-trash"></i> Remove
                                                            </button>
                                                        @endif
                                                    </div>
                                                @else
                                                    @if($schedule)
                                                        <div class="schedule-display">
                                                            <div class="shift-time">In: {{ $schedule->shift_in ? date('g:i A', strtotime($schedule->shift_in)) : 'N/A' }}</div>
                                                            <div class="shift-time">Out: {{ $schedule->shift_out ? date('g:i A', strtotime($schedule->shift_out)) : 'N/A' }}</div>
                                                        </div>
                                                    @endif
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

                    @if($canEdit)
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Save Schedule Changes</button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.calendar-container {
    max-width: 1000px;
    margin: 0 auto;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
    background: rgba(255,255,255,0.1);
    border: 2px solid rgba(255,255,255,0.2);
    border-radius: 12px;
    overflow: hidden;
    backdrop-filter: blur(10px);
}

.calendar-day-header {
    background: rgba(255,255,255,0.2);
    padding: 15px;
    text-align: center;
    font-weight: bold;
    color: white;
    border-bottom: 1px solid rgba(255,255,255,0.3);
    font-size: 1.1rem;
}

.calendar-day {
    background: rgba(255,255,255,0.9);
    min-height: 140px;
    padding: 12px;
    border: 1px solid rgba(255,255,255,0.5);
    position: relative;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.calendar-day:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.calendar-day.empty {
    background: rgba(255,255,255,0.3);
}

.calendar-day.inactive {
    background: rgba(255,255,255,0.4);
    opacity: 0.7;
}

.calendar-day.active {
    background: rgba(255,255,255,0.95);
}

.day-number {
    font-weight: bold;
    margin-bottom: 8px;
    color: #495057;
    font-size: 1.2rem;
}

.schedule-inputs {
    font-size: 0.8rem;
}

.schedule-inputs select {
    padding: 4px 8px;
    font-size: 0.8rem;
    height: 32px;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    margin-bottom: 4px;
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

.remove-schedule-btn {
    width: 100%;
    font-size: 0.7rem;
    padding: 4px 8px;
    margin-top: 4px;
}

.calendar-day.past {
    background: rgba(176,196,222,0.8) !important;
    opacity: 0.8;
}

.schedule-display {
    font-size: 0.8rem;
    color: #495057;
    background: rgba(40,167,69,0.1);
    padding: 6px;
    border-radius: 6px;
    border-left: 3px solid #28a745;
}

.shift-time {
    margin-bottom: 3px;
    font-weight: 600;
    color: #28a745;
}

.calendar-header {
    background: rgba(255,255,255,0.9);
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.calendar-header h4 {
    color: #495057;
    font-weight: 700;
    margin: 0;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}
</style>


@endsection
