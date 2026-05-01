@php
    $activeSection = $activeSection ?? '';

    $dashboardClasses = $activeSection === 'dashboard'
        ? 'nav-active flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-900 hover:text-slate-900'
        : 'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900';

    $alumniClasses = $activeSection === 'alumni'
        ? 'nav-active flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-900 hover:text-slate-900'
        : 'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900';

    $newsClasses = $activeSection === 'news'
        ? 'nav-active flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150'
        : 'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900';

    $eventsClasses = $activeSection === 'events'
        ? 'nav-active flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150'
        : 'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900';

    $galleryClasses = $activeSection === 'gallery'
        ? 'nav-active flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150'
        : 'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900';

    $jobsClasses = $activeSection === 'jobs'
        ? 'nav-active flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150'
        : 'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900';

    $engageClasses = $activeSection === 'engage'
        ? 'nav-active flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150'
        : 'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900';

    $messagesClasses = $activeSection === 'messages'
        ? 'nav-active flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-900 hover:text-slate-900'
        : 'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900';

    $activityClasses = $activeSection === 'activity-logs'
        ? 'nav-active flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-900 hover:text-slate-900'
        : 'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900';

    $newsUpdateClasses = $activeSection === 'news'
        ? 'rounded-lg px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-700'
        : 'rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900';

    $eventsUpdateClasses = $activeSection === 'events'
        ? 'rounded-lg px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-700'
        : 'rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900';

    $galleryUpdateClasses = $activeSection === 'gallery'
        ? 'rounded-lg px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-700'
        : 'rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900';

    $jobsUpdateClasses = $activeSection === 'jobs'
        ? 'rounded-lg px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-700'
        : 'rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900';

    $engageUpdateClasses = $activeSection === 'engage'
        ? 'rounded-lg px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-700'
        : 'rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900';
@endphp

<aside class="w-64 min-h-screen flex flex-col fixed left-0 top-0 bottom-0 z-50 bg-white border-r border-slate-300">
    <div class="px-6 border-b border-slate-300 min-h-[89px] flex flex-col justify-center" style="padding-top: 1.25rem; padding-bottom: 1.36rem;">
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

    <nav class="flex-1 px-4 py-5 space-y-0.5">
        <p class="text-xs font-semibold tracking-widest uppercase px-3 mb-2 mt-1 text-slate-500">Overview</p>

        <a href="{{ route('admin.dashboard') }}" class="{{ $dashboardClasses }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('admin.allalumini') }}" class="{{ $alumniClasses }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            All SRU Alumni
        </a>

        <p class="text-xs font-semibold tracking-widest uppercase px-3 mb-2 mt-5 text-slate-500">Management</p>

        <a href="{{ route('messages.index') }}" class="{{ $messagesClasses }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
            </svg>
            Messages
        </a>

        <div class="group relative">
            <a href="#" class="{{ $newsClasses }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v14l-4-2-4 2-4-2-4 2V5z"/>
                    <line x1="8" y1="8" x2="16" y2="8"/>
                    <line x1="8" y1="11" x2="16" y2="11"/>
                </svg>
                <span class="flex-1">News</span>
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="transition-transform duration-150 group-hover:rotate-180">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </a>
            <div class="absolute left-full top-0 z-50 hidden min-w-[11rem] flex-col gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-lg group-hover:flex">
                <a href="{{ route('newsroom') }}" target="_blank" rel="noopener noreferrer" onclick="event.preventDefault(); event.stopPropagation(); window.open(this.href, '_blank');" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">View</a>
                <a href="{{ route('admin.news.create') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">New</a>
                <a href="{{ route('admin.news.manage') }}" class="{{ $newsUpdateClasses }}">Update/Delete</a>
            </div>
        </div>

        <div class="group relative">
            <a href="#" class="{{ $eventsClasses }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <span class="flex-1">Events</span>
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="transition-transform duration-150 group-hover:rotate-180">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </a>
            <div class="absolute left-full top-0 z-50 hidden min-w-[11rem] flex-col gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-lg group-hover:flex">
                <a href="{{ route('events.index') }}" target="_blank" rel="noopener noreferrer" onclick="event.preventDefault(); event.stopPropagation(); window.open(this.href, '_blank');" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">View</a>
                <a href="{{ route('admin.events.create') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">New</a>
                <a href="{{ route('admin.events.manage') }}" class="{{ $eventsUpdateClasses }}">Update/Delete</a>
            </div>
        </div>

        <div class="group relative">
            <a href="#" class="{{ $galleryClasses }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="flex-1">Gallery</span>
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="transition-transform duration-150 group-hover:rotate-180">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </a>
            <div class="absolute left-full top-0 z-50 hidden min-w-[11rem] flex-col gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-lg group-hover:flex">
                <a href="{{ route('gallery') }}" target="_blank" rel="noopener noreferrer" onclick="event.preventDefault(); event.stopPropagation(); window.open(this.href, '_blank');" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">View</a>
                <a href="{{ route('admin.gallery.create', ['section' => 'albums']) }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">New</a>
                <a href="{{ route('admin.gallery.manage', ['section' => 'albums']) }}" class="{{ $galleryUpdateClasses }}">Update/Delete</a>
            </div>
        </div>

        <div class="group relative">
            <a href="#" class="{{ $jobsClasses }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                    <path d="M7 12h10"></path>
                    <path d="M7 16h10"></path>
                </svg>
                <span class="flex-1">Jobs</span>
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="transition-transform duration-150 group-hover:rotate-180">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </a>
            <div class="absolute left-full top-0 z-50 hidden min-w-[11rem] flex-col gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-lg group-hover:flex">
                <a href="{{ route('jobs.index') }}" target="_blank" rel="noopener noreferrer" onclick="event.preventDefault(); event.stopPropagation(); window.open(this.href, '_blank');" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">View</a>
                <a href="{{ route('admin.jobs.create') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">New</a>
                <a href="{{ route('admin.jobs.manage') }}" class="{{ $jobsUpdateClasses }}">Update/Delete</a>
            </div>
        </div>

        <div class="group relative">
            <a href="#" class="{{ $engageClasses }}">
                <span class="flex-1">Engage</span>
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="transition-transform duration-150 group-hover:rotate-180">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </a>
            <div class="absolute left-full top-0 z-50 hidden min-w-[11rem] flex-col gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-lg group-hover:flex">
                <a href="{{ route('engage') }}" target="_blank" rel="noopener noreferrer" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">View</a>
                <a href="{{ route('admin.engage.create') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">New</a>
                <a href="{{ route('admin.engage.manage') }}" class="{{ $engageUpdateClasses }}">Update/Delete</a>
            </div>
        </div>

        <a href="{{ route('admin.activity-logs') }}" class="{{ $activityClasses }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 3v18h18"/>
                <path d="M8 14l3-3 3 2 4-5"/>
            </svg>
            Activity Logs
        </a>
    </nav>

    <div class="px-4 py-5 border-t border-slate-300">
        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50">
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
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="transition-colors duration-150">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </a>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
    </div>
</aside>

