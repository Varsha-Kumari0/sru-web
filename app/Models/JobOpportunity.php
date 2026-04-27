<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobOpportunity extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'company_name',
        'company_website',
        'experience_level',
        'work_mode',
        'location',
        'contact_email',
        'job_area',
        'skills',
        'salary',
        'application_deadline',
        'description',
        'attachment',
        'attachment_original_name',
    ];

    protected $casts = [
        'skills' => 'array',
        'application_deadline' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
