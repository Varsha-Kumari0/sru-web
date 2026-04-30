<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Job - SRU Admin</title>
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
            <h2 class="font-display text-2xl font-semibold">Create Job Opportunity</h2>
            <p class="text-xs mt-0.5 text-slate-500">Add a new job or internship opportunity.</p>
        </div>
    </header>

    <div class="p-9">
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_340px] xl:items-start">
            <div class="rounded-xl border border-slate-300 bg-white p-6">
                @if(session('success'))
                    <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <p class="font-semibold mb-1">Please fix the following:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.jobs.store') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label for="type" class="mb-1.5 block text-sm font-semibold text-slate-700">Type</label>
                        <select id="type" name="type" required
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none bg-white">
                            <option value="">Select type</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" @selected(old('type') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="title" class="mb-1.5 block text-sm font-semibold text-slate-700">Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="company_name" class="mb-1.5 block text-sm font-semibold text-slate-700">Company Name</label>
                        <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="company_website" class="mb-1.5 block text-sm font-semibold text-slate-700">Website</label>
                            <input type="url" id="company_website" name="company_website" value="{{ old('company_website') }}"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label for="contact_email" class="mb-1.5 block text-sm font-semibold text-slate-700">Contact Email</label>
                            <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email') }}" required
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="job_area" class="mb-1.5 block text-sm font-semibold text-slate-700">Job Area</label>
                            <select id="job_area" name="job_area" required
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none bg-white">
                                <option value="">Select area</option>
                                @foreach($jobAreas as $key => $label)
                                    <option value="{{ $key }}" @selected(old('job_area') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="experience_level" class="mb-1.5 block text-sm font-semibold text-slate-700">Experience Level</label>
                            <select id="experience_level" name="experience_level" required
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none bg-white">
                                <option value="">Select level</option>
                                @foreach($experienceLevels as $key => $label)
                                    <option value="{{ $key }}" @selected(old('experience_level') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="work_mode" class="mb-1.5 block text-sm font-semibold text-slate-700">Work Mode</label>
                            <select id="work_mode" name="work_mode" required
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none bg-white">
                                <option value="">Select mode</option>
                                @foreach($workModes as $key => $label)
                                    <option value="{{ $key }}" @selected(old('work_mode') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="location" class="mb-1.5 block text-sm font-semibold text-slate-700">Location</label>
                            <input type="text" id="location" name="location" value="{{ old('location') }}"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="skills" class="mb-1.5 block text-sm font-semibold text-slate-700">Skills (comma-separated)</label>
                            <input type="text" id="skills" name="skills" value="{{ old('skills') }}" required
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label for="salary" class="mb-1.5 block text-sm font-semibold text-slate-700">Salary/Stipend</label>
                            <input type="text" id="salary" name="salary" value="{{ old('salary') }}"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="application_deadline" class="mb-1.5 block text-sm font-semibold text-slate-700">Application Deadline</label>
                            <input type="date" id="application_deadline" name="application_deadline" value="{{ old('application_deadline') }}"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label for="attachment" class="mb-1.5 block text-sm font-semibold text-slate-700">Attachment</label>
                            <input type="file" id="attachment" name="attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label for="description" class="mb-1.5 block text-sm font-semibold text-slate-700">Description</label>
                        <textarea id="description" name="description" rows="6" required
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
                        <a href="{{ route('admin.jobs.manage') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Create Job</button>
                    </div>
                </form>
            </div>

            <aside class="rounded-xl border border-slate-300 bg-white p-5 shadow-sm h-fit">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">Recent Jobs</h3>
                <div class="space-y-3">
                    @forelse($recentJobs as $job)
                        <div class="text-xs">
                            <p class="font-semibold text-slate-900 truncate">{{ $job->title }}</p>
                            <p class="text-slate-500 truncate">{{ $job->company_name }}</p>
                            <p class="text-slate-400 mt-1">
                                @php
                                    $badge = $job->created_at->format('Y-m-d') === $job->updated_at->format('Y-m-d') ? 'Created' : 'Updated';
                                @endphp
                                <span class="inline-block rounded px-1.5 py-0.5 bg-slate-100 text-slate-600">{{ $badge }}</span>
                            </p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500">No recent jobs</p>
                    @endforelse
                </div>
            </aside>
        </div>
    </div>
</main>
</body>
</html>
