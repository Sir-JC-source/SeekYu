<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\IncidentReport;

class IncidentReportStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $incidentReport;
    protected $oldStatus;
    protected $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(IncidentReport $incidentReport, $oldStatus, $newStatus)
    {
        $this->incidentReport = $incidentReport;
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Incident Report Status Updated')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('An incident report status has been updated.')
            ->line('**Incident:** ' . $this->incidentReport->incident_name)
            ->line('**Location:** ' . $this->incidentReport->location)
            ->line('**Status changed from:** ' . ucfirst($this->oldStatus) . ' to ' . ucfirst($this->newStatus))
            ->action('View Report', route('incident-reports.logs'))
            ->line('Please review the updated status.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'incident_report_id' => $this->incidentReport->id,
            'incident_name' => $this->incidentReport->incident_name,
            'location' => $this->incidentReport->location,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'updated_at' => now(),
        ];
    }
}
