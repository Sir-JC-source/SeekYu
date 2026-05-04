<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Deployment;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SecurityController extends Controller
{
    /**
     * Show list of Security Guards and Head Guards
     */
    public function index()
    {
        $guards = Employee::with('assignedHeadGuard')
                          ->whereIn('position', ['Security Guard', 'Head Guard'])
                          ->get();

        return view('Security.GuardsList', compact('guards'));
    }

    /**
     * Show deployments page (empty form if accessed via sidebar)
     * Optionally pre-fill a guard if guard_id is passed as query param
     */
    public function deployments(Request $request)
    {
        $guard = null;
        $headGuards = Employee::where('position', 'Head Guard')->get();
        $deployments = Deployment::with(['employee', 'headGuard'])
                                ->orderBy('deployment_date', 'desc')
                                ->orderBy('shift_in', 'asc')
                                ->get();

        if ($request->has('guard_id')) {
            $guard = Employee::find($request->guard_id);
        }

        return view('Security.DeployGuard', compact('guard', 'headGuards', 'deployments'));
    }

    /**
     * Show deploy form for a specific guard (from List of Guards)
     */
    public function showDeployForm($id)
    {
        $guard = Employee::findOrFail($id);
        $headGuards = Employee::where('position', 'Head Guard')->get();
        $deployments = Deployment::with(['employee', 'headGuard'])
                                ->where('employee_id', $id)
                                ->orderBy('deployment_date', 'desc')
                                ->get();

        return view('Security.DeployGuard', compact('guard', 'headGuards', 'deployments'));
    }

    /**
     * Store deployment information
     */
    public function storeDeployment(Request $request, $id)
    {
        $guard = Employee::findOrFail($id);

        $request->validate([
'deployment_date' => 'required|date|after_or_equal:today|before_or_equal:' . now()->endOfYear()->addYear()->format('Y-m-d'),
            'shift_in' => 'required|date_format:H:i',
            'shift_out' => 'required|date_format:H:i|after:shift_in',
            'assigned_head_guard_id' => 'required|exists:employees,id',
        ]);

        // Check for conflicts
        $conflict = Deployment::where('employee_id', $id)
            ->where('deployment_date', $request->deployment_date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('shift_in', [$request->shift_in, $request->shift_out])
                      ->orWhereBetween('shift_out', [$request->shift_in, $request->shift_out])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('shift_in', '<=', $request->shift_in)
                            ->where('shift_out', '>=', $request->shift_out);
                      });
            })
            ->exists();

        if ($conflict) {
            return back()->withInput()->withErrors(['shift_in' => 'This guard is already deployed during this time slot.']);
        }

        Deployment::create([
            'employee_id' => $id,
            'deployment_date' => $request->deployment_date,
            'shift_in' => $request->shift_in,
            'shift_out' => $request->shift_out,
            'assigned_head_guard_id' => $request->assigned_head_guard_id,
            'status' => 'pending',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('security.deployments')
                         ->with('success', 'Guard deployment scheduled successfully!');
    }

    /**
     * Update deployment status
     */
    public function updateDeploymentStatus(Request $request, $deploymentId)
    {
        $deployment = Deployment::findOrFail($deploymentId);

        $request->validate([
            'status' => 'required|in:pending,active,completed,cancelled',
        ]);

        $deployment->update(['status' => $request->status]);

        $statusMessage = ucfirst($request->status);
        return back()->with('success', "Deployment status updated to {$statusMessage}.");
    }

    /**
     * Edit a guard's details
     */
    public function edit($id)
    {
        $guard = Employee::findOrFail($id);
        return view('Security.EditGuard', compact('guard'));
    }

    /**
     * Update guard information
     */
    public function update(Request $request, $id)
    {
        $guard = Employee::findOrFail($id);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'position'  => 'required|string|in:Security Guard,Head Guard',
            'shift_in'  => 'nullable|string|max:50',
            'shift_out' => 'nullable|string|max:50',
            'designation' => 'nullable|string|max:255',
            'assigned_head_guard_id' => 'nullable|exists:employees,id',
        ]);

        $guard->update([
            'full_name' => $request->full_name,
            'position'  => $request->position,
            'shift_in'  => $request->shift_in,
            'shift_out' => $request->shift_out,
            'designation' => $request->designation,
            'assigned_head_guard_id' => $request->assigned_head_guard_id,
        ]);

        return redirect()->route('security.list')
                         ->with('success', 'Guard details updated successfully!');
    }

    /**
     * Make a guard inactive
     */
    public function makeInactive($id)
    {
        $guard = Employee::findOrFail($id);
        $guard->status = 'Inactive';
        $guard->deployment_status = 'Not Deployed';
        $guard->save();

        return redirect()->route('security.list')
                         ->with('success', 'Guard has been made inactive.');
    }

    /**
     * Assign Schedule page - List of guards to select from
     */
    public function assignSchedule()
    {
        $guards = Employee::with('assignedHeadGuard')
                          ->whereIn('position', ['Security Guard', 'Head Guard'])
                          ->where('status', 'Active')
                          ->get();

        return view('Security.AssignSchedule', compact('guards'));
    }

    /**
     * Show calendar for specific guard to assign schedules
     */
    public function showGuardSchedule(Request $request, $guardId)
    {
        $guard = Employee::findOrFail($guardId);

        // Get the month from request, default to current month
        $month = $request->get('month', now()->format('Y-m'));
        $currentMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();

        // Get schedules for the selected month + 2 future months
        $startDate = $currentMonth->copy();
        $endDate = $currentMonth->copy()->addMonths(2)->endOfMonth();

        $schedules = Schedule::where('guard_id', $guardId)
                            ->whereBetween('schedule_date', [$startDate, $endDate])
                            ->get()
                            ->keyBy(function ($schedule) {
                                return $schedule->schedule_date->format('Y-m-d');
                            });

        return view('Security.GuardSchedule', compact('guard', 'schedules', 'startDate', 'endDate'));
    }

    /**
     * Store/update schedules for a guard
     */
    public function storeSchedule(Request $request, $guardId)
    {
        $guard = Employee::findOrFail($guardId);

        // Check if this is a removal request
        if ($request->has('remove_date')) {
            $removeDate = $request->remove_date;
            Schedule::where('guard_id', $guardId)
                   ->where('schedule_date', $removeDate)
                   ->delete();

            return back()->with('success', 'Schedule removed successfully!');
        }

        $request->validate([
            'schedules' => 'required|array',
            'schedules.*.date' => 'required|date',
            'schedules.*.shift_in' => 'nullable|date_format:H:i',
            'schedules.*.shift_out' => 'nullable|date_format:H:i|after:schedules.*.shift_in',
        ]);

        try {
            foreach ($request->schedules as $scheduleData) {
                $date = $scheduleData['date'];
                $shiftIn = $scheduleData['shift_in'] ?? null;
                $shiftOut = $scheduleData['shift_out'] ?? null;

                if ($shiftIn && $shiftOut) {
                    // Create or update schedule
                    Schedule::updateOrCreate(
                        [
                            'guard_id' => $guardId,
                            'schedule_date' => $date,
                        ],
                        [
                            'shift_in' => $shiftIn,
                            'shift_out' => $shiftOut,
                            'created_by' => Auth::check() && \App\Models\User::find(Auth::id()) ? Auth::id() : null,
                            'updated_by' => Auth::check() && \App\Models\User::find(Auth::id()) ? Auth::id() : null,
                        ]
                    );
                } else {
                    // Remove schedule if no shift times provided
                    Schedule::where('guard_id', $guardId)
                           ->where('schedule_date', $date)
                           ->delete();
                }
            }

            return back()->with('success', 'Guard schedule updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save schedule. Please try again.');
        }
    }

    /**
     * Deploy page
     */
    public function deploy()
    {
        $headGuards = Employee::where('position', 'Head Guard')->get();
        $deployments = Deployment::with(['employee', 'headGuard'])
                                ->orderBy('deployment_date', 'desc')
                                ->orderBy('shift_in', 'asc')
                                ->get();
        
        $activeDeployments = Deployment::where('status', 'active')->count();
        $pendingDeployments = Deployment::where('status', 'pending')->count();
        $completedDeployments = Deployment::where('status', 'completed')->count();
        $cancelledDeployments = Deployment::where('status', 'cancelled')->count();

        return view('Security.Deploy', compact('headGuards', 'deployments', 'activeDeployments', 'pendingDeployments', 'completedDeployments', 'cancelledDeployments'));
    }

    /**
     * Guard List page
     */
    public function guardList()
    {
        // Reuse the existing index method for guard list
        return $this->index();
    }

    /**
     * View all Security Guard Schedules
     */
    public function viewAllSchedules()
    {
        $guards = Employee::with(['assignedHeadGuard', 'schedules' => function ($query) {
            $query->orderBy('schedule_date');
        }])
        ->where('position', 'Security Guard')
        ->where('status', 'Active')
        ->whereNull('deleted_at') // Exclude soft-deleted employees
        ->get();

        return view('Security.ViewAllSchedules', compact('guards'));
    }
}
