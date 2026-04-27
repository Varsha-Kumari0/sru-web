@extends('layouts.app')

@section('content')

{{--
    SRU Alumni App Theme:
    Navy:   #1a2d4a  |  Teal: #2a9d8f  |  Gold: #c9a84c
    Page bg: #f0f0ee  |  Card: #ffffff  |  Border: #e5e7eb
--}}

<style>
    .sru-hero-gradient {
        background: linear-gradient(135deg, #1a2d4a 0%, #1e4a52 50%, #2a9d8f 100%);
    }
    .sru-section-label {
        display: inline-block;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: #1a2d4a;
        border-bottom: 3px solid #c9a84c;
        padding-bottom: 4px;
    }
    .news-card {
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .news-card:hover {
        box-shadow: 0 6px 24px rgba(26, 45, 74, 0.10);
        transform: translateY(-2px);
    }
    .news-card:hover .news-card-title {
        color: #2a9d8f;
    }
    .archive-link {
        transition: background 0.15s, color 0.15s, border-color 0.15s;
        border-left: 3px solid transparent;
    }
    .archive-link:hover {
        background: #f0fafa;
        border-left-color: #2a9d8f;
        color: #1a2d4a;
    }
    .archive-link.active {
        background: #f0fafa;
        border-left-color: #2a9d8f;
        color: #1a2d4a;
        font-weight: 700;
    }
    .read-more-btn {
        transition: background 0.15s, transform 0.15s;
    }
    .read-more-btn:hover {
        background: #2a9d8f !important;
        transform: translateX(2px);
    }
    .fade-up {
        animation: sruFadeUp 0.4s ease both;
    }
    .fu1 { animation-delay: 0.04s; }
    .fu2 { animation-delay: 0.10s; }
    .fu3 { animation-delay: 0.17s; }
    @keyframes sruFadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="min-h-screen" style="background: #f0f0ee;">

    {{-- ═══════════════════════════════
         PAGE HERO BANNER
    ═══════════════════════════════ --}}
    <div class="sru-hero-gradient relative overflow-hidden" style="height: 160px;">
        <div class="absolute -top-10 -left-10 w-60 h-60 rounded-full" style="background:rgba(255,255,255,0.03);"></div>
        <div class="absolute -bottom-16 right-16 w-80 h-80 rounded-full" style="background:rgba(255,255,255,0.03);"></div>
        <div class="absolute bottom-2 left-6 font-black select-none tracking-tighter leading-none"
             style="color:rgba(255,255,255,0.055); font-size:5rem;">SRU</div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-end pb-6">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest mb-1" style="color: #2a9d8f; opacity: 0.9;">
                    SR University
                </p>
                <h1 class="text-3xl font-bold text-white tracking-tight">Newsroom</h1>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- ═══════════════════════════════
             HEADER ROW
        ═══════════════════════════════ --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-8 fade-up fu1">
            <div>
                <p class="text-sm" style="color: #64748b;">All the News and Updates from SRUNI.</p>
            </div>

            @if($selectedMonth)
                <div class="flex flex-wrap items-center gap-2 text-sm" style="color: #64748b;">
                    <span class="font-medium">Showing:</span>
                    <span class="flex items-center gap-1.5 px-3 py-1 rounded-full border font-semibold"
                          style="background: #f8fffe; color: #2a9d8f; border-color: #b2ece5;">
                        📅 {{ $selectedMonth }}
                    </span>
                    <a href="{{ route('newsroom') }}"
                       class="inline-flex items-center gap-1 px-3 py-1 rounded-full border text-xs font-semibold transition-all hover:bg-[#1a2d4a] hover:text-white hover:border-[#1a2d4a]"
                       style="color: #1a2d4a; border-color: #1a2d4a;">
                        ✕ Clear filter
                    </a>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- ═══════════════════════════════
                 LEFT: NEWS FEED
            ═══════════════════════════════ --}}
            <div class="md:col-span-2 space-y-5">

                @if($news->isEmpty())
                    <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center shadow-sm fade-up fu2">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"
                             style="background: #f0fafa;">
                            <svg class="w-8 h-8" style="color: #2a9d8f;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        <p class="font-semibold mb-1" style="color: #1a2d4a;">No news found</p>
                        <p class="text-sm" style="color: #94a3b8;">
                            No news items for this archive. Try a different month or
                            <a href="{{ route('newsroom') }}" class="font-semibold hover:underline" style="color: #2a9d8f;">clear the filter</a>.
                        </p>
                    </div>

                @else
                    @foreach($news as $index => $item)
                        <div class="news-card bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden fade-up"
                             style="animation-delay: {{ 0.08 + $index * 0.07 }}s;">
                            <div class="flex flex-col sm:flex-row sm:items-stretch">

                                {{-- Image --}}
                                @if($item->image)
                                    <div class="sm:w-44 shrink-0">
                                        <img src="/images/{{ $item->image }}"
                                             class="w-full h-44 sm:h-full object-cover"
                                             alt="{{ $item->title }}">
                                    </div>
                                @else
                                    <div class="sm:w-44 shrink-0 flex items-center justify-center"
                                         style="background: linear-gradient(135deg, #1a2d4a, #2a9d8f); min-height: 140px;">
                                        <span class="text-white font-black text-3xl opacity-20 select-none">SRU</span>
                                    </div>
                                @endif

                                {{-- Content --}}
                                <div class="flex-1 p-5 flex flex-col justify-between">
                                    <div>
                                        {{-- Month tag --}}
                                        <span class="inline-block text-xs font-bold uppercase tracking-wide mb-2"
                                              style="color: #c9a84c;">
                                            {{ \Carbon\Carbon::parse($item->published_at)->format('F Y') }}
                                        </span>

                                        <h3 class="news-card-title text-lg font-bold leading-snug mb-2 transition-colors"
                                            style="color: #1a2d4a;">
                                            {{ $item->title }}
                                        </h3>

                                        <p class="text-sm leading-relaxed line-clamp-2" style="color: #64748b;">
                                            {{ $item->excerpt }}
                                        </p>
                                    </div>

                                    <div class="flex items-center justify-between mt-4 pt-4"
                                         style="border-top: 1px solid #f1f5f9;">
                                        <a href="{{ route('news.show', $item->id) }}"
                                           class="read-more-btn inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white"
                                           style="background: #1a2d4a;">
                                            Read More
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                        <span class="text-xs" style="color: #94a3b8;">
                                            {{ \Carbon\Carbon::parse($item->published_at)->format('jS M, Y') }}
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                @endif

            </div>

            {{-- ═══════════════════════════════
                 RIGHT: SIDEBAR
            ═══════════════════════════════ --}}
            <div class="space-y-5 fade-up fu3">

                {{-- Archive --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                    <h3 class="sru-section-label mb-4">Archive</h3>
                    <div class="space-y-1">
                        @forelse($archives as $monthKey => $data)
                            <a href="{{ route('newsroom', ['archive' => $monthKey]) }}"
                               class="archive-link flex items-center justify-between px-3 py-2.5 rounded-xl text-sm"
                               style="color: #334155;"
                               @class(['active' => $archive === $monthKey])>
                                <span>{{ $data['label'] }}</span>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full"
                                      style="background: #f0fafa; color: #2a9d8f;">
                                    {{ $data['count'] }}
                                </span>
                            </a>
                        @empty
                            <p class="text-xs px-3 py-2" style="color: #94a3b8;">No archives yet.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Stay Connected CTA --}}
                <div class="rounded-2xl p-5 text-center sru-hero-gradient">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <p class="text-white text-sm font-semibold mb-1">Stay Connected</p>
                    <p class="text-white/65 text-xs mb-3">Never miss an update from SRUNI.</p>
                    <a href="{{ route('profile') }}"
                       class="inline-block px-5 py-2 rounded-xl bg-white text-xs font-bold hover:opacity-90 transition-opacity"
                       style="color: #1a2d4a;">
                        Go to Profile
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection