<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RegisteredUsers;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendStudentCredentialsMail;
use App\Mail\SendFacultyMemberCredentialsMail;
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
        $query = RegisteredUsers::where('account_status', 'Approved');

        $totalData = $query->count();

        // Search filter
        if ($search = $request->input('search.value')) {
            $query->where('fullname', 'like', "%{$search}%");
            $query->orWhere('student_no', 'like', "%{$search}%");
            $query->orWhere('email', 'like', "%{$search}%");
        }

        $totalFiltered = $query->count();

        // Ordering
        if ($request->has('order')) {
            $orderColIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');
            $columns = ['fullname', 'role', 'student_no', 'email', 'address', 'status'];
            $query->orderBy($columns[$orderColIndex], $orderDir);
        }

        // Pagination
        $users = $query
                ->offset($request->input('start'))
                ->limit($request->input('length'))
                ->get();

        $data = $users->map(function ($user) {
            return [
                'fullname' => $user->fullname,
                'student_no'  => $user->student_no ?? 'N/A',
                'faculty_no'  => $user->faculty_no ?? 'N/A',
                'email'        => $user->email,
                'address'     => $user->address ?? 'N/A',
                'role'        => '<span class="badge bg-label-primary">'
                                    . e($user->role) .
                                '</span>',
                'account_status'       => '<span class="badge bg-label-success">'
                                    . e($user->account_status) .
                                '</span>',
                'action'       => '
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="' . route('user-management.edit', $user->id) . '">
                                <i class="ti ti-pencil me-1"></i> Edit
                            </a>
                            <a class="dropdown-item text-danger" href="javascript:void(0);" 
                            onclick="deleteUser(\'' . route('user-management.destroy', $user->id) . '\')" >
                                <i class="ti ti-trash me-1"></i> Delete
                            </a>
                        </div>
                    </div>'
            ];
        });

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data
        ]);
    }

    public function getUsersForApproval(Request $request)
    {
        $query = RegisteredUsers::where('account_status', 'Pending')->where('role', 'Student');

        $totalData = $query->count();

        // Search filter
        if ($search = $request->input('search.value')) {
            $query->where('fullname', 'like', "%{$search}%");
            $query->orWhere('student_no', 'like', "%{$search}%");
            $query->orWhere('email', 'like', "%{$search}%");
        }

        $totalFiltered = $query->count();

        // Ordering
        if ($request->has('order')) {
            $orderColIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');
            $columns = ['fullname', 'role', 'student_no', 'email', 'address', 'status'];
            $query->orderBy($columns[$orderColIndex], $orderDir);
        }

        // Pagination
        $users = $query
                ->offset($request->input('start'))
                ->limit($request->input('length'))
                ->get();

        $data = $users->map(function ($user) {
            return [
                'fullname' => $user->fullname,
                'student_no'  => $user->student_no,
                'email'        => $user->email,
                'address'     => $user->address ?? 'N/A',
                'role'        => '<span class="badge bg-label-primary">'
                                    . e($user->role) .
                                '</span>',
                'account_status'       => '<span class="badge bg-label-danger">'
                                    . e($user->account_status) .
                                '</span>',
                'action' => '
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item approve-btn" data-id="' . $user->id . '" href="javascript:void(0);">
                                <i class="ti ti-pencil me-1"></i> Approve
                            </a>
                            <a class="dropdown-item text-danger" href="javascript:void(0);"
                            onclick="deleteUser(\'' . route('user-management.destroy', $user->id) . '\')">
                                <i class="ti ti-trash me-1"></i> Delete
                            </a>
                        </div>
                    </div>'

            ];
        });

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data
        ]);
    }

    public function forApprovalIndex()
    {
        return view('UserManagement.PendingApprovalView');
    }

    public function approveUser($id)
    {
        try {
            $user = RegisteredUsers::findOrFail($id);

            // Generate a random 8-character password
            $plainPassword = Str::random(8);

            // Save hashed password
            $user->password = Hash::make($plainPassword);
            $user->account_status = 'Approved';
            $user->save();

            // Assign correct Spatie role (based on existing role field)
            if (strtolower($user->role) === 'student') {
                $user->syncRoles(['Student']); // remove old roles & assign Student
            } elseif (strtolower($user->role) === 'faculty') {
                $user->syncRoles(['Faculty']);
            }

            // Send credentials email
            Mail::to($user->email)->send(new SendStudentCredentialsMail(
                $user->fullname,
                $user->student_no,
                $plainPassword
            ));

            // Check if it's an AJAX request
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User approved and credentials sent!'
                ]);
            }

            // For non-AJAX requests (regular browser requests)
            return redirect()->back()->with('success', 'User approved and credentials sent!');
            
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function facultyMembersCreationIndex()
    {
        return view('UserManagement.FacultyMemberCreationView');
    }

    public function storeFacultyMember(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'facultyNo' => 'required|string|max:50|unique:registered_users,faculty_no',
            'email' => 'required|email|unique:registered_users,email',
            'address' => 'nullable|string|max:255',
        ]);

        try {
            // Generate random password
            $randomPassword = Str::random(8);

            $user = new RegisteredUsers();
            $user->fullname = $request->input('fullname');
            $user->faculty_no = $request->input('facultyNo');
            $user->email = $request->input('email');
            $user->address = $request->input('address');
            $user->role = 'Faculty';
            $user->account_status = 'Approved'; // Default to Approved
            $user->password = Hash::make($randomPassword);
            $user->save();

            // Send welcome email with credentials
            $this->sendFacultyWelcomeEmail($user, $randomPassword);

            // Assign Spatie role
            $user->assignRole('faculty');

            return response()->json([
                'status' => 'success',
                'message' => 'Faculty member created successfully! Welcome email sent.',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create faculty member: ' . $e->getMessage()
            ], 500);
        }
    }

    private function sendFacultyWelcomeEmail($user, $password)
    {
        try {
            Mail::to($user->email)->send(new sendFacultyMemberCredentialsMail(
                $user->fullname,
                $user->faculty_no,
                $password
            ));
        } catch (\Exception $e) {
            // Log the email error but don't fail the user creation
            Log::error('Failed to send faculty welcome email: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = RegisteredUsers::findOrFail($id);
        return view('UserManagement.FacultyMemberCreationView', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:registered_users,email,' . $id,
            'address' => 'nullable|string|max:255',
        ]);

        try {
            $user = RegisteredUsers::findOrFail($id);
            $user->fullname = $request->input('fullname');
            $user->email = $request->input('email');
            $user->address = $request->input('address');
            $user->save();

            return redirect()->route('user-management.index')->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = RegisteredUsers::findOrFail($id);

        $user->delete();

        return response()->json(['message' => 'User deleted successfully.'], 200);
    }

}
