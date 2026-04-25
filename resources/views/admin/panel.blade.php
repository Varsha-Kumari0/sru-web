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
<aside class="w-64 min-h-screen flex flex-col fixed left-0 top-0 bottom-0 z-50 bg-white border-r border-slate-300">

    {{-- Logo --}}
    <div class="px-7 py-8 border-b border-slate-300">
        @php($dashboardLogoPath = 'images/logos/sru_logo_new.png')

        @if(file_exists(public_path($dashboardLogoPath)))
            <img src="{{ asset($dashboardLogoPath) }}" alt="SRU Alumni Logo" class="h-12 w-auto object-contain">
        @else
            <h1 class="font-display text-xl font-bold text-sky-400 tracking-[0.02em] leading-tight">
                SRU<br>Alumni
            </h1>
        @endif

        <span class="text-xs font-semibold tracking-widest uppercase mt-1 block text-slate-500">
            Admin Control
        </span>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 py-5 space-y-0.5">

        <p class="text-xs font-semibold tracking-widest uppercase px-3 mb-2 mt-1 text-slate-500">Overview</p>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-active flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-900 hover:text-slate-900">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('admin.allalumini') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            All SRU Alumni
        </a>

        <p class="text-xs font-semibold tracking-widest uppercase px-3 mb-2 mt-5 text-slate-500">Management</p>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
            </svg>
            Messages
        </a>

        <a href="{{ route('admin.activity-logs') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 3v18h18"/>
                <path d="M8 14l3-3 3 2 4-5"/>
            </svg>
            Activity Logs
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
            Reports
        </a>

        <p class="text-xs font-semibold tracking-widest uppercase px-3 mb-2 mt-5 text-slate-500">System</p>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.07 4.93l-1.41 1.41M4.93 4.93l1.41 1.41M4.93 19.07l1.41-1.41M19.07 19.07l-1.41-1.41M12 2v2M12 20v2M2 12h2M20 12h2"/>
            </svg>
            Settings
        </a>
    </nav>

    {{-- Admin Footer --}}
    <div class="px-4 py-5 border-t border-slate-300">
        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50">
            {{-- Clickable admin avatar: click to change photo --}}
            <form method="POST" action="{{ route('admin.profile.avatar') }}" enctype="multipart/form-data" id="adminAvatarForm">
                @csrf
                <label for="adminAvatarInput" title="Click to change profile photo"
                       onmouseover="this.querySelector('.av-overlay').style.opacity='1'"
                       onmouseout="this.querySelector('.av-overlay').style.opacity='0'"
                       style="cursor:pointer;position:relative;display:block;width:36px;height:36px;border-radius:50%;flex-shrink:0;overflow:hidden;">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Admin"
                             style="width:36px;height:36px;border-radius:50%;object-fit:contain;object-position:center;background:#fff;border:1px solid #dde3ec;">
                    @else
                        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#1d4ed8);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:#fff;">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                    @endif
                    <div class="av-overlay" style="position:absolute;inset:0;background:rgba(0,0,0,.45);border-radius:50%;display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity .15s;">
                        <svg width="13" height="13" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    </div>
                </label>
                <input type="file" id="adminAvatarInput" name="avatar" accept="image/jpg,image/jpeg,image/png" class="hidden" onchange="this.form.submit()">
            </form>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold truncate text-slate-900">{{ auth()->user()->name ?? 'Administrator' }}</p>
                <p class="text-xs text-slate-500">Super Admin</p>
            </div>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               title="Logout"
               class="inline-flex h-8 w-8 items-center justify-center rounded-md text-slate-500 transition-all duration-150 hover:bg-slate-100 hover:text-slate-900">
                 <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                     class="transition-colors duration-150">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </a>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
    </div>
</aside>

{{-- ══════════════════════════════════════
     MAIN AREA
══════════════════════════════════════ --}}
<main class="ml-64 flex-1 flex flex-col min-h-screen">

    {{-- Topbar --}}
    <header class="sticky top-0 z-40 flex items-center justify-between px-9 py-5 bg-white border-b border-slate-300">
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

            {{-- ── Alumni Table ── --}}
            <div class="rounded-xl overflow-hidden bg-[#ffffff] border border-[#dde3ec]">

            {{-- Table Header / Controls --}}
              <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-5 border-b border-[#dde3ec]">
                <h3 class="font-display text-lg font-semibold">All Registered SRU Alumni</h3>
                <div class="flex items-center gap-3 flex-wrap">
                    {{-- Search --}}
                  <div class="search-wrap flex items-center gap-2 px-4 py-2 rounded-lg transition-colors duration-150 bg-[#ffffff] border border-[#dde3ec]">
                        <svg width="13" height="13" fill="none" stroke="#7a7f90" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text" id="searchInput" placeholder="Search SRU alumni…"
                               oninput="filterTable()"
                               class="bg-transparent outline-none text-sm w-48 h-5 border-none text-slate-900"
                               placeholder-style="color:#7a7f90">
                    </div>
                    {{-- Year Filter --}}
                    <select id="yearFilter" onchange="filterTable()"
                            class="px-4 py-2 rounded-lg text-sm outline-none cursor-pointer transition-colors duration-150 bg-[#ffffff] border border-[#dde3ec] text-[#555]">
                        <option value="">All Years</option>
                    </select>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full border-collapse" id="alumniTable">
                    <thead>
                        <tr>
                            @foreach([
                                ['name',            'SRU Alumni'],
                                ['department',      'Department'],
                                ['graduation_year', 'Graduation Year'],
                                ['email',           'Email'],
                                ['created_at',      'Registered'],
                                [null,              'Actions'],
                            ] as [$key, $label])
                            <th class="text-left px-5 py-3.5 text-xs font-semibold tracking-widest uppercase select-none whitespace-nowrap text-[#555] border-b border-[#dde3ec] {{ $key ? 'cursor-pointer hover:text-[#1a1a2e]' : '' }}"
                                {{ $key ? "onclick=sortTable('$key')" : '' }}>
                                {{ $label }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr>
                            <td colspan="6" class="py-16 text-center text-[#555]">
                                <svg class="mx-auto mb-3" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                </svg>
                                <p class="text-sm">Loading SRU alumni data…</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="flex items-center justify-between px-6 py-4 border-t border-[#dde3ec]">
                <span class="text-sm text-[#555]" id="paginationInfo">Showing 0 of 0 alumni</span>
                <div class="flex items-center gap-1" id="paginationControls"></div>
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
    populateYearFilter();
    filterTable();

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
    const years = [...new Set(alumni.map(a => a.graduation_year))].sort((a, b) => b - a);
    const sel = document.getElementById('yearFilter');
    years.forEach(y => {
        if (y === '—') return;
        const o = document.createElement('option');
        o.value = y; o.textContent = y;
        sel.appendChild(o);
    });
}

// ── Filter ───────────────────────────────────────────────────────────────────
function filterTable() {
    const q      = document.getElementById('searchInput').value.toLowerCase();
    const year   = document.getElementById('yearFilter').value;

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

    document.getElementById('paginationInfo').textContent =
        `Showing ${Math.min(start + 1, total)}–${Math.min(start + perPage, total)} of ${total} SRU alumni`;

    // Pagination buttons
    const ctrl = document.getElementById('paginationControls');
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