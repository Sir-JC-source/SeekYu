<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantCredential extends Model
{
    protected $fillable = [
        'user_id',
        'license_no',
        'certifications',
        'license_expiration_date',
        'years_of_experience',
        'work_history',
        'skills',
        'resume_path',
        'license_path',
        'training_certificate_path',
        'nbi_clearance_path',
        'is_first_time',
        'data_consent',
    ];

    protected $casts = [
        'license_expiration_date' => 'date',
        'work_history' => 'array',
        'skills' => 'array',
        'is_first_time' => 'boolean',
        'data_consent' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(RegisteredUsers::class, 'user_id');
    }
}
