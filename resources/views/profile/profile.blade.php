@extends('layouts.app')

@section('title', 'Alumni Profile')

@section('content')
    @php
        $profile = $profile ?? null;
        $experiences = collect($experiences ?? []);
        $skills = collect($skills ?? []);
        $achievements = collect($achievements ?? []);
        $previousEducation = collect($profile?->previous_education ?? []);

        $displayName = trim((string) ($profile?->full_name ?? ''));
        if ($displayName === '') {
            $displayName = trim((string) (($profile?->first_name ?? '') . ' ' . ($profile?->last_name ?? '')));
        }
        if ($displayName === '') {
            $displayName = $profile?->user?->name ?? 'Alumni';
        }

        $completionFields = [
            $profile?->first_name,
            $profile?->last_name,
            $profile?->gender,
            $profile?->full_name,
            $profile?->father_name,
            $profile?->mobile,
            $profile?->contact_email,
            $profile?->city,
            $profile?->country,
            $profile?->degree,
            $profile?->branch,
            $profile?->passing_year,
            $profile?->current_status,
            $profile?->linkedin,
            $profile?->facebook,
            $profile?->instagram,
            $profile?->twitter,
        ];

        $basicProfileComplete = collect($completionFields)->every(fn ($value) => filled($value));

        $studyComplete = collect([
            $profile?->study_institution,
            $profile?->study_degree,
            $profile?->study_branch,
            $profile?->study_from,
            $profile?->study_to,
        ])->every(fn ($value) => filled($value));

        $workComplete = $experiences->count() > 0
            && $experiences->every(function ($experience) {
                return filled($experience->organization)
                    && filled($experience->role)
                    && filled($experience->industry)
                    && filled($experience->location)
                    && filled($experience->from)
                    && filled($experience->to);
            });

        $socialComplete = collect([
            $profile?->linkedin,
            $profile?->facebook,
            $profile?->instagram,
            $profile?->twitter,
        ])->every(fn ($value) => filled($value));

        $groupedEducation = $previousEducation->groupBy(function ($row) {
            $explicitSection = strtolower(trim((string) ($row['section'] ?? '')));

            if (in_array($explicitSection, ['school', 'ug', 'pg', 'other'], true)) {
                return $explicitSection;
            }

            $text = strtolower(trim(implode(' ', array_filter([
                $row['degree'] ?? '',
                $row['branch'] ?? '',
                $row['institution'] ?? '',
            ]))));

            if (preg_match('/\b(school|ssc|10th|12th|inter|high school)\b/', $text)) {
                return 'school';
            }

            if (preg_match('/\b(phd|masters?|mba|msc|mtech|m\.tech|mcom|pg)\b/', $text)) {
                return 'pg';
            }

            if (preg_match('/\b(b\.tech|btech|b\.sc|bsc|b\.com|bcom|bca|bba|ba|ug|bachelor)\b/', $text)) {
                return 'ug';
            }

            return 'other';
        });

        $sectionMeta = [
            'school' => ['title' => 'Schooling Details', 'color' => '#1a2d4a'],
            'ug' => ['title' => 'UG Details', 'color' => '#2a9d8f'],
            'pg' => ['title' => 'PG Details', 'color' => '#c9a84c'],
            'other' => ['title' => 'Other Details', 'color' => '#475569'],
        ];

        $sectionStatus = function (bool $complete) {
            return $complete ? 'Completed' : 'Incomplete';
        };
    @endphp

    <style>
        .profile-shell {
            background:
                radial-gradient(circle at top left, rgba(201, 168, 76, 0.10), transparent 32%),
                linear-gradient(180deg, #f3f1eb 0%, #f0f0ee 100%);
        }

        .profile-hero {
            background: linear-gradient(135deg, #1a2d4a 0%, #1f4c55 48%, #2a9d8f 100%);
        }

        .profile-card {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid #e5e7eb;
            border-radius: 1.25rem;
            box-shadow: 0 10px 30px rgba(26, 45, 74, 0.08);
        }

        .profile-label {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #1a2d4a;
            border-bottom: 3px solid #c9a84c;
            padding-bottom: 4px;
        }

        .profile-pill {
            border-radius: 999px;
            padding: 0.4rem 0.8rem;
            font-size: 0.74rem;
            font-weight: 800;
            letter-spacing: 0.04em;
        }
    </style>

    <div class="profile-shell min-h-screen pb-12">
        @if($profile)
            <section class="profile-hero text-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div class="grid gap-6 lg:grid-cols-[1fr_320px] lg:items-center">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#c9a84c]">Alumni Profile</p>
                            <h1 class="mt-2 text-3xl md:text-4xl font-bold">{{ $displayName }}</h1>
                            <p class="mt-3 max-w-2xl text-sm leading-7 text-white/75">
                                A structured view of the alumni profile, grouped by basic information, education, work, social links, skills, and achievements.
                            </p>

                            <div class="mt-5 flex flex-wrap gap-3">
                                <a href="{{ route('profile.edit') }}"
                                   class="inline-flex items-center rounded-full bg-white px-4 py-2 text-sm font-bold text-[#1a2d4a] transition hover:opacity-90">
                                    Complete Profile
                                </a>
                                <a href="{{ route('profile.edit') }}"
                                   class="inline-flex items-center rounded-full border border-white/25 bg-white/10 px-4 py-2 text-sm font-bold text-white transition hover:bg-white/20">
                                    Edit Profile
                                </a>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-white/15 bg-white/10 p-5 backdrop-blur">
                            <div class="flex items-center gap-4">
                                <div class="h-16 w-16 overflow-hidden rounded-2xl bg-white/15 text-center text-2xl font-black leading-[4rem] text-white">
                                    @if($profile->profile_photo)
                                        <img src="{{ asset('storage/' . $profile->profile_photo) }}" alt="{{ $displayName }}" class="h-full w-full object-cover">
                                    @else
                                        {{ strtoupper(substr($displayName, 0, 1)) }}
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="truncate font-bold">{{ $profile->current_status ?? 'SRU Alumni Member' }}</p>
                                    <p class="truncate text-sm text-white/70">{{ $profile->degree ?? 'Degree not set' }}</p>
                                    <p class="truncate text-xs text-white/60">{{ $profile->city ?? 'Location not set' }}{{ $profile->country ? ', ' . $profile->country : '' }}</p>
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-3 text-center text-sm">
                                <div class="rounded-xl bg-white/10 p-3">
                                    <div class="text-lg font-black">{{ $basicProfileComplete ? '100%' : 'In progress' }}</div>
                                    <div class="text-[11px] uppercase tracking-widest text-white/60">Basic Profile</div>
                                </div>
                                <div class="rounded-xl bg-white/10 p-3">
                                    <div class="text-lg font-black">{{ $profile->current_status ? ucfirst($profile->current_status) : 'Unset' }}</div>
                                    <div class="text-[11px] uppercase tracking-widest text-white/60">Status</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                @if(session('success'))
                    <div class="mb-5 rounded-2xl border border-[#b2ece5] bg-[#eefaf8] px-5 py-3 text-sm font-semibold text-[#1a2d4a]">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-semibold text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                <section class="grid gap-4 md:grid-cols-4 mb-8">
                    @php
                        $summaryCards = [
                            ['label' => 'Basic Profile', 'complete' => $basicProfileComplete, 'value' => $basicProfileComplete ? 'Completed' : 'Incomplete', 'color' => '#1a2d4a'],
                            ['label' => 'Education', 'complete' => $groupedEducation->isNotEmpty() || $studyComplete, 'value' => ($groupedEducation->isNotEmpty() || $studyComplete) ? 'Completed' : 'Incomplete', 'color' => '#2a9d8f'],
                            ['label' => 'Work', 'complete' => $workComplete, 'value' => $workComplete ? 'Completed' : 'Incomplete', 'color' => '#c9a84c'],
                            ['label' => 'Social', 'complete' => $socialComplete, 'value' => $socialComplete ? 'Completed' : 'Incomplete', 'color' => '#475569'],
                        ];
                    @endphp
                    @foreach($summaryCards as $card)
                        <div class="profile-card p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ $card['label'] }}</p>
                            <p class="mt-2 text-lg font-black" style="color: {{ $card['color'] }};">{{ $card['value'] }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $card['complete'] ? 'Ready to share' : 'Needs attention' }}</p>
                            <div class="mt-3">
                                <a href="{{ route('profile.edit') }}" class="inline-flex items-center rounded-full border border-slate-300 px-3 py-1.5 text-sm font-bold text-slate-700">
                                    {{ $card['complete'] ? 'Edit' : 'Complete' }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </section>

                <section class="grid gap-6 lg:grid-cols-2">
                    <div class="profile-card p-6">
                        <div class="flex items-start justify-between gap-4 mb-5">
                            <div>
                                <h2 class="profile-label">Basic Profile Information</h2>
                                <p class="mt-2 text-sm text-slate-500">Core identity, contact, and academic baseline details.</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="profile-pill {{ $basicProfileComplete ? 'bg-[#eefaf8] text-[#1a2d4a]' : 'bg-amber-50 text-amber-700' }}">
                                    {{ $sectionStatus($basicProfileComplete) }}
                                </span>
                                <a href="{{ route('profile.edit') }}" class="inline-flex items-center rounded-full border border-slate-300 px-3 py-1.5 text-sm font-bold text-slate-700">
                                    {{ $basicProfileComplete ? 'Edit' : 'Complete' }}
                                </a>
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            @php
                                $basicDetails = [
                                    'First Name' => $profile->first_name ?? '—',
                                    'Last Name' => $profile->last_name ?? '—',
                                    'Full Name' => $profile->full_name ?? '—',
                                    'Gender' => $profile->gender ?? '—',
                                    'Father Name' => $profile->father_name ?? '—',
                                    'Mobile' => $profile->mobile ?? '—',
                                    'Contact Email' => $profile->contact_email ?? ($profile->user?->email ?? '—'),
                                    'City' => $profile->city ?? '—',
                                    'Country' => $profile->country ?? '—',
                                    'Degree' => $profile->degree ?? '—',
                                    'Branch' => $profile->branch ?? '—',
                                    'Passing Year' => $profile->passing_year ?? '—',
                                    'Pursuing Level' => $profile->pursuing_educational_level ?? '—',
                                    'Highest Completed' => $profile->highest_completed_educational_level ?? '—',
                                ];
                            @endphp

                            @foreach($basicDetails as $label => $value)
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">{{ $label }}</p>
                                    <p class="mt-1 text-sm font-semibold text-slate-800 break-words">{{ $value }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="profile-card p-6">
                        <div class="flex items-start justify-between gap-4 mb-5">
                            <div>
                                <h2 class="profile-label">Current Profile Status</h2>
                                <p class="mt-2 text-sm text-slate-500">Current study or work details and remaining actions.</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="profile-pill bg-[#eefaf8] text-[#1a2d4a]">
                                    {{ $sectionStatus($studyComplete || $workComplete) }}
                                </span>
                                <a href="{{ route('profile.edit') }}" class="inline-flex items-center rounded-full border border-slate-300 px-3 py-1.5 text-sm font-bold text-slate-700">
                                    {{ ($studyComplete || $workComplete) ? 'Edit' : 'Complete' }}
                                </a>
                            </div>
                        </div>

                        @if($profile->current_status === 'studying')
                            <div class="space-y-3">
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Institution / College</p>
                                    <p class="mt-1 text-sm font-semibold text-slate-800">{{ $profile->study_institution ?? '—' }}</p>
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                        <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Study Degree</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-800">{{ $profile->study_degree ?? '—' }}</p>
                                    </div>
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                        <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Study Branch</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-800">{{ $profile->study_branch ?? '—' }}</p>
                                    </div>
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                        <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">From</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-800">{{ $profile->study_from ?? '—' }}</p>
                                    </div>
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                        <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">To</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-800">{{ $profile->study_to ?? 'Present' }}</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-sm font-semibold text-slate-800">{{ $profile->company ?? 'Work details not added yet.' }}</p>
                                <p class="mt-1 text-xs text-slate-500">
                                    @if($experiences->isNotEmpty())
                                        {{ $experiences->count() }} work experience record{{ $experiences->count() === 1 ? '' : 's' }} available.
                                    @else
                                        Use Complete Profile to add work experience.
                                    @endif
                                </p>
                            </div>
                        @endif

                        <div class="mt-4 flex gap-3">
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center rounded-full bg-[#1a2d4a] px-4 py-2 text-sm font-bold text-white">
                                Complete Profile
                            </a>
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center rounded-full border border-slate-300 px-4 py-2 text-sm font-bold text-slate-700">
                                Edit
                            </a>
                        </div>
                    </div>
                </section>

                <section class="mt-6 grid gap-6 lg:grid-cols-2">
                    @foreach($sectionMeta as $key => $meta)
                        <div class="profile-card p-6">
                            <div class="flex items-start justify-between gap-4 mb-5">
                                <div>
                                    <h2 class="profile-label">{{ $meta['title'] }}</h2>
                                    <p class="mt-2 text-sm text-slate-500">Separate education group from the profile data.</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="profile-pill {{ ($groupedEducation->get($key, collect())->isNotEmpty()) ? 'bg-[#eefaf8] text-[#1a2d4a]' : 'bg-amber-50 text-amber-700' }}">
                                        {{ $sectionStatus($groupedEducation->get($key, collect())->isNotEmpty()) }}
                                    </span>
                                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center rounded-full border border-slate-300 px-3 py-1.5 text-sm font-bold text-slate-700">
                                        {{ $groupedEducation->get($key, collect())->isNotEmpty() ? 'Edit' : 'Complete' }}
                                    </a>
                                </div>
                            </div>

                            @if($groupedEducation->get($key, collect())->isNotEmpty())
                                <div class="space-y-3">
                                    @foreach($groupedEducation->get($key) as $row)
                                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                            <p class="text-sm font-bold text-slate-800">{{ $row['institution'] ?? '—' }}</p>
                                            <p class="mt-1 text-sm text-slate-600">{{ $row['degree'] ?? '—' }}@if(!empty($row['branch'])) · {{ $row['branch'] }}@endif</p>
                                            <p class="mt-1 text-xs text-slate-500">{{ $row['from'] ?? '—' }} - {{ $row['to'] ?? '—' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-5 text-sm text-slate-500">
                                    No {{ strtolower($meta['title']) }} added yet.
                                </div>
                            @endif
                        </div>
                    @endforeach
                </section>

                <section class="mt-6 grid gap-6 lg:grid-cols-2">
                    <div class="profile-card p-6">
                        <div class="flex items-start justify-between gap-4 mb-5">
                            <div>
                                <h2 class="profile-label">Social Links</h2>
                                <p class="mt-2 text-sm text-slate-500">Public profile links for networking and visibility.</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="profile-pill {{ $socialComplete ? 'bg-[#eefaf8] text-[#1a2d4a]' : 'bg-amber-50 text-amber-700' }}">
                                    {{ $sectionStatus($socialComplete) }}
                                </span>
                                <a href="{{ route('profile.edit') }}" class="inline-flex items-center rounded-full border border-slate-300 px-3 py-1.5 text-sm font-bold text-slate-700">
                                    {{ $socialComplete ? 'Edit' : 'Complete' }}
                                </a>
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            @php
                                $socialDetails = [
                                    'LinkedIn' => $profile->linkedin,
                                    'Facebook' => $profile->facebook,
                                    'Instagram' => $profile->instagram,
                                    'X / Twitter' => $profile->twitter,
                                ];
                            @endphp
                            @foreach($socialDetails as $label => $value)
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">{{ $label }}</p>
                                    @if($value)
                                        <a href="{{ $value }}" target="_blank" rel="noopener noreferrer" class="mt-1 block text-sm font-semibold text-[#2a9d8f] break-all">{{ $value }}</a>
                                    @else
                                        <p class="mt-1 text-sm font-semibold text-slate-800">—</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="profile-card p-6">
                        <div class="flex items-start justify-between gap-4 mb-5">
                            <div>
                                <h2 class="profile-label">Skills and Achievements</h2>
                                <p class="mt-2 text-sm text-slate-500">Additional profile sections shown on the profile page.</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center rounded-full border border-slate-300 px-4 py-2 text-sm font-bold text-slate-700">
                                Edit
                            </a>
                        </div>

                        <div class="mb-5">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400 mb-3">Skills</p>
                            @if($skills->isNotEmpty())
                                <div class="flex flex-wrap gap-2">
                                    @foreach($skills as $skill)
                                        <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm font-medium text-slate-700">{{ $skill->name }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-slate-500">No skills added yet.</p>
                            @endif
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400 mb-3">Achievements</p>
                            @if($achievements->isNotEmpty())
                                <div class="space-y-3">
                                    @foreach($achievements as $achievement)
                                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                            <p class="text-sm font-bold text-slate-800">{{ $achievement->title }}</p>
                                            @if($achievement->description)
                                                <p class="mt-1 text-sm text-slate-600">{{ $achievement->description }}</p>
                                            @endif
                                            @if($achievement->earned_at)
                                                <p class="mt-1 text-xs font-bold uppercase tracking-wide text-[#c9a84c]">{{ $achievement->earned_at->format('M Y') }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-slate-500">No achievements added yet.</p>
                            @endif
                        </div>
                    </div>
                </section>
            </main>
        @else
            <div class="min-h-screen flex items-center justify-center px-4">
                <div class="max-w-md rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-xl">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#2a9d8f]">Profile Missing</p>
                    <h2 class="mt-3 text-2xl font-bold text-[#1a2d4a]">Create your profile first</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">The alumni profile page is ready, but no profile record exists yet.</p>
                    <a href="{{ route('profile.create') }}" class="mt-6 inline-flex items-center rounded-full bg-[#1a2d4a] px-5 py-2.5 text-sm font-bold text-white">
                        Create Your Profile
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
