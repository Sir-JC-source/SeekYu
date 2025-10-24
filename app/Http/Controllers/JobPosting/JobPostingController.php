<?php

namespace App\Http\Controllers\JobPosting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPosting;
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
        $jobPostings = JobPosting::orderBy('created_at', 'desc')->get();
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

        // Paginate 9 jobs per page
        $jobPostings = $query->orderBy('created_at', 'desc')->paginate(9);

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
}
