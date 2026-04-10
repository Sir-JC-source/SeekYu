<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    protected $fillable = [
        'job_posting_id',
        'user_id',
        'status',
        'applied_at',
        'rejection_notes',
        'rejected_at',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(RegisteredUsers::class, 'user_id');
    }

    public function gameScores()
    {
        return $this->hasMany(ApplicantGameScore::class);
    }
}

