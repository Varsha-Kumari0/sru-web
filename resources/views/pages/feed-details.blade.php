@extends('layouts.app')

@section('title', 'Feed Details')

@section('content')
@php
    $typeLabel = ucfirst($feedType);
    $typeBadgeClasses = match ($feedType) {
        'news' => 'bg-blue-50 text-blue-700 border-blue-200',
        'event' => 'bg-amber-50 text-amber-700 border-amber-200',
        'testimonial' => 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200',
        default => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    };
@endphp

<div class="max-w-5xl mx-auto space-y-5">
    <div class="flex items-center justify-between gap-3">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#2a9d8f]">Feed Details</p>
            <h1 class="text-2xl font-bold text-[#1a2d4a]">{{ $sourceData['title'] }}</h1>
            <div class="mt-2">
                <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-bold uppercase tracking-wide {{ $typeBadgeClasses }}">
                    {{ $typeLabel }}
                </span>
            </div>
            <p class="mt-1 text-sm text-slate-500">
                {{ $sourceData['kind'] }} by {{ $sourceData['owner'] }}
                @if(!empty($sourceData['time']))
                    • {{ $sourceData['time'] }}
                @endif
            </p>
        </div>
        <a href="{{ route('dashboard') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Back to Feed
        </a>
    </div>

    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-4 flex flex-wrap items-center gap-2 text-xs text-slate-500">
            <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-700">{{ strtoupper($feedType) }} #{{ $feedId }}</span>
            <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-700">{{ $likesCount }} likes</span>
            <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-700">{{ $comments->count() }} comments</span>
            <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-700">{{ $sharesCount }} shares</span>
            @if($viewerLiked)
                <span class="rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-700">You liked this</span>
            @endif
        </div>

        <div class="whitespace-pre-wrap break-words text-sm leading-7 text-slate-700">{{ $sourceData['body'] }}</div>

        <div class="mt-6 flex flex-wrap gap-2">
            <form method="POST" action="{{ route('dashboard.feed.like', [$feedType, $feedId]) }}">
                @csrf
                <button type="submit" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    {{ $viewerLiked ? 'Unlike' : 'Like' }}
                </button>
            </form>

            <form method="POST" action="{{ route('dashboard.feed.share', [$feedType, $feedId]) }}">
                @csrf
                <button type="submit" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Share
                </button>
            </form>
        </div>
    </article>

    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" id="comments">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-lg font-semibold text-slate-900">Comments</h2>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $comments->count() }}</span>
        </div>

        <form method="POST" action="{{ route('dashboard.feed.comments.store', [$feedType, $feedId]) }}" class="mt-4 flex flex-col gap-2 sm:flex-row">
            @csrf
            <input name="body" maxlength="500" required class="min-w-0 flex-1 rounded-xl border-gray-200 text-sm focus:border-[#2a9d8f] focus:ring-[#2a9d8f]" placeholder="Add a thoughtful comment">
            <button type="submit" class="rounded-xl px-4 py-2 text-sm font-bold text-white bg-[#1a2d4a] hover:bg-[#0d1428]">Post</button>
        </form>

        <div class="mt-5 space-y-3">
            @forelse($comments as $comment)
                <article class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <p class="text-sm font-semibold text-slate-900">{{ $comment->user?->profile?->full_name ?: ($comment->user?->name ?? 'Alumni') }}</p>
                        <p class="text-xs text-slate-500">{{ $comment->created_at?->diffForHumans() ?? '-' }}</p>
                    </div>
                    <p class="mt-2 whitespace-pre-wrap break-words text-sm leading-6 text-slate-700">{{ $comment->body }}</p>
                </article>
            @empty
                <p class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-500">No comments yet. Be the first to comment.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
