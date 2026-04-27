<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Event - SRU Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        .nav-active {
            background: rgba(59,130,246,.14);
            color: #1d4ed8 !important;
        }
    </style>
</head>
<body class="min-h-screen flex bg-slate-50 text-slate-900 [background-image:radial-gradient(ellipse_at_10%_20%,rgba(59,130,246,.08)_0%,transparent_60%),radial-gradient(ellipse_at_90%_80%,rgba(148,163,184,.12)_0%,transparent_60%)]">

<aside class="w-64 min-h-screen flex flex-col fixed left-0 top-0 bottom-0 z-50 bg-white border-r border-slate-300">
    <div class="px-6 py-5 border-b border-slate-300 min-h-[89px] flex flex-col justify-center">
        @php
            $dashboardLogoPath = 'images/logos/sru_logo_new.png';
        @endphp
        @if(file_exists(public_path($dashboardLogoPath)))
            <img src="{{ asset($dashboardLogoPath) }}" alt="SRU Alumni Logo" class="h-12 w-auto object-contain">
        @else
            <h1 class="font-display text-xl font-bold text-sky-400 tracking-[0.02em] leading-tight">SRU<br>Alumni</h1>
        @endif
        <span class="text-xs font-semibold tracking-widest uppercase mt-1 block text-slate-500">Admin Control</span>
    </div>

    <nav class="flex-1 px-4 py-5 space-y-0.5">
        <p class="text-xs font-semibold tracking-widest uppercase px-3 mb-2 mt-1 text-slate-500">Overview</p>

        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
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

        <div class="group relative">
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
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
                <a href="{{ route('admin.news.manage') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">Update/Delete</a>
            </div>
        </div>

        <div class="group relative">
            <a href="#" class="nav-active flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150">
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
                <a href="{{ route('admin.events.create') }}" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50">New</a>
                <a href="{{ route('admin.events.manage') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">Update/Delete</a>
            </div>
        </div>

        <div class="group relative">
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
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
                <a href="{{ route('admin.gallery.manage', ['section' => 'albums']) }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">Update/Delete</a>
            </div>
        </div>

        <a href="{{ route('admin.activity-logs') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
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

<main class="ml-64 flex-1 flex flex-col min-h-screen">
    <header class="sticky top-0 z-40 flex items-center justify-between px-6 pt-[1.9rem] pb-[1.7em] xl:px-9 bg-white border-b border-slate-300">
        <div>
            <h2 class="font-display text-2xl font-semibold">Create Event</h2>
            <p class="text-xs mt-0.5 text-slate-500">Add a new event to appear on the events page.</p>
        </div>
    </header>

    <div class="p-9">
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_340px] xl:items-start">
            <div class="rounded-xl border border-slate-300 bg-white p-6">
                @if(session('success'))
                    <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <p class="font-semibold mb-1">Please fix the following:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label for="title" class="mb-1.5 block text-sm font-semibold text-slate-700">Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="excerpt" class="mb-1.5 block text-sm font-semibold text-slate-700">Excerpt</label>
                        <textarea id="excerpt" name="excerpt" rows="3"
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">{{ old('excerpt') }}</textarea>
                    </div>

                    <div>
                        <label for="description" class="mb-1.5 block text-sm font-semibold text-slate-700">Description</label>
                        <textarea id="description" name="description" rows="6"
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="event_type" class="mb-1.5 block text-sm font-semibold text-slate-700">Event Type</label>
                            <select id="event_type" name="event_type" required
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none bg-white">
                                <option value="campus-events" {{ old('event_type') === 'campus-events' ? 'selected' : '' }}>Campus Events</option>
                                <option value="hackathons"    {{ old('event_type') === 'hackathons'    ? 'selected' : '' }}>Hackathons</option>
                                <option value="reunions"      {{ old('event_type') === 'reunions'      ? 'selected' : '' }}>Reunions</option>
                                <option value="webinars"      {{ old('event_type') === 'webinars'      ? 'selected' : '' }}>Webinars</option>
                            </select>
                        </div>
                        <div>
                            <label for="location" class="mb-1.5 block text-sm font-semibold text-slate-700">Location <span class="text-slate-400 font-normal">(optional)</span></label>
                            <input type="text" id="location" name="location" value="{{ old('location') }}"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_at" class="mb-1.5 block text-sm font-semibold text-slate-700">Start Date &amp; Time</label>
                            <input type="datetime-local" id="start_at" name="start_at" value="{{ old('start_at') }}" required
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label for="end_at" class="mb-1.5 block text-sm font-semibold text-slate-700">End Date &amp; Time <span class="text-slate-400 font-normal">(optional)</span></label>
                            <input type="datetime-local" id="end_at" name="end_at" value="{{ old('end_at') }}"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="registration_link" class="mb-1.5 block text-sm font-semibold text-slate-700">Registration Link <span class="text-slate-400 font-normal">(optional)</span></label>
                            <input type="url" id="registration_link" name="registration_link" value="{{ old('registration_link') }}"
                                   placeholder="https://..."
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label for="image" class="mb-1.5 block text-sm font-semibold text-slate-700">Image <span class="text-slate-400 font-normal">(optional)</span></label>
                            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('admin.dashboard') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Publish Event</button>
                    </div>
                </form>
            </div>

            <aside class="rounded-xl border border-slate-300 bg-white p-6 xl:sticky xl:top-28">
                <div class="mb-4 flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-display text-xl font-semibold text-slate-900">Recent Events</h3>
                        <p class="mt-1 text-xs text-slate-500">Latest events ordered by most recent update.</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ $recentEvents->count() }}</span>
                </div>

                <div class="space-y-3">
                    @forelse($recentEvents as $event)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-slate-900">{{ $event->title }}</p>
                                    <p class="mt-1 line-clamp-2 text-xs text-slate-600">{{ $event->excerpt }}</p>
                                </div>
                                @if($event->created_at?->eq($event->updated_at))
                                    <span class="flex-shrink-0 whitespace-nowrap rounded-md bg-green-50 px-2 py-1 text-[11px] font-semibold text-green-700">Created</span>
                                @else
                                    <span class="flex-shrink-0 whitespace-nowrap rounded-md bg-blue-50 px-2 py-1 text-[11px] font-semibold text-blue-700">Updated</span>
                                @endif
                            </div>
                            <div class="mt-3 space-y-1 text-xs text-slate-500">
                                @if($event->created_at?->eq($event->updated_at))
                                    <div>Created: {{ $event->created_at?->format('d M Y, h:i A') ?? '-' }}</div>
                                @else
                                    <div>Updated: {{ $event->updated_at?->format('d M Y, h:i A') ?? '-' }}</div>
                                    <div>Created: {{ $event->created_at?->format('d M Y, h:i A') ?? '-' }}</div>
                                @endif
                                <div>Starts: {{ $event->start_at?->format('d M Y, h:i A') ?? '-' }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
                            No events have been created yet.
                        </div>
                    @endforelse
                </div>
            </aside>
        </div>
    </div>
</main>

</body>
</html>
