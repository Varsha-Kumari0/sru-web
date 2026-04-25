<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'excerpt',
        'description',
        'image',
        'event_type',
        'location',
        'start_at',
        'end_at',
        'registration_link',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];
}
