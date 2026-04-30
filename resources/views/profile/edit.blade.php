@extends('layouts.app')

@section('content')
@php
    $profile = $profile ?? null;
    $experiences = collect($experiences ?? []);
    $previousEducation = collect($profile?->previous_education ?? []);

    $educationDefaults = [
        'school' => ['institution' => '', 'degree' => '', 'branch' => '', 'from' => '', 'to' => ''],
        'ug' => ['institution' => '', 'degree' => '', 'branch' => '', 'from' => '', 'to' => ''],
        'pg' => ['institution' => '', 'degree' => '', 'branch' => '', 'from' => '', 'to' => ''],
        'other' => ['institution' => '', 'degree' => '', 'branch' => '', 'from' => '', 'to' => ''],
    ];

    foreach ($previousEducation as $row) {
        $section = $row['section'] ?? 'other';
        if (! array_key_exists($section, $educationDefaults)) {
            $section = 'other';
        }

        if (blank($educationDefaults[$section]['institution']) && blank($educationDefaults[$section]['degree']) && blank($educationDefaults[$section]['branch']) && blank($educationDefaults[$section]['from']) && blank($educationDefaults[$section]['to'])) {
            $educationDefaults[$section] = [
                'institution' => $row['institution'] ?? '',
                'degree' => $row['degree'] ?? '',
                'branch' => $row['branch'] ?? '',
                'from' => $row['from'] ?? '',
                'to' => $row['to'] ?? '',
            ];
        }
    }

    $summaryComplete = [
        'basic' => filled($profile?->first_name) && filled($profile?->last_name) && filled($profile?->gender) && filled($profile?->contact_email),
        'education' => filled($profile?->current_status),
        'work' => $experiences->isNotEmpty(),
        'social' => filled($profile?->linkedin) && filled($profile?->instagram) && filled($profile?->facebook) && filled($profile?->twitter),
    ];

    $displayName = trim((string) ($profile?->full_name ?? ''));
    if ($displayName === '') {
        $displayName = trim((string) (($profile?->first_name ?? '') . ' ' . ($profile?->last_name ?? '')));
    }
    if ($displayName === '') {
        $displayName = $profile?->user?->name ?? 'Alumni';
    }
@endphp

<style>
    .edit-shell {
        background: linear-gradient(180deg, #f5f2eb 0%, #f0f0ee 100%);
    }

    .edit-card {
        background: rgba(255,255,255,0.95);
        border: 1px solid #e5e7eb;
        border-radius: 1.25rem;
        box-shadow: 0 12px 30px rgba(26, 45, 74, 0.08);
    }

    .edit-button {
        border-radius: 999px;
        padding: 0.7rem 1rem;
        font-weight: 800;
        font-size: 0.92rem;
    }

    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        z-index: 70;
    }

    .modal-overlay.is-open {
        display: flex;
    }

    .modal-panel {
        width: min(100%, 920px);
        max-height: 90vh;
        overflow: auto;
        background: #fff;
        border-radius: 1.5rem;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.3);
    }

    .input {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 0.75rem;
        padding: 0.75rem 0.9rem;
        background: #fff;
    }

    .label {
        display: block;
        font-size: 0.84rem;
        font-weight: 700;
        color: #4b5563;
        margin-bottom: 0.35rem;
    }
</style>

