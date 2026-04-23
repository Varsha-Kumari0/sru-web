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
        /* ── Custom properties not in Tailwind ── */
        :root {
            --gold:      #003366;
            --gold-lt:   #005599;
            --surface:   #13161e;
            --card:      #181c26;
            --border:    #252a38;
        }
        body { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }

        /* Sidebar active state */
        .nav-active {
            background: rgba(201,168,76,.13);
            color: #e8c97a !important;
        }

        /* Gold accent top bar on stat cards */
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: var(--gold);
            border-radius: 12px 12px 0 0;
        }

        /* Sortable header arrow indicators */
        th.sort-asc::after  { content: ' ↑'; color: var(--gold); }
        th.sort-desc::after { content: ' ↓'; color: var(--gold); }

        /* Row action buttons fade in on hover */
        tr:hover .row-actions { opacity: 1 !important; }

        /* Search focus ring gold */
        .search-wrap:focus-within { border-color: var(--gold) !important; }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0d0f14; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #35394a; }

        /* Toast */
        #toast {
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.3s ease;
        }
        #toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        /* Modal */
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

        /* Badge pulse for pending */
        .badge-pending { animation: pendingPulse 2.5s ease-in-out infinite; }
        @keyframes pendingPulse {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.65; }
        }

        /* Gradient mesh background subtle */
        .bg-mesh {
            background-color: #0d0f14;
            background-image:
                radial-gradient(ellipse at 10% 20%, rgba(201,168,76,.04) 0%, transparent 60%),
                radial-gradient(ellipse at 90% 80%, rgba(91,141,238,.04) 0%, transparent 60%);
        }
    </style>
</head>

<body class="bg-mesh min-h-screen flex" style="background-color:#0d0f14; color:#e8e6df;">

