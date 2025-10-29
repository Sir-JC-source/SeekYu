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
                                                @if($schedule)
                                                    <div class="schedule-display">
                                                        <div class="shift-time">In: {{ $schedule->shift_in ? date('g:i A', strtotime($schedule->shift_in)) : 'N/A' }}</div>
                                                        <div class="shift-time">Out: {{ $schedule->shift_out ? date('g:i A', strtotime($schedule->shift_out)) : 'N/A' }}</div>
                                                    </div>
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

                    </div>
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


@endsection
