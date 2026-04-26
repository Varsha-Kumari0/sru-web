@extends('layouts.app')

@section('content')

    <div class="max-w-4xl mx-auto px-4 py-8 md:py-12">

        <!-- Header Section -->
        <div class="mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-3">Testimonials</h1>
            <p class="text-lg text-gray-600">Success stories from our alumni community</p>
        </div>

        @if($testimonials->isEmpty())
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center text-gray-600">
                <p class="text-lg">No testimonials found.</p>
            </div>
        @else
            <div class="space-y-6 md:space-y-8">
                @foreach($testimonials as $testimonial)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                        <div class="flex flex-col sm:flex-row gap-4 md:gap-6 p-5 md:p-6">
                            <!-- Image Section -->
                            <div class="flex-shrink-0">
                                <img
                                    src="{{ asset('images/' . ($testimonial->image ?: 'testimonial-placeholder.svg')) }}"
                                    alt="{{ $testimonial->name }}"
                                    class="w-32 h-40 md:w-40 md:h-48 object-cover rounded-lg bg-gray-100"
                                    onerror="this.onerror=null;this.src='{{ asset('images/testimonial-placeholder.svg') }}'"
                                >
                            </div>

                            <!-- Content Section -->
                            <div class="flex-1 flex flex-col justify-between">
                                <!-- Testimonial Text -->
                                <div>
                                    <p class="text-gray-700 leading-relaxed text-base md:text-lg mb-4">
                                        {{ $testimonial->content }}
                                    </p>
                                </div>

                                <!-- Author Info -->
                                <div class="border-t border-gray-200 pt-4">
                                    <h3 class="font-semibold text-gray-900 text-lg">{{ $testimonial->name }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <span class="font-medium">{{ $testimonial->position }}</span>
                                        @if($testimonial->company)
                                            <span>, {{ $testimonial->company }}</span>
                                        @endif
                                    </p>
                                    @if($testimonial->department)
                                        <p class="text-sm text-gray-500 mt-1">
                                            Dept. of <span class="font-medium">{{ $testimonial->department }}</span>
                                            @if($testimonial->year_from && $testimonial->year_to)
                                                <span>({{ $testimonial->year_from }}-{{ $testimonial->year_to }})</span>
                                            @elseif($testimonial->year_from)
                                                <span>({{ $testimonial->year_from }})</span>
                                            @endif
                                        </p>
                                    @elseif($testimonial->year_from && $testimonial->year_to)
                                        <p class="text-sm text-gray-500 mt-1">{{ $testimonial->year_from }} - {{ $testimonial->year_to }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

@endsection
