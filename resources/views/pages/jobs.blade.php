@extends('layouts.app')

@section('title', 'Job Board')

@section('content')
<div class="-m-6 min-h-screen" style="background:#f0f0ee;">
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="rounded-2xl bg-white border border-gray-100 shadow-sm p-8">
            <p class="inline-block text-xs font-bold uppercase tracking-widest border-b-4 border-[#c9a84c] pb-1 text-[#1a2d4a]">Job Board</p>
            <h1 class="mt-3 text-3xl font-bold text-[#1a2d4a]">Opportunities from alumni and partners</h1>
            <p class="mt-3 max-w-2xl text-slate-600 leading-7">The job board page now exists and is ready for the next step: a jobs table, admin posting form, and alumni applications or referrals.</p>
            <a href="{{ route('contact') }}" class="mt-6 inline-block rounded-xl bg-[#2a9d8f] px-5 py-3 text-sm font-bold text-white">Share an opportunity</a>
        </div>
    </section>
</div>
@endsection