{{-- ══════════════════════════════════════
     SIDEBAR
══════════════════════════════════════ --}}
<aside class="w-64 min-h-screen flex flex-col fixed left-0 top-0 bottom-0 z-50"
       style="background:var(--surface); border-right:1px solid var(--border);">

    {{-- Logo --}}
    <div class="px-7 py-8" style="border-bottom:1px solid var(--border);">
        <h1 class="font-display text-xl font-bold" style="color:var(--gold-lt); letter-spacing:.02em; line-height:1.2;">
            SRU<br>Alumni
        </h1>
        <span class="text-xs font-semibold tracking-widest uppercase mt-1 block" style="color:#7a7f90;">
            Admin Control
        </span>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 py-5 space-y-0.5">

        <p class="text-xs font-semibold tracking-widest uppercase px-3 mb-2 mt-1" style="color:#7a7f90;">Overview</p>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-active flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 hover:text-white"
           style="color:#e8e6df;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
            </svg>
            Dashboard
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 hover:text-white"
           style="color:#7a7f90;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            All SRU Alumni
        </a>

        <p class="text-xs font-semibold tracking-widest uppercase px-3 mb-2 mt-5" style="color:#7a7f90;">Management</p>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 hover:text-white"
           style="color:#7a7f90;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            Pending SRU Approvals
            @php $pendingCount = $users->where('status','Pending')->count(); @endphp
            @if($pendingCount > 0)
                <span class="ml-auto text-xs font-bold px-2 py-0.5 rounded-full"
                      style="background:rgba(201,168,76,.2); color:var(--gold-lt);">
                    {{ $pendingCount }}
                </span>
            @endif
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 hover:text-white"
           style="color:#7a7f90;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
            </svg>
            Messages
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 hover:text-white"
           style="color:#7a7f90;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
            Reports
        </a>

        <p class="text-xs font-semibold tracking-widest uppercase px-3 mb-2 mt-5" style="color:#7a7f90;">System</p>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 hover:text-white"
           style="color:#7a7f90;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.07 4.93l-1.41 1.41M4.93 4.93l1.41 1.41M4.93 19.07l1.41-1.41M19.07 19.07l-1.41-1.41M12 2v2M12 20v2M2 12h2M20 12h2"/>
            </svg>
            Settings
        </a>
    </nav>

    {{-- Admin Footer --}}
    <div class="px-4 py-5" style="border-top:1px solid var(--border);">
        <div class="flex items-center gap-3 p-3 rounded-xl" style="background:var(--card);">
            <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm text-white flex-shrink-0"
                 style="background: linear-gradient(135deg, var(--gold), #8b6a28);">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold truncate" style="color:#e8e6df;">{{ auth()->user()->name ?? 'Administrator' }}</p>
                <p class="text-xs" style="color:#7a7f90;">Super Admin</p>
            </div>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               title="Logout">
                <svg width="15" height="15" fill="none" stroke="#7a7f90" stroke-width="2" viewBox="0 0 24 24"
                     class="hover:stroke-white transition-colors duration-150">
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
    <header class="sticky top-0 z-40 flex items-center justify-between px-9 py-5"
            style="background:var(--surface); border-bottom:1px solid var(--border);">
        <div>
            <h2 class="font-display text-2xl font-semibold" style="letter-spacing:.01em;">SRU Alumni Dashboard</h2>
            <p class="text-xs mt-0.5" style="color:#7a7f90;">
                {{ now()->format('l, d F Y') }} &mdash; Welcome back to SRU, {{ auth()->user()->name ?? 'Admin' }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="exportCSV()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150 hover:text-white"
                    style="background:var(--card); color:#7a7f90; border:1px solid var(--border);">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Export CSV
            </button>
            <button onclick="showToast('Invite link copied to clipboard!')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-bold transition-all duration-150 hover:-translate-y-0.5"
                    style="background:var(--gold); color:#0d0f14;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Invite SRU Alumni
            </button>
        </div>
    </header>

    {{-- Content --}}
    <div class="p-9 flex-1">

        {{-- ── Stats Row ── --}}
        <div class="grid grid-cols-4 gap-5 mb-8">

            {{-- Stat Card helper macro (inline) --}}
            @foreach([
                ['label'=>'Total SRU Alumni',       'value'=> $totalCount,    'change'=> $totalChange,      'changeColor'=>'#4caf7d', 'icon'=>'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2 M23 21v-2a4 4 0 0 0-3-3.87 M16 3.13a4 4 0 0 1 0 7.75 circle cx=9 cy=7 r=4'],
                ['label'=>'Active SRU Members',     'value'=> $activeCount,   'change'=> $activeChange,     'changeColor'=>'#4caf7d', 'icon'=>'polyline points=9 11 12 14 22 4 M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11'],
                ['label'=>'Pending Approval',   'value'=> $pendingCount,  'change'=>'Needs review',        'changeColor'=>'#e8c97a', 'icon'=>'circle cx=12 cy=12 r=10 polyline points=12 6 12 12 16 14'],
                ['label'=>'Graduation Batches', 'value'=> $yearsCount,    'change'=>'Across all years',    'changeColor'=>'#7a7f90', 'icon'=>'rect x=3 y=4 width=18 height=18 rx=2 M16 2v4 M8 2v4 M3 10h18'],
            ] as $stat)
            <div class="stat-card relative rounded-xl p-6 transition-transform duration-200 hover:-translate-y-0.5"
                 style="background:var(--card); border:1px solid var(--border);">
                <p class="text-xs font-semibold tracking-widest uppercase" style="color:#7a7f90; letter-spacing:.1em;">
                    {{ $stat['label'] }}
                </p>
                <p class="font-display text-4xl font-bold my-2" style="color:#e8e6df;">{{ $stat['value'] }}</p>
                <p class="text-xs font-medium" style="color:{{ $stat['changeColor'] }}">{{ $stat['change'] }}</p>
            </div>
            @endforeach

        </div>

        {{-- ── Alumni Table ── --}}
        <div class="rounded-xl overflow-hidden" style="background:var(--card); border:1px solid var(--border);">

            {{-- Table Header / Controls --}}
            <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-5"
                 style="border-bottom:1px solid var(--border);">
                <h3 class="font-display text-lg font-semibold">All Registered SRU Alumni</h3>
                <div class="flex items-center gap-3 flex-wrap">
                    {{-- Search --}}
                    <div class="search-wrap flex items-center gap-2 px-4 py-2 rounded-lg transition-colors duration-150"
                         style="background:var(--surface); border:1px solid var(--border);">
                        <svg width="13" height="13" fill="none" stroke="#7a7f90" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text" id="searchInput" placeholder="Search SRU alumni…"
                               oninput="filterTable()"
                               class="bg-transparent outline-none text-sm w-48"
                               style="color:#e8e6df; font-family:'DM Sans',sans-serif;"
                               placeholder-style="color:#7a7f90">
                    </div>
                    {{-- Status Filter --}}
                    <select id="statusFilter" onchange="filterTable()"
                            class="px-4 py-2 rounded-lg text-sm outline-none cursor-pointer transition-colors duration-150"
                            style="background:var(--surface); border:1px solid var(--border); color:#7a7f90; font-family:'DM Sans',sans-serif;">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Pending">Pending</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                    {{-- Year Filter --}}
                    <select id="yearFilter" onchange="filterTable()"
                            class="px-4 py-2 rounded-lg text-sm outline-none cursor-pointer transition-colors duration-150"
                            style="background:var(--surface); border:1px solid var(--border); color:#7a7f90; font-family:'DM Sans',sans-serif;">
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
                                ['graduation_year', 'Batch Year'],
                                ['email',           'Email'],
                                ['status',          'Status'],
                                ['created_at',      'Registered'],
                                [null,              'Actions'],
                            ] as [$key, $label])
                            <th class="text-left px-5 py-3.5 text-xs font-semibold tracking-widest uppercase select-none whitespace-nowrap {{ $key ? 'cursor-pointer hover:text-white' : '' }}"
                                style="color:#7a7f90; border-bottom:1px solid var(--border);"
                                {{ $key ? "onclick=sortTable('$key')" : '' }}>
                                {{ $label }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr>
                            <td colspan="7" class="py-16 text-center" style="color:#7a7f90;">
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
            <div class="flex items-center justify-between px-6 py-4" style="border-top:1px solid var(--border);">
                <span class="text-sm" id="paginationInfo" style="color:#7a7f90;">Showing 0 of 0 alumni</span>
                <div class="flex items-center gap-1" id="paginationControls"></div>
            </div>
        </div>

    </div>{{-- /content --}}
</main>

{{-- ══════════════════════════════════════
     MODAL — Alumni Detail
══════════════════════════════════════ --}}
<div id="modalOverlay" onclick="handleOverlayClick(event)"
     class="fixed inset-0 z-50 flex items-center justify-center p-6"
     style="background:rgba(0,0,0,.65); backdrop-filter:blur(4px);">
    <div class="modal-box w-full max-w-lg rounded-2xl overflow-hidden"
         style="background:var(--card); border:1px solid var(--border);">
        <div class="flex items-center justify-between px-6 py-5"
             style="border-bottom:1px solid var(--border);">
            <h3 class="font-display text-lg font-semibold">SRU Alumni Details</h3>
            <button onclick="closeModal()"
                    class="w-8 h-8 rounded-full flex items-center justify-center text-sm transition-colors duration-150 hover:text-white"
                    style="background:#252a38; color:#7a7f90;">✕</button>
        </div>
        <div class="p-6" id="modalContent" style="max-height:340px; overflow-y:auto;"></div>
        <div class="flex items-center justify-end gap-3 px-6 py-4"
             style="border-top:1px solid var(--border);">
            <button onclick="closeModal()"
                    class="px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors duration-150 hover:text-white"
                    style="background:var(--surface); color:#7a7f90; border:1px solid var(--border);">
                Close
            </button>
            <button id="modalApproveBtn"
                    class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all duration-150 hover:-translate-y-0.5"
                    style="background:var(--gold); color:#0d0f14; display:none;">
                ✓ Approve
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     TOAST
══════════════════════════════════════ --}}
<div id="toast"
     class="fixed bottom-6 right-6 z-50 px-5 py-3.5 rounded-xl text-sm font-semibold shadow-2xl"
     style="background:#1e2330; border:1px solid var(--border); color:#e8e6df; min-width:220px;">
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
        'phone'           => $u->phone ?? '—',
        'full_name'       => $u->full_name ?? $u->name,
        'department'      => $u->department ?? '—',
        'graduation_year' => $u->graduation_year ?? '—',
        'location'        => $u->location ?? '—',
        'status'          => $u->status ?? 'Pending',
        'created_at'      => $u->created_at,
        // Profile fields
        'city'            => $u->city ?? '—',
        'country'         => $u->country ?? '—',
        'degree'          => $u->degree ?? '—',
        'branch'          => $u->branch ?? '—',
        'current_status'  => $u->current_status ?? '—',
        'company'         => $u->company ?? '—',
        // Professional fields
        'organization'    => $u->organization ?? '—',
        'industry'        => $u->industry ?? '—',
        'role'            => $u->role ?? '—',
        'from'            => $u->from ?? '—',
        'to'              => $u->to ?? '—',
        'pro_location'    => $u->pro_location ?? '—',
    ];
})) !!};

