<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantGameScore extends Model
{
    protected $fillable = [
        'job_application_id',
        'game_type',
        'score',
        'total',
        'percentage',
        'time_taken',
    ];

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class);
    }
}
