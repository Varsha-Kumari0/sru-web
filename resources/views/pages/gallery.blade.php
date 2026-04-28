@extends('layouts.app')

@section('title', 'Gallery')

@section('content')
<div class="-m-6 min-h-screen bg-[#f2f0e8]">
    <section class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl">
            <div class="mb-3 flex items-end gap-2">
                <h1 class="text-4xl font-light tracking-tight text-[#1d2f54]">Albums</h1>
                <span class="pb-1 text-sm text-slate-400">Pictures from SRUNI</span>
            </div>

            <div class="rounded-xl bg-white/90 p-1 shadow-sm ring-1 ring-black/5">
                <div class="flex flex-wrap gap-1" role="tablist" aria-label="Gallery sections">
                    <button type="button" class="gallery-tab is-active rounded-lg px-4 py-2 text-sm font-semibold text-[#1d2f54] transition hover:bg-[#edf3ff]" data-tab="albums" aria-selected="true">Albums</button>
                    <button type="button" class="gallery-tab rounded-lg px-4 py-2 text-sm font-semibold text-slate-500 transition hover:bg-[#edf3ff]" data-tab="videos" aria-selected="false">Videos</button>
                    <button type="button" class="gallery-tab rounded-lg px-4 py-2 text-sm font-semibold text-slate-500 transition hover:bg-[#edf3ff]" data-tab="memories" aria-selected="false">Memories</button>
                </div>
            </div>

            <div class="mt-4 rounded-2xl bg-white px-5 py-5 shadow-sm ring-1 ring-black/5 sm:px-6 sm:py-6">
                <div class="gallery-panel" data-panel="albums">
                    @if($albums->isNotEmpty())
                        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 xl:grid-cols-3">
                            @foreach($albums as $album)
                                <article class="group w-full overflow-hidden rounded-xl bg-white text-center shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-md">
                                    <a href="{{ route('gallery.album.show', $album->id) }}" class="block">
                                        <div class="aspect-[4/5] overflow-hidden bg-slate-100">
                                            @php $coverImage = $album->getCoverImageUrl(); @endphp
                                            @if($coverImage)
                                                <img src="{{ $coverImage }}" alt="{{ $album->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                            @endif
                                        </div>
                                        <div class="px-3 py-3">
                                            <h2 class="text-base font-semibold text-[#1d2f54]">{{ $album->title }}</h2>
                                            <p class="mt-1 text-xs text-slate-400">{{ $album->photo_count }} Photos</p>
                                        </div>
                                    </a>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-10 text-center text-slate-500">
                            Albums will appear here once they are added in the database.
                        </div>
                    @endif
                </div>

                <div class="gallery-panel hidden" data-panel="videos">
                    @if($videos->isNotEmpty())
                        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                            @foreach($videos as $video)
                                @php
                                    $thumbnail = $video->thumbnail_image ? asset('images/' . $video->thumbnail_image) : asset('images/home-carosel2.jpeg');
                                @endphp
                                <article class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100">
                                    <div class="relative aspect-video overflow-hidden bg-slate-100">
                                        <img src="{{ $thumbnail }}" alt="{{ $video->title }}" class="h-full w-full object-cover">
                                        <div class="absolute inset-0 bg-slate-900/25"></div>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-white/90 text-lg text-[#1d2f54] shadow-lg">&#9658;</div>
                                        </div>
                                    </div>
                                    <div class="p-5">
                                        <div class="flex items-start justify-between gap-3">
                                            <h2 class="text-lg font-semibold text-[#1d2f54]">{{ $video->title }}</h2>
                                            @if($video->duration)
                                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">{{ $video->duration }}</span>
                                            @endif
                                        </div>
                                        @if($video->summary)
                                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $video->summary }}</p>
                                        @endif
                                        @if($video->video_url)
                                            <a href="{{ $video->video_url }}" target="_blank" rel="noreferrer" class="mt-4 inline-flex items-center rounded-full bg-[#153c83] px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-white transition hover:bg-[#1d2f54]">Watch video</a>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-10 text-center text-slate-500">
                            Videos will appear here once they are added in the database.
                        </div>
                    @endif
                </div>

                <div class="gallery-panel hidden" data-panel="memories">
                    @if($memories->isNotEmpty())
                        <div class="grid gap-5 lg:grid-cols-3">
                            @foreach($memories as $memory)
                                <article class="rounded-2xl border border-slate-100 bg-[#fcfcfb] p-6 shadow-sm">
                                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#c0922d]">Memory note</p>
                                    <h2 class="mt-3 text-xl font-semibold text-[#1d2f54]">{{ $memory->title }}</h2>
                                    <p class="mt-3 text-sm leading-7 text-slate-600">{{ $memory->excerpt }}</p>
                                    <div class="mt-5 flex items-center justify-between gap-4 text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">
                                        <span>{{ $memory->author_name ?: 'SRU Alumni' }}</span>
                                        <span>{{ optional($memory->published_at)->format('M Y') }}</span>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-10 text-center text-slate-500">
                            Memories will appear here once they are added in the database.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.gallery-tab');
        const panels = document.querySelectorAll('.gallery-panel');

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                const currentTab = tab.dataset.tab;

                tabs.forEach(function (item) {
                    const isActive = item === tab;
                    item.classList.toggle('is-active', isActive);
                    item.classList.toggle('bg-[#edf3ff]', isActive);
                    item.classList.toggle('text-[#1d2f54]', isActive);
                    item.classList.toggle('text-slate-500', !isActive);
                    item.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });

                panels.forEach(function (panel) {
                    panel.classList.toggle('hidden', panel.dataset.panel !== currentTab);
                });
            });
        });
    });
</script>
@endsection
