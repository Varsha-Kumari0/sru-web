@extends('layouts.app')

@section('title', 'Events')

@section('content')

{{--
    SRU Alumni App Theme (matches Newsroom):
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
    .event-card {
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .event-card:hover {
        box-shadow: 0 6px 24px rgba(26, 45, 74, 0.10);
        transform: translateY(-2px);
    }
    .event-card:hover .event-card-title {
        color: #2a9d8f;
    }
    .category-link {
        transition: background 0.15s, color 0.15s, border-color 0.15s;
        border-left: 3px solid transparent;
    }
    .category-link:hover {
        background: #f0fafa;
        border-left-color: #2a9d8f;
        color: #1a2d4a;
    }
    .category-link.active {
        background: #f0fafa;
        border-left-color: #2a9d8f;
        color: #1a2d4a;
        font-weight: 700;
    }
    .filter-btn {
        transition: background 0.15s, color 0.15s, border-color 0.15s;
    }
    .filter-btn.active {
        background: #1a2d4a;
        color: #ffffff;
        border-color: #1a2d4a;
    }
    .view-event-btn {
        transition: background 0.15s, transform 0.15s;
    }
    .view-event-btn:hover {
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
                <h1 class="text-3xl font-bold text-white tracking-tight">Events</h1>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- ═══════════════════════════════
             HEADER ROW: subtitle + filter buttons
        ═══════════════════════════════ --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-8 fade-up fu1">
            <p class="text-sm" style="color: #64748b;">View upcoming, past, and all SRU alumni events.</p>

            <div class="flex flex-wrap gap-2">
                @foreach($allowedFilters as $filterKey => $label)
                    <a href="{{ route('events.index', ['filter' => $filterKey]) }}"
                       class="filter-btn rounded-xl px-4 py-2 text-sm font-semibold border transition
                              {{ $currentFilter === $filterKey
                                 ? 'active'
                                 : 'bg-white border-gray-200 hover:bg-[#f0fafa] hover:border-[#2a9d8f]' }}"
                       style="{{ $currentFilter === $filterKey ? '' : 'color:#1a2d4a;' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- ═══════════════════════════════
                 LEFT: EVENT CARDS
            ═══════════════════════════════ --}}
            <div class="md:col-span-2 space-y-5">

                @if($events->isEmpty())
                    <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center shadow-sm fade-up fu2">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"
                             style="background: #f0fafa;">
                            <svg class="w-8 h-8" style="color: #2a9d8f;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="font-semibold mb-1" style="color: #1a2d4a;">No events found</p>
                        <p class="text-sm" style="color: #94a3b8;">No events for this category. Try another filter or check back later.</p>
                    </div>

                @else
                    <div class="mb-2 text-sm fade-up fu2" style="color: #64748b;">
                        Showing {{ $events->count() }} event{{ $events->count() === 1 ? '' : 's' }}@if($currentType) for {{ $eventTypes[$currentType] }}@endif.
                    </div>

                    @foreach($events as $index => $event)
                        <div class="event-card bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden fade-up"
                             style="animation-delay: {{ 0.08 + $index * 0.07 }}s;">
                            <div class="flex flex-col sm:flex-row sm:items-stretch">

                                {{-- Image --}}
                                @if($event->image)
                                    <div class="sm:w-44 shrink-0">
                                        <img src="/images/{{ $event->image }}"
                                             class="w-full h-44 sm:h-full object-cover"
                                             alt="{{ $event->title }}">
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
                                        {{-- Type tag + date --}}
                                        <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                            <span class="inline-block text-xs font-bold uppercase tracking-wide"
                                                  style="color: #c9a84c;">
                                                {{ $eventTypes[$event->event_type] ?? ucwords(str_replace('-', ' ', $event->event_type)) }}
                                            </span>
                                            <span class="text-xs" style="color: #94a3b8;">
                                                {{ $event->start_at->format('jS M, Y') }}
                                            </span>
                                        </div>

                                        <h3 class="event-card-title text-lg font-bold leading-snug mb-2 transition-colors"
                                            style="color: #1a2d4a;">
                                            {{ $event->title }}
                                        </h3>

                                        <p class="text-sm leading-relaxed line-clamp-2" style="color: #64748b;">
                                            {{ $event->excerpt }}
                                        </p>
                                    </div>

                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mt-4 pt-4"
                                         style="border-top: 1px solid #f1f5f9;">
                                        <div class="text-xs space-y-0.5" style="color: #64748b;">
                                            <p>{{ $event->location }}</p>
                                            <p>{{ $event->start_at->format('g:i a') }}@if($event->end_at) – {{ $event->end_at->format('g:i a') }}@endif</p>
                                        </div>
                                        <a href="{{ route('events.show', $event->id) }}"
                                           class="view-event-btn inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white"
                                           style="background: #1a2d4a;">
                                            View event
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
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

                {{-- Categories --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                    <h3 class="sru-section-label mb-4">Categories</h3>
                    <p class="text-xs mb-3" style="color: #94a3b8;">Filter events by type.</p>
                    <div class="space-y-1">
                        <a href="{{ route('events.index', ['filter' => $currentFilter]) }}"
                           class="category-link flex items-center justify-between px-3 py-2.5 rounded-xl text-sm {{ !$currentType ? 'active' : '' }}"
                           style="color: #334155;">
                            <span>All categories</span>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full"
                                  style="background: #f0fafa; color: #2a9d8f;">
                                {{ array_sum($typeCounts) }}
                            </span>
                        </a>
                        @foreach($eventTypes as $typeKey => $label)
                            <a href="{{ route('events.index', array_merge(['filter' => $currentFilter, 'type' => $typeKey])) }}"
                               class="category-link flex items-center justify-between px-3 py-2.5 rounded-xl text-sm {{ $currentType === $typeKey ? 'active' : '' }}"
                               style="color: #334155;">
                                <span>{{ $label }}</span>
                                @if(isset($typeCounts[$typeKey]))
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full"
                                          style="background: #f0fafa; color: #2a9d8f;">
                                        {{ $typeCounts[$typeKey] }}
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Status legend --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                    <h3 class="sru-section-label mb-3">Status legend</h3>
                    <p class="text-sm" style="color: #64748b;">
                        Upcoming events are shown in chronological order. Past events remain visible in the selected filter.
                    </p>
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