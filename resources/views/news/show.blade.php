@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto mt-8">

    <div class="bg-white p-6 rounded shadow">

        <!-- TITLE -->
        <h1 class="text-3xl font-bold mb-3">
            {{ $news->title }}
        </h1>

        <!-- DATE -->
        <p class="text-gray-400 text-sm mb-4">
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
            <a href="/newsroom" class="text-blue-600">
                ← Back to Newsroom
            </a>
        </div>

    </div>

</div>

@endsection