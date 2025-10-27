<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobApplication;

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
}
