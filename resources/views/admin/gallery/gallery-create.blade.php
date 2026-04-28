<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Gallery Item - SRU Admin</title>
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
                <a href="{{ route('admin.gallery.create', ['section' => $section]) }}" class="rounded-lg px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-700">New</a>
                <a href="{{ route('admin.gallery.manage', ['section' => $section]) }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 hover:bg-slate-100 hover:text-slate-900">Update/Delete</a>
            </div>
        </div>

        <a href="{{ route('admin.activity-logs') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-500 hover:text-slate-900">Activity Logs</a>
    </nav>
</aside>

<main class="ml-64 flex-1 flex flex-col min-h-screen">
    <header class="sticky top-0 z-40 flex items-center justify-between px-6 pt-[1.9rem] pb-[1.7em] xl:px-9 bg-white border-b border-slate-300">
        <div>
            <h2 class="font-display text-2xl font-semibold">Create Gallery Item</h2>
            <p class="text-xs mt-0.5 text-slate-500">Add new {{ strtolower($sectionLabel) }} content to the public gallery.</p>
        </div>
        <a href="{{ route('admin.gallery.manage', ['section' => $section]) }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Manage {{ $sectionLabel }}</a>
    </header>

    <div class="p-9">
        <div class="mb-6 flex flex-wrap gap-2">
            @foreach($sections as $key => $meta)
                <a href="{{ route('admin.gallery.create', ['section' => $key]) }}" class="rounded-lg px-4 py-2 text-sm font-semibold {{ $section === $key ? 'bg-blue-600 text-white' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50' }}">{{ $meta['label'] }}</a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_340px] xl:items-start">
            <div class="rounded-xl border border-slate-300 bg-white p-6">
                @if(session('success'))
                    <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
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

                <form method="POST" action="{{ route('admin.gallery.store', ['section' => $section]) }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label for="title" class="mb-1.5 block text-sm font-semibold text-slate-700">Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="summary" class="mb-1.5 block text-sm font-semibold text-slate-700">Summary <span class="text-slate-400 font-normal">(optional)</span></label>
                        <textarea id="summary" name="summary" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">{{ old('summary') }}</textarea>
                    </div>

                    @if($section === 'albums')
                        <div>
                            <label for="photos" class="mb-1.5 block text-sm font-semibold text-slate-700">Album Photos <span class="text-slate-400 font-normal">(select multiple)</span></label>
                            <input type="file" id="photos" name="photos[]" accept="image/jpeg,image/png,image/jpg,image/webp" multiple
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                            <p id="photo-count-hint" class="mt-1 text-xs text-slate-400">No photos selected.</p>
                        </div>
                    @endif

                    @if($section === 'videos')
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="video_url" class="mb-1.5 block text-sm font-semibold text-slate-700">Video URL <span class="text-red-500">*</span></label>
                                <input type="url" id="video_url" name="video_url" value="{{ old('video_url') }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label for="duration" class="mb-1.5 block text-sm font-semibold text-slate-700">Duration <span class="text-slate-400 font-normal">(optional)</span></label>
                                <input type="text" id="duration" name="duration" value="{{ old('duration') }}" placeholder="e.g. 04:12" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label for="published_at" class="mb-1.5 block text-sm font-semibold text-slate-700">Publish Date <span class="text-slate-400 font-normal">(optional)</span></label>
                            <input type="date" id="published_at" name="published_at" value="{{ old('published_at') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label for="display_order" class="mb-1.5 block text-sm font-semibold text-slate-700">Display Order</label>
                            <input type="number" min="0" id="display_order" name="display_order" value="{{ old('display_order', 0) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label for="image" class="mb-1.5 block text-sm font-semibold text-slate-700">{{ $section === 'albums' ? 'Cover Image' : ($section === 'videos' ? 'Thumbnail Image' : 'Image') }} <span class="text-slate-400 font-normal">(optional)</span></label>
                            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-blue-600">
                            Active
                        </label>

                        @if($section === 'albums')
                            <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-blue-600">
                                Mark as Featured Album
                            </label>
                        @endif
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('admin.gallery.manage', ['section' => $section]) }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Create {{ rtrim($sectionLabel, 's') }}</button>
                    </div>
                </form>
            </div>

            <aside class="rounded-xl border border-slate-300 bg-white p-5">
                <h3 class="text-sm font-semibold text-slate-900">Recent {{ $sectionLabel }}</h3>
                <p class="mt-1 text-xs text-slate-500">Most recently updated items for this section.</p>
                <div class="mt-4 space-y-3">
                    @forelse($recentItems as $item)
                        <div class="rounded-lg border border-slate-200 px-3 py-3">
                            <p class="text-sm font-semibold text-slate-900 truncate">{{ $item->title }}</p>
                            <p class="mt-1 text-xs text-slate-500">Updated {{ $item->updated_at?->diffForHumans() ?? '-' }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No {{ strtolower($sectionLabel) }} items yet.</p>
                    @endforelse
                </div>
            </aside>
        </div>
    </div>
</main>
<script>
    const photosInput = document.getElementById('photos');
    if (photosInput) {
        photosInput.addEventListener('change', function () {
            const hint = document.getElementById('photo-count-hint');
            const count = photosInput.files.length;
            hint.textContent = count > 0 ? count + ' photo' + (count !== 1 ? 's' : '') + ' selected.' : 'No photos selected.';
        });
    }
</script>
</body>
</html>
