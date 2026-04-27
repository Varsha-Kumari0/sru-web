@extends('layouts.app')

@section('title', 'Alumni Profile')

@section('content')

{{--
    SRU Alumni App Theme (matched from your screenshots):
    Navy:        #1a2d4a   (navbar, hero dark, login button, headings)
    Teal:        #2a9d8f   (hero gradient, section underlines, event borders, skill badges)
    Gold/Amber:  #c9a84c   (headline accent, section underline bars, date text)
    Page bg:     #f0f0ee   (warm light grey - matches your home page body)
    Card bg:     #ffffff
    Border:      #e5e7eb
    Text:        #1e293b
    Muted:       #64748b
--}}

<style>
    .sru-hero-gradient {
        background: linear-gradient(135deg, #1a2d4a 0%, #1e4a52 45%, #2a9d8f 100%);
    }
    .sru-section-label {
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #1a2d4a;
        border-bottom: 3px solid #c9a84c;
        padding-bottom: 5px;
    }
    .sru-teal-left {
        border-left: 3px solid #2a9d8f;
        padding-left: 14px;
    }
    .sru-card {
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .sru-card:hover {
        box-shadow: 0 6px 24px rgba(26, 45, 74, 0.09);
        transform: translateY(-2px);
    }
    .sru-skill-pill {
        transition: background 0.15s, color 0.15s, border-color 0.15s;
        cursor: default;
    }
    .sru-skill-pill:hover {
        background: #1a2d4a !important;
        color: #fff !important;
        border-color: #1a2d4a !important;
    }
    .sru-achievement-card {
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .sru-achievement-card:hover {
        border-color: #2a9d8f;
        box-shadow: 0 2px 12px rgba(42, 157, 143, 0.13);
    }
    .sru-contact-row {
        transition: background 0.15s, border-color 0.15s;
    }
    .sru-contact-row:hover {
        background: #f0fafa !important;
        border-color: #2a9d8f !important;
    }
    .sru-social-btn {
        transition: background 0.15s, color 0.15s, border-color 0.15s;
    }
    .sru-social-btn:hover {
        background: #1a2d4a !important;
        color: #fff !important;
        border-color: #1a2d4a !important;
    }
    .sru-stat-col {
        transition: background 0.15s;
    }
    .sru-stat-col:hover {
        background: #f8fffe;
    }
    .avatar-ring {
        box-shadow: 0 0 0 4px #fff, 0 4px 20px rgba(26,45,74,0.20);
    }
    .fade-up {
        animation: sruFadeUp 0.42s ease both;
    }
    .fu1 { animation-delay: 0.04s; }
    .fu2 { animation-delay: 0.10s; }
    .fu3 { animation-delay: 0.17s; }
    .fu4 { animation-delay: 0.24s; }
    .fu5 { animation-delay: 0.31s; }
    @keyframes sruFadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="min-h-screen" style="background: #f0f0ee;">

@if($profile)

    {{-- ═══════════════════════════════
         HERO BANNER
    ═══════════════════════════════ --}}
    <div class="sru-hero-gradient relative overflow-hidden" style="height: 200px;">
        <div class="absolute -top-14 -left-14 w-72 h-72 rounded-full" style="background:rgba(255,255,255,0.03);"></div>
        <div class="absolute top-6 right-1/3 w-44 h-44 rounded-full" style="background:rgba(255,255,255,0.025);"></div>
        <div class="absolute -bottom-20 right-10 w-96 h-96 rounded-full" style="background:rgba(255,255,255,0.03);"></div>
        <div class="absolute bottom-2 left-6 font-black select-none tracking-tighter leading-none"
             style="color:rgba(255,255,255,0.055); font-size:5.5rem;">SRU</div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ═══════════════════════════════
             PROFILE HEADER CARD
        ═══════════════════════════════ --}}
        <div class="relative -mt-16 mb-8 fade-up fu1">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

                <div class="p-6 lg:p-8">
                    <div class="flex flex-col md:flex-row md:items-start gap-6">

                        {{-- Avatar --}}
                        <div class="relative shrink-0">
                            <img
                                src="{{ $profile->profile_photo
                                    ? asset('storage/'.$profile->profile_photo)
                                    : 'data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22150%22 height=%22150%22 viewBox=%220 0 150 150%22><rect width=%22150%22 height=%22150%22 fill=%22%231a2d4a%22/><circle cx=%2275%22 cy=%2250%22 r=%2226%22 fill=%22%23ffffff33%22/><path d=%22M30 125c5-22 25-37 45-37s40 15 45 37%22 fill=%22%23ffffff33%22/></svg>' }}"
                                class="avatar-ring h-28 w-28 md:h-32 md:w-32 rounded-2xl object-cover border-4 border-white"
                                alt="{{ $profile->full_name }}"
                            >
                            <span class="absolute bottom-2 right-2 w-3.5 h-3.5 rounded-full border-2 border-white"
                                  style="background: #2a9d8f;"></span>
                        </div>

                        {{-- Name + Meta --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold uppercase tracking-widest mb-1"
                               style="color: #2a9d8f;">SRU Alumni</p>

                            <h1 class="text-2xl md:text-3xl font-bold tracking-tight"
                                style="color: #1a2d4a;">
                                {{ strtoupper($profile->full_name) }}
                            </h1>

                            <p class="mt-1 text-sm" style="color: #475569;">
                                {{ $profile->company ?? 'Professional' }}
                                @if($profile->degree)
                                    <span style="color:#d1d5db;" class="mx-1.5">•</span>
                                    {{ $profile->degree }} – {{ $profile->branch }}
                                @endif
                            </p>

                            <p class="text-sm mt-1" style="color: #94a3b8;">
                                {{ $profile->city ? $profile->city . ', ' : '' }}{{ $profile->country ?? 'Location not set' }}
                                @if($profile->passing_year)
                                    <span style="color:#d1d5db;" class="mx-1.5">•</span>
                                    Class of {{ $profile->passing_year }}
                                @endif
                            </p>

                            {{-- Tags --}}
                            <div class="flex flex-wrap gap-2 mt-3">
                                @if($profile->passing_year)
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide border"
                                          style="background:#f8f9fa; color:#1a2d4a; border-color:#d1d5db;">
                                        Class of {{ $profile->passing_year }}
                                    </span>
                                @endif
                                @if($profile->branch)
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide border"
                                          style="background:#f8f9fa; color:#1a2d4a; border-color:#d1d5db;">
                                        {{ $profile->branch }}
                                    </span>
                                @endif
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide text-white"
                                      style="background: linear-gradient(90deg, #1a2d4a, #2a9d8f);">
                                    Alumni Network
                                </span>
                            </div>
                        </div>

                        {{-- Profile Status + Action Buttons --}}
                        <div class="shrink-0 space-y-3" style="min-width: 210px;">
                            <div class="rounded-xl border p-4"
                                 style="background:#f8fffe; border-color:#b2ece5;">
                                <p class="text-xs font-bold uppercase tracking-widest mb-3"
                                   style="color:#94a3b8;">Profile Status</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-4 py-1.5 text-xs font-bold text-white"
                                          style="background: #2a9d8f;">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Completed
                                    </span>
                                    <span class="inline-flex items-center rounded-full px-4 py-1.5 text-xs font-bold text-white"
                                          style="background: #1a2d4a;">
                                        {{ $profile->current_status ?? 'SRU Alumni Member' }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('messages.index') }}"
                                   class="flex items-center justify-center gap-1.5 rounded-xl py-2.5 text-sm font-semibold text-white transition-opacity hover:opacity-85"
                                   style="background: #2a9d8f;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                    </svg>
                                    Message
                                </a>
                                <a href="{{ route('profile.edit') }}"
                                   class="flex items-center justify-center rounded-xl border-2 py-2.5 text-sm font-semibold transition-all hover:bg-[#1a2d4a] hover:text-white"
                                   style="border-color: #1a2d4a; color: #1a2d4a;">
                                    More
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Stats strip --}}
                <div class="grid grid-cols-2 md:grid-cols-4 border-t" style="border-color: #e5e7eb;">
                    @php
                        $stats = [
                            ['label' => 'Connections',   'value' => $connectionCount ?? 0,   'color' => '#1a2d4a'],
                            ['label' => 'Profile Views', 'value' => $profileViewsCount ?? 0, 'color' => '#2a9d8f'],
                            ['label' => 'Skills',        'value' => $skillsCount ?? 0,        'color' => '#1a2d4a'],
                            ['label' => 'Achievements',  'value' => $achievementsCount ?? 0,  'color' => '#c9a84c'],
                        ];
                    @endphp
                    @foreach($stats as $stat)
                    <div class="sru-stat-col text-center py-5 px-4 transition-colors"
                         style="border-right: 1px solid #e5e7eb;">
                        <div class="text-2xl font-bold" style="color: {{ $stat['color'] }};">{{ $stat['value'] }}</div>
                        <div class="text-xs mt-1 font-medium" style="color: #94a3b8;">{{ $stat['label'] }}</div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>

        {{-- ═══════════════════════════════
             MAIN CONTENT GRID
        ═══════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-14">

            {{-- ── LEFT COLUMN ────────────────── --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- ABOUT --}}
                <div class="sru-card bg-white rounded-2xl border border-gray-100 p-6 shadow-sm fade-up fu2">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="sru-section-label">About</h2>
                            <p class="text-xs mt-2" style="color: #94a3b8;">A concise view of your alumni background.</p>
                        </div>
                        <a href="{{ route('profile.edit-bio') }}"
                           class="text-xs font-bold px-3 py-1.5 rounded-lg border transition-all hover:bg-[#2a9d8f] hover:text-white hover:border-[#2a9d8f]"
                           style="color: #2a9d8f; border-color: #2a9d8f;">
                            Edit
                        </a>
                    </div>
                    <p class="text-sm leading-relaxed" style="color: #475569;">
                        @if($profile->description)
                            {{ $profile->description }}
                        @else
                            <span style="color: #94a3b8;" class="italic">
                                No bio added yet. Add a brief summary to help other alumni learn more about you.
                            </span>
                        @endif
                    </p>
                </div>

                {{-- EXPERIENCE --}}
                <div class="sru-card bg-white rounded-2xl border border-gray-100 p-6 shadow-sm fade-up fu3">
                    <div class="mb-5">
                        <h2 class="sru-section-label">Experience</h2>
                        <p class="text-xs mt-2" style="color: #94a3b8;">A chronological record of your positions and achievements.</p>
                    </div>
                    @forelse($experiences as $exp)
                        <div class="sru-teal-left mb-5 last:mb-0 py-1">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-1">
                                <div>
                                    <p class="font-bold text-sm" style="color: #1a2d4a;">{{ $exp->role }}</p>
                                    <p class="text-sm font-medium mt-0.5" style="color: #2a9d8f;">{{ $exp->organization }}</p>
                                    @if($exp->location)
                                        <p class="text-xs mt-0.5" style="color: #94a3b8;">{{ $exp->location }}</p>
                                    @endif
                                </div>
                                <span class="text-xs whitespace-nowrap mt-1 sm:mt-0 px-2 py-1 rounded-lg border"
                                      style="color: #64748b; background: #f8f9fa; border-color: #e5e7eb;">
                                    {{ $exp->from }} – {{ $exp->to ?? 'Present' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto mb-3" style="color: #d1d5db;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm" style="color: #94a3b8;">No experience added yet.</p>
                            <a href="{{ route('profile.edit') }}"
                               class="mt-1 inline-block text-sm font-semibold hover:underline"
                               style="color: #2a9d8f;">
                                Add experience →
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- SKILLS --}}
                <div class="sru-card bg-white rounded-2xl border border-gray-100 p-6 shadow-sm fade-up fu4">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="sru-section-label">Skills</h2>
                            <p class="text-xs mt-2" style="color: #94a3b8;">Showcase what you do best.</p>
                        </div>
                        <a href="{{ route('skills.index') }}"
                           class="text-xs font-bold px-3 py-1.5 rounded-lg border transition-all hover:bg-[#1a2d4a] hover:text-white hover:border-[#1a2d4a]"
                           style="color: #1a2d4a; border-color: #1a2d4a;">
                            Manage
                        </a>
                    </div>
                    @if($skills && count($skills) > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($skills as $skill)
                                <span class="sru-skill-pill inline-flex items-center gap-2 px-3 py-1.5 rounded-full border text-sm font-medium"
                                      style="background: #f8f9fa; color: #334155; border-color: #e5e7eb;">
                                    {{ $skill->name }}
                                    @if($skill->endorsements > 0)
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-white text-[10px] font-bold"
                                              style="background: #2a9d8f;">
                                            {{ $skill->endorsements }}
                                        </span>
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <p class="text-sm" style="color: #94a3b8;">No skills added yet.</p>
                            <a href="{{ route('profile.edit') }}"
                               class="mt-1 inline-block text-sm font-semibold hover:underline"
                               style="color: #2a9d8f;">
                                Add your skills →
                            </a>
                        </div>
                    @endif
                </div>

                {{-- ACHIEVEMENTS --}}
                @if($achievements && count($achievements) > 0)
                <div class="sru-card bg-white rounded-2xl border border-gray-100 p-6 shadow-sm fade-up fu5">
                    <h2 class="sru-section-label mb-5">Achievements</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($achievements as $achievement)
                            <div class="sru-achievement-card flex gap-4 p-4 rounded-xl border"
                                 style="background: #f8fffe; border-color: #b2ece5;">
                                <div class="text-3xl shrink-0 leading-none mt-0.5">
                                    {{ $achievement->badge_icon ?? '⭐' }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-sm" style="color: #1a2d4a;">{{ $achievement->title }}</h4>
                                    @if($achievement->description)
                                        <p class="text-xs mt-0.5 leading-snug" style="color: #64748b;">
                                            {{ $achievement->description }}
                                        </p>
                                    @endif
                                    @if($achievement->earned_at)
                                        <p class="text-[10px] font-bold mt-1.5 uppercase tracking-wide"
                                           style="color: #c9a84c;">
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

            {{-- ── RIGHT COLUMN ────────────────── --}}
            <div class="space-y-5">

                {{-- CONTACT --}}
                <div class="sru-card bg-white rounded-2xl border border-gray-100 p-5 shadow-sm fade-up fu2">
                    <h2 class="sru-section-label mb-4">Contact</h2>
                    <div class="space-y-2">
                        @if($profile->mobile)
                            <a href="tel:{{ $profile->mobile }}"
                               class="sru-contact-row flex items-center gap-3 p-3 rounded-xl border"
                               style="background: #f8f9fa; border-color: #e5e7eb;">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                                     style="background: #1a2d4a;">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium" style="color: #334155;">{{ $profile->mobile }}</span>
                            </a>
                        @endif
                        @if($profile->user && $profile->user->email)
                            <a href="mailto:{{ $profile->user->email }}"
                               class="sru-contact-row flex items-center gap-3 p-3 rounded-xl border"
                               style="background: #f8f9fa; border-color: #e5e7eb;">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                                     style="background: #2a9d8f;">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium truncate" style="color: #334155;">{{ $profile->user->email }}</span>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- SOCIAL --}}
                <div class="sru-card bg-white rounded-2xl border border-gray-100 p-5 shadow-sm fade-up fu3">
                    <h2 class="sru-section-label mb-4">Social</h2>
                    <div class="flex gap-2 flex-wrap">
                        @if($profile->linkedin)
                            <a href="{{ $profile->linkedin }}" target="_blank"
                               class="sru-social-btn w-10 h-10 flex items-center justify-center rounded-xl border text-sm font-bold"
                               style="background: #f8f9fa; color: #1a2d4a; border-color: #e5e7eb;" title="LinkedIn">
                                in
                            </a>
                        @endif
                        @if($profile->facebook)
                            <a href="{{ $profile->facebook }}" target="_blank"
                               class="sru-social-btn w-10 h-10 flex items-center justify-center rounded-xl border text-sm font-bold"
                               style="background: #f8f9fa; color: #1a2d4a; border-color: #e5e7eb;" title="Facebook">
                                f
                            </a>
                        @endif
                        @if($profile->twitter)
                            <a href="{{ $profile->twitter }}" target="_blank"
                               class="sru-social-btn w-10 h-10 flex items-center justify-center rounded-xl border text-sm font-bold"
                               style="background: #f8f9fa; color: #1a2d4a; border-color: #e5e7eb;" title="X">
                                𝕏
                            </a>
                        @endif
                        @if($profile->instagram)
                            <a href="{{ $profile->instagram }}" target="_blank"
                               class="sru-social-btn w-10 h-10 flex items-center justify-center rounded-xl border text-lg"
                               style="background: #f8f9fa; color: #1a2d4a; border-color: #e5e7eb;" title="Instagram">
                                📷
                            </a>
                        @endif
                        @if(!$profile->linkedin && !$profile->facebook && !$profile->twitter && !$profile->instagram)
                            <p class="text-xs italic" style="color: #94a3b8;">No social links added.</p>
                        @endif
                    </div>
                </div>

                {{-- EDUCATION --}}
                <div class="sru-card bg-white rounded-2xl border border-gray-100 p-5 shadow-sm fade-up fu4">
                    <h2 class="sru-section-label mb-4">Education</h2>
                    <div class="rounded-xl border p-4 sru-achievement-card"
                         style="background: #f8fffe; border-color: #b2ece5;">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0"
                                 style="background: #1a2d4a;">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-sm" style="color: #1a2d4a;">{{ $profile->degree ?? '—' }}</p>
                                <p class="text-xs font-semibold mt-0.5" style="color: #2a9d8f;">{{ $profile->branch ?? '—' }}</p>
                                <p class="text-xs mt-1" style="color: #94a3b8;">
                                    Graduation Year: {{ $profile->passing_year ?? '—' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CTA CARD --}}
                <div class="rounded-2xl p-5 text-center sru-hero-gradient fade-up fu5">
                    <p class="text-white text-sm font-semibold mb-1">Keep your profile updated</p>
                    <p class="text-sm mb-3" style="color: rgba(255,255,255,0.65);">
                        Help employers and alumni find you.
                    </p>
                    <a href="{{ route('profile.edit') }}"
                       class="inline-block px-5 py-2 rounded-xl bg-white text-sm font-bold hover:opacity-90 transition-opacity"
                       style="color: #1a2d4a;">
                        Edit Profile
                    </a>
                </div>

            </div>
        </div>
    </div>

@else

    {{-- ═══════════════════════════════
         EMPTY STATE
    ═══════════════════════════════ --}}
    <div class="min-h-screen flex items-center justify-center px-4" style="background: #f0f0ee;">
        <div class="text-center max-w-sm fade-up">
            <div class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6"
                 style="background: #e4f5f3;">
                <svg class="w-12 h-12" style="color: #2a9d8f;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold mb-2" style="color: #1a2d4a;">Profile Not Found</h2>
            <p class="text-sm mb-6" style="color: #64748b;">
                Let's create your profile and join the alumni community.
            </p>
            <a href="/profile/create"
               class="inline-block px-8 py-3 rounded-xl font-bold text-white hover:opacity-90 transition-opacity sru-hero-gradient">
                Create Your Profile
            </a>
        </div>
    </div>

@endif

</div>

@endsection