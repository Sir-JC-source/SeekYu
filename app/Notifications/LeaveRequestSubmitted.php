<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Leave;

class LeaveRequestSubmitted extends Notification
{
    use Queueable;


    protected $leave;

    /**
     * Create a new notification instance.
     */
    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
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
                    ->line('A new leave request has been submitted.')
                    ->action('View Leave Requests', url('/leaves/pending'))
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
            'type' => 'leave_submitted',
            'leave_type' => ucfirst($this->leave->leave_type),
            'requestor' => $this->leave->requestor,
            'date_from' => $this->leave->date_from,
            'date_to' => $this->leave->date_to,
            'message' => "A new {$this->leave->leave_type} request has been submitted by {$this->leave->requestor} from {$this->leave->date_from} to {$this->leave->date_to}.",
            'url' => route('leaves.pending'),
        ];
    }
}
