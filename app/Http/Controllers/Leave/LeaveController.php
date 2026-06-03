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
        $user = Auth::user();
        $role = $user->role;

        $query = Leave::where('status', 'Pending');

        // Filter based on user role
        switch ($role) {
            case 'hr-officer':
                // HR Officer can see leaves from security-guard and head-guard
                $query->whereHas('user', function ($q) {
                    $q->whereIn('role', ['security-guard', 'head-guard']);
                });
                break;
            case 'admin':
                // Admin can see leaves from hr-officer, security-guard, and head-guard
                $query->whereHas('user', function ($q) {
                    $q->whereIn('role', ['hr-officer', 'security-guard', 'head-guard']);
                });
                break;
            case 'super-admin':
                // Super Admin can see leaves from admin and hr-officer
                $query->whereHas('user', function ($q) {
                    $q->whereIn('role', ['admin', 'hr-officer']);
                });
                break;
            default:
                // Other roles see nothing
                $query->whereRaw('1 = 0');
                break;
        }

        $leaves = $query->orderBy('created_at', 'desc')->get();
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
     * Display processed leaves (approved + rejected).
     */
    public function processed(Request $request)
    {
        $query = Leave::whereIn('status', ['Approved', 'Rejected']);

        // Optional filters
        $requestor = $request->input('requestor');
        if ($request->filled('requestor')) {
            $query->where('requestor', 'like', '%' . $request->input('requestor') . '%');
        }

        $exactDate = $request->input('date_exact');
        $dateFrom  = $request->input('date_from');
        $dateTo    = $request->input('date_to');

        if ($request->filled('date_exact')) {
            // Exact match on the Date Requested column (created_at)
            $query->whereDate('created_at', $exactDate);
        } else {
            // Range filter (only applied when exact date is not set)
            if ($request->filled('date_from') && $request->filled('date_to')) {
                $query->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
            } elseif ($request->filled('date_from')) {
                $query->where('created_at', '>=', $dateFrom . ' 00:00:00');
            } elseif ($request->filled('date_to')) {
                $query->where('created_at', '<=', $dateTo . ' 23:59:59');
            }
        }

        $leaves = $query->orderBy('created_at', 'desc')->get();
        return view('leaves.processed', compact('leaves'));
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
            'date_from' => 'required|date|after_or_equal:today',
            'date_to' => 'required|date|after_or_equal:date_from',
            'position' => 'required|string|max:50',
        ]);

        $user = Auth::user();

        // Calculate credits based on date range (number of days)
        $dateFrom = \Carbon\Carbon::parse($validated['date_from']);
        $dateTo = \Carbon\Carbon::parse($validated['date_to']);
        $days = $dateFrom->diffInDays($dateTo) + 1; // Inclusive of both dates
        $credits = max(1, min(10, $days)); // Clamp between 1 and 10 credits

        // Check if user has sufficient credits
        if ($user->leave_credits < $credits) {
            return redirect()->back()->withErrors(['leave_credits' => 'Insufficient leave credits. You have ' . $user->leave_credits . ' credits but need ' . $credits . ' credits for this leave request.'])->withInput();
        }

        $leave = Leave::create([
            'requestor' => $user->employee->full_name ?? $user->fullname,
            'leave_type' => $validated['leave_type'],
            'reason' => $validated['reason'],
            'date_from' => $validated['date_from'],
            'date_to' => $validated['date_to'],
            'position' => $validated['position'],
            'status' => 'Pending',
            'leave_credits' => $credits,
            'approved_by' => null,
            'rejected_by' => null,
            'user_id' => $user->id,
        ]);

        // Send notifications based on user's role
        $role = $user->role;
        $recipients = collect();

        switch ($role) {
            case 'admin':
                // Notify all super-admins
                $recipients = $recipients->merge(\App\Models\RegisteredUsers::where('role', 'super-admin')->get());
                break;
            case 'hr-officer':
                // Notify all super-admins and admins
                $recipients = $recipients->merge(\App\Models\RegisteredUsers::where('role', 'super-admin')->get());
                $recipients = $recipients->merge(\App\Models\RegisteredUsers::where('role', 'admin')->get());
                break;
            case 'security-guard':
            case 'head-guard':
                // Notify all hr-officers and admins
                $recipients = $recipients->merge(\App\Models\RegisteredUsers::where('role', 'hr-officer')->get());
                $recipients = $recipients->merge(\App\Models\RegisteredUsers::where('role', 'admin')->get());
                break;
        }

        // Send notification to each recipient
        foreach ($recipients as $recipient) {
            $recipient->notify(new \App\Notifications\LeaveRequestSubmitted($leave));
        }

        return redirect()->route('leaves.request')->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Approve a leave request.
     */
    public function approve(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);

        if ($leave->status !== 'Pending') {
            return response()->json(['success' => false, 'message' => 'Leave already processed.']);
        }

        // Calculate credits based on the leave's duration attribute (calculated from dates)
        $daysToDeduct = $leave->duration;

        // Check if user has sufficient credits
        $user = $leave->user;
        if ($user->leave_credits < $daysToDeduct) {
            return response()->json(['success' => false, 'message' => 'Insufficient leave credits.']);
        }

        // Deduct credits and approve leave
        $user->leave_credits -= $daysToDeduct;
        $user->save();

        $leave->status = 'Approved';
        $leave->approved_by = Auth::user()->id;
        $leave->rejected_by = null;
        $leave->save();

        // Notify the user
        $user->notify(new \App\Notifications\LeaveRequestStatusUpdated($leave, 'Pending', 'Approved'));

        return response()->json(['success' => true, 'status' => 'Approved']);
    }

    /**
     * Reject a leave request.
     */
    public function reject(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);

        if ($leave->status !== 'Pending') {
            return response()->json(['success' => false, 'message' => 'Leave already processed.']);
        }

        $leave->status = 'Rejected';
        $leave->rejected_by = Auth::user()->id;
        $leave->approved_by = null;
        $leave->save();

        // Notify the user
        $leave->user->notify(new \App\Notifications\LeaveRequestStatusUpdated($leave, 'Pending', 'Rejected'));

        return response()->json(['success' => true, 'status' => 'Rejected']);
    }


}
