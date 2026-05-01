@extends('layouts.app')

@section('title', $sectionTitle . ' - Details')

@section('content')
<div class="-m-6 min-h-screen" style="background:#f0f0ee;">
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="inline-block text-xs font-bold uppercase tracking-widest border-b-4 border-[#c9a84c] pb-1 text-[#1a2d4a]">Engage Details</p>
                <h1 class="mt-3 text-3xl font-bold text-[#1a2d4a]">{{ $sectionTitle }}</h1>
                <p class="mt-2 text-sm text-slate-600">All posts in this section by users and admins. You can like and comment here.</p>
            </div>
            <a href="{{ route('engage') }}" class="rounded-xl border border-[#1a2d4a] px-4 py-2 text-sm font-bold text-[#1a2d4a] hover:bg-[#1a2d4a] hover:text-white">Back to Engage</a>
        </div>

        <div class="mt-8 space-y-5">
            @forelse($posts as $post)
                @php
                    $key = 'post:' . $post->id;
                    $isLiked = $viewerReactionKeys->has($key);
                    $comments = $commentGroups->get($key, collect())->take(5);
                    $authorName = $post->user?->profile?->full_name ?: ($post->user?->name ?? 'Alumni');
                @endphp
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-sm font-bold text-[#1a2d4a]">{{ $authorName }}</p>
                            <p class="text-xs text-slate-500">{{ $post->created_at?->diffForHumans() ?? '-' }}</p>
                        </div>
                        <a href="{{ route('dashboard.feed.details', ['post', $post->id]) }}" class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-bold text-slate-600 hover:border-[#2a9d8f]">Open full post</a>
                    </div>

                    <p class="mt-4 whitespace-pre-wrap break-words text-sm leading-7 text-slate-700">{{ $post->body }}</p>

                    <div class="mt-4 grid grid-cols-3 gap-2 border-y border-gray-100 py-3">
                        @auth
                            <form method="POST" action="{{ route('dashboard.feed.like', ['post', $post->id]) }}">
                                @csrf
                                <button type="submit" class="w-full rounded-lg border px-3 py-2 text-sm font-bold {{ $isLiked ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-200 text-slate-700 hover:border-[#2a9d8f]' }}">
                                    {{ $isLiked ? 'Liked' : 'Like' }} {{ $reactionCounts->get($key, 0) }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-center text-sm font-bold text-slate-700">Login to like</a>
                        @endauth

                        <div class="w-full rounded-lg border border-slate-200 px-3 py-2 text-center text-sm font-bold text-slate-700">
                            Comments {{ $commentCounts->get($key, 0) }}
                        </div>

                        <div class="w-full rounded-lg border border-slate-200 px-3 py-2 text-center text-sm font-bold text-slate-700">
                            Shares {{ $shareCounts->get($key, 0) }}
                        </div>
                    </div>

                    <div class="mt-4 space-y-3">
                        @foreach($comments as $comment)
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                <p class="text-sm font-semibold text-slate-900">{{ $comment->user?->profile?->full_name ?: ($comment->user?->name ?? 'Alumni') }}</p>
                                <p class="mt-1 whitespace-pre-wrap break-words text-sm text-slate-700">{{ $comment->body }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $comment->created_at?->diffForHumans() ?? '-' }}</p>
                            </div>
                        @endforeach

                        @if($comments->isEmpty())
                            <p class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-3 text-sm text-slate-500">No comments yet.</p>
                        @endif
                    </div>

                    @auth
                        <form method="POST" action="{{ route('dashboard.feed.comments.store', ['post', $post->id]) }}" class="mt-4 flex flex-col gap-2 sm:flex-row">
                            @csrf
                            <input name="body" maxlength="500" required class="min-w-0 flex-1 rounded-xl border-gray-200 text-sm focus:border-[#2a9d8f] focus:ring-[#2a9d8f]" placeholder="Add a thoughtful comment">
                            <button type="submit" class="rounded-xl px-4 py-2 text-sm font-bold text-white bg-[#1a2d4a] hover:bg-[#0d1428]">Post</button>
                        </form>
                    @else
                        <p class="mt-4 text-sm text-slate-500">Please <a href="{{ route('login') }}" class="font-semibold text-[#1a2d4a] underline">login</a> to comment.</p>
                    @endauth
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500">No posts found in this section yet.</div>
            @endforelse
        </div>
    </section>
</div>
@endsection
