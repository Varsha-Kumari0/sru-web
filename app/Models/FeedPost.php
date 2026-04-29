<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeedPost extends Model
{
    protected $fillable = [
        'user_id',
        'post_type',
        'body',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(FeedComment::class, 'feed_id')->where('feed_type', 'post');
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(FeedReaction::class, 'feed_id')->where('feed_type', 'post');
    }
}
