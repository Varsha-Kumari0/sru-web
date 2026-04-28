@extends('layouts.app')

@section('title', $video->title . ' - Video Gallery')

@section('content')
<div class="-m-6 min-h-screen bg-[#f2f0e8]">
    <section class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl">
            <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
                <a href="{{ route('gallery') }}" class="hover:text-[#1d2f54]">Gallery</a>
                <span>/</span>
                <span class="text-[#1d2f54] font-medium">{{ $video->title }}</span>
            </nav>

            <div class="mb-6">
                <h1 class="text-3xl font-light tracking-tight text-[#1d2f54]">{{ $video->title }}</h1>
                @if($video->summary)
                    <p class="mt-2 max-w-2xl text-sm text-slate-500">{{ $video->summary }}</p>
                @endif
                <div class="mt-3 flex items-center gap-4 text-xs text-slate-400">
                    @if($video->duration)
                        <span>{{ $video->duration }}</span>
                    @endif
                    @if($video->published_at)
                        <span>{{ $video->published_at->format('F Y') }}</span>
                    @endif
                </div>
            </div>

            @php
                $sourceUrl = $video->video_url;
                $embedUrl = null;

                if ($video->video_url) {
                    $parsed = parse_url($video->video_url);
                    $host = strtolower($parsed['host'] ?? '');

                    if (str_contains($host, 'youtube.com') || str_contains($host, 'youtu.be')) {
                        $videoId = null;

                        if (str_contains($host, 'youtu.be')) {
                            $videoId = trim($parsed['path'] ?? '', '/');
                        } else {
                            parse_str($parsed['query'] ?? '', $query);
                            $videoId = $query['v'] ?? null;
                            if (! $videoId && ! empty($parsed['path'])) {
                                $pathParts = explode('/', trim($parsed['path'], '/'));
                                if (in_array($pathParts[0] ?? '', ['shorts', 'embed'], true)) {
                                    $videoId = $pathParts[1] ?? null;
                                }
                            }
                        }

                        if ($videoId) {
                            $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                        }
                    } elseif (str_contains($host, 'vimeo.com')) {
                        $pathParts = explode('/', trim($parsed['path'] ?? '', '/'));
                        $videoId = end($pathParts);
                        if ($videoId) {
                            $embedUrl = 'https://player.vimeo.com/video/' . $videoId;
                        }
                    }
                }
            @endphp

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/5">
                <div class="aspect-video bg-black">
                    @if($embedUrl)
                        <iframe src="{{ $embedUrl }}" title="{{ $video->title }}" class="h-full w-full" allowfullscreen allow="autoplay; encrypted-media" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                    @elseif($sourceUrl)
                        <video class="h-full w-full" controls playsinline preload="metadata">
                            <source src="{{ $sourceUrl }}">
                            Your browser does not support the video tag.
                        </video>
                    @else
                        <div class="flex h-full w-full items-center justify-center text-slate-300">Video source unavailable.</div>
                    @endif
                </div>
                <div class="p-5">
                    @if($video->video_url)
                        <a href="{{ $video->video_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-full bg-[#153c83] px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-white transition hover:bg-[#1d2f54]">Open Source Link</a>
                    @endif
                </div>
            </div>

            <div class="mt-8">
                <a href="{{ route('gallery') }}" class="inline-flex items-center gap-2 rounded-full bg-[#1d2f54] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#153c83]">
                    <- Back to Gallery
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
