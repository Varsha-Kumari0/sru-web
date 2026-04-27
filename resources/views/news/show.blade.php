@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto mt-8">

    <div class="bg-white p-6 rounded shadow border border-[#e2e8f0]">

        <!-- TITLE -->
        <h1 class="text-3xl font-bold mb-3 text-[#1a2d5a]">
            {{ $news->title }}
        </h1>

        <!-- DATE -->
        <p class="text-gray-500 text-sm mb-4">
            Posted on {{ \Carbon\Carbon::parse($news->published_at)->format('jS M, Y') }}
        </p>

        <!-- IMAGE -->
        @if($news->image)
            <img src="/images/{{ $news->image }}" 
                 class="w-full h-80 object-cover rounded mb-5">
        @endif

        <!-- CONTENT -->
        <div class="text-gray-700 leading-relaxed">
            {{ $news->content ?? $news->excerpt }}
        </div>

        <!-- BACK BUTTON -->
        <div class="mt-6">
            <a href="/newsroom" class="text-[#1a2d5a] hover:text-[#c0006a]">
                ← Back to Newsroom
            </a>
        </div>

    </div>

</div>

@endsection