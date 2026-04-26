<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name',
        'department',
        'year_from',
        'year_to',
        'position',
        'company',
        'content',
        'image',
        'status'
    ];

    protected $casts = [
        'year_from' => 'integer',
        'year_to' => 'integer',
    ];
}
