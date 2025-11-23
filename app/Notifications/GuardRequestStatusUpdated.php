<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GuardRequestStatusUpdated extends Notification
{
    use Queueable;

    protected $guardRequest;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\GuardRequest  $guardRequest
     * @return void
     */
    public function __construct($guardRequest)
    {
        $this->guardRequest = $guardRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $status = ucfirst($this->guardRequest->status);

        return (new MailMessage)
                    ->subject('Your Guard Request Status Updated')
                    ->greeting('Hello ' . $notifiable->full_name . ',')
                    ->line("Your guard request for {$this->guardRequest->number_of_guards} guard(s) has been {$status}.")
                    ->action('View Requests', url(route('guard-requests.create')))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'guard_request_id' => $this->guardRequest->id,
            'status' => $this->guardRequest->status,
            'number_of_guards' => $this->guardRequest->number_of_guards,
        ];
    }
}
