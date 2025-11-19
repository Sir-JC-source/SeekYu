<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'job_post_id',
        'title',
        'position',
        'description',
        'type_of_employment',
        'location',
        'created_by', // ✅ Important: allow assigning the creator ID
        'status',
        'role', // Added role field
    ];

    /**
     * Relationship: the user who created this job posting.
     * 
     * Assumes your users are stored in the RegisteredUsers model
     * and the column 'created_by' references that user's ID.
     */
    public function creator()
    {
        return $this->belongsTo(RegisteredUsers::class, 'created_by');
    }

    /**
     * Optional helper — get the creator’s name safely.
     * You can use $job->creator_name in Blade templates.
     */
    public function getCreatorNameAttribute()
    {
        return $this->postedBy ? $this->postedBy->name ?? 'Unknown User' : 'Unknown User';
    }

    /**
     * Relationship: applications for this job posting.
     */
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