<div class="edit-shell min-h-screen py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="edit-card p-6 md:p-8">
            <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between mb-6">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#2a9d8f]">Edit Profile</p>
                    <h1 class="mt-2 text-3xl font-bold text-[#1a2d4a]">{{ $displayName }}</h1>
                    <p class="mt-2 text-sm text-slate-500">Open a section, fill the details, and save just that part.</p>
                </div>

                <a href="{{ route('profile') }}" class="inline-flex items-center rounded-full border border-slate-300 px-4 py-2 text-sm font-bold text-slate-700">
                    Back to Profile
                </a>
            </div>

            @if(session('success'))
                <div class="mb-5 rounded-2xl border border-[#b2ece5] bg-[#eefaf8] px-5 py-3 text-sm font-semibold text-[#1a2d4a]">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(!$profile)
                <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-amber-800">
                    You haven't created a profile yet. Please create it first.
                    <div class="mt-3">
                        <a href="{{ route('profile.create') }}" class="inline-flex items-center rounded-full bg-[#1a2d4a] px-4 py-2 text-sm font-bold text-white">Create Profile</a>
                    </div>
                </div>
            @else
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    @php
                        $cards = [
                            ['key' => 'basic', 'title' => 'Basic Profile Information', 'route' => 'profile.update.basic', 'button' => $summaryComplete['basic'] ? 'Edit Details' : 'Complete Details', 'color' => '#1a2d4a'],
                            ['key' => 'education', 'title' => 'Schooling, UG, PG, Other', 'route' => 'profile.update.education', 'button' => $summaryComplete['education'] ? 'Edit Details' : 'Complete Details', 'color' => '#2a9d8f'],
                            ['key' => 'work', 'title' => 'Work Experience', 'route' => 'profile.update.work', 'button' => $summaryComplete['work'] ? 'Edit Details' : 'Complete Details', 'color' => '#c9a84c'],
                            ['key' => 'social', 'title' => 'Social Links', 'route' => 'profile.update.social', 'button' => $summaryComplete['social'] ? 'Edit Details' : 'Complete Details', 'color' => '#475569'],
                        ];
                    @endphp

                    @foreach($cards as $card)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ $card['key'] }}</p>
                            <h2 class="mt-2 text-lg font-bold" style="color: {{ $card['color'] }};">{{ $card['title'] }}</h2>
                            <p class="mt-2 text-sm text-slate-500">{{ $summaryComplete[$card['key']] ? 'Completed' : 'Needs details' }}</p>
                            <button type="button" class="edit-button mt-4 inline-flex items-center text-white" style="background: {{ $card['color'] }};" data-open-modal="{{ $card['key'] }}-modal">
                                {{ $card['button'] }}
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<div id="basic-modal" class="modal-overlay">
    <div class="modal-panel">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#2a9d8f]">Section Form</p>
                <h2 class="text-2xl font-bold text-[#1a2d4a]">Basic Profile Information</h2>
            </div>
            <button type="button" class="text-2xl leading-none text-slate-500" data-close-modal>&times;</button>
        </div>

        <form method="POST" action="{{ route('profile.update.basic') }}" enctype="multipart/form-data" class="px-6 py-6">
            @csrf
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="label">First Name</label>
                    <input class="input" name="first_name" value="{{ old('first_name', $profile->first_name ?? '') }}" required>
                </div>
                <div>
                    <label class="label">Last Name</label>
                    <input class="input" name="last_name" value="{{ old('last_name', $profile->last_name ?? '') }}" required>
                </div>
                <div>
                    <label class="label">Gender</label>
                    <select class="input" name="gender" required>
                        <option value="">Select gender</option>
                        <option value="male" @selected(old('gender', $profile->gender ?? '') === 'male')>Male</option>
                        <option value="female" @selected(old('gender', $profile->gender ?? '') === 'female')>Female</option>
                        <option value="other" @selected(old('gender', $profile->gender ?? '') === 'other')>Other</option>
                        <option value="prefer_not_to_say" @selected(old('gender', $profile->gender ?? '') === 'prefer_not_to_say')>Prefer not to say</option>
                    </select>
                </div>
                <div>
                    <label class="label">Contact Email</label>
                    <input type="email" class="input" name="contact_email" value="{{ old('contact_email', $profile->contact_email ?? auth()->user()->email) }}" required>
                </div>
                <div>
                    <label class="label">Full Name</label>
                    <input class="input" name="full_name" value="{{ old('full_name', $profile->full_name ?? '') }}" required>
                </div>
                <div>
                    <label class="label">Father's Name</label>
                    <input class="input" name="father_name" value="{{ old('father_name', $profile->father_name ?? '') }}" required>
                </div>
                <div>
                    <label class="label">Mobile Number</label>
                    <input class="input" name="mobile" value="{{ old('mobile', $profile->mobile ?? '') }}" required>
                </div>
                <div>
                    <label class="label">City</label>
                    <input class="input" name="city" value="{{ old('city', $profile->city ?? '') }}" required>
                </div>
                <div>
                    <label class="label">Country</label>
                    <input class="input" name="country" value="{{ old('country', $profile->country ?? '') }}" required>
                </div>
                <div>
                    <label class="label">Degree</label>
                    <input class="input" name="degree" value="{{ old('degree', $profile->degree ?? '') }}" required>
                </div>
                <div>
                    <label class="label">Branch</label>
                    <input class="input" name="branch" value="{{ old('branch', $profile->branch ?? '') }}" required>
                </div>
                <div>
                    <label class="label">Passing Year</label>
                    <input class="input" name="passing_year" value="{{ old('passing_year', $profile->passing_year ?? '') }}" required>
                </div>
                <div>
                    <label class="label">Pursuing Educational Level</label>
                    <select class="input" name="pursuing_educational_level">
                        <option value="">Select level</option>
                        <option value="school" @selected(old('pursuing_educational_level', $profile->pursuing_educational_level ?? '') === 'school')>School</option>
                        <option value="ug" @selected(old('pursuing_educational_level', $profile->pursuing_educational_level ?? '') === 'ug')>UG</option>
                        <option value="pg" @selected(old('pursuing_educational_level', $profile->pursuing_educational_level ?? '') === 'pg')>PG</option>
                        <option value="other" @selected(old('pursuing_educational_level', $profile->pursuing_educational_level ?? '') === 'other')>Other</option>
                    </select>
                </div>
                <div>
                    <label class="label">Highest Completed Educational Level</label>
                    <select class="input" name="highest_completed_educational_level">
                        <option value="">Select level</option>
                        <option value="school" @selected(old('highest_completed_educational_level', $profile->highest_completed_educational_level ?? '') === 'school')>School</option>
                        <option value="ug" @selected(old('highest_completed_educational_level', $profile->highest_completed_educational_level ?? '') === 'ug')>UG</option>
                        <option value="pg" @selected(old('highest_completed_educational_level', $profile->highest_completed_educational_level ?? '') === 'pg')>PG</option>
                        <option value="other" @selected(old('highest_completed_educational_level', $profile->highest_completed_educational_level ?? '') === 'other')>Other</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="label">Profile Photo</label>
                    <input type="file" class="input" name="profile_photo">
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" class="rounded-full border border-slate-300 px-4 py-2 text-sm font-bold text-slate-700" data-close-modal>Cancel</button>
                <button type="submit" class="rounded-full bg-[#1a2d4a] px-4 py-2 text-sm font-bold text-white">Save Basic Details</button>
            </div>
        </form>
    </div>
