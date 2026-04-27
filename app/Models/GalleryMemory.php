<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryMemory extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'excerpt',
        'author_name',
        'cover_image',
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