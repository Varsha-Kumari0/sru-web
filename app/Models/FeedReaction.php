<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedReaction extends Model
{
    protected $fillable = [
        'user_id',
        'feed_type',
        'feed_id',
        'reaction',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
