<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Update Event - SRU Admin</title>
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
            <h2 class="font-display text-2xl font-semibold">Update Event</h2>
            <p class="text-xs mt-0.5 text-slate-500">Edit the selected event and save your changes.</p>
        </div>
        <a href="{{ route('admin.events.manage') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Back to Manage</a>
    </header>

    <div class="p-9">
        <div class="max-w-4xl rounded-xl border border-slate-300 bg-white p-6">
            @if($errors->any())
                <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="mb-1 font-semibold">Please fix the following:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.events.update', $event->id) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="mb-1.5 block text-sm font-semibold text-slate-700">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                </div>

                <div>
                    <label for="excerpt" class="mb-1.5 block text-sm font-semibold text-slate-700">Excerpt</label>
                    <textarea id="excerpt" name="excerpt" rows="3"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">{{ old('excerpt', $event->excerpt) }}</textarea>
                </div>

                <div>
                    <label for="description" class="mb-1.5 block text-sm font-semibold text-slate-700">Description</label>
                    <textarea id="description" name="description" rows="6"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">{{ old('description', $event->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="event_type" class="mb-1.5 block text-sm font-semibold text-slate-700">Event Type</label>
                        <select id="event_type" name="event_type" required
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none bg-white">
                            @php $currentType = old('event_type', $event->event_type); @endphp
                            <option value="campus-events" {{ $currentType === 'campus-events' ? 'selected' : '' }}>Campus Events</option>
                            <option value="hackathons"    {{ $currentType === 'hackathons'    ? 'selected' : '' }}>Hackathons</option>
                            <option value="reunions"      {{ $currentType === 'reunions'      ? 'selected' : '' }}>Reunions</option>
                            <option value="webinars"      {{ $currentType === 'webinars'      ? 'selected' : '' }}>Webinars</option>
                        </select>
                    </div>
                    <div>
                        <label for="location" class="mb-1.5 block text-sm font-semibold text-slate-700">Location <span class="text-slate-400 font-normal">(optional)</span></label>
                        <input type="text" id="location" name="location" value="{{ old('location', $event->location) }}"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_at" class="mb-1.5 block text-sm font-semibold text-slate-700">Start Date &amp; Time</label>
                        <input type="datetime-local" id="start_at" name="start_at"
                               value="{{ old('start_at', $event->start_at?->format('Y-m-d\TH:i')) }}" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label for="end_at" class="mb-1.5 block text-sm font-semibold text-slate-700">End Date &amp; Time <span class="text-slate-400 font-normal">(optional)</span></label>
                        <input type="datetime-local" id="end_at" name="end_at"
                               value="{{ old('end_at', $event->end_at?->format('Y-m-d\TH:i')) }}"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="registration_link" class="mb-1.5 block text-sm font-semibold text-slate-700">Registration Link <span class="text-slate-400 font-normal">(optional)</span></label>
                        <input type="url" id="registration_link" name="registration_link"
                               value="{{ old('registration_link', $event->registration_link) }}"
                               placeholder="https://..."
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label for="image" class="mb-1.5 block text-sm font-semibold text-slate-700">Image <span class="text-slate-400 font-normal">(optional – leave blank to keep current)</span></label>
                        @if($event->image)
                            <p class="mb-1.5 text-xs text-slate-500">Current: <span class="font-medium text-slate-700">{{ $event->image }}</span></p>
                        @endif
                        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('admin.events.manage') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</main>
</body>
</html>
