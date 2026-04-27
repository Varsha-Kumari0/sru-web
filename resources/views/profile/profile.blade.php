@extends('layouts.app')

@section('title', 'Alumni Profile')

@section('content')

{{-- SRU Brand Colors:
     Navy/Blue header: #1a2d5a  (from sruniv.com navbar)
     Magenta accent:   #c0006a  (from sruniv.com banner/buttons)
     Light bg:         #f4f6f9
     Card white:       #ffffff
     Border:           #e2e8f0
     Text primary:     #1e293b
     Text muted:       #64748b
--}}

<style>
    .sru-hero-banner {
        background: linear-gradient(135deg, #1a2d5a 0%, #1e3a7a 50%, #c0006a 100%);
    }
    .sru-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(26, 45, 90, 0.12);
    }
    .sru-card:hover {
        box-shadow: 0 4px 20px rgba(26, 45, 90, 0.10);
    }
    .sru-skill-tag:hover {
        background-color: #1a2d5a;
        color: #ffffff;
        border-color: #1a2d5a;
    }
    .sru-achievement-card:hover {
        border-color: #c0006a;
        box-shadow: 0 2px 12px rgba(192, 0, 106, 0.10);
    }
    .profile-avatar {
        box-shadow: 0 4px 20px rgba(26,45,90,0.18);
    }
    .sru-section-title {
        border-left: 4px solid #c0006a;
        padding-left: 12px;
    }
    .sru-tag-alumni {
        background: linear-gradient(90deg, #1a2d5a, #c0006a);
        color: #fff;
    }
    .fade-in {
        animation: fadeUp 0.45s ease both;
    }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fade-in-1 { animation-delay: 0.05s; }
    .fade-in-2 { animation-delay: 0.12s; }
    .fade-in-3 { animation-delay: 0.20s; }
    .fade-in-4 { animation-delay: 0.28s; }
    .fade-in-5 { animation-delay: 0.36s; }
</style>

<div class="min-h-screen bg-[#f4f6f9]">

    @if($profile)

        {{-- ── HERO BANNER ──────────────────────────────────────────── --}}
        <div class="sru-hero-banner h-44 md:h-56 relative overflow-hidden">
            {{-- Decorative circles --}}
            <div class="absolute -top-10 -left-10 w-64 h-64 rounded-full bg-white opacity-[0.04]"></div>
            <div class="absolute -bottom-16 right-20 w-80 h-80 rounded-full bg-white opacity-[0.04]"></div>
            <div class="absolute top-6 right-1/3 w-32 h-32 rounded-full bg-white opacity-[0.03]"></div>

            {{-- SRU wordmark watermark --}}
            <div class="absolute bottom-4 left-6 text-white/10 font-black text-[6rem] leading-none select-none tracking-tighter">SRU</div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ── PROFILE HEADER CARD ───────────────────────────────── --}}
            <div class="relative -mt-20 mb-8 fade-in fade-in-1">
                <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-lg overflow-hidden">
                    <div class="p-6 lg:p-8">
                        <div class="flex flex-col md:flex-row md:items-start gap-6">

                            {{-- Avatar --}}
                            <div class="relative shrink-0">
                                <img
                                    src="{{ $profile->profile_photo ? asset('storage/'.$profile->profile_photo) : 'data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22150%22 height=%22150%22 viewBox=%220 0 150 150%22><rect width=%22150%22 height=%22150%22 fill=%22%231a2d5a%22/><circle cx=%2275%22 cy=%2250%22 r=%2225%22 fill=%22%23ffffff44%22/><path d=%22M30 120c5-20 25-35 45-35s40 15 45 35%22 fill=%22%23ffffff44%22/></svg>' }}"
                                    class="profile-avatar h-28 w-28 md:h-32 md:w-32 rounded-2xl border-4 border-white object-cover -mt-14 md:-mt-16"
                                    alt="{{ $profile->full_name }}"
                                >
                                {{-- Online dot --}}
                                <span class="absolute bottom-2 right-2 w-4 h-4 bg-green-400 rounded-full border-2 border-white"></span>
                            </div>

                            {{-- Name / Meta --}}
                            <div class="flex-1 min-w-0 pt-0 md:pt-1">
                                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#c0006a] mb-1">SRU Alumni</p>
                                <h1 class="text-2xl md:text-3xl font-bold text-[#1a2d5a] tracking-tight">
                                    {{ $profile->full_name }}
                                </h1>
                                <p class="text-[#475569] mt-1 text-sm md:text-base">
                                    {{ $profile->company ?? 'Professional' }}
                                    @if($profile->degree)
                                        <span class="mx-1.5 text-[#cbd5e1]">•</span>
                                        {{ $profile->degree }} – {{ $profile->branch }}
                                    @endif
                                </p>
                                <p class="text-sm text-[#94a3b8] mt-1">
                                    {{ $profile->city ? $profile->city . ', ' : '' }}{{ $profile->country ?? 'Location not set' }}
                                    @if($profile->passing_year)
                                        <span class="mx-1.5 text-[#cbd5e1]">•</span>
                                        Class of {{ $profile->passing_year }}
                                    @endif
                                </p>

                                {{-- Tags --}}
                                <div class="flex flex-wrap gap-2 mt-3">
                                    @if($profile->passing_year)
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#eef2ff] text-[#1a2d5a] border border-[#c7d2fe] uppercase tracking-wide">
                                            Class of {{ $profile->passing_year }}
                                        </span>
                                    @endif
                                    @if($profile->branch)
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#eef2ff] text-[#1a2d5a] border border-[#c7d2fe] uppercase tracking-wide">
                                            {{ $profile->branch }}
                                        </span>
                                    @endif
                                    <span class="sru-tag-alumni px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide">
                                        Alumni Network
                                    </span>
                                </div>
                            </div>

                            {{-- Profile Status + Actions --}}
                            <div class="shrink-0 space-y-4 md:min-w-[220px]">
                                <div class="rounded-xl border border-[#e2e8f0] bg-[#f8fafc] p-4">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-[#94a3b8] mb-3">Profile Status</p>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-[#c0006a] px-4 py-1.5 text-xs font-bold text-white">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            Completed
                                        </span>
                                        <span class="inline-flex items-center rounded-full bg-[#1a2d5a] px-4 py-1.5 text-xs font-bold text-white">
                                            {{ $profile->current_status ?? 'SRU Alumni Member' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="{{ route('messages.index') }}"
                                       class="flex items-center justify-center gap-1.5 rounded-xl bg-[#c0006a] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#a0005a] transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                        Message
                                    </a>
                                    <a href="{{ route('profile.edit') }}"
                                       class="flex items-center justify-center rounded-xl border-2 border-[#1a2d5a] px-4 py-2.5 text-sm font-semibold text-[#1a2d5a] hover:bg-[#1a2d5a] hover:text-white transition-colors">
                                        More
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Stats bar inside the card --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-[#e2e8f0] border-t border-[#e2e8f0]">
                        @foreach([
                            ['label' => 'Connections',   'value' => $connectionCount ?? 0,      'color' => 'text-[#1a2d5a]'],
                            ['label' => 'Profile Views', 'value' => $profileViewsCount ?? 0,    'color' => 'text-[#1a2d5a]'],
                            ['label' => 'Skills',        'value' => $skillsCount ?? 0,           'color' => 'text-[#1a2d5a]'],
                            ['label' => 'Achievements',  'value' => $achievementsCount ?? 0,     'color' => 'text-[#c0006a]'],
                        ] as $stat)
                        <div class="sru-stat-card text-center py-5 px-4 transition-all duration-200 cursor-default">
                            <div class="text-2xl font-bold {{ $stat['color'] }}">{{ $stat['value'] }}</div>
                            <div class="text-xs text-[#94a3b8] mt-1 font-medium">{{ $stat['label'] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ── MAIN CONTENT GRID ────────────────────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-12">

                {{-- LEFT COLUMN ──────────────────────────────────────── --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- ABOUT --}}
                    <div class="sru-card bg-white rounded-2xl border border-[#e2e8f0] p-6 shadow-sm transition-all duration-200 fade-in fade-in-2">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="sru-section-title text-lg font-bold text-[#1a2d5a]">About</h2>
                                <p class="text-xs text-[#94a3b8] mt-1 pl-4">A concise view of your alumni background.</p>
                            </div>
                            <a href="{{ route('profile.edit-bio') }}"
                               class="text-xs font-bold text-[#c0006a] hover:text-[#a0005a] border border-[#c0006a] rounded-lg px-3 py-1.5 hover:bg-[#fff0f7] transition-colors">
                                Edit
                            </a>
                        </div>
                        <p class="text-[#475569] leading-relaxed text-sm">
                            @if($profile->description)
                                {{ $profile->description }}
                            @else
                                <span class="text-[#94a3b8] italic">No bio added yet. Add a brief summary to help other alumni learn more about you.</span>
                            @endif
                        </p>
                    </div>

                    {{-- EXPERIENCE --}}
                    <div class="sru-card bg-white rounded-2xl border border-[#e2e8f0] p-6 shadow-sm transition-all duration-200 fade-in fade-in-3">
                        <div class="mb-5">
                            <h2 class="sru-section-title text-lg font-bold text-[#1a2d5a]">Experience</h2>
                            <p class="text-xs text-[#94a3b8] mt-1 pl-4">A chronological record of your positions and achievements.</p>
                        </div>
                        @forelse($experiences as $exp)
                            <div class="mb-5 last:mb-0 pl-4 border-l-2 border-[#e2e8f0] hover:border-[#c0006a] transition-colors">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-1">
                                    <div>
                                        <p class="font-bold text-[#1a2d5a]">{{ $exp->role }}</p>
                                        <p class="text-sm text-[#c0006a] font-medium mt-0.5">{{ $exp->organization }}</p>
                                        @if($exp->location)
                                            <p class="text-xs text-[#94a3b8] mt-0.5">{{ $exp->location }}</p>
                                        @endif
                                    </div>
                                    <span class="text-xs text-[#94a3b8] whitespace-nowrap mt-1 sm:mt-0 bg-[#f8fafc] border border-[#e2e8f0] px-2 py-1 rounded-lg">
                                        {{ $exp->from }} – {{ $exp->to ?? 'Present' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 mx-auto text-[#cbd5e1] mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-[#94a3b8] text-sm">No experience added yet.</p>
                                <a href="{{ route('profile.edit') }}" class="mt-2 inline-block text-sm font-semibold text-[#c0006a] hover:underline">Add experience →</a>
                            </div>
                        @endforelse
                    </div>

                    {{-- SKILLS --}}
                    <div class="sru-card bg-white rounded-2xl border border-[#e2e8f0] p-6 shadow-sm transition-all duration-200 fade-in fade-in-4">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="sru-section-title text-lg font-bold text-[#1a2d5a]">Skills</h2>
                                <p class="text-xs text-[#94a3b8] mt-1 pl-4">Showcase what you do best.</p>
                            </div>
                            <a href="{{ route('skills.index') }}"
                               class="text-xs font-bold text-[#1a2d5a] hover:text-white border border-[#1a2d5a] rounded-lg px-3 py-1.5 hover:bg-[#1a2d5a] transition-colors">
                                Manage
                            </a>
                        </div>
                        @if($skills && count($skills) > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($skills as $skill)
                                    <span class="sru-skill-tag inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-[#e2e8f0] bg-[#f8fafc] text-sm font-medium text-[#334155] transition-all duration-150 cursor-default">
                                        {{ $skill->name }}
                                        @if($skill->endorsements > 0)
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-[#c0006a] text-white text-[10px] font-bold">
                                                {{ $skill->endorsements }}
                                            </span>
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6">
                                <p class="text-[#94a3b8] text-sm">No skills added yet.</p>
                                <a href="{{ route('profile.edit') }}" class="mt-1 inline-block text-sm font-semibold text-[#c0006a] hover:underline">Add your skills →</a>
                            </div>
                        @endif
                    </div>

                    {{-- ACHIEVEMENTS --}}
                    @if($achievements && count($achievements) > 0)
                    <div class="sru-card bg-white rounded-2xl border border-[#e2e8f0] p-6 shadow-sm transition-all duration-200 fade-in fade-in-5">
                        <h2 class="sru-section-title text-lg font-bold text-[#1a2d5a] mb-5">Achievements</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($achievements as $achievement)
                                <div class="sru-achievement-card flex gap-4 p-4 rounded-xl border border-[#e2e8f0] bg-[#f8fafc] transition-all duration-200">
                                    <div class="text-3xl shrink-0">{{ $achievement->badge_icon ?? '⭐' }}</div>
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-[#1a2d5a] text-sm">{{ $achievement->title }}</h4>
                                        @if($achievement->description)
                                            <p class="text-xs text-[#64748b] mt-0.5 leading-snug">{{ $achievement->description }}</p>
                                        @endif
                                        @if($achievement->earned_at)
                                            <p class="text-[10px] text-[#c0006a] font-semibold mt-1.5 uppercase tracking-wide">
                                                {{ $achievement->earned_at->format('M Y') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

                {{-- RIGHT COLUMN ─────────────────────────────────────── --}}
                <div class="space-y-5">

                    {{-- CONTACT --}}
                    <div class="sru-card bg-white rounded-2xl border border-[#e2e8f0] p-5 shadow-sm transition-all duration-200 fade-in fade-in-2">
                        <h2 class="sru-section-title text-base font-bold text-[#1a2d5a] mb-4">Contact</h2>
                        <div class="space-y-2">
                            @if($profile->mobile)
                                <a href="tel:{{ $profile->mobile }}"
                                   class="flex items-center gap-3 p-3 rounded-xl bg-[#f8fafc] border border-[#e2e8f0] hover:border-[#1a2d5a] hover:bg-[#eef2ff] transition-all group">
                                    <div class="w-8 h-8 rounded-lg bg-[#eef2ff] flex items-center justify-center text-[#1a2d5a] group-hover:bg-[#1a2d5a] group-hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </div>
                                    <span class="text-sm text-[#334155] font-medium">{{ $profile->mobile }}</span>
                                </a>
                            @endif
                            @if($profile->user && $profile->user->email)
                                <a href="mailto:{{ $profile->user->email }}"
                                   class="flex items-center gap-3 p-3 rounded-xl bg-[#f8fafc] border border-[#e2e8f0] hover:border-[#c0006a] hover:bg-[#fff0f7] transition-all group">
                                    <div class="w-8 h-8 rounded-lg bg-[#fff0f7] flex items-center justify-center text-[#c0006a] group-hover:bg-[#c0006a] group-hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="text-sm text-[#334155] font-medium truncate">{{ $profile->user->email }}</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- SOCIAL --}}
                    <div class="sru-card bg-white rounded-2xl border border-[#e2e8f0] p-5 shadow-sm transition-all duration-200 fade-in fade-in-3">
                        <h2 class="sru-section-title text-base font-bold text-[#1a2d5a] mb-4">Social</h2>
                        <div class="flex gap-2 flex-wrap">
                            @if($profile->linkedin)
                                <a href="{{ $profile->linkedin }}" target="_blank"
                                   class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#eef2ff] text-[#1a2d5a] text-sm font-bold hover:bg-[#1a2d5a] hover:text-white transition-colors" title="LinkedIn">
                                    in
                                </a>
                            @endif
                            @if($profile->facebook)
                                <a href="{{ $profile->facebook }}" target="_blank"
                                   class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#eef2ff] text-[#1a2d5a] text-sm font-bold hover:bg-[#1877f2] hover:text-white transition-colors" title="Facebook">
                                    f
                                </a>
                            @endif
                            @if($profile->twitter)
                                <a href="{{ $profile->twitter }}" target="_blank"
                                   class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#eef2ff] text-[#1a2d5a] text-sm font-bold hover:bg-[#1a2d5a] hover:text-white transition-colors" title="X / Twitter">
                                    𝕏
                                </a>
                            @endif
                            @if($profile->instagram)
                                <a href="{{ $profile->instagram }}" target="_blank"
                                   class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#fff0f7] text-[#c0006a] text-lg hover:bg-[#c0006a] hover:text-white transition-colors" title="Instagram">
                                    📷
                                </a>
                            @endif
                            @if(!$profile->linkedin && !$profile->facebook && !$profile->twitter && !$profile->instagram)
                                <p class="text-xs text-[#94a3b8] italic">No social links added.</p>
                            @endif
                        </div>
                    </div>

                    {{-- EDUCATION --}}
                    <div class="sru-card bg-white rounded-2xl border border-[#e2e8f0] p-5 shadow-sm transition-all duration-200 fade-in fade-in-4">
                        <h2 class="sru-section-title text-base font-bold text-[#1a2d5a] mb-4">Education</h2>
                        <div class="rounded-xl border border-[#e2e8f0] bg-[#f8fafc] p-4 hover:border-[#c0006a] transition-colors">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-lg bg-[#1a2d5a] flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-[#1a2d5a] text-sm">{{ $profile->degree ?? '—' }}</p>
                                    <p class="text-xs text-[#c0006a] font-semibold mt-0.5">{{ $profile->branch ?? '—' }}</p>
                                    <p class="text-xs text-[#94a3b8] mt-1">Graduation Year: {{ $profile->passing_year ?? '—' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- QUICK EDIT CTA --}}
                    <div class="rounded-2xl p-5 text-center" style="background: linear-gradient(135deg, #1a2d5a, #c0006a);">
                        <p class="text-white text-sm font-semibold mb-1">Keep your profile updated</p>
                        <p class="text-white/70 text-xs mb-3">Help employers and alumni find you.</p>
                        <a href="{{ route('profile.edit') }}"
                           class="inline-block px-5 py-2 rounded-xl bg-white text-[#1a2d5a] text-sm font-bold hover:bg-[#f0f4ff] transition-colors">
                            Edit Profile
                        </a>
                    </div>

                </div>
            </div>
        </div>

    @else
        {{-- ── EMPTY STATE ──────────────────────────────────────────── --}}
        <div class="min-h-screen flex items-center justify-center px-4 bg-[#f4f6f9]">
            <div class="text-center max-w-sm">
                <div class="w-24 h-24 rounded-full bg-[#eef2ff] flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-[#1a2d5a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-[#1a2d5a] mb-2">Profile Not Found</h2>
                <p class="text-[#64748b] text-sm mb-6">Let's create your profile and join the alumni community.</p>
                <a href="/profile/create"
                   class="inline-block px-8 py-3 rounded-xl font-bold text-white hover:opacity-90 transition-opacity"
                   style="background: linear-gradient(135deg, #1a2d5a, #c0006a);">
                    Create Your Profile
                </a>
            </div>
        </div>
    @endif

</div>

@endsection