<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Engage Post - SRU Admin</title>
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
            <h2 class="font-display text-2xl font-semibold">Create Engage Post</h2>
            <p class="text-xs mt-0.5 text-slate-500">Publish an Engage feed post for the community.</p>
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

                <form method="POST" action="{{ route('admin.engage.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="post_type" class="mb-1.5 block text-sm font-semibold text-slate-700">Post Type</label>
                        <select id="post_type" name="post_type" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none bg-white">
                            <option value="">Select type</option>
                            @foreach($postTypes as $key => $label)
                                <option value="{{ $key }}" @selected(old('post_type') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="body" class="mb-1.5 block text-sm font-semibold text-slate-700">Post Content</label>
                        <textarea id="body" name="body" rows="6" maxlength="1200" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" placeholder="Write the engage post content...">{{ old('body') }}</textarea>
                        <p class="mt-1 text-xs text-slate-500">Minimum 10 characters, maximum 1200.</p>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.engage.manage') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Manage</a>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Publish</button>
                    </div>
                </form>
            </div>

            <div class="rounded-xl border border-slate-300 bg-white p-6">
                <h3 class="text-sm font-semibold text-slate-800">Recent Engage Posts</h3>
                <div class="mt-4 space-y-3">
                    @forelse($recentPosts as $item)
                        <div class="rounded-lg border border-slate-200 p-3">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">{{ $postTypes[$item->post_type] ?? ucfirst($item->post_type) }}</p>
                            <p class="mt-1 text-sm text-slate-700 line-clamp-3">{{ $item->body }}</p>
                            <p class="mt-2 text-xs text-slate-500">By {{ $item->user?->name ?? 'Unknown' }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No engage posts yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
