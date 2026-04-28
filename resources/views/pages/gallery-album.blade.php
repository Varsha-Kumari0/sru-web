@extends('layouts.app')

@section('title', $album->title . ' — Gallery')

@section('content')
<div class="-m-6 min-h-screen bg-[#f2f0e8]">
    <section class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl">

            {{-- Breadcrumb --}}
            <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
                <a href="{{ route('gallery') }}" class="hover:text-[#1d2f54]">Gallery</a>
                <span>/</span>
                <span class="text-[#1d2f54] font-medium">{{ $album->title }}</span>
            </nav>

            {{-- Album header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-light tracking-tight text-[#1d2f54]">{{ $album->title }}</h1>
                @if($album->summary)
                    <p class="mt-2 text-sm text-slate-500 max-w-2xl">{{ $album->summary }}</p>
                @endif
                <div class="mt-3 flex items-center gap-4 text-xs text-slate-400">
                    <span>{{ $album->photo_count }} {{ Str::plural('photo', $album->photo_count) }}</span>
                    @if($album->published_at)
                        <span>{{ $album->published_at->format('F Y') }}</span>
                    @endif
                </div>
            </div>

            {{-- Photos grid --}}
            @if($album->photos->isNotEmpty())
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5">
                    @foreach($album->photos as $photo)
                        <a href="{{ asset('images/albums/' . $photo->file_name) }}" target="_blank" rel="noopener noreferrer"
                           class="group aspect-square overflow-hidden rounded-xl bg-slate-100 ring-1 ring-black/5 transition hover:-translate-y-0.5 hover:shadow-md">
                            <img src="{{ asset('images/albums/' . $photo->file_name) }}"
                                 alt="Photo from {{ $album->title }}"
                                 class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                 loading="lazy">
                        </a>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-200 bg-white px-6 py-16 text-center">
                    @php $cover = $album->cover_image ? asset('images/' . $album->cover_image) : null; @endphp
                    @if($cover)
                        <img src="{{ $cover }}" alt="{{ $album->title }}" class="mx-auto mb-4 h-40 w-auto rounded-xl object-cover shadow-sm">
                    @endif
                    <p class="text-sm text-slate-500">No individual photos have been added to this album yet.</p>
                </div>
            @endif

            <div class="mt-10">
                <a href="{{ route('gallery') }}" class="inline-flex items-center gap-2 rounded-full bg-[#1d2f54] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#153c83]">
                    ← Back to Gallery
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
