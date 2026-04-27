@extends('layouts.app')

@section('title', 'Events')

@section('content')
<div class="max-w-6xl mx-auto mt-8">

    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-8">
        <div>
            <h2 class="text-3xl font-semibold text-[#1a2d5a]">Events</h2>
            <p class="text-gray-600">View upcoming, past, and all SRU alumni events.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            @foreach($allowedFilters as $filterKey => $label)
                <a href="{{ route('events.index', ['filter' => $filterKey]) }}"
                    class="rounded px-4 py-2 text-sm font-medium transition {{ $currentFilter === $filterKey ? 'bg-[#1a2d5a] text-white' : 'bg-white text-[#1a2d5a] border border-[#e2e8f0] hover:bg-[#f4f6f9]' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">
            @if($events->isEmpty())
                <div class="bg-white rounded shadow p-8 text-center text-gray-600 border border-[#e2e8f0]">
                    No events found for this category. Try another filter or check back later.
                </div>
            @else
                <div class="mb-4 text-sm text-gray-600">
                    Showing {{ $events->count() }} event{{ $events->count() === 1 ? '' : 's' }}@if($currentType) for {{ $eventTypes[$currentType] }}@endif.
                </div>
                @foreach($events as $event)
                    <div class="bg-white rounded shadow overflow-hidden transition hover:shadow-xl border border-[#e2e8f0]">
                        <div class="md:flex">
                            @if($event->image)
                                <img src="/images/{{ $event->image }}" alt="{{ $event->title }}" class="h-48 w-full object-cover md:w-56">
                            @endif

                            <div class="p-6 flex-1">
                                <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                                    <span class="inline-flex items-center rounded-full bg-[#f4f6f9] px-3 py-1 text-xs font-semibold text-[#c0006a]">
                                        {{ $eventTypes[$event->event_type] ?? ucwords(str_replace('-', ' ', $event->event_type)) }}
                                    </span>
                                    <span class="text-gray-500 text-sm">{{ $event->start_at->format('jS M, Y') }}</span>
                                </div>
                                <h3 class="text-2xl font-semibold mb-3 text-[#1a2d5a]">{{ $event->title }}</h3>
                                <p class="text-gray-600 mb-4 max-h-20 overflow-hidden">{{ $event->excerpt }}</p>
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="text-gray-600 text-sm space-y-1">
                                        <p>{{ $event->location }}</p>
                                        <p>{{ $event->start_at->format('g:i a') }}@if($event->end_at) - {{ $event->end_at->format('g:i a') }}@endif</p>
                                    </div>
                                    <a href="{{ route('events.show', $event->id) }}" class="inline-flex items-center justify-center rounded bg-[#1a2d5a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#141d42]">
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
            <div class="bg-[#f4f6f9] text-[#1a2d5a] rounded-xl shadow p-5 border border-[#e2e8f0]">
                <h3 class="font-semibold mb-3 text-[#1a2d5a]">Event details</h3>
                <p class="text-gray-600 text-sm">Choose a category from the sidebar to focus on a specific event type.</p>
            </div>

            <div class="bg-white rounded-xl shadow p-5 border border-[#e2e8f0]">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-semibold text-[#1a2d5a]">Categories</h3>
                        <p class="text-sm text-gray-600 mt-1">Filter events by type.</p>
                    </div>
                </div>
                <div class="space-y-3 text-sm">
                    <a href="{{ route('events.index', ['filter' => $currentFilter]) }}"
                        class="flex items-center gap-2 rounded-xl px-4 py-3 transition {{ !$currentType ? 'bg-[#1a2d5a] text-white shadow' : 'bg-[#f4f6f9] text-[#1a2d5a] border border-[#e2e8f0] hover:bg-[#e9ecf1]' }}">
                        <span class="h-2 w-2 rounded-full {{ !$currentType ? 'bg-white' : 'bg-[#1a2d5a]' }}"></span>
                        <span class="flex-1">All categories</span>
                        <span class="text-xs text-gray-600">{{ array_sum($typeCounts) }}</span>
                    </a>
                    @foreach($eventTypes as $typeKey => $label)
                        <a href="{{ route('events.index', array_merge(['filter' => $currentFilter, 'type' => $typeKey])) }}"
                            class="flex items-center gap-2 rounded-xl px-4 py-3 transition {{ $currentType === $typeKey ? 'bg-[#1a2d5a] text-white shadow' : 'bg-[#f4f6f9] text-[#1a2d5a] border border-[#e2e8f0] hover:bg-[#e9ecf1]' }}">
                            <span class="h-2 w-2 rounded-full {{ $currentType === $typeKey ? 'bg-white' : 'bg-[#1a2d5a]' }}"></span>
                            <span class="flex-1">{{ $label }}</span>
                            @if(isset($typeCounts[$typeKey]))
                                <span class="text-xs {{ $currentType === $typeKey ? 'text-gray-200' : 'text-gray-600' }}">({{ $typeCounts[$typeKey] }})</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="bg-[#f4f6f9] text-[#1a2d5a] rounded-xl shadow p-5 border border-[#e2e8f0]">
                <h3 class="font-semibold mb-3 text-[#1a2d5a]">Status legend</h3>
                <p class="text-gray-600 text-sm">Upcoming events are shown in chronological order. Past events remain visible in the selected filter.</p>
            </div>
        </aside>

    </div>
</div>
@endsection
