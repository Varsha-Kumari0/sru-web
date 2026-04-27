<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedComment extends Model
{
    protected $fillable = [
        'user_id',
        'feed_type',
        'feed_id',
        'body',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
