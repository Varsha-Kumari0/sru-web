<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function profileUser()
    {
        return $this->belongsTo(User::class, 'profile_user_id');
    }

    public function visitorUser()
    {
        return $this->belongsTo(User::class, 'visitor_user_id');
    }
}
