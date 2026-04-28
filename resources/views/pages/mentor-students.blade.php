@extends('layouts.app')

@section('title', 'Mentor Students')

@section('content')
<div class="-m-6 min-h-screen" style="background:#f0f0ee;">
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-br from-[#1a2d4a] to-[#2a9d8f] p-8 text-white">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#c9a84c]">Mentor Students</p>
                <h1 class="mt-3 text-3xl font-bold">Share your experience with current students</h1>
                <p class="mt-3 max-w-2xl text-white/80">Help SRU students build stronger portfolios, practice interviews, and learn from alumni who are already working in their dream fields.</p>
            </div>
            <div class="p-8 grid gap-8 md:grid-cols-2">
                <div class="space-y-6">
                    <div class="rounded-3xl bg-slate-50 border border-slate-200 p-6">
                        <h2 class="text-lg font-bold text-[#1a2d4a]">What you can do</h2>
                        <ul class="mt-4 space-y-3 text-sm text-slate-600">
                            <li>• Offer career guidance or resume reviews</li>
                            <li>• Host mock interviews and portfolio feedback</li>
                            <li>• Join mentorship circles for project support</li>
                        </ul>
                    </div>
                    <div class="rounded-3xl bg-slate-50 border border-slate-200 p-6">
                        <h2 class="text-lg font-bold text-[#1a2d4a]">Why it matters</h2>
                        <p class="mt-3 text-sm text-slate-600">Your real-world insight helps current students navigate their next steps with confidence, whether they want to launch a startup, pursue research, or join industry.</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 border border-slate-200 p-6">
                        <h2 class="text-lg font-bold text-[#1a2d4a]">Next step</h2>
                        <p class="mt-3 text-sm text-slate-600">Ready to connect? Use the contact page to introduce yourself and tell us how you'd like to support students.</p>
                        <a href="{{ route('contact') }}" class="mt-4 inline-flex items-center justify-center rounded-xl bg-[#2a9d8f] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#237f72]">Contact the alumni team</a>
                    </div>
                </div>
                <div class="rounded-3xl bg-[#f8faf9] border border-slate-200 p-8">
                    <h2 class="text-lg font-bold text-[#1a2d4a]">Mentorship formats</h2>
                    <div class="mt-5 space-y-5 text-sm text-slate-600">
                        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                            <p class="font-semibold text-[#1a2d4a]">One-on-one coaching</p>
                            <p class="mt-2">Offer focused support on career planning, interviews, and professional development.</p>
                        </div>
                        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                            <p class="font-semibold text-[#1a2d4a]">Workshop sessions</p>
                            <p class="mt-2">Host a resume review, LinkedIn clinic, or project feedback session.</p>
                        </div>
                        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                            <p class="font-semibold text-[#1a2d4a]">Panel discussions</p>
                            <p class="mt-2">Join a panel to share lessons learned, emerging skills, and career paths.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
