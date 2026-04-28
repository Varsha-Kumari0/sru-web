<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use App\Models\GalleryMemory;
use App\Models\GalleryVideo;

class GalleryController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::query()
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->orderBy('display_order')
            ->orderByDesc('published_at')
            ->get();

        $videos = GalleryVideo::query()
            ->where('is_active', true)
            ->orderBy('display_order')
            ->orderByDesc('published_at')
            ->get();

        $memories = GalleryMemory::query()
            ->where('is_active', true)
            ->orderBy('display_order')
            ->orderByDesc('published_at')
            ->get();

        $featuredAlbum = $albums->firstWhere('is_featured', true) ?? $albums->first();

        return view('pages.gallery', compact('albums', 'videos', 'memories', 'featuredAlbum'));
    }

    public function albumShow(int $id)
    {
        $album = GalleryAlbum::query()
            ->where('is_active', true)
            ->with('photos')
            ->findOrFail($id);

        return view('pages.gallery-album', compact('album'));
    }
}