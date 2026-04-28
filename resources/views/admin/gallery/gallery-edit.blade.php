<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Update Gallery Item - SRU Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        .nav-active { background: rgba(59,130,246,.14); color: #1d4ed8 !important; }
    </style>
</head>
<body class="min-h-screen flex bg-slate-50 text-slate-900 [background-image:radial-gradient(ellipse_at_10%_20%,rgba(59,130,246,.08)_0%,transparent_60%),radial-gradient(ellipse_at_90%_80%,rgba(148,163,184,.12)_0%,transparent_60%)]">
<aside class="w-64 min-h-screen flex flex-col fixed left-0 top-0 bottom-0 z-50 bg-white border-r border-slate-300">
    <div class="px-6 py-5 border-b border-slate-300 min-h-[89px] flex flex-col justify-center">
        @php $dashboardLogoPath = 'images/logos/sru_logo_new.png'; @endphp
        @if(file_exists(public_path($dashboardLogoPath)))
            <img src="{{ asset($dashboardLogoPath) }}" alt="SRU Alumni Logo" class="h-12 w-auto object-contain">
        @else
            <h1 class="font-display text-xl font-bold text-sky-400 tracking-[0.02em] leading-tight">SRU<br>Alumni</h1>
        @endif
        <span class="text-xs font-semibold tracking-widest uppercase mt-1 block text-slate-500">Admin Control</span>
    </div>

    <nav class="flex-1 px-4 py-5 space-y-0.5">
        <p class="text-xs font-semibold tracking-widest uppercase px-3 mb-2 mt-1 text-slate-500">Overview</p>
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-500 hover:text-slate-900">Dashboard</a>
        <a href="{{ route('admin.allalumini') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-500 hover:text-slate-900">All SRU Alumni</a>

        <p class="text-xs font-semibold tracking-widest uppercase px-3 mb-2 mt-5 text-slate-500">Management</p>
        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
            </svg>
            Messages
        </a>
        <div class="group relative">
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-500 hover:text-slate-900"><span class="flex-1">News</span></a>
            <div class="absolute left-full top-0 z-50 hidden min-w-[11rem] flex-col gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-lg group-hover:flex">
                <a href="{{ route('newsroom') }}" target="_blank" rel="noopener noreferrer" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 hover:bg-slate-100 hover:text-slate-900">View</a>
                <a href="{{ route('admin.news.create') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 hover:bg-slate-100 hover:text-slate-900">New</a>
                <a href="{{ route('admin.news.manage') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 hover:bg-slate-100 hover:text-slate-900">Update/Delete</a>
            </div>
        </div>

        <div class="group relative">
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-500 hover:text-slate-900"><span class="flex-1">Events</span></a>
            <div class="absolute left-full top-0 z-50 hidden min-w-[11rem] flex-col gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-lg group-hover:flex">
                <a href="{{ route('events.index') }}" target="_blank" rel="noopener noreferrer" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 hover:bg-slate-100 hover:text-slate-900">View</a>
                <a href="{{ route('admin.events.create') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 hover:bg-slate-100 hover:text-slate-900">New</a>
                <a href="{{ route('admin.events.manage') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 hover:bg-slate-100 hover:text-slate-900">Update/Delete</a>
            </div>
        </div>

        <div class="group relative">
            <a href="#" class="nav-active flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium"><span class="flex-1">Gallery</span></a>
            <div class="absolute left-full top-0 z-50 hidden min-w-[11rem] flex-col gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-lg group-hover:flex">
                <a href="{{ route('gallery') }}" target="_blank" rel="noopener noreferrer" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 hover:bg-slate-100 hover:text-slate-900">View</a>
                <a href="{{ route('admin.gallery.create', ['section' => $section]) }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 hover:bg-slate-100 hover:text-slate-900">New</a>
                <a href="{{ route('admin.gallery.manage', ['section' => $section]) }}" class="rounded-lg px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-700">Update/Delete</a>
            </div>
        </div>

        <div class="group relative">
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-500 hover:text-slate-900"><span class="flex-1">Jobs</span></a>
            <div class="absolute left-full top-0 z-50 hidden min-w-[11rem] flex-col gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-lg group-hover:flex">
                <a href="{{ route('jobs.index') }}" target="_blank" rel="noopener noreferrer" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 hover:bg-slate-100 hover:text-slate-900">View</a>
                <a href="{{ route('admin.jobs.create') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 hover:bg-slate-100 hover:text-slate-900">New</a>
                <a href="{{ route('admin.jobs.manage') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 hover:bg-slate-100 hover:text-slate-900">Update/Delete</a>
            </div>
        </div>

        <a href="{{ route('admin.activity-logs') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-500 hover:text-slate-900">Activity Logs</a>
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
            <h2 class="font-display text-2xl font-semibold">Update Gallery Item</h2>
            <p class="text-xs mt-0.5 text-slate-500">Edit and save {{ strtolower($sectionLabel) }} content.</p>
        </div>
        <a href="{{ route('admin.gallery.manage', ['section' => $section]) }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Back to Manage</a>
    </header>

    <div class="p-9">
        <div class="max-w-4xl rounded-xl border border-slate-300 bg-white p-6">
            @if($errors->any())
                <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="mb-1 font-semibold">Please fix the following:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.gallery.update', ['section' => $section, 'id' => $item->id]) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="mb-1.5 block text-sm font-semibold text-slate-700">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $item->title) }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                </div>

                <div>
                    <label for="summary" class="mb-1.5 block text-sm font-semibold text-slate-700">Summary</label>
                    <textarea id="summary" name="summary" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">{{ old('summary', $item->summary) }}</textarea>
                </div>

                @if($section === 'albums')
                    @php $existingPhotos = $item->photos; @endphp
                    @if($existingPhotos->isNotEmpty())
                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-slate-700">Existing Photos ({{ $existingPhotos->count() }})</label>
                            <div class="grid grid-cols-3 gap-3 sm:grid-cols-4 md:grid-cols-6">
                                @foreach($existingPhotos as $photo)
                                    <div class="relative">
                                        <img src="{{ asset('images/albums/' . $photo->file_name) }}" alt="Photo" class="h-20 w-full rounded-lg object-cover ring-1 ring-slate-200">
                                        <label class="absolute top-1 right-1 cursor-pointer">
                                            <input type="checkbox" name="delete_photos[]" value="{{ $photo->id }}" class="sr-only peer">
                                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-white/90 text-xs text-red-600 ring-1 ring-red-300 peer-checked:bg-red-600 peer-checked:text-white">✕</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <p class="mt-1 text-xs text-slate-400">Click the ✕ on a photo to mark it for deletion.</p>
                        </div>
                    @endif
                    <div>
                        <label for="photos" class="mb-1.5 block text-sm font-semibold text-slate-700">Add More Photos <span class="text-slate-400 font-normal">(optional)</span></label>
                        <input type="file" id="photos" name="photos[]" accept="image/jpeg,image/png,image/jpg,image/webp" multiple
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        <p id="photo-count-hint" class="mt-1 text-xs text-slate-400">No new photos selected.</p>
                    </div>
                @endif

                @if($section === 'videos')
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="video_url" class="mb-1.5 block text-sm font-semibold text-slate-700">Video URL <span class="text-red-500">*</span></label>
                            <input type="url" id="video_url" name="video_url" value="{{ old('video_url', $item->video_url) }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label for="duration" class="mb-1.5 block text-sm font-semibold text-slate-700">Duration</label>
                            <input type="text" id="duration" name="duration" value="{{ old('duration', $item->duration) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <label for="published_at" class="mb-1.5 block text-sm font-semibold text-slate-700">Publish Date</label>
                        <input type="date" id="published_at" name="published_at" value="{{ old('published_at', optional($item->published_at)->toDateString()) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label for="display_order" class="mb-1.5 block text-sm font-semibold text-slate-700">Display Order</label>
                        <input type="number" min="0" id="display_order" name="display_order" value="{{ old('display_order', $item->display_order) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label for="image" class="mb-1.5 block text-sm font-semibold text-slate-700">{{ $section === 'albums' ? 'Replace Cover Image' : ($section === 'videos' ? 'Replace Thumbnail Image' : 'Replace Image') }}</label>
                        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $item->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-blue-600">
                        Active
                    </label>

                    @if($section === 'albums')
                        <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $item->is_featured) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-blue-600">
                            Featured Album
                        </label>
                    @endif
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('admin.gallery.manage', ['section' => $section]) }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</main>
<script>
    const photosInput = document.getElementById('photos');
    if (photosInput) {
        photosInput.addEventListener('change', function () {
            const hint = document.getElementById('photo-count-hint');
            const count = photosInput.files.length;
            hint.textContent = count > 0 ? count + ' new photo' + (count !== 1 ? 's' : '') + ' selected.' : 'No new photos selected.';
        });
    }
</script>
</body>
</html>
