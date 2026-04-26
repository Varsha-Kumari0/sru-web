@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="max-w-5xl mx-auto mt-8 bg-white rounded shadow overflow-hidden">
    @if($event->image)
        <img src="/images/{{ $event->image }}" alt="{{ $event->title }}" class="w-full h-72 object-cover">
    @endif

    <div class="p-8">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-blue-600 font-semibold">{{ $event->event_type === 'all' ? 'Event' : ucwords(str_replace('-', ' ', $event->event_type)) }}</p>
                <h1 class="text-3xl font-semibold mt-2">{{ $event->title }}</h1>
            </div>
            <div class="text-right text-sm text-gray-500">
                <p>{{ $event->start_at->format('jS M, Y') }}</p>
                <p>{{ $event->start_at->format('g:i a') }}@if($event->end_at) - {{ $event->end_at->format('g:i a') }}@endif</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[2fr_1fr]">
            <div class="space-y-6 text-gray-700">
                <p class="text-lg leading-relaxed">{{ $event->description }}</p>
            </div>

            <aside class="space-y-4 rounded border border-gray-200 bg-gray-50 p-5 text-sm text-gray-700">
                <div>
                    <h3 class="font-semibold mb-2">Location</h3>
                    <p>{{ $event->location }}</p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">When</h3>
                    <p>{{ $event->start_at->format('jS M, Y') }}</p>
                    <p>{{ $event->start_at->format('g:i a') }}@if($event->end_at) - {{ $event->end_at->format('g:i a') }}@endif</p>
                </div>

                @if($event->registration_link)
                    <a href="{{ $event->registration_link }}" target="_blank" class="inline-flex w-full items-center justify-center rounded bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Register now
                    </a>
                @endif
            </aside>
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('events.index') }}" class="text-blue-600 hover:underline">← Back to events</a>
            <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-sm text-blue-700">{{ ucwords(str_replace('-', ' ', $event->event_type)) }}</span>
        </div>
    </div>
</div>
@endsection
