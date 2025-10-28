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
    public function processed()
    {
        $leaves = Leave::whereIn('status', ['Approved', 'Rejected'])
            ->orderBy('created_at', 'desc')
            ->get();

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
            'duration' => 'required|in:Whole Shift,Half-Shift Early Out,Half-Shift Late In,Multi-Day',
            'date_from' => 'required|date|after_or_equal:today',
            'date_to' => 'required|date|after_or_equal:date_from',
            'position' => 'required|string|max:50',
        ]);

        $user = Auth::user();

        // Calculate credits and adjust date_to based on duration
        $creditCalculation = $this->calculateCredits(
            $validated['duration'],
            $validated['date_from'],
            $validated['date_to']
        );

        // Check if user has sufficient credits
        if ($user->leave_credits < $creditCalculation['credits']) {
            return redirect()->back()->withErrors(['leave_credits' => 'Insufficient leave credits. You have ' . $user->leave_credits . ' credits but need ' . $creditCalculation['credits'] . ' credits for this leave request.'])->withInput();
        }

        Leave::create([
            'requestor' => $user->employee->full_name ?? $user->fullname,
            'leave_type' => $validated['leave_type'],
            'reason' => $validated['reason'],
            'duration' => $validated['duration'],
            'date_from' => $validated['date_from'],
            'date_to' => $creditCalculation['date_to'],
            'position' => $validated['position'],
            'status' => 'Pending',
            'leave_credits' => $creditCalculation['credits'],
            'approved_by' => null,
            'rejected_by' => null,
            'user_id' => $user->id,
        ]);

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

        // Use the calculateCredits function to get the correct credits to deduct
        $creditCalculation = $this->calculateCredits(
            $leave->duration,
            $leave->date_from,
            $leave->date_to
        );
        $daysToDeduct = $creditCalculation['credits'];

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

        return response()->json(['success' => true, 'status' => 'Rejected']);
    }

    /**
     * Calculate credits and adjust date_to based on duration.
     */
    private function calculateCredits($duration, $date_from, $date_to)
    {
        $dateFrom = \Carbon\Carbon::parse($date_from);
        $dateTo = \Carbon\Carbon::parse($date_to);

        switch ($duration) {
            case 'Whole Shift':
                return [
                    'credits' => 1,
                    'date_to' => $dateFrom->toDateString()
                ];
            case 'Half-Shift Early Out':
            case 'Half-Shift Late In':
                return [
                    'credits' => 0.5,
                    'date_to' => $dateFrom->toDateString()
                ];
            case 'Multi-Day':
                $days = $dateFrom->diffInDays($dateTo) + 1;
                $credits = max(2, min(10, $days)); // Clamp between 2 and 10
                return [
                    'credits' => $credits,
                    'date_to' => $dateTo->toDateString()
                ];
            default:
                return [
                    'credits' => 0,
                    'date_to' => $dateTo->toDateString()
                ];
        }
    }
}
