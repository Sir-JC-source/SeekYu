<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show the profile page.
     */
    public function show()
    {
        // Get the logged-in user's employee record
        $employee = Auth::user()->employee;

        if (!$employee) {
            abort(404, 'Employee record not found.');
        }

        return view('Profile.ShowProfileView', compact('employee'));
    }

    /**
     * Update employee profile (Full Name and Contact No. + Avatar).
     */
    public function update(Request $request)
    {
        // Get the logged-in user's employee
        $employee = Auth::user()->employee;

        if (!$employee) {
            return response()->json(['message' => 'Employee record not found'], 404);
        }

        // Validate only editable fields
        $request->validate([
            'full_name' => 'required|string|max:255',
            'contact_no' => 'nullable|string|max:20',
            'employee_image' => 'nullable|image|max:2048',
        ]);

        // Update editable fields
        $employee->full_name = $request->full_name;
        $employee->contact_no = $request->contact_no ?? $employee->contact_no;

        // Handle avatar upload
        if ($request->hasFile('employee_image')) {
            // Delete old avatar if exists
            if ($employee->employee_image && Storage::disk('public')->exists($employee->employee_image)) {
                Storage::disk('public')->delete($employee->employee_image);
            }

            // Store new avatar
            $path = $request->file('employee_image')->store('employee_images', 'public');
            $employee->employee_image = $path;
        }

        $employee->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'employee_image' => $employee->employee_image ? asset('storage/' . $employee->employee_image) : null,
        ]);
    }
}
