<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegisteredUsers;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;

class GamificationController extends Controller
{
    /**
     * Display gamification dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $gamification = $this->getUserGamification($user->id);
        $leaderboard = $this->getLeaderboard();

        return view('applicant.gamification', compact('gamification', 'leaderboard'));
    }

    /**
     * Award points for profile completion
     */
    public function awardProfileCompletionPoints($userId)
    {
        $user = RegisteredUsers::find($userId);
        if (!$user) return;

        // Check if profile is complete (basic check)
        $isComplete = !empty($user->fullname) && !empty($user->contact_no) &&
                     !empty($user->province) && !empty($user->city) && !empty($user->barangay);

        if ($isComplete && !$this->hasBadge($user, 'Profile Master')) {
            $this->addPoints($user, 50);
            $this->addBadge($user, 'Profile Master');
        }
    }

    /**
     * Award points for job application submission
     */
    public function awardApplicationPoints($userId)
    {
        $user = RegisteredUsers::find($userId);
        if (!$user) return;

        $applicationCount = JobApplication::where('user_id', $userId)->count();

        // Points for each application
        $this->addPoints($user, 10);

        // Badges based on application count
        if ($applicationCount >= 5 && !$this->hasBadge($user, 'Job Seeker Rookie')) {
            $this->addBadge($user, 'Job Seeker Rookie');
        }
        if ($applicationCount >= 25 && !$this->hasBadge($user, 'Application Veteran')) {
            $this->addBadge($user, 'Application Veteran');
        }
    }

    /**
     * Award points for application shortlisting
     */
    public function awardShortlistPoints($userId)
    {
        $user = RegisteredUsers::find($userId);
        if (!$user) return;

        $shortlistCount = JobApplication::where('user_id', $userId)->where('status', 'shortlisted')->count();

        // Points for shortlisting
        $this->addPoints($user, 100);

        // Badges based on shortlist count
        if ($shortlistCount >= 1 && !$this->hasBadge($user, 'Shortlisted Candidate')) {
            $this->addBadge($user, 'Shortlisted Candidate');
        }
        if ($shortlistCount >= 5 && !$this->hasBadge($user, 'Top Prospect')) {
            $this->addBadge($user, 'Top Prospect');
        }
    }

    /**
     * Get user's gamification data
     */
    public function getUserGamification($userId)
    {
        $user = RegisteredUsers::find($userId);
        if (!$user) return null;

        return [
            'points' => $user->points,
            'level' => $user->level,
            'badges' => json_decode($user->badges, true) ?? [],
            'next_level_points' => $this->getNextLevelPoints($user->level)
        ];
    }

    /**
     * Get leaderboard
     */
    public function getLeaderboard($limit = 10)
    {
        return RegisteredUsers::where('role', 'applicant')
            ->orderBy('points', 'desc')
            ->limit($limit)
            ->get(['fullname', 'points', 'level', 'badges']);
    }

    /**
     * Add points to user
     */
    public function addPoints($user, $points)
    {
        $user->points += $points;
        $user->level = $this->calculateLevel($user->points);
        $user->save();
    }

    /**
     * Add badge to user
     */
    public function addBadge($user, $badgeName)
    {
        $badges = json_decode($user->badges, true) ?? [];
        if (!in_array($badgeName, $badges)) {
            $badges[] = $badgeName;
            $user->badges = json_encode($badges);
            $user->save();
        }
    }

    /**
     * Check if user has badge
     */
    private function hasBadge($user, $badgeName)
    {
        $badges = json_decode($user->badges, true) ?? [];
        return in_array($badgeName, $badges);
    }

    /**
     * Calculate level based on points
     */
    private function calculateLevel($points)
    {
        // Simple level calculation: every 100 points = 1 level
        return floor($points / 100) + 1;
    }

    /**
     * Get points needed for next level
     */
    private function getNextLevelPoints($currentLevel)
    {
        return $currentLevel * 100;
    }
}
