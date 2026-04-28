<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Connection extends Model
{
    protected $fillable = [
        'user_id',
        'connected_user_id',
        'status', // pending, connected, blocked
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function connectedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'connected_user_id');
    }
}