</div>

<div id="education-modal" class="modal-overlay">
    <div class="modal-panel">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#2a9d8f]">Section Form</p>
                <h2 class="text-2xl font-bold text-[#1a2d4a]">Schooling, UG, PG, Other</h2>
            </div>
            <button type="button" class="text-2xl leading-none text-slate-500" data-close-modal>&times;</button>
        </div>

        <form method="POST" action="{{ route('profile.update.education') }}" class="px-6 py-6 space-y-6">
            @csrf
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <h3 class="font-bold text-[#1a2d4a] mb-4">Current Education</h3>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="label">Current Status</label>
                        <select class="input" name="current_status" required>
                            <option value="studying" @selected(old('current_status', $profile->current_status ?? '') === 'studying')>Studying</option>
                            <option value="working" @selected(old('current_status', $profile->current_status ?? '') === 'working')>Working</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Institution / College</label>
                        <input class="input" name="study_institution" value="{{ old('study_institution', $profile->study_institution ?? '') }}">
                    </div>
                    <div>
                        <label class="label">Current Degree</label>
                        <input class="input" name="study_degree" value="{{ old('study_degree', $profile->study_degree ?? '') }}">
                    </div>
                    <div>
                        <label class="label">Specialization / Branch</label>
                        <input class="input" name="study_branch" value="{{ old('study_branch', $profile->study_branch ?? '') }}">
                    </div>
                    <div>
                        <label class="label">From Date</label>
                        <input class="input" type="date" name="study_from" value="{{ old('study_from', $profile->study_from ?? '') }}">
                    </div>
                    <div>
                        <label class="label">To Date</label>
                        <input class="input" type="text" name="study_to" value="{{ old('study_to', $profile->study_to ?? '') }}" placeholder="Present or date">
                    </div>
                </div>
            </div>

            @foreach(['school' => 'Schooling Details', 'ug' => 'UG Details', 'pg' => 'PG Details', 'other' => 'Other Details'] as $section => $title)
                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                    <h3 class="font-bold text-[#1a2d4a] mb-4">{{ $title }}</h3>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="label">Institution / College</label>
                            <input class="input" name="{{ $section }}_institution" value="{{ old($section . '_institution', $educationDefaults[$section]['institution']) }}">
                        </div>
                        <div>
                            <label class="label">Degree</label>
                            <input class="input" name="{{ $section }}_degree" value="{{ old($section . '_degree', $educationDefaults[$section]['degree']) }}">
                        </div>
                        <div>
                            <label class="label">Specialization / Branch</label>
                            <input class="input" name="{{ $section }}_branch" value="{{ old($section . '_branch', $educationDefaults[$section]['branch']) }}">
                        </div>
                        <div>
                            <label class="label">From</label>
                            <input class="input" type="date" name="{{ $section }}_from" value="{{ old($section . '_from', $educationDefaults[$section]['from']) }}">
                        </div>
                        <div>
                            <label class="label">To</label>
                            <input class="input" type="date" name="{{ $section }}_to" value="{{ old($section . '_to', $educationDefaults[$section]['to']) }}">
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="flex justify-end gap-3">
                <button type="button" class="rounded-full border border-slate-300 px-4 py-2 text-sm font-bold text-slate-700" data-close-modal>Cancel</button>
                <button type="submit" class="rounded-full bg-[#2a9d8f] px-4 py-2 text-sm font-bold text-white">Save Education Details</button>
            </div>
        </form>
    </div>
