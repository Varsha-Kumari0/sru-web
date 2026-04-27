@extends('layouts.app')

@section('content')

    <div class="max-w-6xl mx-auto mt-8">

        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h2 class="text-3xl font-semibold text-[#1a2d5a]">Newsroom</h2>
                <p class="text-gray-600">All the News and Updates from SRUNI.</p>
            </div>

            @if($selectedMonth)
                <div class="flex flex-wrap items-center gap-3 text-sm text-gray-700">
                    <span class="font-medium">Showing:</span>
                    <span class="bg-[#f4f6f9] text-[#c0006a] px-3 py-1 rounded border border-[#e2e8f0]">{{ $selectedMonth }}</span>
                    <a href="{{ route('newsroom') }}" class="text-[#1a2d5a] hover:underline">Clear filter</a>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- LEFT CONTENT -->
            <div class="md:col-span-2 space-y-6">
                @if($news->isEmpty())
                    <div class="bg-white p-6 rounded shadow text-center text-gray-600 border border-[#e2e8f0]">
                        No news items found for this archive. Try a different month or clear the filter.
                    </div>
                @else
                    @foreach($news as $item)
                        <div class="bg-white p-5 rounded shadow hover:shadow-lg transition-shadow duration-200 border border-[#e2e8f0]">
                            <div class="flex flex-col gap-4 md:flex-row md:items-start">

                                @if($item->image)
                                    <img src="/images/{{ $item->image }}" class="w-full md:w-40 h-40 object-cover rounded">
                                @endif

                                <div class="flex-1">
                                    <p class="text-sm text-[#c0006a] font-semibold mb-2">{{ \Carbon\Carbon::parse($item->published_at)->format('F Y') }}</p>
                                    <h3 class="text-2xl font-semibold mb-3 text-[#1a2d5a]">{{ $item->title }}</h3>
                                    <p class="text-gray-600 leading-relaxed mb-4 max-h-20 overflow-hidden">{{ $item->excerpt }}</p>

                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <a href="{{ route('news.show', $item->id) }}" class="inline-flex items-center justify-center rounded bg-[#1a2d5a] text-white px-4 py-2 text-sm font-medium hover:bg-[#141d42]">
                                            Read More
                                        </a>

                                        <span class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($item->published_at)->format('jS M, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- RIGHT SIDEBAR -->
            <div class="bg-white p-5 rounded shadow border border-[#e2e8f0]">
                <h3 class="font-semibold mb-4 text-[#1a2d5a]">Archive</h3>
                <div class="space-y-2">
                    @foreach($archives as $monthKey => $data)
                        <a href="{{ route('newsroom', ['archive' => $monthKey]) }}" class="block rounded px-3 py-2 transition hover:bg-[#f4f6f9] {{ $archive === $monthKey ? 'bg-[#f4f6f9] text-[#c0006a] font-semibold' : 'text-gray-700' }}">
                            <span>{{ $data['label'] }}</span>
                            <span class="text-xs text-gray-500 ml-2">({{ $data['count'] }})</span>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

@endsection