<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\JobApplication;

class JobApplicationStatusUpdated extends Notification
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
        // No queue used — database only
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $jobTitle = optional($this->application->jobPosting)->title ?? 'Job Posting';
        $company = optional($this->application->jobPosting)->company ?? 'Company';
        $oldStatus = ucfirst($this->oldStatus);
        $newStatus = ucfirst($this->newStatus);

        $message = "Your application for '{$jobTitle}' has been updated from {$oldStatus} to {$newStatus}.";

        // ✅ Use route() safely with fallback to prevent errors if route not found
        $url = route('applicant.applications', [], false) ?? url('/applicant/applications');

        return [
            'type' => 'job_application_status_update',
            'job_title' => $jobTitle,
            'company' => $company,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'application_id' => $this->application->id,
            'job_posting_id' => $this->application->job_posting_id,
            'message' => $message,
            'url' => $url,
        ];
    }
}
