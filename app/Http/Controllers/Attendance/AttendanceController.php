<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Schedule;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('employee_number', $user->login_id)->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee record not found.');
        }

        // Week range (Mon–Sun)
        $weekStart = $request->get('week', Carbon::now('Asia/Manila')->startOfWeek()->format('Y-m-d'));
        $weekStart = Carbon::parse($weekStart, 'Asia/Manila')->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();

        // Fetch schedules & attendances
        $schedules = Schedule::where('guard_id', $employee->id)
            ->whereBetween('schedule_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->get()
            ->keyBy(fn($item) => Carbon::parse($item->schedule_date)->format('Y-m-d'));

        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereBetween('attendance_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->get()
            ->keyBy(fn($item) => Carbon::parse($item->attendance_date)->format('Y-m-d'));

        // Build weekly data
        $weeklyData = [];
        for ($date = $weekStart->copy(); $date <= $weekEnd; $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            $schedule = $schedules->get($dateStr);
            $attendance = $attendances->get($dateStr);

            $totalHoursDecimal = null;
            $totalHoursFormatted = null;

            if ($attendance?->shift_in_time && $attendance?->shift_out_time) {
                $shiftIn = Carbon::parse($attendance->shift_in_time, 'Asia/Manila');
                $shiftOut = Carbon::parse($attendance->shift_out_time, 'Asia/Manila');
                $diffMinutes = $shiftIn->diffInMinutes($shiftOut);

                // Decimal hours for DB
                $totalHoursDecimal = round($diffMinutes / 60, 2);

                // Human-readable format
                $hours = floor($diffMinutes / 60);
                $minutes = $diffMinutes % 60;
                $totalHoursFormatted = ($hours > 0 ? $hours . ' hour' . ($hours > 1 ? 's' : '') : '0 hour') .
                    ($minutes > 0 ? ' and ' . $minutes . ' minute' . ($minutes > 1 ? 's' : '') : '');
            }

            // Determine status
            $statusLabel = 'No Shift';
            $statusClass = 'light text-dark';

            if ($attendance?->shift_in_time && $attendance?->shift_out_time) {
                if ($schedule) {
                    $scheduledShiftIn = Carbon::parse($dateStr . ' ' . $schedule->shift_in, 'Asia/Manila');
                    $scheduledShiftOut = Carbon::parse($dateStr . ' ' . $schedule->shift_out, 'Asia/Manila');
                    $actualShiftIn = Carbon::parse($attendance->shift_in_time, 'Asia/Manila');
                    $actualShiftOut = Carbon::parse($attendance->shift_out_time, 'Asia/Manila');

                    if ($actualShiftIn->gt($scheduledShiftIn)) {
                        $statusLabel = 'Late';
                        $statusClass = 'danger';
                    } elseif ($actualShiftOut->lt($scheduledShiftOut)) {
                        $statusLabel = 'Undertime';
                        $statusClass = 'warning text-dark';
                    } else {
                        $statusLabel = 'Completed';
                        $statusClass = 'success';
                    }
                } else {
                    $statusLabel = 'Completed';
                    $statusClass = 'success';
                }
            } elseif ($attendance?->shift_in_time) {
                $statusLabel = 'In Progress';
                $statusClass = 'warning text-dark';
            } elseif ($schedule) {
                $statusLabel = 'Scheduled';
                $statusClass = 'secondary';
            }

            $weeklyData[] = [
                'date' => $date->format('l, M j'),
                'schedule' => $schedule
                    ? date('g:i A', strtotime($schedule->shift_in)) . ' - ' . date('g:i A', strtotime($schedule->shift_out))
                    : 'No Shift',
                'shift_in' => $attendance?->shift_in_time ? Carbon::parse($attendance->shift_in_time, 'Asia/Manila')->format('h:i A') : null,
                'shift_out' => $attendance?->shift_out_time ? Carbon::parse($attendance->shift_out_time, 'Asia/Manila')->format('h:i A') : null,
                'total_hours' => $totalHoursDecimal,
                'total_hours_display' => $totalHoursFormatted,
                'is_today' => $date->isToday(),
                'status_label' => $statusLabel,
                'status_class' => $statusClass,
            ];
        }

        // Today's schedule and attendance
        $today = Carbon::today('Asia/Manila');
        $todaySchedule = Schedule::where('guard_id', $employee->id)->whereDate('schedule_date', $today)->first();
        $todayAttendance = Attendance::where('employee_id', $employee->id)->whereDate('attendance_date', $today)->first();

        $todayTotalHoursDecimal = null;
        $todayTotalHoursFormatted = null;
        if ($todayAttendance?->shift_in_time && $todayAttendance?->shift_out_time) {
            $shiftIn = Carbon::parse($todayAttendance->shift_in_time, 'Asia/Manila');
            $shiftOut = Carbon::parse($todayAttendance->shift_out_time, 'Asia/Manila');
            $diffMinutes = $shiftIn->diffInMinutes($shiftOut);

            $todayTotalHoursDecimal = round($diffMinutes / 60, 2);

            $hours = floor($diffMinutes / 60);
            $minutes = $diffMinutes % 60;
            $todayTotalHoursFormatted = ($hours > 0 ? $hours . ' hour' . ($hours > 1 ? 's' : '') : '0 hour') .
                ($minutes > 0 ? ' and ' . $minutes . ' minute' . ($minutes > 1 ? 's' : '') : '');
        }

        $todayData = [
            'schedule' => $todaySchedule
                ? date('g:i A', strtotime($todaySchedule->shift_in)) . ' - ' . date('g:i A', strtotime($todaySchedule->shift_out))
                : 'No Shift',
            'shift_in' => $todayAttendance?->shift_in_time ? Carbon::parse($todayAttendance->shift_in_time, 'Asia/Manila')->format('h:i A') : null,
            'shift_out' => $todayAttendance?->shift_out_time ? Carbon::parse($todayAttendance->shift_out_time, 'Asia/Manila')->format('h:i A') : null,
            'total_hours' => $todayTotalHoursDecimal,
            'total_hours_display' => $todayTotalHoursFormatted,
            'can_shift_in' => !$todayAttendance || !$todayAttendance->shift_in_time,
            'can_shift_out' => $todayAttendance && $todayAttendance->shift_in_time && !$todayAttendance->shift_out_time,
        ];

        return view('Attendance.my-shift', compact('weeklyData', 'todayData', 'weekStart'));
    }

    public function shiftIn(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('employee_number', $user->login_id)->first();

        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee record not found.']);
        }

        $today = Carbon::today('Asia/Manila');

        $attendance = Attendance::firstOrCreate(
            ['employee_id' => $employee->id, 'attendance_date' => $today],
            ['shift_in_time' => now('Asia/Manila')->format('H:i:s')]
        );

        if (!$attendance->wasRecentlyCreated && !$attendance->shift_in_time) {
            $attendance->update(['shift_in_time' => now('Asia/Manila')->format('H:i:s')]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Shift In recorded successfully.',
            'time' => now('Asia/Manila')->format('h:i A')
        ]);
    }

    public function shiftOut(Request $request)
    {
        try {
            $user = Auth::user();
            $employee = Employee::where('employee_number', $user->login_id)->firstOrFail();

            $today = Carbon::today('Asia/Manila');

            $attendance = Attendance::firstOrCreate(
                ['employee_id' => $employee->id, 'attendance_date' => $today],
                ['shift_in_time' => null]
            );

            if (!$attendance->shift_in_time) {
                return response()->json([
                    'success' => false,
                    'message' => 'No shift in record found.'
                ]);
            }

            $schedule = Schedule::where('guard_id', $employee->id)->whereDate('schedule_date', $today)->first();
            $currentTime = now('Asia/Manila');
            $force = $request->boolean('force', false);

            if ($schedule && $currentTime->lt(Carbon::parse($today->format('Y-m-d') . ' ' . $schedule->shift_out, 'Asia/Manila')) && !$force) {
                $scheduledShiftOut = Carbon::parse($today->format('Y-m-d') . ' ' . $schedule->shift_out, 'Asia/Manila');
                $diffMinutes = $currentTime->diffInMinutes($scheduledShiftOut);
                $hours = floor($diffMinutes / 60);
                $minutes = $diffMinutes % 60;
                $formattedDiff = ($hours > 0 ? "{$hours} hour" . ($hours > 1 ? 's' : '') : '') .
                    ($minutes > 0 ? ($hours > 0 ? ' and ' : '') . "{$minutes} minute" . ($minutes > 1 ? 's' : '') : '');

                return response()->json([
                    'success' => false,
                    'warning' => true,
                    'message' => "You are shifting out {$formattedDiff} earlier than your scheduled shift out ({$scheduledShiftOut->format('g:i A')}).",
                    'allow_force' => true
                ]);
            }

            // Calculate total hours as decimal
            $shiftInTime = Carbon::parse($attendance->shift_in_time, 'Asia/Manila');
            $diffMinutes = $shiftInTime->diffInMinutes($currentTime);
            $totalHoursDecimal = round($diffMinutes / 60, 2);

            // Human-readable format
            $hours = floor($diffMinutes / 60);
            $minutes = $diffMinutes % 60;
            $totalHoursFormatted = ($hours > 0 ? $hours . ' hour' . ($hours > 1 ? 's' : '') : '0 hour') .
                ($minutes > 0 ? ' and ' . $minutes . ' minute' . ($minutes > 1 ? 's' : '') : '');

            $attendance->update([
                'shift_out_time' => $currentTime->format('H:i:s'),
                'total_hours' => $totalHoursDecimal
            ]);

            return response()->json([
                'success' => true,
                'warning' => $force,
                'message' => $force ? 'Shift Out recorded successfully (forced early).' : 'Shift Out recorded successfully.',
                'time' => $currentTime->format('h:i A'),
                'total_hours' => $totalHoursDecimal,
                'total_hours_display' => $totalHoursFormatted
            ]);

        } catch (\Throwable $e) {
            \Log::error('ShiftOut error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error while recording shift out. Please contact admin.'
            ], 500);
        }
    }

    public function forceShiftOut(Request $request)
    {
        $request->merge(['force' => true]);
        return $this->shiftOut($request);
    }

    public function kpi(Request $request)
    {
        // Get guard employee IDs (security-guard and head-guard roles)
        $guardEmployeeIds = Employee::whereHas('registeredUser', function ($query) {
            $query->whereIn('role', ['security-guard', 'head-guard'])
                  ->whereIn('account_status', ['approved', 'active']);
        })->pluck('id');

        // Total Guards
        $totalGuards = $guardEmployeeIds->count();

        // Total Shifts (scheduled shifts for guards)
        $totalShifts = Schedule::whereIn('guard_id', $guardEmployeeIds)->count();

        // Completed Shifts (attendances with both in and out times)
        $completedShifts = Attendance::whereIn('employee_id', $guardEmployeeIds)
            ->whereNotNull('shift_in_time')
            ->whereNotNull('shift_out_time')
            ->count();

        // Late and Undertime calculations
        $lateShifts = 0;
        $undertimeShifts = 0;

        $attendancesWithSchedules = Attendance::whereIn('employee_id', $guardEmployeeIds)
            ->whereNotNull('shift_in_time')
            ->whereNotNull('shift_out_time')
            ->with('employee.schedules')
            ->get();

        foreach ($attendancesWithSchedules as $attendance) {
            $schedule = $attendance->employee->schedules->where('schedule_date', $attendance->attendance_date)->first();
            if ($schedule) {
                $scheduledIn = Carbon::parse($schedule->schedule_date->format('Y-m-d') . ' ' . $schedule->shift_in, 'Asia/Manila');
                $scheduledOut = Carbon::parse($schedule->schedule_date->format('Y-m-d') . ' ' . $schedule->shift_out, 'Asia/Manila');
                $actualIn = Carbon::parse($attendance->shift_in_time, 'Asia/Manila');
                $actualOut = Carbon::parse($attendance->shift_out_time, 'Asia/Manila');

                if ($actualIn->gt($scheduledIn)) {
                    $lateShifts++;
                }
                if ($actualOut->lt($scheduledOut)) {
                    $undertimeShifts++;
                }
            }
        }

        // Average Hours (from completed attendances)
        $averageHours = Attendance::whereIn('employee_id', $guardEmployeeIds)
            ->whereNotNull('shift_in_time')
            ->whereNotNull('shift_out_time')
            ->where('total_hours', '>', 0)
            ->avg('total_hours') ?? 0;

        $averageHours = round($averageHours, 2);

        $kpiData = [
            'total_guards' => $totalGuards,
            'total_shifts' => $totalShifts,
            'completed_shifts' => $completedShifts,
            'late_shifts' => $lateShifts,
            'undertime_shifts' => $undertimeShifts,
            'average_hours' => $averageHours,
        ];

        return view('Attendance.kpi', compact('kpiData'));
    }
}
