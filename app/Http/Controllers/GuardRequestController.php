<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GuardRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Deployment;
use App\Notifications\GuardRequestStatusUpdated;
use Illuminate\Support\Facades\Notification;

class GuardRequestController extends Controller
{
    // Display client form to create a guard request
    public function create()
    {
        return view('GuardRequests.create');
    }

    // Store client guard request submission
    public function store(Request $request)
    {
        $request->validate([
            'number_of_guards' => 'required|integer|min:1',
            'request_details' => 'nullable|string|max:1000',
        ]);

        $guardRequest = GuardRequest::create([
            'client_id' => Auth::id(),
            'number_of_guards' => $request->number_of_guards,
            'request_details' => $request->request_details,
            'status' => 'pending',
        ]);

        // Notify super-admins about new guard request
        $superAdmins = \App\Models\User::role('super-admin')->get();
        Notification::send($superAdmins, new GuardRequestStatusUpdated($guardRequest));

        return redirect()->route('dashboard.index')->with('success', 'Guard request sent successfully.');
    }

    // Show super-admin list of guard requests
    public function index()
    {
        $requests = GuardRequest::with('client')->orderByDesc('created_at')->get();

        return view('GuardRequests.index', compact('requests'));
    }

    // Update status of a guard request (approve, reject)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $guardRequest = GuardRequest::findOrFail($id);
        $guardRequest->status = $request->status;
        $guardRequest->save();

        // Notify the client
        $guardRequest->client->notify(new GuardRequestStatusUpdated($guardRequest));

        if ($guardRequest->status === 'approved') {
            // Create deployments for the approved guard requests
            for ($i = 0; $i < $guardRequest->number_of_guards; $i++) {
                Deployment::create([
                    'guard_request_id' => $guardRequest->id,
                    'status' => 'pending',
                    // other necessary fields with default values or nulls
                ]);
            }
        }

        return back()->with('success', 'Guard request status updated successfully.');
    }
}
