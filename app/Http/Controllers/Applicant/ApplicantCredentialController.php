<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\ApplicantCredential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicantCredentialController extends Controller
{
    /**
     * Display the credentials form.
     */
    public function index()
    {
        $user = Auth::user();
        $credentials = ApplicantCredential::where('user_id', $user->id)->first();

        return view('applicant.credentials', compact('credentials'));
    }

    /**
     * Show applicant's applications.
     */
    public function applications()
    {
        $user = Auth::user();
        $applications = \App\Models\JobApplication::with('jobPosting')
            ->where('user_id', $user->id)
            ->orderBy('applied_at', 'desc')
            ->get();

        return view('applicant.applications', compact('applications'));
    }

    /**
     * Store or update the credentials.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'license_no' => 'nullable|string|max:255',
            'certifications' => 'nullable|string',
            'license_expiration_date' => 'nullable|date',
            'years_of_experience' => 'nullable|integer|min:0',
            'work_history' => 'nullable|array',
            'work_history.*.company_name' => 'required_with:work_history|string|max:255',
            'work_history.*.position' => 'required_with:work_history|string|max:255',
            'work_history.*.start_date' => 'required_with:work_history|date',
            'work_history.*.end_date' => 'nullable|date|after:work_history.*.start_date',
            'work_history.*.responsibilities' => 'required_with:work_history|string',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:255',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'license' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'training_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'nbi_clearance' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $data = $request->only([
            'license_no', 'certifications', 'license_expiration_date',
            'years_of_experience', 'work_history', 'skills'
        ]);

        $data['user_id'] = $user->id;
        $data['data_consent'] = true;

        // Handle file uploads
        $files = ['resume', 'license', 'training_certificate', 'nbi_clearance'];
        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = time() . '_' . $file . '.' . $uploadedFile->getClientOriginalExtension();
                $path = $uploadedFile->storeAs('applicant_credentials', $filename, 'public');
                $data[$file . '_path'] = $path;
            }
        }

        $credentials = ApplicantCredential::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        // Award gamification points for completing credentials
        $gamificationController = new \App\Http\Controllers\GamificationController();
        $gamificationController->addPoints($user, 25);
        $gamificationController->addBadge($user, 'Credential Master');

        return redirect()->back()->with('success', 'Credentials saved successfully! You earned 25 points!');
    }
}