// ── State ────────────────────────────────────────────────────────────────────
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
    const status = document.getElementById('statusFilter').value;
    const year   = document.getElementById('yearFilter').value;

    filtered = alumni.filter(a => {
        const matchQ = !q ||
            a.name.toLowerCase().includes(q) ||
            a.email.toLowerCase().includes(q) ||
            (a.department && a.department.toLowerCase().includes(q)) ||
            String(a.graduation_year).includes(q);
        const matchS = !status || a.status === status;
        const matchY = !year   || a.graduation_year == year;
        return matchQ && matchS && matchY;
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

    const headers = ['name', 'department', 'graduation_year', 'email', 'status', 'created_at'];
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
        tbody.innerHTML = `<tr><td colspan="7" style="padding:60px 0; text-align:center; color:#7a7f90;">
            <svg style="margin:0 auto 12px" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
            </svg>
            <p style="font-size:14px">No SRU alumni match your filters.</p>
        </td></tr>`;
    } else {
        tbody.innerHTML = slice.map(a => {
            const initials  = (a.full_name && a.full_name !== '—' ? a.full_name : a.name).split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase();
            const color     = getColor(a.id);
            const badgeStyles = {
                Active:   'background:rgba(76,175,125,.15); color:#4caf7d;',
                Pending:  'background:rgba(201,168,76,.15);  color:#e8c97a;',
                Inactive: 'background:rgba(122,127,144,.12); color:#7a7f90;',
            };
            const badge = badgeStyles[a.status] || badgeStyles.Inactive;
            const date  = a.created_at
                ? new Date(a.created_at).toLocaleDateString('en-IN', { day:'numeric', month:'short', year:'numeric' })
                : '—';

            // Prefer full_name if available, otherwise name
            const displayName = (a.full_name && a.full_name !== '—') ? a.full_name : a.name;

            return `
            <tr style="border-bottom:1px solid #252a38; cursor:default; transition:background .12s;"
                onmouseover="this.style.background='rgba(255,255,255,.025)'"
                onmouseout="this.style.background='transparent'">

                <td style="padding:16px 20px; vertical-align:middle;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:36px;height:36px;border-radius:50%;background:${color};
                                    display:flex;align-items:center;justify-content:center;
                                    font-weight:700;font-size:13px;color:#fff;flex-shrink:0;">
                            ${initials}
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:14px;">${displayName}</div>
                            <div style="font-size:11px;color:#7a7f90;">#SRU-${String(a.id).padStart(4,'0')}</div>
                        </div>
                    </div>
                </td>

                <td style="padding:16px 20px;font-size:13.5px;vertical-align:middle;">${a.department}</td>
                <td style="padding:16px 20px;font-size:13.5px;vertical-align:middle;">${a.graduation_year}</td>
                <td style="padding:16px 20px;font-size:13.5px;vertical-align:middle;color:#7a7f90;">${a.email}</td>

                <td style="padding:16px 20px;vertical-align:middle;">
                    <span class="${a.status === 'Pending' ? 'badge-pending' : ''}"
                          style="${badge} display:inline-flex;align-items:center;padding:3px 10px;
                                 border-radius:20px;font-size:11px;font-weight:600;letter-spacing:.04em;">
                        ${a.status}
                    </span>
                </td>

                <td style="padding:16px 20px;font-size:13px;color:#7a7f90;vertical-align:middle;">${date}</td>

                <td style="padding:16px 20px;vertical-align:middle;">
                    <div class="row-actions" style="display:flex;align-items:center;gap:8px;opacity:0;transition:opacity .15s;">
                        <button title="View Details" onclick="openModal(${a.id})"
                                style="width:30px;height:30px;border-radius:7px;background:#252a38;
                                       border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;
                                       transition:all .15s;"
                                onmouseover="this.style.background='#35394a'"
                                onmouseout="this.style.background='#252a38'">
                            <svg width="13" height="13" fill="none" stroke="#7a7f90" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>

                        ${a.status === 'Pending' ? `
                        <button title="Approve" onclick="approveAlumni(${a.id})"
                                style="width:30px;height:30px;border-radius:7px;background:rgba(76,175,125,.15);
                                       border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;
                                       transition:all .15s;"
                                onmouseover="this.style.background='rgba(76,175,125,.3)'"
                                onmouseout="this.style.background='rgba(76,175,125,.15)'">
                            <svg width="13" height="13" fill="none" stroke="#4caf7d" stroke-width="2.5" viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </button>` : ''}

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
            min-width:32px; height:32px; padding:0 10px; border-radius:7px; border:none; cursor:pointer;
            font-size:13px; font-weight:600; font-family:'DM Sans',sans-serif;
            background:${active ? 'rgba(201,168,76,.2)' : '#252a38'};
            color:${active ? '#e8c97a' : '#7a7f90'};
            transition:all .15s;
        `;
        btn.onmouseover = () => { if (!active) btn.style.background = '#35394a'; };
        btn.onmouseout  = () => { if (!active) btn.style.background = '#252a38'; };
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

    const color    = getColor(a.id);
    const initials = a.name.split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase();
    const badgeStyles = {
        Active:   'background:rgba(76,175,125,.15); color:#4caf7d;',
        Pending:  'background:rgba(201,168,76,.15);  color:#e8c97a;',
        Inactive: 'background:rgba(122,127,144,.12); color:#7a7f90;',
    };
    const badge = badgeStyles[a.status] || badgeStyles.Inactive;

    document.getElementById('modalContent').innerHTML = `
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
            <div style="width:52px;height:52px;border-radius:50%;background:${color};
                        display:flex;align-items:center;justify-content:center;
                        font-weight:700;font-size:18px;color:#fff;flex-shrink:0;">
                ${initials}
            </div>
            <div>
                <div style="font-size:20px;font-weight:700;font-family:'Playfair Display',serif;">${a.name}</div>
                <div style="font-size:12px;color:#7a7f90;margin-top:3px;">#SRU-${String(a.id).padStart(4,'0')}</div>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            ${
              [
                ['Full Name',       a.full_name],
                ['Email',           a.email],
                ['Phone',           a.phone],
                ['City',            a.city],
                ['Country',         a.country],
                ['Organization',    a.organization],
                ['Industry',        a.industry],
                ['Role',            a.role],
                ['Degree',          a.degree],
                ['Branch',          a.branch],
                ['Passing Year',    a.graduation_year],
                ['Current Status',  a.current_status],
                ['Company',         a.company],
                ['Work From',       a.from],
                ['Work To',         a.to],
                ['Work Location',   a.pro_location],
                ['Status',          `<span style="${badge};display:inline-flex;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">${a.status}</span>`],
                ['Registered',      a.created_at ? new Date(a.created_at).toLocaleDateString('en-IN',{day:'numeric',month:'long',year:'numeric'}) : '—'],
              ].filter(([_, val]) => val !== '—').map(([label, val]) => `
                <div style="background:#0d0f14;border-radius:10px;padding:14px 16px;">
                    <p style="font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#7a7f90;margin-bottom:5px;">${label}</p>
                    <p style="font-size:14px;font-weight:500;">${val}</p>
                </div>
              `).join('')
            }
        </div>
    `;

    const approveBtn = document.getElementById('modalApproveBtn');
    if (a.status === 'Pending') {
        approveBtn.style.display = '';
        approveBtn.onclick = () => { approveAlumni(id); closeModal(); };
    } else {
        approveBtn.style.display = 'none';
    }

    document.getElementById('modalOverlay').classList.add('open');
}

function handleOverlayClick(e) {
    if (e.target === document.getElementById('modalOverlay')) closeModal();
}

function closeModal() {
    document.getElementById('modalOverlay').classList.remove('open');
}

// ── Actions ───────────────────────────────────────────────────────────────────
function approveAlumni(id) {
    const a = alumni.find(x => x.id === id);
    if (!a) return;

    // Send to Laravel backend via AJAX
    fetch(`/admin/alumni/${id}/approve`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            a.status = 'Active';
            filterTable();
            showToast(`✓ ${a.name} approved successfully`);
        } else {
            showToast(`✗ Failed to approve ${a.name}: ${data.message}`);
        }
    })
    .catch(err => {
        console.warn('Backend error:', err);
        showToast(`✗ Failed to approve ${a.name}`);
    });
}

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
    }).catch(err => console.warn('Backend not connected yet:', err));

    alumni.splice(alumni.indexOf(a), 1);
    filtered = filtered.filter(x => x.id !== id);
    renderTable();
    showToast(`${a.name} removed from SRU registry`);
}

// ── Export CSV ────────────────────────────────────────────────────────────────
function exportCSV() {
    const headers = ['ID','Name','Email','Phone','Department','Batch Year','Status','Location','Registered'];
    const rows = alumni.map(a => [
        a.id, a.name, a.email, a.phone, a.department,
        a.graduation_year, a.status, a.location, a.created_at
    ]);
    const csv  = [headers, ...rows].map(r => r.join(',')).join('\n');
    const blob = new Blob([csv], { type: 'text/csv' });
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