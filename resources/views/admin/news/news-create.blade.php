<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create News - SRU Admin</title>
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

@include('admin.partials.sidebar', ['activeSection' => 'news'])

<main class="ml-64 flex-1 flex flex-col min-h-screen">
    <header class="sticky top-0 z-40 flex items-center justify-between px-6 pt-[1.9rem] pb-[1.7em] xl:px-9 bg-white border-b border-slate-300">
        <div>
            <h2 class="font-display text-2xl font-semibold">Create News</h2>
            <p class="text-xs mt-0.5 text-slate-500">Add a new news item to appear in the newsroom page.</p>
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

            <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="title" class="mb-1.5 block text-sm font-semibold text-slate-700">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                </div>

                <div>
                    <label for="excerpt" class="mb-1.5 block text-sm font-semibold text-slate-700">Excerpt</label>
                    <textarea id="excerpt" name="excerpt" rows="3" required
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">{{ old('excerpt') }}</textarea>
                </div>

                <div>
                    <label for="content" class="mb-1.5 block text-sm font-semibold text-slate-700">Content</label>
                    <textarea id="content" name="content" rows="6"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">{{ old('content') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="published_at" class="mb-1.5 block text-sm font-semibold text-slate-700">Published Date</label>
                        <input type="date" id="published_at" name="published_at" value="{{ old('published_at', now()->toDateString()) }}" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label for="image" class="mb-1.5 block text-sm font-semibold text-slate-700">Image (optional)</label>
                        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('admin.dashboard') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Publish News</button>
                </div>
            </form>
            </div>

            <aside class="rounded-xl border border-slate-300 bg-white p-6 xl:sticky xl:top-28">
                <div class="mb-4 flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-display text-xl font-semibold text-slate-900">Recent Updated News</h3>
                        <p class="mt-1 text-xs text-slate-500">Latest news items ordered by most recent update time.</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ $recentUpdatedNews->count() }}</span>
                </div>

                <div class="space-y-3">
                    @forelse($recentUpdatedNews as $recentNews)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-slate-900">{{ $recentNews->title }}</p>
                                    <p class="mt-1 line-clamp-2 text-xs text-slate-600">{{ $recentNews->excerpt }}</p>
                                </div>
                                @if($recentNews->created_at?->eq($recentNews->updated_at))
                                    <span class="flex-shrink-0 whitespace-nowrap rounded-md bg-green-50 px-2 py-1 text-[11px] font-semibold text-green-700">Created</span>
                                @else
                                    <span class="flex-shrink-0 whitespace-nowrap rounded-md bg-blue-50 px-2 py-1 text-[11px] font-semibold text-blue-700">Updated</span>
                                @endif
                            </div>

                            <div class="mt-3 space-y-1 text-xs text-slate-500">
                                @if($recentNews->created_at?->eq($recentNews->updated_at))
                                    <div>Created: {{ $recentNews->created_at?->format('d M Y, h:i A') ?? '-' }}</div>
                                @else
                                    <div>Updated: {{ $recentNews->updated_at?->format('d M Y, h:i A') ?? '-' }}</div>
                                    <div>Created: {{ $recentNews->created_at?->format('d M Y, h:i A') ?? '-' }}</div>
                                @endif
                                <div>Published: {{ $recentNews->published_at?->format('d M Y') ?? '-' }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
                            No news items have been created yet.
                        </div>
                    @endforelse
                </div>
            </aside>
        </div>
    </div>
</main>

</body>
</html>
