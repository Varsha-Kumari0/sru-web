@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .sru-hero-gradient {
        background: linear-gradient(135deg, #1a2d4a 0%, #1f4c55 48%, #2a9d8f 100%);
    }
    .sru-label {
        display: inline-block;
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: #1a2d4a;
        border-bottom: 3px solid #c9a84c;
        padding-bottom: 4px;
    }
    .pulse-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 8px 28px rgba(26, 45, 74, 0.065);
    }
    .pulse-post {
        position: relative;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 5px 22px rgba(26, 45, 74, 0.055);
        overflow: hidden;
        transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
    }
    .pulse-post:hover {
        border-color: #b2ece5;
        box-shadow: 0 12px 34px rgba(26, 45, 74, 0.11);
        transform: translateY(-2px);
    }
    .pulse-action {
        border-radius: 999px;
        padding: 0.55rem 0.9rem;
        font-size: 0.82rem;
        font-weight: 800;
        color: #64748b;
        transition: background 0.15s ease, color 0.15s ease, border-color 0.15s ease;
    }
    .pulse-action:hover,
    .pulse-action.is-active {
        background: #eefaf8;
        color: #1a2d4a;
    }
    .avatar-mark {
        background: linear-gradient(135deg, #1a2d4a, #2a9d8f);
    }
    .compose-chip {
        border-radius: 999px;
        background: #f8f9fa;
        padding: 0.5rem 0.85rem;
        font-size: 0.75rem;
        font-weight: 800;
        color: #475569;
        transition: background 0.15s ease, color 0.15s ease;
    }
    .compose-chip.is-selected {
        background: #eefaf8;
        color: #1a2d4a;
        box-shadow: inset 0 0 0 1px #b2ece5;
    }
    .feed-toast {
        opacity: 0;
        transform: translateY(-6px);
        pointer-events: none;
        transition: opacity 0.18s ease, transform 0.18s ease;
    }
    .feed-toast.is-visible {
        opacity: 1;
        transform: translateY(0);
    }
    .feed-density-compact .pulse-post .p-5 {
        padding: 1rem !important;
    }
    .feed-density-compact .pulse-post h2 {
        font-size: 1.1rem;
        margin-top: 0.9rem;
    }
    .feed-density-compact .pulse-post p {
        line-height: 1.45rem;
    }
    .density-button.is-selected {
        background: #eefaf8;
        color: #1a2d4a;
        box-shadow: inset 0 0 0 1px #b2ece5;
    }
</style>

@php
    $displayName = trim((string) ($profile?->full_name ?? '')) ?: ($user->name ?? 'Alumni');
    $firstName = explode(' ', $displayName)[0] ?? 'Alumni';
    $initials = collect(explode(' ', $displayName))->filter()->take(2)->map(fn ($part) => strtoupper(substr($part, 0, 1)))->implode('') ?: 'A';
    $avatarUrl = $profile?->profile_photo ? asset('storage/' . $profile->profile_photo) : null;
    $profileCompletion = collect([
        $profile?->full_name,
        $profile?->mobile,
        $profile?->degree,
        $profile?->branch,
        $profile?->passing_year,
        $profile?->city,
        $profile?->description,
    ])->filter(fn ($value) => filled($value))->count();
    $profilePercent = (int) round(($profileCompletion / 7) * 100);

    $latestPosts = collect($latestPosts ?? []);
    $latestNews = collect($latestNews ?? []);
    $upcomingEvents = collect($upcomingEvents ?? []);
    $latestTestimonials = collect($latestTestimonials ?? []);

    $feedItems = collect();

    foreach ($latestPosts as $post) {
        $authorName = $post->user?->profile?->full_name ?: ($post->user?->name ?? 'Alumni');

        $feedItems->push([
            'feed_type' => 'post',
            'feed_id' => $post->id,
            'kind' => ucwords($post->post_type),
            'source' => $authorName,
            'time' => $post->created_at?->diffForHumans() ?? 'Recently',
            'title' => 'Shared by ' . $authorName,
            'body' => $post->body,
            'href' => route('profile'),
            'cta' => 'View profile',
            'accent' => '#2a9d8f',
            'sort_at' => $post->created_at,
        ]);
    }

    foreach ($latestNews as $item) {
        $feedItems->push([
            'feed_type' => 'news',
            'feed_id' => $item->id,
            'kind' => 'Campus Note',
            'source' => 'SRU Newsroom',
            'time' => optional($item->published_at ?? $item->created_at)->format('d M Y') ?? 'Recently',
            'title' => $item->title,
            'body' => $item->excerpt,
            'href' => route('news.show', $item->id),
            'cta' => 'Open note',
            'accent' => '#2a9d8f',
            'sort_at' => $item->published_at ?? $item->created_at,
        ]);
    }

    foreach ($upcomingEvents as $event) {
        $feedItems->push([
            'feed_type' => 'event',
            'feed_id' => $event->id,
            'kind' => 'Gathering',
            'source' => 'Events Desk',
            'time' => optional($event->start_at)->format('d M Y, g:i A') ?? 'Upcoming',
            'title' => $event->title,
            'body' => trim(($event->excerpt ?? '') . ' ' . ($event->location ? 'Venue: ' . $event->location : '')),
            'href' => route('events.show', $event->id),
            'cta' => 'Event details',
            'accent' => '#c9a84c',
            'sort_at' => $event->start_at,
        ]);
    }

    foreach ($latestTestimonials as $testimonial) {
        $feedItems->push([
            'feed_type' => 'testimonial',
            'feed_id' => $testimonial->id,
            'kind' => 'Alumni Voice',
            'source' => $testimonial->name,
            'time' => trim(($testimonial->position ?? '') . (($testimonial->company ?? null) ? ' at ' . $testimonial->company : '')) ?: 'Alumni story',
            'title' => 'A story from the network',
            'body' => $testimonial->content,
            'href' => route('testimonials.index'),
            'cta' => 'Read stories',
            'accent' => '#1a2d4a',
            'sort_at' => $testimonial->created_at,
        ]);
    }

    $feedItems = $feedItems
        ->sortByDesc(fn ($item) => $item['sort_at'] ?? now())
        ->values();

    $currentPrompt = 'What is one thing you wish every current SRU student knew before graduating?';
@endphp

<div class="-m-6 min-h-screen feed-density-{{ $feedDensity }}" data-dashboard-root style="background:#f0f0ee;">
    <section class="sru-hero-gradient">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-7">
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_340px] gap-6 items-center">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em]" style="color:#c9a84c;">SRU Alumni Pulse</p>
                    <h1 class="mt-2 text-3xl md:text-4xl font-bold text-white">Good to see you, {{ $firstName }}.</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-white/75">
                        A quieter community feed for campus updates, alumni voices, events, opportunities, and useful conversations.
                    </p>
                </div>

                <div class="rounded-2xl border border-white/15 bg-white/10 p-4 text-white backdrop-blur">
                    <div class="flex items-center gap-3">
                        <div class="h-14 w-14 rounded-2xl overflow-hidden inline-flex items-center justify-center font-black text-white avatar-mark shrink-0">
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" alt="{{ $displayName }}" class="h-full w-full object-cover">
                            @else
                                {{ $initials }}
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="truncate font-bold">{{ $displayName }}</p>
                            <p class="truncate text-xs text-white/65">{{ $profile?->branch ?? 'SRU Alumni' }} @if($profile?->passing_year) - {{ $profile->passing_year }} @endif</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="h-2 flex-1 rounded-full bg-white/15 overflow-hidden">
                            <div class="h-full rounded-full" style="width:{{ $profilePercent }}%; background:#c9a84c;"></div>
                        </div>
                        <span class="text-xs font-black">{{ $profilePercent }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-7">
        @if(session('status'))
            <div class="mb-5 rounded-2xl border border-[#b2ece5] bg-[#eefaf8] px-5 py-3 text-sm font-semibold text-[#1a2d4a]">
                {{ session('status') }}
            </div>
        @endif

        <section class="mb-6 grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="{{ route('engage') }}" class="pulse-card p-4 hover:border-[#2a9d8f]">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Mentor</p>
                <p class="mt-2 text-sm font-bold" style="color:#1a2d4a;">Guide someone</p>
            </a>
            <a href="{{ route('jobs.index') }}" class="pulse-card p-4 hover:border-[#2a9d8f]">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Jobs</p>
                <p class="mt-2 text-sm font-bold" style="color:#1a2d4a;">Share openings</p>
            </a>
            <a href="{{ route('events.index') }}" class="pulse-card p-4 hover:border-[#2a9d8f]">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Events</p>
                <p class="mt-2 text-sm font-bold" style="color:#1a2d4a;">Meet alumni</p>
            </a>
            <a href="{{ route('testimonials.index') }}" class="pulse-card p-4 hover:border-[#2a9d8f]">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Stories</p>
                <p class="mt-2 text-sm font-bold" style="color:#1a2d4a;">Read journeys</p>
            </a>
        </section>

        <div class="grid grid-cols-1 xl:grid-cols-[1fr_360px] gap-6 items-start">
            <section class="space-y-5">
                <div class="pulse-card p-5">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center">
                        <div class="flex-1">
                            <p class="sru-label">Community Prompt</p>
                            <p class="mt-3 text-lg font-bold leading-snug" style="color:#1a2d4a;">{{ $currentPrompt }}</p>
                        </div>
                        <a href="{{ route('contact') }}" class="rounded-xl px-5 py-3 text-center text-sm font-bold text-white sru-hero-gradient">
                            Suggest a prompt
                        </a>
                    </div>
                </div>

                <div class="pulse-card p-4">
                    <form method="POST" action="{{ route('dashboard.feed.posts.store') }}" class="js-compose-form flex gap-3">
                        @csrf
                        <div class="h-12 w-12 rounded-2xl overflow-hidden inline-flex items-center justify-center text-white font-black avatar-mark shrink-0">
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" alt="{{ $displayName }}" class="h-full w-full object-cover">
                            @else
                                {{ $initials }}
                            @endif
                        </div>
                        <div class="flex-1">
                            <textarea name="body" rows="1" maxlength="1200" required
                                      class="js-compose-body w-full resize-none rounded-2xl border border-gray-200 px-4 py-3 text-sm text-slate-700 focus:border-[#2a9d8f] focus:ring-[#2a9d8f]"
                                      placeholder="Share an achievement, question, referral, memory, or campus update"></textarea>
                            <input type="hidden" name="post_type" value="opportunity" class="js-compose-type">
                            <div class="mt-3 flex flex-wrap gap-2">
                                <button type="button" class="compose-chip is-selected" data-compose-type="opportunity">Opportunity</button>
                                <button type="button" class="compose-chip" data-compose-type="meetup">Meetup</button>
                                <button type="button" class="compose-chip" data-compose-type="memory">Memory</button>
                                <button type="button" class="compose-chip" data-compose-type="mentoring">Mentoring</button>
                                <button type="submit" class="ml-auto rounded-full px-5 py-2 text-xs font-black text-white sru-hero-gradient">Post</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="js-feed-list space-y-5">
                @forelse($feedItems as $item)
                    @php
                        $feedKey = $item['feed_type'] . ':' . $item['feed_id'];
                        $feedDomId = $item['feed_type'] . '-' . $item['feed_id'];
                        $isLiked = $viewerReactionKeys->has($feedKey);
                        $comments = $commentGroups->get($feedKey, collect())->take(3);
                    @endphp
                    <article class="pulse-post" data-feed-item data-feed-type="{{ $item['feed_type'] }}" data-feed-id="{{ $item['feed_id'] }}">
                        <div class="absolute left-0 top-0 h-full w-1.5" style="background:{{ $item['accent'] }};"></div>
                        <div class="p-5 md:p-6">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <span class="rounded-full px-3 py-1 text-[11px] font-black uppercase tracking-wide"
                                          style="background:#f8f9fa; color:{{ $item['accent'] }};">
                                        {{ $item['kind'] }}
                                    </span>
                                    <p class="mt-3 font-bold" style="color:#1a2d4a;">{{ $item['source'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $item['time'] }}</p>
                                </div>
                                <a href="{{ $item['href'] }}" class="rounded-xl border border-gray-200 px-4 py-2 text-xs font-bold text-slate-600 hover:border-[#2a9d8f]">
                                    {{ $item['cta'] }}
                                </a>
                            </div>

                            <h2 class="mt-5 text-xl md:text-2xl font-bold leading-snug" style="color:#1a2d4a;">{{ $item['title'] }}</h2>
                            <p class="mt-3 text-sm leading-7 text-slate-600">{{ $item['body'] }}</p>

                            <div class="mt-5 grid grid-cols-3 gap-2 border-y border-gray-100 py-3">
                                <form method="POST" action="{{ route('dashboard.feed.like', [$item['feed_type'], $item['feed_id']]) }}" class="js-like-form">
                                    @csrf
                                    <button type="submit" class="pulse-action w-full {{ $isLiked ? 'is-active' : '' }}" data-liked="{{ $isLiked ? '1' : '0' }}">
                                        <span class="js-like-label">{{ $isLiked ? 'Liked' : 'Like' }}</span>
                                        <span class="font-black js-like-count">{{ $reactionCounts->get($feedKey, 0) }}</span>
                                    </button>
                                </form>
                                <a href="#comments-{{ $feedDomId }}" class="pulse-action text-center js-focus-comment">
                                    Comment <span class="font-black js-comment-count">{{ $commentCounts->get($feedKey, 0) }}</span>
                                </a>
                                <form method="POST" action="{{ route('dashboard.feed.share', [$item['feed_type'], $item['feed_id']]) }}" class="js-share-form">
                                    @csrf
                                    <button type="submit" class="pulse-action w-full">
                                        Share <span class="font-black js-share-count">{{ $shareCounts->get($feedKey, 0) }}</span>
                                    </button>
                                </form>
                            </div>

                            <div id="comments-{{ $feedDomId }}" class="mt-4 space-y-3">
                                <div class="js-comments-list space-y-3">
                                @foreach($comments as $comment)
                                    <div class="rounded-2xl bg-[#f8f9fa] px-4 py-3">
                                        <p class="text-xs font-bold" style="color:#1a2d4a;">{{ $comment->user?->name ?? 'Alumni' }}</p>
                                        <p class="mt-1 text-sm leading-6 text-slate-600">{{ $comment->body }}</p>
                                    </div>
                                @endforeach
                                </div>

                                <form method="POST" action="{{ route('dashboard.feed.comments.store', [$item['feed_type'], $item['feed_id']]) }}" class="js-comment-form flex flex-col gap-2 sm:flex-row">
                                    @csrf
                                    <input name="body" maxlength="500" required
                                           class="min-w-0 flex-1 rounded-xl border-gray-200 text-sm focus:border-[#2a9d8f] focus:ring-[#2a9d8f]"
                                           placeholder="Add a thoughtful comment">
                                    <button type="submit" class="rounded-xl px-4 py-2 text-sm font-bold text-white sru-hero-gradient">
                                        Post
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="pulse-card p-8 text-center js-empty-feed">
                        <p class="text-lg font-bold" style="color:#1a2d4a;">Your alumni pulse is warming up.</p>
                        <p class="mt-2 text-sm text-slate-600">News, events, and alumni stories will appear here after they are published.</p>
                    </div>
                @endforelse
                </div>
            </section>

            <aside class="space-y-5 xl:sticky xl:top-5">
                <section class="pulse-card p-5">
                    <div class="flex items-center justify-between">
                        <h2 class="sru-label">Welcome Desk</h2>
                        <span class="text-xs font-bold text-slate-400">{{ $memberCount }} alumni</span>
                    </div>
                    <div class="mt-4 space-y-4">
                        @forelse($latestMembers as $member)
                            @php($memberName = $member->profile?->full_name ?: $member->name)
                            <a href="{{ route('messages.show', $member->id) }}" class="flex items-center gap-3">
                                <span class="h-10 w-10 rounded-2xl inline-flex items-center justify-center text-sm font-black text-white avatar-mark shrink-0">
                                    {{ strtoupper(substr($memberName ?: 'A', 0, 1)) }}
                                </span>
                                <span class="min-w-0">
                                    <span class="block truncate text-sm font-bold" style="color:#1a2d4a;">{{ $memberName }}</span>
                                    <span class="block truncate text-xs text-slate-500">{{ $member->profile?->branch ?? 'SRU Alumni' }}</span>
                                </span>
                            </a>
                        @empty
                            <p class="text-sm text-slate-500">New alumni will appear here.</p>
                        @endforelse
                    </div>
                </section>

                <section class="pulse-card p-5">
                    <div class="flex items-center justify-between">
                        <h2 class="sru-label">Next Up</h2>
                        <a href="{{ route('events.index') }}" class="text-xs font-bold" style="color:#2a9d8f;">All events</a>
                    </div>
                    <div class="mt-4 space-y-4">
                        @forelse($upcomingEvents as $event)
                            <a href="{{ route('events.show', $event->id) }}" class="block rounded-2xl border border-gray-100 bg-[#f8f9fa] p-4 hover:border-[#2a9d8f]">
                                <p class="text-sm font-bold leading-snug" style="color:#1a2d4a;">{{ $event->title }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $event->start_at?->format('d M Y') }} - {{ $event->location }}</p>
                            </a>
                        @empty
                            <p class="text-sm text-slate-500">No upcoming events yet.</p>
                        @endforelse
                    </div>
                </section>

                <section class="pulse-card p-5">
                    <h2 class="sru-label">Useful Doors</h2>
                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <a href="{{ route('profile') }}" class="rounded-xl bg-[#f8f9fa] px-3 py-3 text-sm font-bold text-[#1a2d4a] hover:bg-[#eefaf8]">Profile</a>
                        <a href="{{ route('messages.index') }}" class="rounded-xl bg-[#f8f9fa] px-3 py-3 text-sm font-bold text-[#1a2d4a] hover:bg-[#eefaf8]">Messages</a>
                        <a href="{{ route('gallery') }}" class="rounded-xl bg-[#f8f9fa] px-3 py-3 text-sm font-bold text-[#1a2d4a] hover:bg-[#eefaf8]">Gallery</a>
                        <a href="{{ route('contact') }}" class="rounded-xl bg-[#f8f9fa] px-3 py-3 text-sm font-bold text-[#1a2d4a] hover:bg-[#eefaf8]">Contact</a>
                    </div>
                    <div class="mt-4 rounded-2xl border border-[#b2ece5] bg-[#eefaf8] px-4 py-3">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500">This session</p>
                        <p class="mt-1 text-sm font-semibold text-[#1a2d4a]" data-session-action>{{ $lastSessionAction }}</p>
                    </div>
                </section>

                <section class="pulse-card p-5">
                    <h2 class="sru-label">Display Preference</h2>
                    <p class="mt-3 text-sm text-slate-600">Saved only if preference cookies are accepted.</p>
                    <form method="POST" action="{{ route('dashboard.preferences.feed-density') }}" class="mt-4 grid grid-cols-2 gap-2 js-density-form">
                        @csrf
                        <button type="submit" name="density" value="comfortable"
                                class="density-button rounded-xl bg-[#f8f9fa] px-3 py-3 text-sm font-bold text-slate-600 {{ $feedDensity === 'comfortable' ? 'is-selected' : '' }}">
                            Comfortable
                        </button>
                        <button type="submit" name="density" value="compact"
                                class="density-button rounded-xl bg-[#f8f9fa] px-3 py-3 text-sm font-bold text-slate-600 {{ $feedDensity === 'compact' ? 'is-selected' : '' }}">
                            Compact
                        </button>
                    </form>
                </section>

                <section class="rounded-2xl p-5 text-white sru-hero-gradient">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-white/60">Invite</p>
                    <h2 class="mt-3 text-xl font-bold">Bring your batch online</h2>
                    <p class="mt-2 text-sm leading-6 text-white/70">The feed becomes useful when classmates share jobs, events, milestones, and advice.</p>
                    <a href="mailto:?subject=Join the SRU Alumni Network&body=Join me on the SRU Alumni Network." class="mt-4 inline-block rounded-xl bg-white px-4 py-2 text-sm font-bold" style="color:#1a2d4a;">
                        Invite by email
                    </a>
                </section>
            </aside>
        </div>
    </main>

    <div class="feed-toast fixed right-5 top-5 z-50 rounded-2xl border border-[#b2ece5] bg-white px-5 py-3 text-sm font-bold shadow-lg" style="color:#1a2d4a;" data-feed-toast></div>
</div>

<script>
    (function () {
        const feedList = document.querySelector('.js-feed-list');
        const toast = document.querySelector('[data-feed-toast]');

        function showToast(message) {
            if (!toast) {
                return;
            }

            toast.textContent = message;
            toast.classList.add('is-visible');
            window.setTimeout(function () {
                toast.classList.remove('is-visible');
            }, 2200);
        }

        function updateSessionAction(message) {
            const sessionAction = document.querySelector('[data-session-action]');

            if (sessionAction) {
                sessionAction.textContent = message;
            }
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function csrfInput() {
            const token = document.querySelector('input[name="_token"]')?.value || '';
            return '<input type="hidden" name="_token" value="' + escapeHtml(token) + '">';
        }

        async function submitJson(form) {
            const response = await fetch(form.action, {
                method: form.method || 'POST',
                body: new FormData(form),
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                const data = await response.json().catch(function () {
                    return {};
                });
                throw new Error(data.message || 'Something went wrong.');
            }

            return response.json();
        }

        function feedArticle(post) {
            const domId = post.feed_type + '-' + post.feed_id;

            return `
                <article class="pulse-post" data-feed-item data-feed-type="${escapeHtml(post.feed_type)}" data-feed-id="${escapeHtml(post.feed_id)}">
                    <div class="absolute left-0 top-0 h-full w-1.5" style="background:${escapeHtml(post.accent)};"></div>
                    <div class="p-5 md:p-6">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <span class="rounded-full px-3 py-1 text-[11px] font-black uppercase tracking-wide" style="background:#f8f9fa; color:${escapeHtml(post.accent)};">
                                    ${escapeHtml(post.kind)}
                                </span>
                                <p class="mt-3 font-bold" style="color:#1a2d4a;">${escapeHtml(post.source)}</p>
                                <p class="text-xs text-slate-500">${escapeHtml(post.time)}</p>
                            </div>
                            <a href="${escapeHtml(post.href)}" class="rounded-xl border border-gray-200 px-4 py-2 text-xs font-bold text-slate-600 hover:border-[#2a9d8f]">
                                ${escapeHtml(post.cta)}
                            </a>
                        </div>

                        <h2 class="mt-5 text-xl md:text-2xl font-bold leading-snug" style="color:#1a2d4a;">${escapeHtml(post.title)}</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-600">${escapeHtml(post.body)}</p>

                        <div class="mt-5 grid grid-cols-3 gap-2 border-y border-gray-100 py-3">
                            <form method="POST" action="${escapeHtml(post.like_url)}" class="js-like-form">
                                ${csrfInput()}
                                <button type="submit" class="pulse-action w-full" data-liked="0">
                                    <span class="js-like-label">Like</span>
                                    <span class="font-black js-like-count">0</span>
                                </button>
                            </form>
                            <a href="#comments-${escapeHtml(domId)}" class="pulse-action text-center js-focus-comment">
                                Comment <span class="font-black js-comment-count">0</span>
                            </a>
                            <form method="POST" action="${escapeHtml(post.share_url)}" class="js-share-form">
                                ${csrfInput()}
                                <button type="submit" class="pulse-action w-full">
                                    Share <span class="font-black js-share-count">0</span>
                                </button>
                            </form>
                        </div>

                        <div id="comments-${escapeHtml(domId)}" class="mt-4 space-y-3">
                            <div class="js-comments-list space-y-3"></div>
                            <form method="POST" action="${escapeHtml(post.comment_url)}" class="js-comment-form flex flex-col gap-2 sm:flex-row">
                                ${csrfInput()}
                                <input name="body" maxlength="500" required class="min-w-0 flex-1 rounded-xl border-gray-200 text-sm focus:border-[#2a9d8f] focus:ring-[#2a9d8f]" placeholder="Add a thoughtful comment">
                                <button type="submit" class="rounded-xl px-4 py-2 text-sm font-bold text-white sru-hero-gradient">Post</button>
                            </form>
                        </div>
                    </div>
                </article>
            `;
        }

        document.querySelectorAll('[data-compose-type]').forEach(function (button) {
            button.addEventListener('click', function () {
                const form = button.closest('.js-compose-form');
                form.querySelector('.js-compose-type').value = button.dataset.composeType;
                form.querySelectorAll('[data-compose-type]').forEach(function (chip) {
                    chip.classList.toggle('is-selected', chip === button);
                });
            });
        });

        const composeBody = document.querySelector('.js-compose-body');
        if (composeBody) {
            composeBody.addEventListener('input', function () {
                composeBody.style.height = 'auto';
                composeBody.style.height = Math.min(composeBody.scrollHeight, 170) + 'px';
            });
        }

        document.addEventListener('submit', async function (event) {
            const composeForm = event.target.closest('.js-compose-form');
            const likeForm = event.target.closest('.js-like-form');
            const commentForm = event.target.closest('.js-comment-form');
            const shareForm = event.target.closest('.js-share-form');
            const densityForm = event.target.closest('.js-density-form');

            if (composeForm) {
                event.preventDefault();
                const submitButton = composeForm.querySelector('[type="submit"]');
                submitButton.disabled = true;

                try {
                    const data = await submitJson(composeForm);
                    document.querySelector('.js-empty-feed')?.remove();
                    feedList.insertAdjacentHTML('afterbegin', feedArticle(data.post));
                    composeForm.reset();
                    composeForm.querySelector('.js-compose-type').value = data.post.raw_type || data.post.kind.toLowerCase();
                    if (composeBody) {
                        composeBody.style.height = 'auto';
                    }
                    showToast('Post shared.');
                    updateSessionAction('Posted a ' + (data.post.raw_type || data.post.kind.toLowerCase()) + ' update.');
                } catch (error) {
                    showToast(error.message);
                } finally {
                    submitButton.disabled = false;
                }
            }

            if (likeForm) {
                event.preventDefault();
                const button = likeForm.querySelector('button');

                try {
                    const data = await submitJson(likeForm);
                    button.classList.toggle('is-active', data.liked);
                    button.dataset.liked = data.liked ? '1' : '0';
                    button.querySelector('.js-like-label').textContent = data.liked ? 'Liked' : 'Like';
                    button.querySelector('.js-like-count').textContent = data.count;
                    updateSessionAction(data.liked ? 'Liked a feed item.' : 'Removed a reaction.');
                } catch (error) {
                    showToast(error.message);
                }
            }

            if (commentForm) {
                event.preventDefault();
                const input = commentForm.querySelector('input[name="body"]');
                const article = commentForm.closest('[data-feed-item]');

                try {
                    const data = await submitJson(commentForm);
                    article.querySelector('.js-comment-count').textContent = data.count;
                    article.querySelector('.js-comments-list').insertAdjacentHTML('beforeend', `
                        <div class="rounded-2xl bg-[#f8f9fa] px-4 py-3">
                            <p class="text-xs font-bold" style="color:#1a2d4a;">${escapeHtml(data.comment.author)}</p>
                            <p class="mt-1 text-sm leading-6 text-slate-600">${escapeHtml(data.comment.body)}</p>
                        </div>
                    `);
                    input.value = '';
                    updateSessionAction('Commented on a feed item.');
                } catch (error) {
                    showToast(error.message);
                }
            }

            if (shareForm) {
                event.preventDefault();

                try {
                    const data = await submitJson(shareForm);
                    shareForm.querySelector('.js-share-count').textContent = data.count;
                    showToast('Shared to your alumni activity.');
                    updateSessionAction('Shared a feed item.');
                } catch (error) {
                    showToast(error.message);
                }
            }

            if (densityForm) {
                event.preventDefault();
                const clickedButton = event.submitter;
                const density = clickedButton?.value || 'comfortable';
                const formData = new FormData(densityForm);
                formData.set('density', density);

                try {
                    const response = await fetch(densityForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Could not save display preference.');
                    }

                    const root = document.querySelector('[data-dashboard-root]');
                    root?.classList.remove('feed-density-comfortable', 'feed-density-compact');
                    root?.classList.add('feed-density-' + data.density);

                    densityForm.querySelectorAll('.density-button').forEach(function (button) {
                        button.classList.toggle('is-selected', button.value === data.density);
                    });

                    showToast('Display preference saved.');
                } catch (error) {
                    showToast(error.message);
                }
            }
        });

        document.addEventListener('click', function (event) {
            const focusLink = event.target.closest('.js-focus-comment');

            if (!focusLink) {
                return;
            }

            const article = focusLink.closest('[data-feed-item]');
            const input = article.querySelector('.js-comment-form input[name="body"]');

            if (input) {
                input.focus();
            }
        });
    })();
</script>
@endsection
