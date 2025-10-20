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
        'duration',
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
}
