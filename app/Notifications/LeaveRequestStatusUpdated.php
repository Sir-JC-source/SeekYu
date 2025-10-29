<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Leave;

class LeaveRequestStatusUpdated extends Notification
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
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $approverName = $this->leave->approver
            ? ($this->leave->approver->employee->full_name ?? $this->leave->approver->fullname)
            : null;

        $rejecterName = $this->leave->rejecter
            ? ($this->leave->rejecter->employee->full_name ?? $this->leave->rejecter->fullname)
            : null;

        $actionBy = $this->newStatus === 'Approved' ? $approverName : $rejecterName;
        $actionWord = $this->newStatus === 'Approved' ? 'approved' : 'rejected';

        // ✅ Safe fallback for URL — avoids "route not defined" error
        $url = route('leaves.pending', [], false) ?? url('/leaves');

        return [
            'type' => 'leave_status_update',
            'leave_type' => ucfirst($this->leave->leave_type),
            'old_status' => ucfirst($this->oldStatus),
            'new_status' => ucfirst($this->newStatus),
            'leave_id' => $this->leave->id,
            'date_from' => $this->leave->date_from,
            'date_to' => $this->leave->date_to,
            'action_by' => $actionBy,
            'message' => "Your {$this->leave->leave_type} leave request from {$this->leave->date_from} to {$this->leave->date_to} has been {$actionWord} by {$actionBy}.",
            'url' => $url,
        ];
    }
}
