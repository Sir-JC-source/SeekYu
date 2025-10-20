<?php

namespace App\Http\Controllers\JobPosting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPosting;

class JobPostingController extends Controller
{
    /**
     * Show the form to create a new job posting
     */
    public function create()
    {
        return view('job_postings.create'); // Make sure this Blade exists
    }

    /**
     * Store a new job posting
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'position' => 'required|in:Security Guard,Head Guard',
            'description' => 'required|string', // Must match DB column
            'type_of_employment' => 'required|in:Contractual,Full-Time',
            'location' => 'required|string|max:255',
        ]);

        JobPosting::create([
            'job_post_id' => 'JOB-' . strtoupper(uniqid()),
            'title' => $validated['title'],
            'position' => $validated['position'],
            'description' => $validated['description'], // Matches DB
            'type_of_employment' => $validated['type_of_employment'],
            'location' => $validated['location'],
        ]);

        // Redirect to list page with success message
        return redirect()->route('job_postings.list')->with('success', 'Job posting created successfully!');
    }

    /**
     * List all job postings (for admin/super-admin)
     */
    public function index()
    {
        $jobPostings = JobPosting::orderBy('created_at', 'desc')->get();
        return view('job_postings.list', compact('jobPostings'));
    }

    /**
     * Optional: alias for index
     */
    public function list()
    {
        return $this->index();
    }
}
