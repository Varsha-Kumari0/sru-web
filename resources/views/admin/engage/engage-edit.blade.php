<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Update Engage Post - SRU Admin</title>
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
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">Dashboard</a>
        <a href="{{ route('admin.allalumini') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">All SRU Alumni</a>

        <p class="text-xs font-semibold tracking-widest uppercase px-3 mb-2 mt-5 text-slate-500">Management</p>
        <div class="group relative"><a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900"><span class="flex-1">News</span><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="transition-transform duration-150 group-hover:rotate-180"><polyline points="6 9 12 15 18 9"/></svg></a><div class="absolute left-full top-0 z-50 hidden min-w-[11rem] flex-col gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-lg group-hover:flex"><a href="{{ route('newsroom') }}" target="_blank" rel="noopener noreferrer" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">View</a><a href="{{ route('admin.news.create') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">New</a><a href="{{ route('admin.news.manage') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">Update/Delete</a></div></div>

        <div class="group relative"><a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900"><span class="flex-1">Events</span><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="transition-transform duration-150 group-hover:rotate-180"><polyline points="6 9 12 15 18 9"/></svg></a><div class="absolute left-full top-0 z-50 hidden min-w-[11rem] flex-col gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-lg group-hover:flex"><a href="{{ route('events.index') }}" target="_blank" rel="noopener noreferrer" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">View</a><a href="{{ route('admin.events.create') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">New</a><a href="{{ route('admin.events.manage') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">Update/Delete</a></div></div>

        <div class="group relative"><a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900"><span class="flex-1">Jobs</span><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="transition-transform duration-150 group-hover:rotate-180"><polyline points="6 9 12 15 18 9"/></svg></a><div class="absolute left-full top-0 z-50 hidden min-w-[11rem] flex-col gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-lg group-hover:flex"><a href="{{ route('jobs.index') }}" target="_blank" rel="noopener noreferrer" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">View</a><a href="{{ route('admin.jobs.create') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">New</a><a href="{{ route('admin.jobs.manage') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">Update/Delete</a></div></div>

        <div class="group relative"><a href="#" class="nav-active flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150"><span class="flex-1">Engage</span><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="transition-transform duration-150 group-hover:rotate-180"><polyline points="6 9 12 15 18 9"/></svg></a><div class="absolute left-full top-0 z-50 hidden min-w-[11rem] flex-col gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-lg group-hover:flex"><a href="{{ route('engage') }}" target="_blank" rel="noopener noreferrer" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">View</a><a href="{{ route('admin.engage.create') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">New</a><a href="{{ route('admin.engage.manage') }}" class="rounded-lg px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-700">Update/Delete</a></div></div>

        <a href="{{ route('admin.activity-logs') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">Activity Logs</a>
    </nav>

    <div class="px-4 py-5 border-t border-slate-300">
        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50">
            <form method="POST" action="{{ route('admin.profile.avatar') }}" enctype="multipart/form-data" id="adminAvatarForm">@csrf
                <label for="adminAvatarInput" style="cursor:pointer;position:relative;display:block;width:36px;height:36px;border-radius:50%;flex-shrink:0;overflow:hidden;">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Admin" style="width:36px;height:36px;border-radius:50%;object-fit:contain;object-position:center;background:#fff;border:1px solid #dde3ec;">
                    @else
                        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#1d4ed8);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:#fff;">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                    @endif
                </label>
                <input type="file" id="adminAvatarInput" name="avatar" accept="image/jpg,image/jpeg,image/png" class="hidden" onchange="this.form.submit()">
            </form>
            <div class="flex-1 min-w-0"><p class="text-sm font-semibold truncate text-slate-900">{{ auth()->user()->name ?? 'Administrator' }}</p><p class="text-xs text-slate-500">Super Admin</p></div>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="inline-flex h-8 w-8 items-center justify-center rounded-md text-slate-500 transition-all duration-150 hover:bg-slate-100 hover:text-slate-900"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></a>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
    </div>
</aside>

<main class="ml-64 flex-1 flex flex-col min-h-screen">
    <header class="sticky top-0 z-40 flex items-center justify-between px-6 pt-[1.9rem] pb-[1.7em] xl:px-9 bg-white border-b border-slate-300">
        <div>
            <h2 class="font-display text-2xl font-semibold">Engage Post Details</h2>
            <p class="text-xs mt-0.5 text-slate-500">Review the post, see who commented or liked it, and delete those interactions if needed.</p>
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
                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $postTypes[$post->post_type] ?? ucfirst($post->post_type) }}</span>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">Post #{{ $post->id }}</span>
                    </div>
                    <p class="mt-3 text-sm text-slate-500">Posted by {{ $post->user?->display_name ?? 'Unknown User' }}</p>
                </div>
                <form method="POST" action="{{ route('admin.engage.delete', $post->id) }}" onsubmit="return confirm('Delete this engage post and all its comments and likes?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Delete Post</button>
                </form>
            </div>

            <div class="mt-5 rounded-xl bg-slate-50 p-4 text-sm leading-7 text-slate-800 whitespace-pre-wrap break-words">{{ $post->body }}</div>

            <div class="mt-5 flex flex-wrap gap-3 text-xs text-slate-500">
                <span class="rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-700">{{ $post->comments->count() }} comments</span>
                <span class="rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-700">{{ $post->reactions->where('reaction', 'like')->count() }} likes</span>
                <span>Updated {{ $post->updated_at?->diffForHumans() }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <section class="rounded-xl border border-slate-300 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Comments</h3>
                        <p class="text-xs text-slate-500">All comments made on this post.</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $post->comments->count() }}</span>
                </div>

                <div class="space-y-4">
                    @forelse($post->comments as $comment)
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
                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500">No comments on this post yet.</div>
                    @endforelse
                </div>
            </section>

            <section class="rounded-xl border border-slate-300 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Likes</h3>
                        <p class="text-xs text-slate-500">People who reacted to this post.</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $post->reactions->count() }}</span>
                </div>

                <div class="space-y-4">
                    @forelse($post->reactions as $reaction)
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
                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500">No likes or reactions on this post yet.</div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</main>
</body>
</html>
