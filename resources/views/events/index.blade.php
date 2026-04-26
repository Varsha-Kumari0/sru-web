@extends('layouts.app')

@section('title', 'Events')

@section('content')
<div class="max-w-6xl mx-auto mt-8">

    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-8">
        <div>
            <h2 class="text-3xl font-semibold">Events</h2>
            <p class="text-gray-500">View upcoming, past, and all SRU alumni events.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            @foreach($allowedFilters as $filterKey => $label)
                <a href="{{ route('events.index', ['filter' => $filterKey]) }}"
                    class="rounded px-4 py-2 text-sm font-medium transition {{ $currentFilter === $filterKey ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:bg-blue-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">
            @if($events->isEmpty())
                <div class="bg-white rounded shadow p-8 text-center text-gray-600">
                    No events found for this category. Try another filter or check back later.
                </div>
            @else
                @foreach($events as $event)
                    <div class="bg-white rounded shadow overflow-hidden transition hover:shadow-xl">
                        <div class="md:flex">
                            @if($event->image)
                                <img src="/images/{{ $event->image }}" alt="{{ $event->title }}" class="h-48 w-full object-cover md:w-56">
                            @endif

                            <div class="p-6 flex-1">
                                <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                        {{ $eventTypes[$event->event_type] ?? ucwords(str_replace('-', ' ', $event->event_type)) }}
                                    </span>
                                    <span class="text-gray-400 text-sm">{{ $event->start_at->format('jS M, Y') }}</span>
                                </div>
                                <h3 class="text-2xl font-semibold mb-3">{{ $event->title }}</h3>
                                <p class="text-gray-600 mb-4 max-h-20 overflow-hidden">{{ $event->excerpt }}</p>
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="text-gray-500 text-sm space-y-1">
                                        <p>{{ $event->location }}</p>
                                        <p>{{ $event->start_at->format('g:i a') }}@if($event->end_at) - {{ $event->end_at->format('g:i a') }}@endif</p>
                                    </div>
                                    <a href="{{ route('events.show', $event->id) }}" class="inline-flex items-center justify-center rounded bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                        View event
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <aside class="space-y-6">
            <div class="bg-white rounded shadow p-5">
                <h3 class="font-semibold mb-3">Event details</h3>
                <p class="text-gray-600 text-sm">Click a category tab to filter events and open any card for full information and registration links.</p>
            </div>

            <div class="bg-white rounded shadow p-5">
                <h3 class="font-semibold mb-3">Event categories</h3>
                <div class="space-y-3 text-sm">
                    @foreach($eventTypes as $typeKey => $label)
                        <div class="flex items-center gap-2 rounded px-3 py-2 text-gray-600">
                            <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                            <span>{{ $label }}</span>
                            @if(isset($typeCounts[$typeKey]))
                                <span class="text-gray-400">({{ $typeCounts[$typeKey] }})</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded shadow p-5">
                <h3 class="font-semibold mb-3">Status legend</h3>
                <p class="text-gray-600 text-sm">Upcoming events are shown in chronological order. Past events remain visible in the selected category.</p>
            </div>
        </aside>

    </div>
</div>
@endsection
