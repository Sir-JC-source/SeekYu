<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RegisteredUsers;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'requestor',
        'leave_type',
        'reason',
        'date_from',
        'date_to',
        'position',
        'status',
        'leave_credits',
        'approved_by',
        'rejected_by',
    ];

    public function user()
    {
        return $this->belongsTo(RegisteredUsers::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(RegisteredUsers::class, 'approved_by');
    }

    public function rejecter()
    {
        return $this->belongsTo(RegisteredUsers::class, 'rejected_by');
    }

    /**
     * Get the duration attribute dynamically calculated from date_from and date_to.
     */
    public function getDurationAttribute()
    {
        if ($this->date_from && $this->date_to) {
            $from = \Carbon\Carbon::parse($this->date_from);
            $to = \Carbon\Carbon::parse($this->date_to);
            return $from->diffInDays($to) + 1; // Inclusive of both dates
        }
        return 0;
    }
}
