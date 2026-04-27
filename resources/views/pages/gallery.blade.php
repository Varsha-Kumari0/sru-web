@extends('layouts.app')

@section('title', 'Gallery')

@section('content')
@php
    $images = [
        'home-carosel1.jpeg',
        'home-carosel2.jpeg',
        'home-carosel3.jpeg',
        '1777187457_sru_bg_old.png',
        'logos/sru_bg.png',
        'logos/sru_logo_new.png',
    ];
@endphp

<div class="-m-6 min-h-screen" style="background:#f0f0ee;">
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="mb-8">
            <p class="inline-block text-xs font-bold uppercase tracking-widest border-b-4 border-[#c9a84c] pb-1 text-[#1a2d4a]">Gallery</p>
            <h1 class="mt-3 text-3xl font-bold text-[#1a2d4a]">Campus and alumni moments</h1>
            <p class="mt-2 text-sm text-slate-600">A starting gallery using the images already available in the project.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($images as $image)
                <figure class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <img src="{{ asset('images/' . $image) }}" alt="SRU gallery image" class="h-56 w-full object-cover">
                    <figcaption class="p-4 text-sm font-semibold text-[#1a2d4a]">SRU Alumni Network</figcaption>
                </figure>
            @endforeach
        </div>
    </section>
</div>
@endsection
