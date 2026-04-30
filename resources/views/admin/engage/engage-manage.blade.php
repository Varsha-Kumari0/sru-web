<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage Engage - SRU Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        .nav-active { background: rgba(59,130,246,.14); color: #1d4ed8 !important; }
    </style>
</head>
<body class="min-h-screen flex bg-slate-50 text-slate-900 [background-image:radial-gradient(ellipse_at_10%_20%,rgba(59,130,246,.08)_0%,transparent_60%),radial-gradient(ellipse_at_90%_80%,rgba(148,163,184,.12)_0%,transparent_60%)]">
@include('admin.partials.sidebar', ['activeSection' => 'engage'])

<main class="ml-64 flex-1 flex flex-col min-h-screen">
    <header class="sticky top-0 z-40 flex items-center justify-between px-6 pt-[1.9rem] pb-[1.7em] xl:px-9 bg-white border-b border-slate-300">
        <div>
            <h2 class="font-display text-2xl font-semibold">Manage Feed Interactions</h2>
            <p class="text-xs mt-0.5 text-slate-500">Review likes and comments across posts, news, events, and testimonials.</p>
        </div>
        <a href="{{ route('admin.engage.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Create New</a>
    </header>

    <div class="p-9">
        @if(session('success'))
            <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
            @forelse($items as $item)
                <div class="rounded-xl border border-slate-300 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-lg font-semibold text-slate-900">{{ $item['kind'] }}</h3>
                            <p class="mt-1 text-xs text-slate-500">{{ $item['title'] }}</p>
                        </div>
                        <span class="flex-shrink-0 whitespace-nowrap rounded-md bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-700">{{ strtoupper($item['feed_type']) }} #{{ $item['feed_id'] }}</span>
                    </div>

                    <p class="mt-4 text-sm text-slate-700 line-clamp-4">{{ $item['body'] }}</p>

                    <div class="mt-4 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                        <span class="rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-700">{{ $item['comments_count'] }} comments</span>
                        <span class="rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-700">{{ $item['likes_count'] }} likes</span>
                        <span>By {{ $item['owner'] }}</span>
                    </div>

                    <div class="mt-5 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.engage.feed.review', [$item['feed_type'], $item['feed_id']]) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Review</a>
                        @if($item['can_delete_source'] && $item['source_delete_route'])
                            <form method="POST" action="{{ $item['source_delete_route'] }}" onsubmit="return confirm('Delete this engage post?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Delete Post</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500 xl:col-span-2">No feed items available to moderate.</div>
            @endforelse
        </div>
    </div>
</main>
</body>
</html>
