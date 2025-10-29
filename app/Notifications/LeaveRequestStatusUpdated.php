<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Leave;

class LeaveRequestStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $leave;
    protected $oldStatus;
    protected $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Leave $leave, $oldStatus, $newStatus)
    {
        $this->leave = $leave;
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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Your leave request status has been updated.')
                    ->action('View Leave Requests', url('/leaves'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'leave_request',
            'leave_type' => ucfirst($this->leave->duration),
            'old_status' => ucfirst($this->oldStatus),
            'new_status' => ucfirst($this->newStatus),
            'leave_id' => $this->leave->id,
            'start_date' => $this->leave->start_date,
            'end_date' => $this->leave->end_date,
            'message' => "Your {$this->leave->duration} leave request from {$this->leave->start_date} to {$this->leave->end_date} has been {$this->newStatus}.",
            'url' => route('leaves.index'),
        ];
    }
}
