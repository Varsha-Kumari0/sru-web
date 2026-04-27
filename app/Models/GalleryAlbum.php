<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryAlbum extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'summary',
        'cover_image',
        'photo_count',
        'is_featured',
        'is_active',
        'published_at',
        'display_order',
    ];

    protected $casts = [
        'photo_count' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'published_at' => 'date',
        'display_order' => 'integer',
    ];
}