@extends('layouts.app')

@section('title', 'Host an Event')

@section('content')
<div class="-m-6 min-h-screen" style="background:#f0f0ee;">
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-br from-[#1a2d4a] to-[#2a9d8f] p-8 text-white">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#c9a84c]">Host an Event</p>
                <h1 class="mt-3 text-3xl font-bold">Bring alumni together with a meaningful event</h1>
                <p class="mt-3 max-w-2xl text-white/80">Whether it’s a reunion, workshop, webinar, or mentoring meetup, SRU alumni events help our community reconnect and grow.</p>
            </div>
            <div class="p-8 grid gap-8 md:grid-cols-2">
                <div class="space-y-6">
                    <div class="rounded-3xl bg-slate-50 border border-slate-200 p-6">
                        <h2 class="text-lg font-bold text-[#1a2d4a]">Event support</h2>
                        <ul class="mt-4 space-y-3 text-sm text-slate-600">
                            <li>• Guidance on event format and agenda</li>
                            <li>• Promotion to the alumni community</li>
                            <li>• Logistics support for speakers and attendance</li>
                        </ul>
                    </div>
                    <div class="rounded-3xl bg-slate-50 border border-slate-200 p-6">
                        <h2 class="text-lg font-bold text-[#1a2d4a]">Event types</h2>
                        <p class="mt-3 text-sm text-slate-600">Host a career panel, class reunion, industry webinar, or campus meetup. We can help you match the right audience and share details with alumni across batches.</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 border border-slate-200 p-6">
                        <h2 class="text-lg font-bold text-[#1a2d4a]">Start planning</h2>
                        <p class="mt-3 text-sm text-slate-600">If you already have a date or theme in mind, let us know and we’ll work on the next steps.</p>
                        <a href="{{ route('contact') }}" class="mt-4 inline-flex items-center justify-center rounded-xl bg-[#2a9d8f] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#237f72]">Contact the events team</a>
                    </div>
                </div>
                <div class="rounded-3xl bg-[#f8faf9] border border-slate-200 p-8">
                    <h2 class="text-lg font-bold text-[#1a2d4a]">Upcoming event spotlight</h2>
                    <div class="mt-5 space-y-5 text-sm text-slate-600">
                        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                            <p class="font-semibold text-[#1a2d4a]">Campus Hackathon 2026</p>
                            <p class="mt-2">A 24-hour innovation experience for students and alumni to launch ideas, network, and learn from speakers.</p>
                        </div>
                        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                            <p class="font-semibold text-[#1a2d4a]">Mentorship Meetup</p>
                            <p class="mt-2">Small-group sessions focused on career transition, startups, and portfolio building.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
