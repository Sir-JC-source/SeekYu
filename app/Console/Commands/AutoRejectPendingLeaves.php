<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Leave;
use App\Notifications\LeaveRequestStatusUpdated;

class AutoRejectPendingLeaves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-reject-pending-leaves';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically reject pending leave requests that are older than 5 minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting auto-rejection of pending leaves older than 5 minutes...');

        // Find pending leaves older than 5 minutes
        $oldPendingLeaves = Leave::where('status', 'Pending')
            ->where('created_at', '<', now()->subMinutes(5))
            ->get();

        $count = 0;
        foreach ($oldPendingLeaves as $leave) {
            // Auto-reject the leave
            $leave->status = 'Rejected';
            $leave->rejected_by = null; // System rejection
            $leave->approved_by = null;
            $leave->save();

            // Notify the user
            $leave->user->notify(new LeaveRequestStatusUpdated($leave, 'Pending', 'Rejected'));

            $this->info("Rejected leave request ID {$leave->id} for user {$leave->user->fullname}");
            $count++;
        }

        $this->info("Auto-rejection completed. {$count} leave requests were rejected.");
    }
}
