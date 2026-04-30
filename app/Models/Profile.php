<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'gender',
        'profile_photo',
        'full_name',
        'father_name',
        'mobile',
        'contact_email',
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
        'pursuing_educational_level',
        'highest_completed_educational_level',
        'company',
        'employment_from',
        'employment_to',
        'study_institution',
        'study_degree',
        'study_branch',
        'study_from',
        'study_to',
        'previous_education',
        'description',
    ];

    protected $casts = [
        'previous_education' => 'array',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}