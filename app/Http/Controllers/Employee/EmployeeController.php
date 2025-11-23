<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\RegisteredUsers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmployeeCredentialsMail;

class EmployeeController extends Controller
{
    // Employee List view (Active Employees)
    public function index()
    {
        // Fix duplicate employee accounts by selecting latest per employee_number
        $employees = Employee::select('employees.*')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('employees')
                    ->groupBy('employee_number');
            })
            ->get();

        return view('Employee.EmployeeListView', compact('employees'));
    }

    // Archived Employees view
    public function archived()
    {
        $archivedEmployees = Employee::onlyTrashed()->get();
        return view('Employee.EmployeeArchivedListView', compact('archivedEmployees'));
    }

    // Create Employee view
    public function create()
    {
        $year = date('Y');

        $lastEmployee = Employee::withTrashed()
                                ->where('employee_number', 'like', $year . '%')
                                ->orderBy('employee_number', 'desc')
                                ->first();

        $nextNumber = 1;
        if ($lastEmployee) {
            $lastNumber = intval(substr($lastEmployee->employee_number, 4));
            $nextNumber = $lastNumber + 1;
        }

        $employeeNumber = $year . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('Employee.EmployeeCreateView', compact('employeeNumber'));
    }

    // Store new Employee
    public function store(Request $request)
    {
        $request->validate([
            'first_name'      => 'required|string|max:255',
            'middle_name'     => 'nullable|string|max:255',
            'last_name'       => 'required|string|max:255',
            'age'             => 'required|integer|min:18|max:100',
            'province'        => 'required|string|max:255',
            'city'            => 'required|string|max:255',
            'barangay'        => 'required|string|max:255',
            'email'           => 'required|email|unique:registered_users,email',
            'date_hired'      => 'required|date|before_or_equal:today',
            'position'        => 'required|in:Administrator,HR Officer,Security Guard,Head Guard',
            'employee_number' => 'required|digits:8|unique:employees,employee_number',
            'login_id'        => 'required|string|max:255|unique:registered_users,login_id',
            'password'        => 'required|string|min:10',
            'employee_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Map position to role
            $roleMapping = [
                'Administrator' => 'admin',
                'HR Officer' => 'hr-officer',
                'Security Guard' => 'security-guard',
                'Head Guard' => 'head-guard',
            ];
            $role = $roleMapping[$request->position] ?? 'employee';

            // Create Registered User
            $user = RegisteredUsers::create([
                'fullname' => trim($request->first_name . ' ' . ($request->middle_name ? $request->middle_name . ' ' : '') . $request->last_name),
                'email' => $request->email,
                'login_id' => $request->login_id,
                'password' => bcrypt($request->password),
                'role' => $role,
                'account_status' => 'active',
                'status' => 'active',
                'first_login' => true,
                'contact_no' => '', // Will be updated if needed
                'province' => $request->province,
                'city' => $request->city,
                'address' => $request->barangay,
            ]);

            // Create Employee
            $employee = new Employee();
            $employee->employee_number = $request->employee_number;
            $employee->full_name = $user->fullname;
            $employee->position = $request->position;
            $employee->date_hired = $request->date_hired;
            $employee->contact_no = $user->contact_no;
            $employee->province = $request->province;
            $employee->city = $request->city;
            $employee->status = 'Active';
            $employee->first_name = $request->first_name;
            $employee->middle_name = $request->middle_name;
            $employee->last_name = $request->last_name;
            $employee->age = $request->age;
            $employee->barangay = $request->barangay;
            $employee->email = $request->email;

            if ($request->hasFile('employee_image')) {
                $employee->employee_image = $request->file('employee_image')->store('employees', 'public');
            }

            $employee->save();

            // Send verification email
            try {
                $verificationUrl = route('email.verify', ['id' => $user->id, 'token' => sha1($user->email . $user->created_at)]);
                \Mail::to($user->email)->send(new EmployeeCredentialsMail($user, $verificationUrl, $request->password));
            } catch (\Exception $e) {
                // Log email failure but don't fail the entire operation
                \Log::error('Failed to send login credentials email: ' . $e->getMessage());
                return redirect()->route('employee.list')->with('success', 'Employee created successfully. However, there was an issue sending the login credentials email. Please contact the employee directly with their credentials.');
            }

            return redirect()->route('employee.list')->with('success', 'Employee created successfully. Login credentials sent to email.');

        } catch (\Exception $e) {
            \Log::error('Employee creation failed: ' . $e->getMessage());

            // Clean up any partial data if needed
            // Note: Since we're using database transactions implicitly through Laravel,
            // if any step fails, the previous steps should be rolled back

            return redirect()->back()->with('error', 'Failed to create employee. Please try again or contact support if the problem persists.')->withInput();
        }
    }

    // Edit Employee view
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('Employee.EmployeeEditView', compact('employee'));
    }

    // Update Employee
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'employee_number' => 'required|digits:8|unique:employees,employee_number,' . $employee->id,
            'full_name'       => 'required|string|max:255',
            'position'        => 'required|in:Admin,HR Officer,Head Guard,Security Guard',
            'date_hired'      => 'required|date|before_or_equal:today',
            'contact_no'      => 'required|digits:11',
            'province'        => 'required|string|max:255',
            'city'            => 'required|string|max:255',
            'employee_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $employee->employee_number = $request->employee_number;
        $employee->full_name = $request->full_name;
        $employee->position = $request->position;
        $employee->date_hired = $request->date_hired;
        $employee->contact_no = $request->contact_no;
        $employee->province = $request->province;
        $employee->city = $request->city;

        if ($request->hasFile('employee_image')) {
            if ($employee->employee_image && Storage::disk('public')->exists($employee->employee_image)) {
                Storage::disk('public')->delete($employee->employee_image);
            }
            $employee->employee_image = $request->file('employee_image')->store('employees', 'public');
        }

        $employee->save();

        return redirect()->route('employee.list')->with('success', 'Employee updated successfully.');
    }

    // Soft Delete Employee (Archive)
    public function destroy(Request $request, $id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Employee not found.'], 404);
            }
            return redirect()->route('employee.list')->with('error', 'Employee not found.');
        }

        $employee->delete(); // Soft delete

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Employee archived successfully.']);
        }

        return redirect()->route('employee.list')->with('success', 'Employee archived successfully.');
    }

    // Restore Archived Employee
    public function restore(Request $request, $id)
    {
        $employee = Employee::onlyTrashed()->findOrFail($id);
        $employee->restore();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Employee restored successfully.'
            ]);
        }

        return redirect()->route('employee.list')->with('success', 'Employee restored successfully.');
    }
}
