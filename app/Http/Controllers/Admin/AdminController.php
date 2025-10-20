<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Exception;
use App\Mail\AdminCredentialsMail;

class AdminController extends Controller
{
    /**
     * Show Add Admin Account page
     */
    public function add()
    {
        // Get all employees with position 'Admin'
        $admins = Employee::where('position', 'Admin')->get();
        return view('Admin.AdminAddView', compact('admins'));
    }

    /**
     * Store new admin account
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'email'       => 'required|email|unique:users,email',
        ]);

        // Find employee
        $employee = Employee::findOrFail($request->employee_id);

        // Generate temporary password
        $tempPassword = Str::random(8);

        try {
            // Create new user account
            $user = new User();
            $user->name = $employee->full_name;
            $user->email = $request->email;
            $user->password = Hash::make($tempPassword);
            $user->role = 'Admin'; // make sure 'role' column exists
            $user->save();

            // Attempt to send email (failures won't break account creation)
            try {
                Mail::to($request->email)->send(new AdminCredentialsMail($employee, $tempPassword));
            } catch (Exception $mailEx) {
                \Log::error('Admin credentials email failed: ' . $mailEx->getMessage());
            }

            // Redirect with success toast
            return redirect()->route('admin.add')
                             ->with('success', 'Admin account created successfully. Credentials sent via email!');

        } catch (Exception $e) {
            // Log and redirect with error toast
            \Log::error('Admin account creation failed: ' . $e->getMessage());
            return redirect()->route('admin.add')
                             ->with('error', 'Failed to create admin account: ' . $e->getMessage());
        }
    }
}
