<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

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

        if ($request->has('guard_id')) {
            $guard = Employee::find($request->guard_id);
        }

        return view('Security.DeployGuard', compact('guard', 'headGuards'));
    }

    /**
     * Show deploy form for a specific guard (from List of Guards)
     */
    public function showDeployForm($id)
    {
        $guard = Employee::findOrFail($id);
        $headGuards = Employee::where('position', 'Head Guard')->get();

        return view('Security.DeployGuard', compact('guard', 'headGuards'));
    }

    /**
     * Store deployment information
     */
    public function storeDeployment(Request $request, $id)
    {
        $guard = Employee::findOrFail($id);

        $request->validate([
            'time_in' => 'required',
            'time_out' => 'required',
            'designation' => 'required',
            'assigned_head_guard_id' => 'required|exists:employees,id',
        ]);

        $guard->shift_in = $request->time_in;
        $guard->shift_out = $request->time_out;
        $guard->designation = $request->designation;

        // Head Guard auto-assigns to self
        if ($guard->position === 'Head Guard') {
            $guard->assigned_head_guard_id = $guard->id;
        } else {
            $guard->assigned_head_guard_id = $request->assigned_head_guard_id;
        }

        $guard->deployment_status = 'Deployed';
        $guard->status = 'Active';
        $guard->save();

        return redirect()->route('security.list')
                         ->with('success', 'Guard deployed successfully!');
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
}
