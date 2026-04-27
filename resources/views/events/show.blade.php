@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="max-w-5xl mx-auto mt-8 bg-white rounded shadow overflow-hidden border border-[#e2e8f0]">
    @if($event->image)
        <img src="/images/{{ $event->image }}" alt="{{ $event->title }}" class="w-full h-72 object-cover">
    @endif

    <div class="p-8">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-[#c0006a] font-semibold">{{ $event->event_type === 'all' ? 'Event' : ucwords(str_replace('-', ' ', $event->event_type)) }}</p>
                <h1 class="text-3xl font-semibold mt-2 text-[#1a2d5a]">{{ $event->title }}</h1>
            </div>
            <div class="text-right text-sm text-gray-600">
                <p>{{ $event->start_at->format('jS M, Y') }}</p>
                <p>{{ $event->start_at->format('g:i a') }}@if($event->end_at) - {{ $event->end_at->format('g:i a') }}@endif</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[2fr_1fr]">
            <div class="space-y-6 text-gray-700">
                <p class="text-lg leading-relaxed">{{ $event->description }}</p>
            </div>

            <aside class="space-y-4 rounded border border-[#e2e8f0] bg-[#f4f6f9] p-5 text-sm text-[#1a2d5a]">
                <div>
                    <h3 class="font-semibold mb-2 text-[#1a2d5a]">Location</h3>
                    <p class="text-gray-700">{{ $event->location }}</p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2 text-[#1a2d5a]">When</h3>
                    <p class="text-gray-700">{{ $event->start_at->format('jS M, Y') }}</p>
                    <p class="text-gray-700">{{ $event->start_at->format('g:i a') }}@if($event->end_at) - {{ $event->end_at->format('g:i a') }}@endif</p>
                </div>

                @if($event->registration_link)
                    <a href="{{ $event->registration_link }}" target="_blank" class="inline-flex w-full items-center justify-center rounded bg-[#1a2d5a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#141d42]">
                        Register now
                    </a>
                @endif
            </aside>
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('events.index') }}" class="text-[#1a2d5a] hover:underline">← Back to events</a>
            <span class="inline-flex items-center rounded-full bg-[#f4f6f9] px-3 py-1 text-sm text-[#c0006a] border border-[#e2e8f0]">{{ ucwords(str_replace('-', ' ', $event->event_type)) }}</span>
        </div>
    </div>
</div>
@endsection
