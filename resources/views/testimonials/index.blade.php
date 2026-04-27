@extends('layouts.app')

@section('title', 'Testimonials')

@section('content')
<style>
    .sru-hero-gradient {
        background: linear-gradient(135deg, #1a2d4a 0%, #1e4a52 50%, #2a9d8f 100%);
    }
    .sru-section-label {
        display: inline-block;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: #1a2d4a;
        border-bottom: 3px solid #c9a84c;
        padding-bottom: 4px;
    }
    .testimonial-card {
        transition: box-shadow 0.2s ease, transform 0.2s ease, border-color 0.2s ease;
    }
    .testimonial-card:hover {
        box-shadow: 0 8px 28px rgba(26, 45, 74, 0.10);
        transform: translateY(-2px);
        border-color: #b2ece5;
    }
</style>

<div class="-m-6 min-h-screen" style="background:#f0f0ee;">
    <section class="sru-hero-gradient">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <p class="text-xs font-bold uppercase tracking-[0.18em] mb-2" style="color:#c9a84c;">Alumni Stories</p>
            <h1 class="text-4xl md:text-5xl font-bold text-white">Testimonials</h1>
            <p class="mt-4 max-w-2xl text-white/75 leading-7">Success stories and reflections from the SRU alumni community.</p>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="sru-section-label">Featured Alumni</h2>
                <p class="mt-3 text-sm text-slate-600">These entries are loaded from the active testimonials in the database.</p>
            </div>
            <a href="{{ route('contact') }}" class="inline-flex rounded-xl bg-[#2a9d8f] px-4 py-2 text-sm font-bold text-white">
                Share your story
            </a>
        </div>

        @if($testimonials->isEmpty())
            <div class="bg-white border border-gray-100 rounded-2xl p-8 text-center text-slate-600 shadow-sm">
                <p class="text-lg">No testimonials found.</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($testimonials as $testimonial)
                    <article class="testimonial-card bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                        <div class="flex flex-col sm:flex-row gap-5 p-5">
                            <div class="flex-shrink-0">
                                <img
                                    src="{{ asset('images/' . ($testimonial->image ?: 'testimonial-placeholder.svg')) }}"
                                    alt="{{ $testimonial->name }}"
                                    class="w-full sm:w-36 h-48 object-cover rounded-xl bg-gray-100"
                                    onerror="this.onerror=null;this.src='{{ asset('images/testimonial-placeholder.svg') }}'"
                                >
                            </div>

                            <div class="flex-1 flex flex-col justify-between">
                                <blockquote>
                                    <p class="text-slate-700 leading-7 text-sm md:text-base mb-4">
                                        {{ $testimonial->content }}
                                    </p>
                                </blockquote>

                                <footer class="border-t border-gray-100 pt-4">
                                    <h3 class="font-bold text-[#1a2d4a] text-lg">{{ $testimonial->name }}</h3>
                                    <p class="text-sm text-slate-600 mt-1">
                                        <span class="font-medium">{{ $testimonial->position }}</span>
                                        @if($testimonial->company)
                                            <span>, {{ $testimonial->company }}</span>
                                        @endif
                                    </p>
                                    @if($testimonial->department)
                                        <p class="text-sm text-slate-500 mt-1">
                                            Dept. of <span class="font-medium">{{ $testimonial->department }}</span>
                                            @if($testimonial->year_from && $testimonial->year_to)
                                                <span>({{ $testimonial->year_from }}-{{ $testimonial->year_to }})</span>
                                            @elseif($testimonial->year_from)
                                                <span>({{ $testimonial->year_from }})</span>
                                            @endif
                                        </p>
                                    @elseif($testimonial->year_from && $testimonial->year_to)
                                        <p class="text-sm text-slate-500 mt-1">{{ $testimonial->year_from }} - {{ $testimonial->year_to }}</p>
                                    @endif
                                </footer>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif

    </div>
</div>

@endsection
