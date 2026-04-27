@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="-m-6 min-h-screen" style="background:#f0f0ee;">
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-br from-[#1a2d4a] to-[#2a9d8f] p-8 text-white">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#c9a84c]">Contact</p>
                <h1 class="mt-3 text-3xl font-bold">Reach the alumni team</h1>
                <p class="mt-3 max-w-xl text-white/75">Use these links for help with profile updates, events, jobs, or alumni engagement.</p>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="mailto:alumni@sru.edu.in" class="rounded-xl border border-gray-200 p-5 hover:border-[#2a9d8f]">
                    <p class="font-bold text-[#1a2d4a]">Email</p>
                    <p class="mt-1 text-sm text-slate-600">alumni@sru.edu.in</p>
                </a>
                <a href="{{ route('profile') }}" class="rounded-xl border border-gray-200 p-5 hover:border-[#2a9d8f]">
                    <p class="font-bold text-[#1a2d4a]">Profile Support</p>
                    <p class="mt-1 text-sm text-slate-600">Review or update your alumni profile.</p>
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
