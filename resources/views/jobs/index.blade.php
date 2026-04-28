@extends('layouts.app')

@section('title', 'Jobs and Internships')

@section('content')

<style>
    .sru-hero-gradient {
        background: linear-gradient(135deg, #1a2d4a 0%, #1e4a52 50%, #2a9d8f 100%);
    }
    .sru-section-label {
        display: inline-block;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: #1a2d4a;
        border-bottom: 3px solid #c9a84c;
        padding-bottom: 4px;
    }
    .job-card {
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .job-card:hover {
        box-shadow: 0 6px 24px rgba(26, 45, 74, 0.10);
        transform: translateY(-2px);
    }
    .fade-up {
        animation: sruFadeUp 0.4s ease both;
    }
    @keyframes sruFadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="min-h-screen" style="background:#f0f0ee;">
    <div class="sru-hero-gradient relative overflow-hidden" style="height:170px;">
        <div class="absolute -top-10 -left-10 w-60 h-60 rounded-full pointer-events-none" style="background:rgba(255,255,255,0.03);"></div>
        <div class="absolute -bottom-16 right-16 w-80 h-80 rounded-full pointer-events-none" style="background:rgba(255,255,255,0.03);"></div>
        <div class="absolute bottom-2 left-6 font-black select-none tracking-tighter leading-none pointer-events-none"
             style="color:rgba(255,255,255,0.055); font-size:5rem;">SRU</div>

        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-end justify-between gap-4 pb-6">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest mb-1" style="color:#2a9d8f;">SR University</p>
                <h1 class="text-3xl font-bold text-white tracking-tight">Jobs and Internships</h1>
            </div>
            <a href="{{ route('jobs.create') }}"
               class="hidden sm:inline-flex rounded-xl bg-white px-5 py-2.5 cursor-pointer text-sm font-bold hover:opacity-90"
               style="color:#1a2d4a;">
                Post Opportunity
            </a>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-[300px_minmax(0,1fr)] gap-6">
            <aside class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm h-fit fade-up">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <h2 class="sru-section-label">Filters</h2>
                    <a href="{{ route('jobs.index') }}" class="text-xs font-semibold hover:underline" style="color:#2a9d8f;">Clear</a>
                </div>

                <form method="GET" action="{{ route('jobs.index') }}" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide mb-1" style="color:#64748b;">Type</label>
                        <select name="type" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm bg-white">
                            <option value="">All opportunities</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" @selected($selectedType === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide mb-1" style="color:#64748b;">Skill Area</label>
                        <select name="area" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm bg-white">
                            <option value="">All areas</option>
                            @foreach($jobAreas as $key => $label)
                                <option value="{{ $key }}" @selected($selectedArea === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide mb-1" style="color:#64748b;">Work Mode</label>
                        <select name="mode" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm bg-white">
                            <option value="">Any mode</option>
                            @foreach($workModes as $key => $label)
                                <option value="{{ $key }}" @selected($selectedMode === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide mb-1" style="color:#64748b;">Experience</label>
                        <select name="experience" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm bg-white">
                            <option value="">Any level</option>
                            @foreach($experienceLevels as $key => $label)
                                <option value="{{ $key }}" @selected($selectedExperience === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide mb-1" style="color:#64748b;">Location</label>
                        <input name="location" value="{{ $selectedLocation }}" list="jobLocations"
                               class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm"
                               placeholder="Hyderabad, Remote...">
                        <datalist id="jobLocations">
                            @foreach($locations as $location)
                                <option value="{{ $location }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide mb-1" style="color:#64748b;">Skill</label>
                        <input name="skill" value="{{ $selectedSkill }}" list="jobSkills"
                               class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm"
                               placeholder="Laravel, SEO, Flutter...">
                        <datalist id="jobSkills">
                            @foreach($skills as $skill)
                                <option value="{{ $skill }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <button class="w-full rounded-xl px-4 py-2.5 text-sm font-bold text-white" style="background:#1a2d4a;">
                        Apply Filters
                    </button>
                </form>

                <div class="mt-5 grid grid-cols-1 gap-2">
                    <a href="{{ route('jobs.create', ['type' => 'job']) }}"
                       class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-bold text-center hover:bg-gray-50"
                       style="color:#1a2d4a;">Post Job</a>
                    <a href="{{ route('jobs.create', ['type' => 'internship']) }}"
                       class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-bold text-center hover:bg-gray-50"
                       style="color:#1a2d4a;">Post Internship</a>
                </div>
            </aside>

            <section class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 fade-up">
                    <p class="text-sm" style="color:#64748b;">
                        Showing {{ $jobs->count() }} opportunit{{ $jobs->count() === 1 ? 'y' : 'ies' }}.
                    </p>
                    <a href="{{ route('jobs.create') }}"
                       class="sm:hidden rounded-xl px-4 py-2 text-sm font-bold text-white text-center"
                       style="background:#1a2d4a;">Post Opportunity</a>
                </div>

                @forelse($jobs as $index => $job)
                    <article class="job-card bg-white rounded-2xl border border-gray-100 p-5 shadow-sm fade-up"
                             style="animation-delay: {{ 0.06 + $index * 0.04 }}s;">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <span class="rounded-full px-3 py-1 text-xs font-bold text-white"
                                          style="background:{{ $job->type === 'internship' ? '#2a9d8f' : '#1a2d4a' }};">
                                        {{ $types[$job->type] ?? ucfirst($job->type) }}
                                    </span>
                                    <span class="rounded-full px-3 py-1 text-xs font-bold"
                                          style="background:#fff7df; color:#8d6a00;">
                                        {{ $jobAreas[$job->job_area] ?? $job->job_area }}
                                    </span>
                                    <span class="rounded-full px-3 py-1 text-xs font-bold"
                                          style="background:#f0fafa; color:#14756d;">
                                        {{ $workModes[$job->work_mode] ?? $job->work_mode }}
                                    </span>
                                </div>

                                <h3 class="text-xl font-bold leading-snug" style="color:#1a2d4a;">{{ $job->title }}</h3>
                                <p class="text-sm font-semibold mt-1" style="color:#2a9d8f;">{{ $job->company_name }}</p>
                                <p class="text-sm mt-2 line-clamp-3" style="color:#64748b;">{{ $job->description }}</p>
                            </div>

                            <div class="md:text-right shrink-0 text-sm space-y-1" style="color:#64748b;">
                                <p>{{ $job->location ?: 'Remote' }}</p>
                                <p>{{ $experienceLevels[$job->experience_level] ?? $job->experience_level }}</p>
                                @if($job->salary)
                                    <p class="font-semibold" style="color:#1a2d4a;">{{ $job->salary }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach(($job->skills ?? []) as $skill)
                                <a href="{{ route('jobs.index', ['skill' => $skill]) }}"
                                   class="rounded-full border border-gray-200 px-3 py-1 text-xs font-semibold hover:bg-[#f0fafa]"
                                   style="color:#334155;">{{ $skill }}</a>
                            @endforeach
                        </div>

                        <div class="mt-5 pt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
                             style="border-top:1px solid #f1f5f9;">
                            <div class="text-xs" style="color:#94a3b8;">
                                Posted by {{ $job->user?->profile?->full_name ?? $job->user?->name ?? 'Alumni' }}
                                @if($job->application_deadline)
                                    <span class="mx-1">|</span>
                                    Apply by {{ $job->application_deadline->format('d M Y') }}
                                @endif
                            </div>

                            <div class="flex flex-wrap gap-2">
                                @auth
                                    @if(auth()->id() === $job->user_id)
                                        <a href="{{ route('jobs.edit', $job) }}"
                                           class="rounded-xl border border-gray-200 px-4 py-2 text-xs font-bold hover:bg-gray-50"
                                           style="color:#1a2d4a;">Edit</a>
                                    @endif
                                @endauth
                                @if($job->company_website)
                                    <a href="{{ $job->company_website }}" target="_blank" rel="noopener noreferrer"
                                       class="rounded-xl border border-gray-200 px-4 py-2 text-xs font-bold hover:bg-gray-50"
                                       style="color:#1a2d4a;">Company</a>
                                @endif
                                @if($job->attachment)
                                    <a href="{{ asset('storage/' . $job->attachment) }}" target="_blank" rel="noopener noreferrer"
                                       class="rounded-xl border border-gray-200 px-4 py-2 text-xs font-bold hover:bg-gray-50"
                                       style="color:#1a2d4a;">Attachment</a>
                                @endif
                                <a href="mailto:{{ $job->contact_email }}?subject={{ rawurlencode('Regarding ' . $job->title) }}"
                                   class="rounded-xl px-4 py-2 text-xs font-bold text-white"
                                   style="background:#1a2d4a;">Apply / Contact</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center shadow-sm fade-up">
                        <p class="font-semibold mb-1" style="color:#1a2d4a;">No opportunities found</p>
                        <p class="text-sm" style="color:#94a3b8;">Try changing the filters, or post the first opportunity for alumni.</p>
                    </div>
                @endforelse
            </section>
        </div>
    </div>
</div>

@endsection
