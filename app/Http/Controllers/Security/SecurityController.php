<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Deployment;
use App\Models\Schedule;
use App\Models\RegisteredUsers;
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
        $clients = RegisteredUsers::query()
            ->where('role', 'client')
            ->orderBy('fullname')
            ->get();

        $deployments = Deployment::with(['employee', 'headGuard'])
                                ->orderBy('deployment_date', 'desc')
                                ->get();


        if ($request->has('guard_id')) {
            $guard = Employee::find($request->guard_id);
        }

        return view('Security.DeployGuard', compact('guard', 'headGuards', 'clients', 'deployments'));
    }

    /**
     * Show deploy form for a specific guard (from List of Guards)
     */
    public function showDeployForm(Request $request, $id)
    {
        $guard = Employee::findOrFail($id);
        $headGuards = Employee::where('position', 'Head Guard')->get();
        $clients = RegisteredUsers::query()
            ->where('role', 'client')
            ->orderBy('fullname')
            ->get();

        // If this is loaded into the modal via AJAX, return ONLY the modal body (no layout/sidebar)
        if ($request->ajax()) {
            return view('Security.partials.DeployGuardForm', compact('guard', 'headGuards', 'clients'));
        }





        $deployments = Deployment::with(['employee', 'headGuard'])
            ->where('employee_id', $id)
            ->orderBy('deployment_date', 'desc')
            ->get();

        return view('Security.DeployGuard', compact('guard', 'headGuards', 'clients', 'deployments'));
    }


    /**
     * Store deployment information
     */
    public function storeDeployment(Request $request, $id)
    {
        $guard = Employee::findOrFail($id);

        $request->validate([
            'deployment_date' => 'required|date|after_or_equal:today|before_or_equal:' . now()->endOfYear()->addYear()->format('Y-m-d'),
            'client_id' => 'required|exists:registered_users,id',
            'assigned_head_guard_id' => 'required|exists:employees,id',
        ]);

        // Exclusivity rule: guard can only have one ACTIVE deployment at a time.
        // If it already has an active deployment for a different client, deactivate it.
        $existingActive = Deployment::where('employee_id', $id)
            ->where('status', 'active')
            ->first();

        if ($existingActive && $existingActive->client_id != $request->client_id) {
            $existingActive->update(['status' => 'completed']);
        }

        Deployment::create([
            'client_id' => $request->client_id,
            'employee_id' => $id,
            'deployment_date' => $request->deployment_date,
            'assigned_head_guard_id' => $request->assigned_head_guard_id,
            'status' => 'active',
            'created_by' => Auth::id() ?? 1,
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

        // Client exclusivity: deny if this guard is not actively deployed to the client.
        $userRole = Auth::user()->getRoleNames()->first();
        if ($userRole === 'client') {
            $clientId = Auth::id();
            $ownsGuard = Deployment::where('employee_id', $guardId)
                ->where('client_id', $clientId)
                ->where('status', 'active')
                ->exists();

            abort_unless($ownsGuard, 404);
        }


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
        $clients = RegisteredUsers::query()
            ->where('role', 'client')
            ->orderBy('fullname')
            ->get();

        // Guards shown on the deploy dashboard
        $guards = Employee::query()
            ->whereIn('position', ['Security Guard', 'Head Guard'])
            ->get();

        // Status counters (used by Security/Deploy dashboard)
        $activeDeployments = Deployment::where('status', 'active')->count();
        $pendingDeployments = Deployment::where('status', 'pending')->count();
        $completedDeployments = Deployment::where('status', 'completed')->count();
        $cancelledDeployments = Deployment::where('status', 'cancelled')->count();

        // Pass deployments to the view because Security/Deploy.blade.php expects $deployments.
        // If logged-in user is a client, only show active deployments for that client.
        $userRole = Auth::user()->getRoleNames()->first();

        $deploymentsQuery = Deployment::with(['employee', 'headGuard'])
            ->orderBy('deployment_date', 'desc');

        if ($userRole === 'client') {
            $clientId = Auth::id();
            $deploymentsQuery->where('client_id', $clientId);
        }

        $deployments = $deploymentsQuery->get();

        return view('Security.Deploy', compact(
            'guards',
            'clients',
            'deployments',
            'activeDeployments',
            'pendingDeployments',
            'completedDeployments',
            'cancelledDeployments'
        ));
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
        // If user is a client, only show guards owned by that client.
        $userRole = Auth::user()->getRoleNames()->first();

        $base = Employee::with(['assignedHeadGuard', 'schedules' => function ($query) {
            $query->orderBy('schedule_date');
        }])
        ->where('position', 'Security Guard')
        ->where('status', 'Active')
        ->whereNull('deleted_at');

        if ($userRole === 'client') {
            $clientId = Auth::id();

            $base->whereIn('id', function ($q) use ($clientId) {
                $q->select('employee_id')
                  ->from('deployments')
                  ->where('client_id', $clientId)
                  ->where('status', 'active');
            });
        }

        $guards = $base->get();

        return view('Security.ViewAllSchedules', compact('guards'));
    }
}
