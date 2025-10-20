<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Leave;

class LeaveController extends Controller
{
    /**
     * Display all leave requests (admin/HR view).
     */
    public function index()
    {
        $leaves = Leave::orderBy('created_at', 'desc')->get();
        return view('leaves.index', compact('leaves'));
    }

    /**
     * Display pending leave requests.
     */
    public function pending()
    {
        $leaves = Leave::where('status', 'Pending')->orderBy('created_at', 'desc')->get();
        return view('leaves.pending', compact('leaves'));
    }

    /**
     * Display approved leave requests.
     */
    public function accepted()
    {
        $leaves = Leave::where('status', 'Approved')->orderBy('created_at', 'desc')->get();
        return view('leaves.accepted', compact('leaves'));
    }

    /**
     * Display rejected leave requests.
     */
    public function rejected()
    {
        $leaves = Leave::where('status', 'Rejected')->orderBy('created_at', 'desc')->get();
        return view('leaves.rejected', compact('leaves'));
    }

    /**
     * Show leave request form.
     */
    public function create()
    {
        $user = Auth::user();
        $role = $user->role;

        $position = match($role) {
            'admin' => 'Admin',
            'hr-officer' => 'HR Officer',
            'head-guard' => 'Head Guard',
            'security-guard' => 'Security Guard',
            default => '',
        };

        return view('leaves.request', [
            'user' => $user,
            'position' => $position,
        ]);
    }

    /**
     * Store a new leave request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type' => 'required|in:Sick Leave,Vacation Leave',
            'reason' => 'required|string|max:1000',
            'duration' => 'required|in:Whole Shift,Half-Shift Early Out,Half-Shift Late In',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'position' => 'required|string|max:50',
        ]);

        $user = Auth::user();

        Leave::create([
            'requestor' => $user->fullname,
            'leave_type' => $validated['leave_type'],
            'reason' => $validated['reason'],
            'duration' => $validated['duration'],
            'date_from' => $validated['date_from'],
            'date_to' => $validated['date_to'],
            'position' => $validated['position'],
            'status' => 'Pending',
            'leave_credits' => $user->leave_credits ?? 5,
            'approved_by' => null,
            'rejected_by' => null,
            'user_id' => $user->id,
        ]);

        return redirect()->route('leaves.list')->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Approve a leave request (one-time only).
     */
    public function approve(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);

        if ($leave->status !== 'Pending') {
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'This leave has already been processed.'])
                : back()->with('error', 'This leave has already been processed.');
        }

        $leave->status = 'Approved';
        $leave->approved_by = Auth::user()->fullname;
        $leave->save();

        return $request->ajax()
            ? response()->json([
                'success' => true,
                'status' => $leave->status,
                'message' => 'Leave approved successfully.'
            ])
            : back()->with('success', 'Leave request approved successfully.');
    }

    /**
     * Reject a leave request (one-time only).
     */
    public function reject(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);

        if ($leave->status !== 'Pending') {
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'This leave has already been processed.'])
                : back()->with('error', 'This leave has already been processed.');
        }

        $leave->status = 'Rejected';
        $leave->rejected_by = Auth::user()->fullname;
        $leave->save();

        return $request->ajax()
            ? response()->json([
                'success' => true,
                'status' => $leave->status,
                'message' => 'Leave rejected successfully.'
            ])
            : back()->with('success', 'Leave request rejected successfully.');
    }
}
