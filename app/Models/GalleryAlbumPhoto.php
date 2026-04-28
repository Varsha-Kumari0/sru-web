<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryAlbumPhoto extends Model
{
    protected $fillable = [
        'gallery_album_id',
        'file_name',
        'display_order',
    ];

    protected $casts = [
        'display_order' => 'integer',
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }
}
