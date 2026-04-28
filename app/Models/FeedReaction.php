<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedReaction extends Model
{
    protected $fillable = [
        'user_id',
        'feed_type',
        'feed_id',
        'reaction',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
