<?php

namespace App\Http\Controllers\Shift;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use Carbon\Carbon;

class ShiftController extends Controller
{
    public function inOut()
    {
        $user = Auth::user();
        $employee = Employee::where('employee_number', $user->login_id)->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee record not found.');
        }

        return view('Shift.in-out', compact('employee'));
    }

    public function attendance()
    {
        $user = Auth::user();
        $employee = Employee::where('employee_number', $user->login_id)->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee record not found.');
        }

        // Assuming attendance data is stored somewhere, for now just pass employee
        return view('Shift.attendance', compact('employee'));
    }

    public function storeShift(Request $request)
    {
        $request->validate([
            'shift_in' => 'required|date_format:H:i',
            'shift_out' => 'required|date_format:H:i',
        ]);

        $user = Auth::user();
        $employee = Employee::where('employee_number', $user->login_id)->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee record not found.');
        }

        $employee->update([
            'shift_in' => $request->shift_in,
            'shift_out' => $request->shift_out,
        ]);

        return redirect()->back()->with('success', 'Shift times updated successfully.');
    }
}
