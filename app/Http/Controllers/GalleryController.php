<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\GalleryAlbum;
use App\Models\GalleryVideo;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $albums = GalleryAlbum::query()
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->orderBy('display_order', 'asc')
            ->orderByDesc('published_at')
            ->get();

        $videos = GalleryVideo::query()
            ->where('is_active', true)
            ->orderBy('display_order', 'asc')
            ->orderByDesc('published_at')
            ->get();

        $featuredAlbum = $albums->firstWhere('is_featured', true) ?? $albums->first();

        $actor = Auth::user();
        if ($actor) {
            ActivityLog::record(
                $actor->id,
                $actor->id,
                'gallery_viewed',
                ($actor->name ?? 'Alumni') . ' viewed gallery page',
                [
                    'albums_count' => $albums->count(),
                    'videos_count' => $videos->count(),
                    'featured_album_id' => $featuredAlbum?->id,
                ]
            );
        }

        return view('pages.gallery', compact('albums', 'videos', 'featuredAlbum'));
    }

    public function albumShow(int $id): View
    {
        $album = GalleryAlbum::query()
            ->where('is_active', true)
            ->with('photos')
            ->findOrFail($id);

        $actor = Auth::user();
        if ($actor) {
            ActivityLog::record(
                $actor->id,
                $actor->id,
                'gallery_album_viewed',
                ($actor->name ?? 'Alumni') . ' viewed gallery album: ' . ($album->title ?? 'Album'),
                [
                    'album_id' => $album->id,
                    'title' => $album->title,
                ]
            );
        }

        return view('pages.gallery-album', compact('album'));
    }

    public function videoShow(int $id): View
    {
        $video = GalleryVideo::query()
            ->where('is_active', true)
            ->findOrFail($id);

        $actor = Auth::user();
        if ($actor) {
            ActivityLog::record(
                $actor->id,
                $actor->id,
                'gallery_video_viewed',
                ($actor->name ?? 'Alumni') . ' viewed gallery video: ' . ($video->title ?? 'Video'),
                [
                    'video_id' => $video->id,
                    'title' => $video->title,
                ]
            );
        }

        return view('pages.gallery-video', compact('video'));
    }
}