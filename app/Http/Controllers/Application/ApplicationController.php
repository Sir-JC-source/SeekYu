<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Notifications\JobApplicationStatusUpdated;

class ApplicationController extends Controller
{
    /**
     * Show all applications.
     */
    public function index()
    {
        $applications = JobApplication::with('user', 'jobPosting')
            ->orderBy('applied_at', 'desc')
            ->get();

        return view('applications.list', compact('applications'));
    }

    /**
     * Show rejected applications.
     */
    public function rejected()
    {
        $applications = JobApplication::with('user', 'jobPosting')
            ->where('status', 'rejected')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('applications.rejected', compact('applications'));
    }

    /**
     * Show shortlisted applications.
     */
    public function shortlist()
    {
        $applications = JobApplication::with('user', 'jobPosting')
            ->where('status', 'shortlisted')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('applications.shortlist', compact('applications'));
    }

    /**
     * Update application status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,shortlisted,rejected,hired',
        ]);

        $application = JobApplication::with('user', 'jobPosting')->findOrFail($id);
        $oldStatus = $application->status;
        $newStatus = $request->status;

        // ✅ Only process if status actually changed
        if ($oldStatus !== $newStatus) {
            $application->update(['status' => $newStatus]);

            // ✅ Make sure user relation exists before notifying
            if ($application->user) {
                // Send notification instantly (not queued)
                $application->user->notify(
                    new JobApplicationStatusUpdated($application, $oldStatus, $newStatus)
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Application status updated successfully.',
        ]);
    }

    /**
     * Get game scores for an application.
     */
    public function getGameScores($id)
    {
        $application = JobApplication::findOrFail($id);
        $scores = \App\Models\ApplicantGameScore::where('job_application_id', $id)->get();

        return response()->json([
            'success' => true,
            'scores' => $scores
        ]);
    }
}
