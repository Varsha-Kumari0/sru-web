<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage Events - SRU Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        .nav-active { background: rgba(59,130,246,.14); color: #1d4ed8 !important; }
    </style>
</head>
<body class="min-h-screen flex bg-slate-50 text-slate-900 [background-image:radial-gradient(ellipse_at_10%_20%,rgba(59,130,246,.08)_0%,transparent_60%),radial-gradient(ellipse_at_90%_80%,rgba(148,163,184,.12)_0%,transparent_60%)]">
@include('admin.partials.sidebar', ['activeSection' => 'events'])

<main class="ml-64 flex-1 flex flex-col min-h-screen">
    <header class="sticky top-0 z-40 flex items-center justify-between px-6 pt-[1.9rem] pb-[1.7em] xl:px-9 bg-white border-b border-slate-300">
        <div>
            <h2 class="font-display text-2xl font-semibold">Manage Events</h2>
            <p class="text-xs mt-0.5 text-slate-500">Use update and delete actions from the events list.</p>
        </div>
        <a href="{{ route('admin.events.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Create New</a>
    </header>

    <div class="p-9">
        @if(session('success'))
            <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif

        @php
            $eventTypeLabels = [
                'campus-events' => 'Campus Events',
                'hackathons'    => 'Hackathons',
                'reunions'      => 'Reunions',
                'webinars'      => 'Webinars',
            ];
        @endphp

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
            @forelse($events as $event)
                <div class="rounded-xl border border-slate-300 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-lg font-semibold text-slate-900">{{ $event->title }}</h3>
                            <p class="mt-2 line-clamp-2 text-sm text-slate-600">{{ $event->excerpt }}</p>
                        </div>
                        <span class="flex-shrink-0 whitespace-nowrap rounded-md bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-700">
                            {{ $eventTypeLabels[$event->event_type] ?? $event->event_type }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-2 text-xs text-slate-500 sm:grid-cols-2">
                        <div>Starts: {{ $event->start_at?->format('d M Y, h:i A') ?? '-' }}</div>
                        <div>{{ $event->end_at ? 'Ends: ' . $event->end_at->format('d M Y, h:i A') : 'No end date' }}</div>
                        @if($event->location)
                            <div class="sm:col-span-2">Location: {{ $event->location }}</div>
                        @endif
                    </div>

                    <div class="mt-5 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.engage.feed.review', ['event', $event->id]) }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Review</a>
                        <a href="{{ route('admin.events.edit', $event->id) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Update</a>
                        <form method="POST" action="{{ route('admin.events.delete', $event->id) }}" onsubmit="return confirm('Delete this event?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500 xl:col-span-2">No events available to manage.</div>
            @endforelse
        </div>
    </div>
</main>
</body>
</html>
