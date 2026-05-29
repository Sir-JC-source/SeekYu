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
            'position' => 'required|in:Security Guard,Head Guard,HR Officer',
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
        $jobPostings = JobPosting::withCount('applications as total_applicants')
            ->orderByRaw("CASE WHEN status = 'active' THEN 1 ELSE 2 END")
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

        // Check if position requires assessment
        if (in_array($job->position, ['Head Guard', 'Security Guard'])) {
            // Store job ID in session and redirect to first game
            session(['pending_application_job_id' => $id]);
            return response()->json([
                'success' => true,
                'redirect' => route('applicant.games.gate'),
                'message' => 'Starting assessment...'
            ]);
        }

        if ($job->position === 'HR Officer') {
            // Store job ID in session and redirect to first HR game
            session(['pending_application_job_id' => $id]);
            return response()->json([
                'success' => true,
                'redirect' => route('applicant.games.hr.resume'),
                'message' => 'Starting HR assessment...'
            ]);
        }

        // For other positions, create application directly
        JobApplication::create([
            'job_posting_id' => $id,
            'user_id' => $user->id,
            'applied_at' => now(),
        ]);


        // Award gamification points for job application
        $gamificationController = new \App\Http\Controllers\GamificationController();
        $gamificationController->awardApplicationPoints($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully! You earned 10 points!'
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
     * Show reject modal (AJAX)
     */
    public function showRejectModal($id)
    {
        $application = JobApplication::with('user')->findOrFail($id);
        return response()->json([
            'application' => $application
        ]);
    }

    /**
     * Reject an application with notes (Admin/HR only).
     */
    public function rejectApplication(Request $request, $id)
    {
        $request->validate([
            'rejection_notes' => 'required|string|max:1000'
        ]);

        $application = JobApplication::findOrFail($id);
        $oldStatus = $application->status;
        $application->status = 'rejected';
        $application->rejection_notes = $request->rejection_notes;
        $application->rejected_at = now();
        $application->save();

        // Notify the applicant with notes
        $application->user->notify(new \App\Notifications\JobApplicationStatusUpdated($application, $oldStatus, 'rejected'));

        return response()->json([
            'success' => true,
            'message' => 'Application rejected with notes.'
        ]);
    }

    /**
     * Shortlist an application (Admin/HR only).
     */
    public function shortlistApplication($id)
    {
        $application = JobApplication::findOrFail($id);
        $oldStatus = $application->status;
        $application->status = 'shortlisted';
        $application->save();

        // Notify the applicant
        $application->user->notify(new \App\Notifications\JobApplicationStatusUpdated($application, $oldStatus, 'shortlisted'));

        // Award gamification points for shortlisting
        $gamificationController = new \App\Http\Controllers\GamificationController();
        $gamificationController->awardShortlistPoints($application->user_id);

        return redirect()->back()->with('success', 'Application shortlisted successfully. Applicant earned 100 points!');
    }

    /**
     * Show Gate Screening Game.
     */
    public function showGateGame()
    {
        $jobId = session('pending_application_job_id');
        if (!$jobId) {
            return redirect()->route('applicant.jobs')->with('error', 'Invalid access to assessment.');
        }
        return view('applicant.games.gate');
    }

    /**
     * Show Bag Inspection Game.
     */
    public function showBagGame()
    {
        $jobId = session('pending_application_job_id');
        if (!$jobId) {
            return redirect()->route('applicant.jobs')->with('error', 'Invalid access to assessment.');
        }
        return view('applicant.games.bag');
    }

    /**
     * Show Memory Test Game.
     */
    public function showMemoryGame()
    {
        $jobId = session('pending_application_job_id');
        if (!$jobId) {
            return redirect()->route('applicant.jobs')->with('error', 'Invalid access to assessment.');
        }
        return view('applicant.games.memory');
    }

    /**
     * Show HR Resume-ID Matching Game (HR/1).
     */
    public function showHrResumeGame()
    {
        $jobId = session('pending_application_job_id');
        if (!$jobId) {
            return redirect()->route('applicant.jobs')->with('error', 'Invalid access to assessment.');
        }
        return view('applicant.games.hr.resume-id');
    }

    /**
     * Show HR Folder Sorting Game (HR/2).
     */
    public function showHrSortingGame()
    {
        $jobId = session('pending_application_job_id');
        if (!$jobId) {
            return redirect()->route('applicant.jobs')->with('error', 'Invalid access to assessment.');
        }
        return view('applicant.games.hr.folder-sorting');
    }

    /**
     * Show HR Client-Guard Match Game (HR/3).
     */
    public function showHrClientGuardGame()
    {
        $jobId = session('pending_application_job_id');
        if (!$jobId) {
            return redirect()->route('applicant.jobs')->with('error', 'Invalid access to assessment.');
        }
        return view('applicant.games.hr.client-guard');
    }

    /**
     * Submit HR Game Scores and Finalize HR Officer Application.
     */
    public function submitHrGameScores(Request $request)
    {
        $request->validate([
            'resume_id_score' => 'required|integer|min:0',
            'resume_id_total' => 'required|integer',
            'resume_id_pct' => 'required|numeric|min:0|max:100',
            'resume_id_time' => 'required|string',

            'folder_sort_score' => 'required|integer|min:0',
            'folder_sort_total' => 'required|integer',
            'folder_sort_pct' => 'required|numeric|min:0|max:100',
            'folder_sort_time' => 'required|string',

            'client_guard_match_score' => 'required|integer|min:0',
            'client_guard_match_total' => 'required|integer',
            'client_guard_match_pct' => 'required|numeric|min:0|max:100',
            'client_guard_match_time' => 'required|string',
        ]);

        $jobId = session('pending_application_job_id');
        $user = Auth::user();

        if (!$jobId) {
            return response()->json(['success' => false, 'message' => 'Invalid session.']);
        }

        $existing = JobApplication::where('job_posting_id', $jobId)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            return response()->json(['success' => false, 'message' => 'Already applied.']);
        }

        $application = JobApplication::create([
            'job_posting_id' => $jobId,
            'user_id' => $user->id,
            'applied_at' => now(),
        ]);

        \App\Models\ApplicantGameScore::create([
            'job_application_id' => $application->id,
            'user_id' => $user->id,
            'game_type' => 'hr_resume_id_matching',
            'score' => $request->resume_id_score,
            'total' => $request->resume_id_total,
            'percentage' => $request->resume_id_pct,
            'time_taken' => $request->resume_id_time,
        ]);

        \App\Models\ApplicantGameScore::create([
            'job_application_id' => $application->id,
            'user_id' => $user->id,
            'game_type' => 'hr_folder_sorting',
            'score' => $request->folder_sort_score,
            'total' => $request->folder_sort_total,
            'percentage' => $request->folder_sort_pct,
            'time_taken' => $request->folder_sort_time,
        ]);

        \App\Models\ApplicantGameScore::create([
            'job_application_id' => $application->id,
            'user_id' => $user->id,
            'game_type' => 'hr_client_guard_match',
            'score' => $request->client_guard_match_score,
            'total' => $request->client_guard_match_total,
            'percentage' => $request->client_guard_match_pct,
            'time_taken' => $request->client_guard_match_time,
        ]);

        session()->forget('pending_application_job_id');

        $gamificationController = new \App\Http\Controllers\GamificationController();
        $gamificationController->awardApplicationPoints($user->id);

        return response()->json([
            'success' => true,
            'message' => 'HR assessment completed! Application submitted successfully. You earned 10 points!'
        ]);
    }

    /**
     * Submit Game Scores and Finalize Application.
     */
    public function submitGameScores(Request $request)
    {

        $request->validate([
            'gate_score' => 'required|integer|min:0|max:10',
            'gate_total' => 'required|integer',
            'gate_pct' => 'required|numeric|min:0|max:100',
            'gate_time' => 'required|string',
            'bag_score' => 'required|integer|min:0|max:7',
            'bag_total' => 'required|integer',
            'bag_pct' => 'required|numeric|min:0|max:100',
            'bag_time' => 'required|string',
            'memory_score' => 'required|integer|min:0|max:5',
            'memory_total' => 'required|integer',
            'memory_pct' => 'required|numeric|min:0|max:100',
            'memory_time' => 'required|string',
        ]);

        $jobId = session('pending_application_job_id');
        $user = Auth::user();

        if (!$jobId) {
            return response()->json(['success' => false, 'message' => 'Invalid session.']);
        }

        // Check if already applied
        $existing = JobApplication::where('job_posting_id', $jobId)->where('user_id', $user->id)->first();
        if ($existing) {
            return response()->json(['success' => false, 'message' => 'Already applied.']);
        }

        // Create JobApplication
        $application = JobApplication::create([
            'job_posting_id' => $jobId,
            'user_id' => $user->id,
            'applied_at' => now(),
        ]);

        // Save Game Scores
        \App\Models\ApplicantGameScore::create([
            'job_application_id' => $application->id,
            'user_id' => $user->id,
            'game_type' => 'gate_screening',
            'score' => $request->gate_score,
            'total' => $request->gate_total,
            'percentage' => $request->gate_pct,
            'time_taken' => $request->gate_time,
        ]);

        \App\Models\ApplicantGameScore::create([
            'job_application_id' => $application->id,
            'user_id' => $user->id,
            'game_type' => 'bag_inspection',
            'score' => $request->bag_score,
            'total' => $request->bag_total,
            'percentage' => $request->bag_pct,
            'time_taken' => $request->bag_time,
        ]);

        \App\Models\ApplicantGameScore::create([
            'job_application_id' => $application->id,
            'user_id' => $user->id,
            'game_type' => 'memory_test',
            'score' => $request->memory_score,
            'total' => $request->memory_total,
            'percentage' => $request->memory_pct,
            'time_taken' => $request->memory_time,
        ]);

        // Clear session
        session()->forget('pending_application_job_id');

        // Award points
        $gamificationController = new \App\Http\Controllers\GamificationController();
        $gamificationController->awardApplicationPoints($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Assessment completed! Application submitted successfully. You earned 10 points!'
        ]);
    }
}
