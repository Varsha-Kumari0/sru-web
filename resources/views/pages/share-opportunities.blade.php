@extends('layouts.app')

@section('title', 'Share Opportunities')

@section('content')
<div class="-m-6 min-h-screen" style="background:#f0f0ee;">
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-br from-[#1a2d4a] to-[#2a9d8f] p-8 text-white">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#c9a84c]">Share Opportunities</p>
                <h1 class="mt-3 text-3xl font-bold">Post jobs, internships, and referrals</h1>
                <p class="mt-3 max-w-2xl text-white/80">Help the alumni community grow by sharing opportunities that suit our emerging talent and experienced professionals.</p>
            </div>
            <div class="p-8 grid gap-8 md:grid-cols-2">
                <div class="space-y-6">
                    <div class="rounded-3xl bg-slate-50 border border-slate-200 p-6">
                        <h2 class="text-lg font-bold text-[#1a2d4a]">Opportunity types</h2>
                        <ul class="mt-4 space-y-3 text-sm text-slate-600">
                            <li>• Full-time jobs and internships</li>
                            <li>• Referrals for alumni candidates</li>
                            <li>• Freelance projects and collaborations</li>
                        </ul>
                    </div>
                    <div class="rounded-3xl bg-slate-50 border border-slate-200 p-6">
                        <h2 class="text-lg font-bold text-[#1a2d4a]">Who can share</h2>
                        <p class="mt-3 text-sm text-slate-600">Alumni, employers, and partners can share openings directly with our community. We make sure the right people see them.</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 border border-slate-200 p-6">
                        <h2 class="text-lg font-bold text-[#1a2d4a]">Share now</h2>
                        <p class="mt-3 text-sm text-slate-600">Have an opportunity ready? Tell us the role and the ideal candidate profile, and we’ll promote it to SRU alumni.</p>
                        <a href="{{ route('jobs.index') }}" class="mt-4 inline-flex items-center justify-center rounded-xl bg-[#2a9d8f] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#237f72]">Submit an opportunity</a>
                    </div>
                </div>
                <div class="rounded-3xl bg-[#f8faf9] border border-slate-200 p-8">
                    <h2 class="text-lg font-bold text-[#1a2d4a]">Live opportunity examples</h2>
                    <div class="mt-5 space-y-5 text-sm text-slate-600">
                        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                            <p class="font-semibold text-[#1a2d4a]">Product Designer Internship</p>
                            <p class="mt-2">Paid summer internship for alumni students interested in UX and design systems.</p>
                        </div>
                        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                            <p class="font-semibold text-[#1a2d4a]">Full-stack Engineering Role</p>
                            <p class="mt-2">Open position for backend/frontend developers at an alumni-founded startup.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
