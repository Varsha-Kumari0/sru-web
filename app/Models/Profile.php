<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'profile_photo',
        'full_name',
        'father_name',
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
        'company',
        'description',
    ];
    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}