<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    //
    protected $fillable = [
    'user_id',
    'organization',
    'industry',
    'role',
    'from',
    'to',
    'location'
];
}