</div>

<div id="work-modal" class="modal-overlay">
    <div class="modal-panel">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#2a9d8f]">Section Form</p>
                <h2 class="text-2xl font-bold text-[#1a2d4a]">Work Experience</h2>
            </div>
            <button type="button" class="text-2xl leading-none text-slate-500" data-close-modal>&times;</button>
        </div>

        <form method="POST" action="{{ route('profile.update.work') }}" class="px-6 py-6">
            @csrf
            <div id="experienceContainer" class="space-y-4">
                @forelse($experiences as $experience)
                    <div class="experience-item rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div><label class="label">Organization</label><input class="input" name="organization[]" value="{{ $experience->organization }}"></div>
                            <div><label class="label">Role</label><input class="input" name="role[]" value="{{ $experience->role }}"></div>
                            <div><label class="label">Industry</label><input class="input" name="industry[]" value="{{ $experience->industry }}"></div>
                            <div><label class="label">Location</label><input class="input" name="location_exp[]" value="{{ $experience->location }}"></div>
                            <div><label class="label">From</label><input class="input" type="date" name="from[]" value="{{ $experience->from }}"></div>
                            <div><label class="label">To</label><input class="input" type="text" name="to[]" value="{{ $experience->to }}"></div>
                        </div>
                    </div>
                @empty
                    <div class="experience-item rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div><label class="label">Organization</label><input class="input" name="organization[]"></div>
                            <div><label class="label">Role</label><input class="input" name="role[]"></div>
                            <div><label class="label">Industry</label><input class="input" name="industry[]"></div>
                            <div><label class="label">Location</label><input class="input" name="location_exp[]"></div>
                            <div><label class="label">From</label><input class="input" type="date" name="from[]"></div>
                            <div><label class="label">To</label><input class="input" type="text" name="to[]"></div>
                        </div>
                    </div>
                @endforelse
            </div>

            <button type="button" class="mt-4 rounded-full border border-slate-300 px-4 py-2 text-sm font-bold text-slate-700" data-add-experience>
                + Add Experience
            </button>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" class="rounded-full border border-slate-300 px-4 py-2 text-sm font-bold text-slate-700" data-close-modal>Cancel</button>
                <button type="submit" class="rounded-full bg-[#c9a84c] px-4 py-2 text-sm font-bold text-white">Save Work Details</button>
            </div>
        </form>
    </div>
