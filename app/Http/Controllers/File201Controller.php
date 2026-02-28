<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\ApplicantCredential;
use App\Models\RegisteredUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class File201Controller extends Controller
{
    /**
     * Show the main 201 File page.
     */
    public function index()
    {
        return view('201file.index');
    }

    /**
     * Get all applicants with their credentials.
     */
    public function applicants()
    {
        $applicants = ApplicantCredential::with('user')
            ->whereHas('user', function ($query) {
                $query->where('role', 'applicant');
            })
            ->get();

        return view('201file.applicants', compact('applicants'));
    }

    /**
     * Get all employees with their 201 file data.
     */
    public function employees()
    {
        $employees = Employee::where('status', 'Active')->get();

        return view('201file.employees', compact('employees'));
    }

    /**
     * Show specific applicant's full credentials.
     */
    public function showApplicant($id)
    {
        $applicant = ApplicantCredential::with('user')->findOrFail($id);

        return view('201file.show-applicant', compact('applicant'));
    }

    /**
     * Show specific employee's full 201 file.
     */
    public function showEmployee($id)
    {
        $employee = Employee::findOrFail($id);

        return view('201file.show-employee', compact('employee'));
    }

    /**
     * Update employee's 201 file data.
     */
    public function updateEmployee(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'sss_number' => 'nullable|string|max:20',
            'pagibig_number' => 'nullable|string|max:20',
            'philhealth_number' => 'nullable|string|max:20',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
            'civil_status' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:10',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_number' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:50',
        ]);

        $employee->update($validated);

        return redirect()->route('201file.employees')->with('success', '201 File updated successfully!');
    }

    /**
     * Show own 201 file (for employees).
     */
    public function showMyFile()
    {
        $user = Auth::user();
        
        // Get the employee record linked to this user
        $employee = Employee::where('employee_number', $user->login_id)->first();

        if (!$employee) {
            return back()->with('error', 'Employee record not found.');
        }

        return view('201file.show-employee', compact('employee'));
    }

    /**
     * Update own 201 file (for employees).
     */
    public function updateMyFile(Request $request)
    {
        $user = Auth::user();
        
        // Get the employee record linked to this user
        $employee = Employee::where('employee_number', $user->login_id)->first();

        if (!$employee) {
            return back()->with('error', 'Employee record not found.');
        }

        $validated = $request->validate([
            'sss_number' => 'nullable|string|max:20',
            'pagibig_number' => 'nullable|string|max:20',
            'philhealth_number' => 'nullable|string|max:20',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
            'civil_status' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:10',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_number' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:50',
        ]);

        $employee->update($validated);

        return back()->with('success', 'Your 201 File has been updated successfully!');
    }
}
