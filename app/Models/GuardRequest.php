<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuardRequest extends Model
{
    protected $fillable = [
        'client_id',
        'number_of_guards',
        'request_details',
        'status',
    ];

    /**
     * Get the client (registered user) who made the request.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(\App\Models\RegisteredUsers::class, 'client_id');
    }
}
