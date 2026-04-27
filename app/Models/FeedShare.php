<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedShare extends Model
{
    protected $fillable = [
        'user_id',
        'feed_type',
        'feed_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
