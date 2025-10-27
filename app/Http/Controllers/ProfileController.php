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
        $user = Auth::user();

        // Check if user is an employee (has employee relation)
        if ($user->employee) {
            $profile = $user->employee;
            return view('Profile.ShowProfileView', compact('profile'));
        }

        // Otherwise, treat as applicant (RegisteredUsers)
        $profile = $user;
        return view('applicant.profile', compact('profile'));
    }

    /**
     * Update profile (for both employees and applicants).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if ($user->employee) {
            // Employee profile update
            $profile = $user->employee;

            $request->validate([
                'full_name' => 'required|string|max:255',
                'contact_no' => 'nullable|string|max:20',
                'employee_image' => 'nullable|image|max:2048',
            ]);

            $profile->full_name = $request->full_name;
            $profile->contact_no = $request->contact_no ?? $profile->contact_no;

            $imageField = 'employee_image';
            $storagePath = 'employee_images';
        } else {
            // Applicant profile update
            $profile = $user;

            $request->validate([
                'fullname' => 'required|string|max:255',
                'contact_no' => 'nullable|string|max:20',
                'profile_picture' => 'nullable|image|max:2048',
            ]);

            $profile->fullname = $request->fullname;
            $profile->contact_no = $request->contact_no ?? $profile->contact_no;

            $imageField = 'profile_picture';
            $storagePath = 'profile_pictures';
        }

        // Handle image upload
        if ($request->hasFile($imageField)) {
            // Delete old image if exists
            if ($profile->$imageField && Storage::disk('public')->exists($profile->$imageField)) {
                Storage::disk('public')->delete($profile->$imageField);
            }

            // Store new image
            $path = $request->file($imageField)->store($storagePath, 'public');
            $profile->$imageField = $path;
        }

        $profile->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'profile_image' => $profile->$imageField ? asset('storage/' . $profile->$imageField) : null,
        ]);
    }
}
