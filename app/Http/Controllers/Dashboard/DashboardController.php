<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RegisteredUsers;
use App\Models\Attendance;
use App\Models\IncidentReport;
use App\Models\Leave;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Only show KPI data for super-admin, admin, and hr-officer
        if ($user->hasRole(['super-admin', 'admin', 'hr-officer'])) {
            $kpiData = $this->calculateKPIs();
            return view('Dashboard.dashboard', compact('kpiData'));
        }

        return view('Dashboard.dashboard');
    }

    private function calculateKPIs()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Get all security guards and head guards
        $securityGuards = RegisteredUsers::whereIn('role', ['security-guard', 'head-guard'])
            ->where('account_status', 'approved')
            ->with('employee')
            ->get();

        $kpiData = [];

        foreach ($securityGuards as $guard) {
            $employee = $guard->employee;

            if (!$employee) continue;

            // Attendance KPIs
            $monthlyAttendance = Attendance::where('employee_id', $employee->id)
                ->whereMonth('attendance_date', $currentMonth)
                ->whereYear('attendance_date', $currentYear)
                ->get();

            $totalDaysWorked = $monthlyAttendance->count();
            $totalHoursWorked = $monthlyAttendance->sum('total_hours');
            $averageHoursPerDay = $totalDaysWorked > 0 ? $totalHoursWorked / $totalDaysWorked : 0;

            // Calculate attendance rate (assuming 30 working days in month)
            $attendanceRate = min(($totalDaysWorked / 30) * 100, 100);

            // Leave KPIs
            $monthlyLeaves = Leave::where('user_id', $guard->id)
                ->whereIn('status', ['approved', 'processed'])
                ->whereMonth('date_from', $currentMonth)
                ->whereYear('date_from', $currentYear)
                ->get();

            $totalLeaveDays = 0;
            foreach ($monthlyLeaves as $leave) {
                $start = Carbon::parse($leave->date_from);
                $end = Carbon::parse($leave->date_to);
                $totalLeaveDays += $start->diffInDays($end) + 1;
            }

            // Incident Reports KPIs (reports where guard was involved)
            $monthlyIncidents = IncidentReport::whereHas('parties', function($query) use ($guard) {
                $query->where('name', $guard->fullname);
            })
            ->whereMonth('date_of_incident', $currentMonth)
            ->whereYear('date_of_incident', $currentYear)
            ->count();

            // Calculate KPI Score (weighted average)
            // Weights: Attendance (40%), Leave Usage (30%), Incident Response (30%)
            $attendanceScore = $attendanceRate;
            $leaveScore = max(0, 100 - ($totalLeaveDays * 3.33)); // Max 30 days leave allowance
            $incidentScore = max(0, 100 - ($monthlyIncidents * 20)); // Penalty per incident

            $overallKPIScore = ($attendanceScore * 0.4) + ($leaveScore * 0.3) + ($incidentScore * 0.3);

            $kpiData[] = [
                'guard_name' => $guard->fullname,
                'guard_role' => ucfirst(str_replace('-', ' ', $guard->role)),
                'attendance_rate' => round($attendanceRate, 1),
                'total_hours_worked' => round($totalHoursWorked, 1),
                'average_hours_per_day' => round($averageHoursPerDay, 1),
                'leave_days_taken' => $totalLeaveDays,
                'incidents_reported' => $monthlyIncidents,
                'kpi_score' => round($overallKPIScore, 1),
                'performance_rating' => $this->getPerformanceRating($overallKPIScore)
            ];
        }

        // Sort by KPI score descending
        usort($kpiData, function($a, $b) {
            return $b['kpi_score'] <=> $a['kpi_score'];
        });

        return $kpiData;
    }

    private function getPerformanceRating($score)
    {
        if ($score >= 90) return 'Excellent';
        if ($score >= 80) return 'Very Good';
        if ($score >= 70) return 'Good';
        if ($score >= 60) return 'Satisfactory';
        return 'Needs Improvement';
    }
}
