<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RegisteredUsers;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendStudentCredentialsMail;
use App\Mail\SendFacultyMemberCredentialsMail;
use App\Mail\LoginCredentialsMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index()
    {
        return view('UserManagement.UserManagementView');
    }

    public function getUsers(Request $request)
    {
        $query = RegisteredUsers::where('account_status', 'active')
            ->whereIn('role', ['admin', 'hr-officer', 'head-guard', 'security-guard'])
            ->with('employee');

        $totalData = $query->count();

        // Search filter
        if ($search = $request->input('search.value')) {
            $query->where('fullname', 'like', "%{$search}%")
                  ->orWhere('login_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $totalFiltered = $query->count();

        // Ordering
        if ($request->has('order')) {
            $orderColIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');
            $columns = ['fullname', 'role', 'login_id', 'email', 'last_login', 'status'];
            $query->orderBy($columns[$orderColIndex], $orderDir);
        }

        // Pagination
        $users = $query
                ->offset($request->input('start'))
                ->limit($request->input('length'))
                ->get();

        $data = $users->map(function ($user) {
            return [
                'employee_no' => $user->login_id ?? 'N/A',
                'login_id' => $user->login_id ?? 'N/A',
                'fullname' => $user->employee->full_name ?? $user->fullname,
                'user_type' => '<span class="badge bg-label-primary">' . e($user->role) . '</span>',
                'status' => '<span class="badge bg-label-' . ($user->status === 'active' ? 'success' : 'danger') . '">' . e($user->status) . '</span>',
                'created_at' => $user->created_at->format('M d, Y H:i'),
                'last_login' => $user->last_login ? $user->last_login->format('M d, Y H:i') : 'Never',
                'action' => '
                <button class="btn btn-sm btn-light view-user-btn" data-user-id="' . $user->id . '" title="View User">
                    <i class="ti ti-eye"></i>
                </button>'
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    public function getNonEmployees(Request $request)
    {
        $query = RegisteredUsers::whereIn('account_status', ['active', 'approved'])
            ->whereIn('role', ['applicant']);

        $totalData = $query->count();

        // Search filter
        if ($search = $request->input('search.value')) {
            $query->where('fullname', 'like', "%{$search}%")
                  ->orWhere('student_no', 'like', "%{$search}%")
                  ->orWhere('faculty_no', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $totalFiltered = $query->count();

        // Ordering
        if ($request->has('order')) {
            $orderColIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');
            $columns = ['fullname', 'role', 'student_no', 'faculty_no', 'email', 'last_login', 'status'];
            $query->orderBy($columns[$orderColIndex], $orderDir);
        }

        // Pagination
        $users = $query
                ->offset($request->input('start'))
                ->limit($request->input('length'))
                ->get();

        $data = $users->map(function ($user) {
            return [
                'login_id' => $user->login_id ?? 'N/A',
                'fullname' => $user->fullname,
                'user_type' => '<span class="badge bg-label-primary">' . e($user->role) . '</span>',
                'created_at' => $user->created_at->format('M d, Y H:i'),
                'last_login' => $user->last_login ? $user->last_login->format('M d, Y H:i') : 'Never',
                'status' => '<span class="badge bg-label-' . ($user->status === 'active' ? 'success' : 'danger') . '">' . e($user->status) . '</span>',
                'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->format('M d, Y H:i') : 'Not Verified',
                'action' => '
                <button class="btn btn-sm btn-light view-user-btn" data-user-id="' . $user->id . '" title="View User">
                    <i class="ti ti-eye"></i>
                </button>'
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    // rest of the original methods remain unchanged...

    /**
     * Return JSON user info for the given user id
     */
    public function userInfo($id)
    {
        $user = RegisteredUsers::with('employee')->find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json([
            'login_id' => $user->login_id ?? 'N/A',
            'fullname' => $user->employee->full_name ?? $user->fullname,
            'role' => $user->role,
            'status' => $user->status,
            'email' => $user->email,
            'created_at' => $user->created_at->format('M d, Y H:i'),
            'last_login' => $user->last_login ? $user->last_login->format('M d, Y H:i') : 'Never'
        ]);
    }
}
