<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage Jobs - SRU Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        .nav-active { background: rgba(59,130,246,.14); color: #1d4ed8 !important; }
    </style>
</head>
<body class="min-h-screen flex bg-slate-50 text-slate-900 [background-image:radial-gradient(ellipse_at_10%_20%,rgba(59,130,246,.08)_0%,transparent_60%),radial-gradient(ellipse_at_90%_80%,rgba(148,163,184,.12)_0%,transparent_60%)]">
@include('admin.partials.sidebar', ['activeSection' => 'jobs'])

<main class="ml-64 flex-1 flex flex-col min-h-screen">
    <header class="sticky top-0 z-40 flex items-center justify-between px-6 pt-[1.9rem] pb-[1.7em] xl:px-9 bg-white border-b border-slate-300">
        <div>
            <h2 class="font-display text-2xl font-semibold">Manage Jobs</h2>
            <p class="text-xs mt-0.5 text-slate-500">Use update and delete actions from the jobs list.</p>
        </div>
        <a href="{{ route('admin.jobs.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Create New</a>
    </header>

    <div class="p-9">
        @if(session('success'))
            <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif

        @php
            $typeLabels = [
                'job' => 'Job',
                'internship' => 'Internship',
            ];
        @endphp

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
            @forelse($jobs as $job)
                <div class="rounded-xl border border-slate-300 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-lg font-semibold text-slate-900">{{ $job->title }}</h3>
                            <p class="mt-1 truncate text-sm text-slate-600">{{ $job->company_name }}</p>
                        </div>
                        <span class="flex-shrink-0 whitespace-nowrap rounded-md bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-700">
                            {{ $typeLabels[$job->type] ?? $job->type }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-2 text-xs text-slate-500 sm:grid-cols-2">
                        <div>Area: {{ $job->job_area ?? '-' }}</div>
                        <div>Level: {{ $job->experience_level ?? '-' }}</div>
                        <div>Mode: {{ $job->work_mode ?? '-' }}</div>
                        @if($job->location)
                            <div>Location: {{ $job->location }}</div>
                        @endif
                        @if($job->application_deadline)
                            <div>Deadline: {{ $job->application_deadline->format('d M Y') }}</div>
                        @endif
                    </div>

                    <div class="mt-5 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.engage.manage') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Review</a>
                        <a href="{{ route('admin.jobs.edit', $job->id) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Update</a>
                        <form method="POST" action="{{ route('admin.jobs.delete', $job->id) }}" onsubmit="return confirm('Delete this job opportunity?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500 xl:col-span-2">No jobs available to manage.</div>
            @endforelse
        </div>
    </div>
</main>
</body>
</html>
