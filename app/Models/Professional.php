<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Professional extends Model
{
    protected $fillable = [
        'user_id',
        'organization',
        'industry',
        'role',
        'from',
        'to',
        'location'
    ];

    /**
     * Get the user that owns the professional record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
