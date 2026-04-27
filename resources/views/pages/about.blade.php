@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="-m-6 min-h-screen" style="background:#f0f0ee;">
    <section class="bg-gradient-to-br from-[#1a2d4a] via-[#1e4a52] to-[#2a9d8f]">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#c9a84c]">About SRU Alumni</p>
            <h1 class="mt-3 text-4xl font-bold text-white">A living network for SR University graduates.</h1>
            <p class="mt-4 max-w-2xl text-white/75 leading-7">The alumni portal helps graduates reconnect, discover events, share stories, mentor students, and stay connected with SRU.</p>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach([
            ['Connect', 'Find alumni across batches, locations, and careers.'],
            ['Celebrate', 'Highlight achievements, testimonials, events, and campus memories.'],
            ['Contribute', 'Mentor students, share opportunities, and support alumni-led initiatives.'],
        ] as $item)
            <article class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <span class="inline-block text-xs font-bold uppercase tracking-widest border-b-4 border-[#c9a84c] pb-1 text-[#1a2d4a]">{{ $item[0] }}</span>
                <p class="mt-4 text-sm leading-6 text-slate-600">{{ $item[1] }}</p>
            </article>
        @endforeach
    </section>
</div>
@endsection
