<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function photos(): HasMany
    {
        return $this->hasMany(GalleryAlbumPhoto::class)->orderBy('display_order');
    }

    public function getCoverImageUrl(): ?string
    {
        if ($this->cover_image) {
            return asset('images/' . $this->cover_image);
        }
        $photo = $this->photos()->inRandomOrder()->first();
        if ($photo) {
            return asset('images/albums/' . $photo->file_name);
        }
        return null;
    }
}