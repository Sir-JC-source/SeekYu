<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RegisteredUsers;
use App\Http\Controllers\GamificationController;

class AwardProfileCompletionPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:award-profile-completion-points';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Award gamification points for profile completion to all eligible applicants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $gamificationController = new GamificationController();

        // Get all applicants with complete profiles
        $applicants = RegisteredUsers::where('role', 'applicant')
            ->whereNotNull('fullname')
            ->whereNotNull('contact_no')
            ->whereNotNull('province')
            ->whereNotNull('city')
            ->whereNotNull('barangay')
            ->get();

        $count = 0;
        foreach ($applicants as $applicant) {
            // Check if they already have the badge
            $badges = json_decode($applicant->badges, true) ?? [];
            if (!in_array('Profile Master', $badges)) {
                $gamificationController->awardProfileCompletionPoints($applicant->id);
                $this->info('Awarded profile completion points to: ' . $applicant->fullname);
                $count++;
            }
        }

        $this->info("Profile completion points awarded to {$count} applicants.");
    }
}
