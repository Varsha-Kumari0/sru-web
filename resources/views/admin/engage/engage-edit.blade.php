<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Review Feed Interactions - SRU Admin</title>
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
            <h2 class="font-display text-2xl font-semibold">Feed Interaction Details</h2>
            <p class="text-xs mt-0.5 text-slate-500">Review this feed item, see who commented or liked it, and delete those interactions if needed.</p>
        </div>
        <a href="{{ route('admin.engage.manage') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Back to Manage</a>
    </header>

    <div class="p-9 space-y-6">
        @if(session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
        @endif

        <div class="rounded-xl border border-slate-300 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $sourceData['kind'] }}</span>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ strtoupper($feedType) }} #{{ $feedId }}</span>
                    </div>
                    <p class="mt-3 text-sm text-slate-500">Posted by {{ $sourceData['owner'] }}</p>
                </div>
                @if($canDeleteSource)
                    <form method="POST" action="{{ route('admin.engage.delete', $feedId) }}" onsubmit="return confirm('Delete this engage post and all its comments and likes?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Delete Post</button>
                    </form>
                @endif
            </div>

            <div class="mt-5 rounded-xl bg-slate-50 p-4 text-sm leading-7 text-slate-800 whitespace-pre-wrap break-words">{{ $sourceData['body'] }}</div>

            <div class="mt-5 flex flex-wrap gap-3 text-xs text-slate-500">
                <span class="rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-700">{{ $comments->count() }} comments</span>
                <span class="rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-700">{{ $reactions->where('reaction', 'like')->count() }} likes</span>
                <span>Updated {{ $sourceData['updated_at']?->diffForHumans() }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <section class="rounded-xl border border-slate-300 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Comments</h3>
                        <p class="text-xs text-slate-500">All comments made on this feed item.</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $comments->count() }}</span>
                </div>

                <div class="space-y-4">
                    @forelse($comments as $comment)
                        <article class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-slate-900">{{ $comment->user?->display_name ?? 'Unknown User' }}</p>
                                    <p class="text-xs text-slate-500">{{ $comment->user?->email ?? '-' }} • {{ $comment->created_at?->format('d M Y, h:i A') ?? '-' }}</p>
                                </div>
                                <form method="POST" action="{{ route('admin.engage.comments.delete', $comment->id) }}" onsubmit="return confirm('Delete this comment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700">Delete</button>
                                </form>
                            </div>
                            <div class="mt-3 whitespace-pre-wrap break-words text-sm leading-6 text-slate-700">{{ $comment->body }}</div>
                        </article>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500">No comments on this feed item yet.</div>
                    @endforelse
                </div>
            </section>

            <section class="rounded-xl border border-slate-300 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Likes</h3>
                        <p class="text-xs text-slate-500">People who reacted to this feed item.</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $reactions->count() }}</span>
                </div>

                <div class="space-y-4">
                    @forelse($reactions as $reaction)
                        <article class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-slate-900">{{ $reaction->user?->display_name ?? 'Unknown User' }}</p>
                                    <p class="text-xs text-slate-500">{{ $reaction->user?->email ?? '-' }} • {{ ucfirst($reaction->reaction ?? 'reaction') }} • {{ $reaction->created_at?->format('d M Y, h:i A') ?? '-' }}</p>
                                </div>
                                <form method="POST" action="{{ route('admin.engage.reactions.delete', $reaction->id) }}" onsubmit="return confirm('Delete this like or reaction?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700">Delete</button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500">No likes or reactions on this feed item yet.</div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</main>
</body>
</html>
