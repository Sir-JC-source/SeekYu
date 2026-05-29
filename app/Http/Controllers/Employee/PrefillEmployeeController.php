<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class PrefillEmployeeController extends Controller
{
    /**
     * Redirect to employee.create while flashing shortlisted-applicant fields
     * so EmployeeCreateView can prefill the form.
     */
    public function fromShortlist(Request $request, $applicationId)
    {
        $application = JobApplication::with('user', 'jobPosting')->findOrFail($applicationId);

        $nameParts = preg_split('/\s+/', trim($application->user->fullname ?? ''));
        $firstName = $nameParts[0] ?? '';
        $lastName = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '';

        // Flash data for EmployeeCreateView
        session()->flash('employee_prefill', [
            'first_name' => $firstName,
            'middle_name' => '',
            'last_name' => $lastName,
            'email' => $application->user->email ?? '',
            'age' => 18,
            'position' => $application->jobPosting->position ?? '',
            'date_hired' => now()->toDateString(),
        ]);

        return redirect()->route('employee.create');
    }
}

