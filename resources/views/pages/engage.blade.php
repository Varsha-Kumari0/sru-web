@extends('layouts.app')

@section('title', 'Engage')

@section('content')
<div class="-m-6 min-h-screen" style="background:#f0f0ee;">
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <p class="inline-block text-xs font-bold uppercase tracking-widest border-b-4 border-[#c9a84c] pb-1 text-[#1a2d4a]">Engage</p>
        <h1 class="mt-3 text-3xl font-bold text-[#1a2d4a]">Ways to stay involved</h1>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach([
                ['Mentor Students', 'Offer career guidance, portfolio reviews, or interview practice.', route('contact')],
                ['Host an Event', 'Run a workshop, reunion, webinar, or alumni meetup.', route('events.index')],
                ['Share Opportunities', 'Send jobs, internships, referrals, and founder stories.', route('jobs.index')],
            ] as $item)
                <article class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-[#1a2d4a]">{{ $item[0] }}</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ $item[1] }}</p>
                    <a href="{{ $item[2] }}" class="mt-5 inline-block rounded-xl bg-[#2a9d8f] px-4 py-2 text-sm font-bold text-white">Get started</a>
                </article>
            @endforeach
        </div>
    </section>
</div>
@endsection
