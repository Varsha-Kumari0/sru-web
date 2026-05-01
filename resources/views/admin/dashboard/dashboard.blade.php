{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SRU Admin Dashboard — Alumni Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }

        .nav-active {
            background: #e8f0fe;
            color: #1a73e8 !important;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: #3b82f6;
            border-radius: 12px 12px 0 0;
        }

        th.sort-asc::after  { content: ' ↑'; color: #3b82f6; }
        th.sort-desc::after { content: ' ↓'; color: #3b82f6; }

        .search-wrap:focus-within { border-color: #1a73e8 !important; }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        #toast {
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.3s ease;
        }
        #toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        #modalOverlay {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }
        #modalOverlay.open {
            opacity: 1;
            pointer-events: all;
        }
        #modalOverlay.open .modal-box {
            transform: scale(1) translateY(0);
        }
        .modal-box {
            transform: scale(0.95) translateY(16px);
            transition: transform 0.25s ease;
        }

    </style>
</head>

<body class="min-h-screen flex bg-[#f8f9fc] text-[#1a1a2e]">

{{-- ══════════════════════════════════════
     SIDEBAR
══════════════════════════════════════ --}}
@include('admin.partials.sidebar', ['activeSection' => 'dashboard'])

{{-- ══════════════════════════════════════
     MAIN AREA
══════════════════════════════════════ --}}
<main class="ml-64 flex-1 flex flex-col min-h-screen">

    {{-- Topbar --}}
    <header class="sticky top-0 z-40 flex items-center justify-between px-6 pt-[1.9rem] pb-[.98em] xl:px-9 bg-white border-b border-slate-300">
        <div>
            <h2 class="font-display text-2xl font-semibold" style="letter-spacing:.01em;">SRU Alumni Dashboard</h2>
            <p class="text-xs mt-0.5 text-slate-500">
                {{ now()->format('l, d F Y') }} &mdash; Welcome back to SRU, {{ auth()->user()->name ?? 'Admin' }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="exportCSV()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150 bg-slate-50 text-slate-500 border border-slate-300 hover:bg-slate-100 hover:text-slate-900">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Export CSV
            </button>
            <button onclick="showToast('Invite link copied to clipboard!')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-bold transition-all duration-150 hover:-translate-y-0.5 bg-blue-500 text-white hover:bg-blue-600">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Invite SRU Alumni
            </button>
        </div>
    </header>

    {{-- Content --}}
    <div class="p-9 flex-1 grid grid-cols-1 xl:grid-cols-3 gap-6">

        <section class="xl:col-span-2 space-y-6">

            {{-- ── Stats Row ── --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="rounded-xl bg-[#ffffff] border border-[#dde3ec] p-5">
                    <p class="text-[11px] font-semibold tracking-[0.08em] uppercase text-[#555]">Total Alumni</p>
                    <p class="font-display text-4xl leading-tight mt-2 text-[#1a1a2e]">{{ $totalCount }}</p>
                    <span class="inline-flex items-center mt-3 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#e6f4ea] text-[#137333]">{{ $totalChipText }}</span>
                </div>

                <div class="rounded-xl bg-[#ffffff] border border-[#dde3ec] p-5">
                    <p class="text-[11px] font-semibold tracking-[0.08em] uppercase text-[#555]">Graduation Batches</p>
                    <p class="font-display text-4xl leading-tight mt-2 text-[#1a1a2e]">{{ $yearsCount }}</p>
                    <span class="inline-flex items-center mt-3 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#e8f0fe] text-[#1a73e8]">{{ $batchChipText }}</span>
                </div>

                <div class="rounded-xl bg-[#ffffff] border border-[#dde3ec] p-5">
                    <p class="text-[11px] font-semibold tracking-[0.08em] uppercase text-[#555]">Unread Messages</p>
                    <div class="mt-2 flex items-center gap-2">
                        <p class="font-display text-4xl leading-tight text-[#1a1a2e]">{{ $unreadMessagesCount }}</p>
                        @if($unreadMessagesCount > 0)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-[#e8f0fe] text-[#1a73e8]">new</span>
                        @endif
                    </div>
                    <span class="inline-flex items-center mt-3 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#e8f0fe] text-[#1a73e8]">{{ $messagesChipText }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                <div class="rounded-xl bg-[#ffffff] border border-[#dde3ec] p-5">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="font-display text-lg font-semibold text-[#1a1a2e]">Latest News</h3>
                        <a href="{{ route('newsroom') }}" target="_blank" rel="noopener noreferrer" class="text-xs font-semibold text-blue-600 hover:text-blue-700">View All</a>
                    </div>

                    <div class="space-y-3">
                        @forelse($latestNews as $news)
                            <a href="{{ route('news.show', $news->id) }}" target="_blank" rel="noopener noreferrer" class="block rounded-lg border border-[#eef0f5] bg-[#f9fafc] px-4 py-3 transition-colors duration-150 hover:bg-[#f1f5fb]">
                                <p class="truncate text-sm font-semibold text-[#1a1a2e]">{{ $news->title }}</p>
                                <p class="mt-1 line-clamp-2 text-xs text-[#555]">{{ $news->excerpt }}</p>
                                <p class="mt-2 text-[11px] text-[#888]">Updated {{ $news->updated_at?->diffForHumans() ?? 'just now' }}</p>
                            </a>
                        @empty
                            <div class="rounded-lg border border-dashed border-[#dde3ec] bg-[#f9fafc] px-4 py-6 text-center text-sm text-[#888]">
                                No news items yet.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-xl bg-[#ffffff] border border-[#dde3ec] p-5">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="font-display text-lg font-semibold text-[#1a1a2e]">Upcoming Events</h3>
                        <a href="{{ route('events.index') }}" target="_blank" rel="noopener noreferrer" class="text-xs font-semibold text-blue-600 hover:text-blue-700">View All</a>
                    </div>

                    <div class="space-y-3">
                        @forelse($upcomingEvents as $event)
                            <a href="{{ route('events.show', $event->id) }}" target="_blank" rel="noopener noreferrer" class="block rounded-lg border border-[#eef0f5] bg-[#f9fafc] px-4 py-3 transition-colors duration-150 hover:bg-[#f1f5fb]">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="truncate text-sm font-semibold text-[#1a1a2e]">{{ $event->title }}</p>
                                    <span class="flex-shrink-0 rounded-md bg-[#e8f0fe] px-2 py-0.5 text-[10px] font-semibold text-[#1a73e8]">
                                        {{ ucfirst(str_replace('-', ' ', $event->event_type)) }}
                                    </span>
                                </div>
                                <p class="mt-1 line-clamp-2 text-xs text-[#555]">{{ $event->excerpt }}</p>
                                <p class="mt-2 text-[11px] text-[#888]">
                                    {{ $event->start_at?->format('d M Y, h:i A') ?? '-' }}
                                    @if($event->location)
                                        • {{ $event->location }}
                                    @endif
                                </p>
                            </a>
                        @empty
                            <div class="rounded-lg border border-dashed border-[#dde3ec] bg-[#f9fafc] px-4 py-6 text-center text-sm text-[#888]">
                                No upcoming events found.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-xl bg-[#ffffff] border border-[#dde3ec] p-5">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="font-display text-lg font-semibold text-[#1a1a2e]">Latest Jobs</h3>
                        <a href="{{ route('jobs.index') }}" target="_blank" rel="noopener noreferrer" class="text-xs font-semibold text-blue-600 hover:text-blue-700">View All</a>
                    </div>

                    <div class="space-y-3">
                        @forelse($latestJobs as $job)
                            <a href="{{ route('jobs.index') }}" target="_blank" rel="noopener noreferrer" class="block rounded-lg border border-[#eef0f5] bg-[#f9fafc] px-4 py-3 transition-colors duration-150 hover:bg-[#f1f5fb]">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="truncate text-sm font-semibold text-[#1a1a2e]">{{ $job->title }}</p>
                                    <span class="flex-shrink-0 rounded-md bg-[#e8f0fe] px-2 py-0.5 text-[10px] font-semibold text-[#1a73e8]">
                                        {{ ucfirst($job->type ?? 'job') }}
                                    </span>
                                </div>
                                <p class="mt-1 text-xs text-[#555]">{{ $job->company_name ?? 'Company not set' }}</p>
                                <p class="mt-2 text-[11px] text-[#888]">
                                    Updated {{ $job->updated_at?->diffForHumans() ?? 'just now' }}
                                    @if($job->application_deadline)
                                        • Apply by {{ $job->application_deadline->format('d M Y') }}
                                    @endif
                                </p>
                            </a>
                        @empty
                            <div class="rounded-lg border border-dashed border-[#dde3ec] bg-[#f9fafc] px-4 py-6 text-center text-sm text-[#888]">
                                No jobs found.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-xl bg-[#ffffff] border border-[#dde3ec] p-5">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="font-display text-lg font-semibold text-[#1a1a2e]">Latest Engage</h3>
                        <a href="{{ route('engage') }}" target="_blank" rel="noopener noreferrer" class="text-xs font-semibold text-blue-600 hover:text-blue-700">View All</a>
                    </div>

                    <div class="space-y-3">
                        @forelse($latestEngage as $post)
                            @php
                                $authorName = trim((string) ($post->user?->profile?->full_name ?? ''));
                                if ($authorName === '') {
                                    $authorName = trim((string) ($post->user?->name ?? 'Alumni'));
                                }
                            @endphp
                            <a href="{{ route('dashboard.feed.details', ['feedType' => 'post', 'feedId' => $post->id]) }}" target="_blank" rel="noopener noreferrer" class="block rounded-lg border border-[#eef0f5] bg-[#f9fafc] px-4 py-3 transition-colors duration-150 hover:bg-[#f1f5fb]">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-xs font-semibold text-[#1a73e8] uppercase tracking-wide">{{ strtoupper($post->post_type ?? 'post') }}</p>
                                    <p class="text-[11px] text-[#888]">{{ $post->updated_at?->diffForHumans() ?? 'just now' }}</p>
                                </div>
                                <p class="mt-1 line-clamp-2 text-sm text-[#1a1a2e]">{{ \Illuminate\Support\Str::limit($post->body, 120) }}</p>
                                <p class="mt-2 text-[11px] text-[#555]">By {{ $authorName }}</p>
                            </a>
                        @empty
                            <div class="rounded-lg border border-dashed border-[#dde3ec] bg-[#f9fafc] px-4 py-6 text-center text-sm text-[#888]">
                                No engage posts found.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </section>

        <aside class="space-y-6">
            <div class="rounded-xl bg-[#ffffff] border border-[#dde3ec] p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-display text-lg font-semibold text-[#1a1a2e]">Department Breakdown</h3>
                    <span class="text-xs text-[#aaa]">Top 6</span>
                </div>

                <div class="space-y-4">
                    @forelse($departmentBreakdown as $dept)
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <p class="text-sm font-medium text-[#1a1a2e] truncate pr-3">{{ $dept['department'] }}</p>
                                <p class="text-xs text-[#555]">{{ $dept['count'] }} ({{ $dept['percent'] }}%)</p>
                            </div>
                            <div class="h-2 w-full rounded-full bg-[#eef0f5] overflow-hidden">
                                <div class="h-full rounded-full bg-[#1a73e8]" style="width: {{ $dept['percent'] }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-[#aaa]">No department data available.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl bg-[#ffffff] border border-[#dde3ec] p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-display text-lg font-semibold text-[#1a1a2e]">Recent Activity</h3>
                    <span class="text-xs text-[#aaa]">Latest updates</span>
                </div>

                <div class="space-y-3">
                    @forelse($recentActivity as $activity)
                        <div class="flex items-start gap-3">
                            <span class="mt-1.5 h-2 w-2 rounded-full {{ $activity['type'] === 'registered' ? 'bg-[#137333]' : 'bg-[#1a73e8]' }}"></span>
                            <div class="min-w-0">
                                <p class="text-sm text-[#1a1a2e] leading-5">{{ $activity['text'] }}</p>
                                <p class="text-xs text-[#aaa] mt-1">{{ $activity['time'] }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-[#aaa]">No recent activity yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl bg-[#ffffff] border border-[#dde3ec] p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-display text-lg font-semibold text-[#1a1a2e]">Gallery</h3>
                    <a href="{{ route('gallery') }}" target="_blank" rel="noopener noreferrer" class="text-xs font-semibold text-blue-600 hover:text-blue-700">View All</a>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="mb-2 text-[11px] font-semibold tracking-[0.08em] uppercase text-[#555]">Latest Albums</p>
                        <div class="space-y-2">
                            @forelse($latestGalleryAlbums as $album)
                                <a href="{{ route('gallery.album.show', $album->id) }}" target="_blank" rel="noopener noreferrer" class="block rounded-lg border border-[#eef0f5] bg-[#f9fafc] px-3 py-2 transition-colors duration-150 hover:bg-[#f1f5fb]">
                                    <p class="truncate text-sm font-semibold text-[#1a1a2e]">{{ $album->title }}</p>
                                    <p class="mt-1 line-clamp-1 text-xs text-[#555]">{{ $album->summary }}</p>
                                    <p class="mt-1 text-[11px] text-[#888]">Updated {{ $album->updated_at?->diffForHumans() ?? 'just now' }}</p>
                                </a>
                            @empty
                                <p class="text-xs text-[#aaa]">No albums yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <p class="mb-2 text-[11px] font-semibold tracking-[0.08em] uppercase text-[#555]">Latest Videos</p>
                        <div class="space-y-2">
                            @forelse($latestGalleryVideos as $video)
                                <a href="{{ route('gallery.video.show', $video->id) }}" target="_blank" rel="noopener noreferrer" class="block rounded-lg border border-[#eef0f5] bg-[#f9fafc] px-3 py-2 transition-colors duration-150 hover:bg-[#f1f5fb]">
                                    <p class="truncate text-sm font-semibold text-[#1a1a2e]">{{ $video->title }}</p>
                                    <p class="mt-1 line-clamp-1 text-xs text-[#555]">{{ $video->summary }}</p>
                                    <p class="mt-1 text-[11px] text-[#888]">Updated {{ $video->updated_at?->diffForHumans() ?? 'just now' }}</p>
                                </a>
                            @empty
                                <p class="text-xs text-[#aaa]">No videos yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </aside>

    </div>{{-- /content --}}
</main>

{{-- ══════════════════════════════════════
     MODAL — Alumni Detail
══════════════════════════════════════ --}}
<div id="modalOverlay" onclick="handleOverlayClick(event)"
    class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-black/65 backdrop-blur-[4px]">
    <div class="modal-box w-full max-w-lg rounded-2xl overflow-hidden bg-slate-50 border border-slate-300">
       <div class="flex items-center justify-between px-6 py-5 border-b border-slate-300">
            <h3 class="font-display text-lg font-semibold">SRU Alumni Details</h3>
            <button onclick="closeModal()"
                    class="w-8 h-8 rounded-full flex items-center justify-center text-sm transition-colors duration-150 bg-slate-200 text-slate-500 hover:bg-slate-300 hover:text-slate-900">✕</button>
        </div>
        <div class="p-6 max-h-[340px] overflow-y-auto" id="modalContent"></div>
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-300">
            <button onclick="closeModal()"
                    class="px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors duration-150 bg-white text-slate-500 border border-slate-300 hover:bg-slate-100 hover:text-slate-900">
                Close
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     TOAST
══════════════════════════════════════ --}}
<div id="toast"
    class="fixed bottom-6 right-6 z-50 px-5 py-3.5 rounded-xl text-sm font-semibold shadow-2xl bg-slate-800 border border-slate-300 text-white min-w-[220px]">
</div>

{{-- ══════════════════════════════════════
     JAVASCRIPT — Data + Interactions
══════════════════════════════════════ --}}
<script>
// ── Laravel Blade → JS data bridge ───────────────────────────────────────────
// This passes the $users collection from your controller directly into JS.
// In your controller: $users = User::where('role','user')->get();

const alumni = {!! json_encode($users->map(function($u) {
    return [
        'id'              => $u->id,
        'name'            => $u->name,
        'email'           => $u->email,
        'profile_photo'   => $u->profile?->profile_photo ? asset('storage/' . $u->profile->profile_photo) : null,
        'phone'           => $u->profile?->mobile ?? '—',
        'full_name'       => $u->profile?->full_name ?? $u->name,
        'father_name'     => $u->profile?->father_name ?? '—',
        'department'      => $u->profile?->branch ?? '—',
        'graduation_year' => $u->profile?->passing_year ?? '—',
        'location'        => $u->professional?->location ?? '—',
        'created_at'      => $u->created_at,
        // Profile fields
        'city'            => $u->profile?->city ?? '—',
        'country'         => $u->profile?->country ?? '—',
        'linkedin'        => $u->profile?->linkedin ?? '—',
        'facebook'        => $u->profile?->facebook ?? '—',
        'instagram'       => $u->profile?->instagram ?? '—',
        'twitter'         => $u->profile?->twitter ?? '—',
        'degree'          => $u->profile?->degree ?? '—',
        'branch'          => $u->profile?->branch ?? '—',
        'current_status'  => $u->profile?->current_status ?? '—',
        'company'         => $u->profile?->company ?? '—',
        // Professional fields
        'organization'    => $u->professional?->organization ?? '—',
        'industry'        => $u->professional?->industry ?? '—',
        'role'            => $u->professional?->role ?? '—',
        'from'            => $u->professional?->from ?? '—',
        'to'              => $u->professional?->to ?? '—',
        'pro_location'    => $u->professional?->location ?? '—',
    ];
})) !!};

// ── State (dashboard table controls) ─────────────────────────────────────────
let filtered    = [...alumni];
let sortKey     = 'name';
let sortDir     = 1;
let currentPage = 1;
const perPage   = 8;

const COLORS = ['#c9a84c','#4caf7d','#5b8dee','#e05c5c','#9b59b6','#e67e22'];
const getColor  = (i) => COLORS[i % COLORS.length];

// ── Init ─────────────────────────────────────────────────────────────────────
window.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('yearFilter') && document.getElementById('searchInput') && document.getElementById('tableBody')) {
        populateYearFilter();
        filterTable();
    }

    // Keep the admin dashboard on-screen when the browser back button is used.
    // This prevents going back to the login page from the protected dashboard.
    window.history.pushState(null, '', window.location.href);
    window.addEventListener('popstate', () => {
        window.history.pushState(null, '', window.location.href);
        window.location.reload();
    });
});

// ── Year filter dropdown ──────────────────────────────────────────────────────
function populateYearFilter() {
    const sel = document.getElementById('yearFilter');
    if (!sel) return;

    const years = [...new Set(alumni.map(a => a.graduation_year))].sort((a, b) => b - a);
    years.forEach(y => {
        if (y === '—') return;
        const o = document.createElement('option');
        o.value = y; o.textContent = y;
        sel.appendChild(o);
    });
}

// ── Filter ───────────────────────────────────────────────────────────────────
function filterTable() {
    const searchInput = document.getElementById('searchInput');
    const yearFilter = document.getElementById('yearFilter');
    const tableBody = document.getElementById('tableBody');
    if (!searchInput || !yearFilter || !tableBody) return;

    const q = searchInput.value.toLowerCase();
    const year = yearFilter.value;

    filtered = alumni.filter(a => {
        const matchQ = !q ||
            a.name.toLowerCase().includes(q) ||
            a.email.toLowerCase().includes(q) ||
            (a.department && a.department.toLowerCase().includes(q)) ||
            String(a.graduation_year).includes(q);
        const matchY = !year   || a.graduation_year == year;
        return matchQ && matchY;
    });

    currentPage = 1;
    renderTable();
}

// ── Sort ─────────────────────────────────────────────────────────────────────
function sortTable(key) {
    if (sortKey === key) sortDir *= -1;
    else { sortKey = key; sortDir = 1; }

    document.querySelectorAll('thead th').forEach(th => {
        th.classList.remove('sort-asc', 'sort-desc');
    });

    const headers = ['name', 'department', 'graduation_year', 'email', 'created_at'];
    const idx = headers.indexOf(key);
    if (idx >= 0) {
        const ths = document.querySelectorAll('thead th');
        ths[idx].classList.add(sortDir === 1 ? 'sort-asc' : 'sort-desc');
    }

    filtered.sort((a, b) => {
        const av = a[key] ?? '', bv = b[key] ?? '';
        if (av < bv) return -1 * sortDir;
        if (av > bv) return  1 * sortDir;
        return 0;
    });

    renderTable();
}

// ── Render table ─────────────────────────────────────────────────────────────
function renderTable() {
    const tbody = document.getElementById('tableBody');
    const paginationInfo = document.getElementById('paginationInfo');
    const ctrl = document.getElementById('paginationControls');
    if (!tbody || !paginationInfo || !ctrl) return;

    const total = filtered.length;
    const pages = Math.max(1, Math.ceil(total / perPage));
    currentPage = Math.min(currentPage, pages);
    const start = (currentPage - 1) * perPage;
    const slice = filtered.slice(start, start + perPage);

    if (slice.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" style="padding:60px 0; text-align:center; color:#555;">
            <svg style="margin:0 auto 12px" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
            </svg>
            <p style="font-size:14px">No SRU alumni match your filters.</p>
        </td></tr>`;
    } else {
        tbody.innerHTML = slice.map(a => {
            const initials  = (a.full_name && a.full_name !== '—' ? a.full_name : a.name).split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase();
            const color     = getColor(a.id);
            const date  = a.created_at
                ? new Date(a.created_at).toLocaleDateString('en-IN', { day:'numeric', month:'short', year:'numeric' })
                : '—';

            // Prefer full_name if available, otherwise name
            const displayName = (a.full_name && a.full_name !== '—') ? a.full_name : a.name;

            return `
            <tr style="border-bottom:1px solid #eef0f5; cursor:default; transition:background .12s;"
                onmouseover="this.style.background='#f8f9fc'"
                onmouseout="this.style.background='transparent'">

                <td style="padding:16px 20px; vertical-align:middle;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        ${a.profile_photo
                            ? `<img src="${a.profile_photo}" alt="${displayName}"
                                style="width:36px;height:36px;border-radius:8px;object-fit:contain;object-position:center;background:#f8f9fc;flex-shrink:0;border:1px solid #dde3ec;"/>`
                            : `<div style="width:36px;height:36px;border-radius:8px;background:${color};
                                    display:flex;align-items:center;justify-content:center;
                                    font-weight:700;font-size:13px;color:#fff;flex-shrink:0;">
                                    ${initials}
                               </div>`}
                        <div>
                            <div style="font-weight:600;font-size:14px;">${displayName}</div>
                            <div style="font-size:11px;color:#aaa;">#SRU-${String(a.id).padStart(4,'0')}</div>
                        </div>
                    </div>
                </td>

                <td style="padding:16px 20px;font-size:13.5px;vertical-align:middle;">${a.department}</td>
                <td style="padding:16px 20px;font-size:13.5px;vertical-align:middle;">${a.graduation_year}</td>
                <td style="padding:16px 20px;font-size:13.5px;vertical-align:middle;color:#555;">${a.email}</td>

                <td style="padding:16px 20px;font-size:13px;color:#aaa;vertical-align:middle;">${date}</td>

                <td style="padding:16px 20px;vertical-align:middle;">
                    <div class="row-actions" style="display:flex;align-items:center;gap:8px;opacity:1;">
                        <button title="View Details" onclick="openModal(${a.id})"
                                style="width:30px;height:30px;border-radius:7px;background:#ffffff;
                                       border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;
                                       transition:all .15s;"
                                onmouseover="this.style.background='#dbeafe'"
                                onmouseout="this.style.background='#ffffff'">
                            <svg width="13" height="13" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>

                        <a href="/admin/alumni/${a.id}/edit" title="Edit Details"
                                style="width:30px;height:30px;border-radius:7px;background:#ffffff;
                                       border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;
                                       transition:all .15s;text-decoration:none;"
                                onmouseover="this.style.background='#fef3c7'"
                                onmouseout="this.style.background='#ffffff'">
                            <svg width="13" height="13" fill="none" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </a>

                        <button title="Remove" onclick="removeAlumni(${a.id})"
                                style="width:30px;height:30px;border-radius:7px;background:rgba(224,92,92,.12);
                                       border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;
                                       transition:all .15s;"
                                onmouseover="this.style.background='rgba(224,92,92,.3)'"
                                onmouseout="this.style.background='rgba(224,92,92,.12)'">
                            <svg width="13" height="13" fill="none" stroke="#e05c5c" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6l-1 14H6L5 6"/>
                                <path d="M10 11v6"/><path d="M14 11v6"/>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>`;
        }).join('');
    }

    paginationInfo.textContent =
        `Showing ${Math.min(start + 1, total)}–${Math.min(start + perPage, total)} of ${total} SRU alumni`;

    // Pagination buttons
    ctrl.innerHTML = '';
    const addBtn = (label, page, active) => {
        const btn = document.createElement('button');
        btn.textContent = label;
        btn.style.cssText = `
            min-width:32px; height:32px; padding:0 10px; border-radius:7px; border:1px solid #dde3ec; cursor:pointer;
            font-size:13px; font-weight:600; font-family:'DM Sans',sans-serif;
            background:${active ? '#e8f0fe' : '#ffffff'};
            color:${active ? '#1a73e8' : '#555'};
            transition:all .15s;
        `;
        btn.onmouseover = () => { if (!active) btn.style.background = '#f8f9fc'; };
        btn.onmouseout  = () => { if (!active) btn.style.background = '#ffffff'; };
        btn.onclick = () => { currentPage = page; renderTable(); };
        ctrl.appendChild(btn);
    };
    if (currentPage > 1) addBtn('‹', currentPage - 1, false);
    for (let p = 1; p <= pages; p++) addBtn(p, p, p === currentPage);
    if (currentPage < pages) addBtn('›', currentPage + 1, false);
}

// ── Modal ────────────────────────────────────────────────────────────────────
function openModal(id) {
    const a = alumni.find(x => x.id === id);
    if (!a) return;

    const displayName = (a.full_name && a.full_name !== '—') ? a.full_name : a.name;
    const color    = getColor(a.id);
    const initials = displayName.split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase();

    document.getElementById('modalContent').innerHTML = `
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
            ${a.profile_photo
                ? `<img src="${a.profile_photo}" alt="${displayName}"
                    style="width:52px;height:52px;border-radius:10px;object-fit:contain;object-position:center;background:#f8f9fc;flex-shrink:0;border:1px solid #dde3ec;"/>`
                : `<div style="width:52px;height:52px;border-radius:10px;background:${color};
                        display:flex;align-items:center;justify-content:center;
                        font-weight:700;font-size:18px;color:#fff;flex-shrink:0;">
                        ${initials}
                    </div>`}
            <div>
                <div style="font-size:20px;font-weight:700;font-family:'Playfair Display',serif;">${displayName}</div>
                <div style="font-size:12px;color:#7a7f90;margin-top:3px;">#SRU-${String(a.id).padStart(4,'0')}</div>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            ${
              [
                ['Full Name',       a.full_name],
                ['Father Name',     a.father_name],
                ['Email',           a.email],
                ['Phone',           a.phone],
                ['City',            a.city],
                ['Country',         a.country],
                ['LinkedIn',        a.linkedin],
                ['Facebook',        a.facebook],
                ['Instagram',       a.instagram],
                ['Twitter',         a.twitter],
                ['Organization',    a.organization],
                ['Industry',        a.industry],
                ['Role',            a.role],
                ['Degree',          a.degree],
                ['Branch',          a.branch],
                ['Graduation Year',    a.graduation_year],
                ['Current Status',  a.current_status],
                ['Company',         a.company],
                ['Work From',       a.from],
                ['Work To',         a.to],
                ['Work Location',   a.pro_location],
                ['Registered',      a.created_at ? new Date(a.created_at).toLocaleDateString('en-IN',{day:'numeric',month:'long',year:'numeric'}) : '—'],
              ].map(([label, val]) => `
                                <div style="background:#e4e5e6;border-radius:10px;padding:14px 16px;">
                                        <p style="font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#27282b;margin-bottom:5px;">${label}</p>
                    <p style="font-size:14px;font-weight:500;">${val}</p>
                </div>
              `).join('')
            }
        </div>
    `;

    document.getElementById('modalOverlay').classList.add('open');
}

function handleOverlayClick(e) {
    if (e.target === document.getElementById('modalOverlay')) closeModal();
}

function closeModal() {
    document.getElementById('modalOverlay').classList.remove('open');
}

// ── Actions ───────────────────────────────────────────────────────────────────
function removeAlumni(id) {
    const a = alumni.find(x => x.id === id);
    if (!a || !confirm(`Remove ${a.name} from the SRU registry? This cannot be undone.`)) return;

    // Send to Laravel backend via AJAX
    fetch(`/admin/alumni/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alumni.splice(alumni.indexOf(a), 1);
            filtered = filtered.filter(x => x.id !== id);
            renderTable();
            showToast(`${a.name} removed from SRU registry`);
        } else {
            showToast(`✗ Failed to delete: ${data.message}`);
        }
    })
    .catch(err => {
        console.warn('Backend error:', err);
        showToast('✗ Failed to delete alumni');
    });
}

// ── Export CSV ────────────────────────────────────────────────────────────────
function exportCSV() {
    // Wrap value in quotes and escape any existing double-quotes.
    const q = (v) => '"' + String(v ?? '—').replace(/"/g, '""') + '"';

    const headers = [
        'ID', 'Account Name', 'Email',
        // Profile
        'Full Name', 'Father Name', 'Phone', 'City', 'Country',
        'Degree', 'Branch / Specialization', 'Graduation Year',
        'Current Status', 'Company',
        // Social
        'LinkedIn', 'Facebook', 'Instagram', 'Twitter',
        // Professional
        'Organization', 'Industry', 'Role',
        'Work From', 'Work To', 'Work Location',
        // Meta
        'Registered'
    ];

    const rows = alumni.map(a => [
        q(a.id),
        q(a.name),
        q(a.email),
        q(a.full_name),
        q(a.father_name),
        q(a.phone),
        q(a.city),
        q(a.country),
        q(a.degree),
        q(a.branch),
        q(a.graduation_year),
        q(a.current_status),
        q(a.company),
        q(a.linkedin),
        q(a.facebook),
        q(a.instagram),
        q(a.twitter),
        q(a.organization),
        q(a.industry),
        q(a.role),
        q(a.from),
        q(a.to),
        q(a.pro_location),
        q(a.created_at),
    ]);

    const csv  = [headers.map(q), ...rows].map(r => r.join(',')).join('\n');
    const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
    const url  = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `sru_alumni_export_${new Date().toISOString().slice(0,10)}.csv`;
    link.click();
    URL.revokeObjectURL(url);
    showToast('SRU alumni CSV exported successfully');
}

// ── Toast ─────────────────────────────────────────────────────────────────────
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3200);
}
</script>

</body>
</html>