<?php

namespace App\Http\Controllers\JobPosting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPosting;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;

class JobPostingController extends Controller
{
    /**
     * Show the form to create a new job posting.
     */
    public function create()
    {
        return view('job_postings.create'); // Ensure this Blade exists
    }

    /**
     * Store a new job posting in the database.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'position' => 'required|in:Security Guard,Head Guard',
            'description' => 'required|string',
            'type_of_employment' => 'required|in:Contractual,Full-Time',
            'location' => 'required|string|max:255',
        ]);

        // Ensure created_by is set to the logged-in user
        $creatorId = Auth::check() ? Auth::id() : null;

        if (!$creatorId) {
            return redirect()
                ->back()
                ->with('error', 'Unable to create job posting: User not authenticated.');
        }

        // Create the job posting
        JobPosting::create([
            'job_post_id' => 'JOB-' . strtoupper(uniqid()),
            'title' => $validated['title'],
            'position' => $validated['position'],
            'description' => $validated['description'],
            'type_of_employment' => $validated['type_of_employment'],
            'location' => $validated['location'],
            'created_by' => $creatorId,
        ]);

        return redirect()
            ->route('job_postings.list')
            ->with('success', 'Job posting created successfully!');
    }

    /**
     * List all job postings (for admin/HR-officer/super-admin).
     */
    public function index()
    {
        $jobPostings = JobPosting::orderByRaw("CASE WHEN status = 'active' THEN 1 ELSE 2 END")
            ->orderBy('created_at', 'desc')
            ->get();
        return view('job_postings.list', compact('jobPostings'));
    }

    /**
     * Alias for index(), used in routes.
     */
    public function list()
    {
        return $this->index();
    }

    /**
     * Show job postings for applicants.
     * Only includes jobs created by HR-Officer, Admin, or Super-Admin.
     * Includes pagination (9 per page) and optional position filter.
     */
    public function applicantJobs(Request $request)
    {
        $query = JobPosting::whereHas('creator', function ($query) {
            $query->whereIn('role', ['hr-officer', 'admin', 'super-admin']);
        });

        // Apply position filter if selected
        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        // Left join with job_applications to check if user has applied
        $query->leftJoin('job_applications', function($join) {
            $join->on('job_postings.id', '=', 'job_applications.job_posting_id')
                 ->where('job_applications.user_id', '=', Auth::id());
        })->addSelect('job_postings.*', 'job_applications.id as applied_id');

        // Paginate 9 jobs per page, order by status (active first) then by created_at
        $jobPostings = $query->orderByRaw("CASE WHEN job_postings.status = 'active' THEN 1 ELSE 2 END")
            ->orderBy('job_postings.created_at', 'desc')
            ->paginate(9);

        return view('applicant.jobs', compact('jobPostings'));
    }

    /**
     * Show details of a specific job posting.
     */
    public function show($id)
    {
        $job = JobPosting::findOrFail($id);
        return view('job_postings.show', compact('job'));
    }

    /**
     * Delete a job posting (Admin/HR only).
     */
    public function destroy($id)
    {
        $job = JobPosting::findOrFail($id);
        $job->delete();

        return redirect()
            ->route('job_postings.list')
            ->with('success', 'Job posting deleted successfully.');
    }

    /**
     * Toggle job status between active and inactive (Admin/HR only).
     */
    public function toggleStatus($id)
    {
        $job = JobPosting::findOrFail($id);

        // Toggle status
        $job->status = $job->status === 'active' ? 'inactive' : 'active';
        $job->save();

        $statusText = ucfirst($job->status);

        return redirect()
            ->route('job_postings.list')
            ->with('success', "Job posting status changed to {$statusText}.");
    }

    /**
     * Apply for a job posting (Applicants only).
     */
    public function apply(Request $request, $id)
    {
        $job = JobPosting::findOrFail($id);
        $user = Auth::user();

        // Check if user already applied
        $existingApplication = JobApplication::where('job_posting_id', $id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingApplication) {
            return response()->json([
                'success' => false,
                'message' => 'You have already applied for this job.'
            ]);
        }

        // Create application
        JobApplication::create([
            'job_posting_id' => $id,
            'user_id' => $user->id,
            'applied_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully!'
        ]);
    }

    /**
     * Show applications for a specific job posting (Admin/HR only).
     */
    public function showApplications($id)
    {
        $job = JobPosting::with('applications.user')->findOrFail($id);
        return view('job_postings.applications', compact('job'));
    }

    /**
     * Show applicant credentials (Admin/HR only).
     */
    public function showApplicantCredentials($applicationId)
    {
        $application = JobApplication::with('user.applicantCredential')->findOrFail($applicationId);
        return view('job_postings.applicant-credentials', compact('application'));
    }

    /**
     * Reject an application (Admin/HR only).
     */
    public function rejectApplication($id)
    {
        $application = JobApplication::findOrFail($id);
        $application->status = 'rejected';
        $application->save();

        return redirect()->back()->with('success', 'Application rejected successfully.');
    }

    /**
     * Shortlist an application (Admin/HR only).
     */
    public function shortlistApplication($id)
    {
        $application = JobApplication::findOrFail($id);
        $application->status = 'shortlisted';
        $application->save();

        return redirect()->back()->with('success', 'Application shortlisted successfully.');
    }
}
