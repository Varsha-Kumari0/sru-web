<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileView extends Model
{
    protected $fillable = [
        'profile_user_id',
        'visitor_user_id',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function profileUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'profile_user_id');
    }

    public function visitorUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'visitor_user_id');
    }
}
