<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',

        'full_name',
        'mobile',

        'city',
        'country',

        'linkedin',
        'facebook',
        'instagram',
        'twitter',

        'degree',
        'branch',
        'passing_year',

        'current_status',
        'company'
    ];
}