</div>

<div id="social-modal" class="modal-overlay">
    <div class="modal-panel">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#2a9d8f]">Section Form</p>
                <h2 class="text-2xl font-bold text-[#1a2d4a]">Social Links</h2>
            </div>
            <button type="button" class="text-2xl leading-none text-slate-500" data-close-modal>&times;</button>
        </div>

        <form method="POST" action="{{ route('profile.update.social') }}" class="px-6 py-6">
            @csrf
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="label">LinkedIn URL</label>
                    <input class="input" type="url" name="linkedin" value="{{ old('linkedin', $profile->linkedin ?? '') }}" required>
                </div>
                <div>
                    <label class="label">Instagram URL</label>
                    <input class="input" type="url" name="instagram" value="{{ old('instagram', $profile->instagram ?? '') }}" required>
                </div>
                <div>
                    <label class="label">Facebook URL</label>
                    <input class="input" type="url" name="facebook" value="{{ old('facebook', $profile->facebook ?? '') }}" required>
                </div>
                <div>
                    <label class="label">X / Twitter URL</label>
                    <input class="input" type="url" name="twitter" value="{{ old('twitter', $profile->twitter ?? '') }}" required>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" class="rounded-full border border-slate-300 px-4 py-2 text-sm font-bold text-slate-700" data-close-modal>Cancel</button>
                <button type="submit" class="rounded-full bg-[#475569] px-4 py-2 text-sm font-bold text-white">Save Social Links</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        const modalMap = {
            'basic-modal': document.getElementById('basic-modal'),
            'education-modal': document.getElementById('education-modal'),
            'work-modal': document.getElementById('work-modal'),
            'social-modal': document.getElementById('social-modal'),
        };

        document.querySelectorAll('[data-open-modal]').forEach(function (button) {
            button.addEventListener('click', function () {
                const modal = modalMap[this.getAttribute('data-open-modal')];
                modal?.classList.add('is-open');
            });
        });

        document.querySelectorAll('[data-close-modal]').forEach(function (button) {
            button.addEventListener('click', function () {
                Object.values(modalMap).forEach(function (modal) {
                    modal?.classList.remove('is-open');
                });
            });
        });

        Object.values(modalMap).forEach(function (modal) {
            modal?.addEventListener('click', function (event) {
                if (event.target === modal) {
                    modal.classList.remove('is-open');
                }
            });
        });

        const workModal = modalMap['work-modal'];
        const experienceContainer = workModal?.querySelector('#experienceContainer');
        const addExperienceButton = workModal?.querySelector('[data-add-experience]');

        if (experienceContainer && addExperienceButton) {
            addExperienceButton.addEventListener('click', function () {
                const wrapper = document.createElement('div');
                wrapper.className = 'experience-item rounded-2xl border border-slate-200 bg-slate-50 p-4';
                wrapper.innerHTML = `
                    <div class="grid gap-4 md:grid-cols-2">
                        <div><label class="label">Organization</label><input class="input" name="organization[]"></div>
                        <div><label class="label">Role</label><input class="input" name="role[]"></div>
                        <div><label class="label">Industry</label><input class="input" name="industry[]"></div>
                        <div><label class="label">Location</label><input class="input" name="location_exp[]"></div>
                        <div><label class="label">From</label><input class="input" type="date" name="from[]"></div>
                        <div><label class="label">To</label><input class="input" type="text" name="to[]"></div>
                    </div>
                `;
                experienceContainer.appendChild(wrapper);
            });
        }
    })();
</script>
@endsection
