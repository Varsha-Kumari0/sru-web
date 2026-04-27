<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'summary',
        'thumbnail_image',
        'video_url',
        'duration',
        'is_active',
        'published_at',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'date',
        'display_order' => 'integer',
    ];
}