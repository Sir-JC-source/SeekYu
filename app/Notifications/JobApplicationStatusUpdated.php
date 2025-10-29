<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\JobApplication;

class JobApplicationStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;
    protected $oldStatus;
    protected $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(JobApplication $application, $oldStatus, $newStatus)
    {
        $this->application = $application;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'job_application',
            'job_title' => optional($this->application->jobPosting)->title ?? 'Job Posting',
            'company' => optional($this->application->jobPosting)->company ?? 'Company',
            'old_status' => ucfirst($this->oldStatus),
            'new_status' => ucfirst($this->newStatus),
            'application_id' => $this->application->id,
            'job_posting_id' => $this->application->job_posting_id,
            'message' => "Your application for '" . (optional($this->application->jobPosting)->title ?? 'Job Posting') . 
                         "' has been updated from {$this->oldStatus} to {$this->newStatus}.",
            'url' => url('/applicant/applications'),
        ];
    }
}
