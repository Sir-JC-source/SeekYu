<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobApplication;
use App\Http\Controllers\GamificationController;

class AwardShortlistPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:award-shortlist-points';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Award gamification points to shortlisted applicants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $applications = JobApplication::where('status', 'shortlisted')->with('user')->get();

        foreach ($applications as $app) {
            if ($app->user) {
                $gamification = new GamificationController();
                $gamification->awardShortlistPoints($app->user->id);
                $this->info('Awarded points to user: ' . $app->user->fullname);
            }
        }

        $this->info('Points awarded to all shortlisted applicants.');
    }
}
