<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    // Employee List view (Active Employees)
    public function index()
    {
        $employees = Employee::all(); // Only non-deleted employees
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
            'employee_number' => 'required|digits:8|unique:employees,employee_number',
            'full_name'       => 'required|string|max:255',
            'position'        => 'required|in:Admin,HR Officer,Head Guard,Security Guard',
            'date_hired'      => 'required|date|before_or_equal:today',
            'contact_no'      => 'required|digits:11',
            'province'        => 'required|string|max:255',
            'city'            => 'required|string|max:255',
            'employee_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $employee = new Employee();
        $employee->employee_number = $request->employee_number;
        $employee->full_name = $request->full_name;
        $employee->position = $request->position;
        $employee->date_hired = $request->date_hired;
        $employee->contact_no = $request->contact_no;
        $employee->province = $request->province;
        $employee->city = $request->city;
        $employee->status = 'Active';

        if ($request->hasFile('employee_image')) {
            $employee->employee_image = $request->file('employee_image')->store('employees', 'public');
        }

        $employee->save();

        return redirect()->route('employee.list')->with('success', 'Employee created successfully.');
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
