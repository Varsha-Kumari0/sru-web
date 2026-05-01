@extends('layouts.app')

@section('title', 'Engage')

@section('content')
<div class="-m-6 min-h-screen" style="background:#f0f0ee;">
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <p class="inline-block text-xs font-bold uppercase tracking-widest border-b-4 border-[#c9a84c] pb-1 text-[#1a2d4a]">Engage</p>
        <h1 class="mt-3 text-3xl font-bold text-[#1a2d4a]">Ways to stay involved</h1>

        @php
            $engagePosts = $engagePosts ?? collect();
            $cards = [
                [
                    'title' => 'Mentor Students',
                    'desc' => 'Offer career guidance, portfolio reviews, or interview practice.',
                    'href' => route('engage.mentor'),
                    'post_type' => 'mentoring',
                    'details_href' => route('engage.section.details', ['postType' => 'mentoring']),
                ],
                [
                    'title' => 'Host an Event',
                    'desc' => 'Run a workshop, reunion, webinar, or alumni meetup.',
                    'href' => route('engage.host'),
                    'post_type' => 'meetup',
                    'details_href' => route('engage.section.details', ['postType' => 'meetup']),
                ],
                [
                    'title' => 'Share Opportunities',
                    'desc' => 'Send referrals and founder stories.',
                    'href' => route('engage.share'),
                    'post_type' => 'opportunity',
                    'details_href' => route('engage.section.details', ['postType' => 'opportunity']),
                ],
            ];
        @endphp

        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($cards as $item)
                @php
                    $fieldPosts = $engagePosts->get($item['post_type'], collect());
                @endphp
                <article class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
                    <div>
                        <h2 class="text-xl font-bold text-[#1a2d4a]">{{ $item['title'] }}</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $item['desc'] }}</p>
                        <div class="mt-5 flex flex-wrap gap-2">
                            <a href="{{ $item['href'] }}" class="inline-block rounded-xl bg-[#2a9d8f] px-4 py-2 text-sm font-bold text-white">Get started</a>
                            <a href="{{ $item['details_href'] }}" class="inline-block rounded-xl border border-[#1a2d4a] px-4 py-2 text-sm font-bold text-[#1a2d4a] hover:bg-[#1a2d4a] hover:text-white">View details</a>
                        </div>
                    </div>

                    @auth
                        <div class="border-t border-slate-100 pt-4">
                            <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">Your uploaded details</h3>

                            @if($fieldPosts->isEmpty())
                                <p class="mt-2 text-sm text-slate-500">No details posted yet in this section.</p>
                            @else
                                <div class="mt-2 max-h-40 overflow-y-auto space-y-2 pr-1">
                                    @foreach($fieldPosts as $post)
                                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                            <p class="text-sm leading-6 text-slate-700 whitespace-pre-wrap break-words">{{ $post->body }}</p>
                                            <p class="mt-1 text-xs text-slate-500">{{ $post->created_at?->diffForHumans() ?? '-' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <form method="POST" action="{{ route('dashboard.feed.posts.store') }}" class="mt-3 space-y-3 js-engage-post-form" data-post-type="{{ $item['post_type'] }}">
                                @csrf
                                <input type="hidden" name="post_type" value="{{ $item['post_type'] }}">

                                @if($item['post_type'] === 'mentoring')
                                    <div>
                                        <label class="text-xs font-semibold text-slate-600">Mentoring Topic</label>
                                        <input type="text" class="mt-1 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-[#2a9d8f] focus:ring-[#2a9d8f]" placeholder="Example: Resume review, Mock interviews" data-compose-field="Topic" required>
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-slate-600">Experience / Expertise</label>
                                        <input type="text" class="mt-1 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-[#2a9d8f] focus:ring-[#2a9d8f]" placeholder="Example: 4 years in Software Engineering" data-compose-field="Expertise" required>
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-slate-600">Availability</label>
                                        <input type="text" class="mt-1 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-[#2a9d8f] focus:ring-[#2a9d8f]" placeholder="Example: Weekends, 1 hour sessions" data-compose-field="Availability" required>
                                    </div>
                                @elseif($item['post_type'] === 'meetup')
                                    <div>
                                        <label class="text-xs font-semibold text-slate-600">Event Title</label>
                                        <input type="text" class="mt-1 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-[#2a9d8f] focus:ring-[#2a9d8f]" placeholder="Example: Alumni Networking Meetup" data-compose-field="Event" required>
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-slate-600">Format / Location</label>
                                        <input type="text" class="mt-1 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-[#2a9d8f] focus:ring-[#2a9d8f]" placeholder="Example: Online (Zoom) or Hyderabad Campus" data-compose-field="Format" required>
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-slate-600">Date & Time</label>
                                        <input type="text" class="mt-1 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-[#2a9d8f] focus:ring-[#2a9d8f]" placeholder="Example: 15 May, 5:00 PM" data-compose-field="Schedule" required>
                                    </div>
                                @else
                                    <div>
                                        <label class="text-xs font-semibold text-slate-600">Opportunity / Referral Title</label>
                                        <input type="text" class="mt-1 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-[#2a9d8f] focus:ring-[#2a9d8f]" placeholder="Example: Backend Developer Referral" data-compose-field="Title" required>
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-slate-600">Organization / Contact</label>
                                        <input type="text" class="mt-1 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-[#2a9d8f] focus:ring-[#2a9d8f]" placeholder="Example: SRU Alumni Network / email@domain.com" data-compose-field="Contact" required>
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-slate-600">Details</label>
                                        <textarea rows="3" class="mt-1 w-full resize-none rounded-xl border border-gray-200 px-3 py-2 text-sm text-slate-700 focus:border-[#2a9d8f] focus:ring-[#2a9d8f]" placeholder="Share role, eligibility, deadlines, or referral notes" data-compose-field="Details" required></textarea>
                                    </div>
                                @endif

                                <input type="hidden" name="body" data-composed-body>
                                <p class="text-xs text-slate-500">This will be posted in your {{ strtolower($item['title']) }} section feed.</p>
                                <button type="submit" class="rounded-xl bg-[#1a2d4a] px-4 py-2 text-sm font-bold text-white">Post here</button>
                            </form>
                        </div>
                    @endauth
                </article>
            @endforeach
        </div>
    </section>
</div>

<script>
    (function () {
        document.querySelectorAll('.js-engage-post-form').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                const bodyField = form.querySelector('[data-composed-body]');
                const parts = [];

                form.querySelectorAll('[data-compose-field]').forEach(function (input) {
                    const label = input.getAttribute('data-compose-field') || 'Detail';
                    const value = (input.value || '').trim();

                    if (value) {
                        parts.push(label + ': ' + value);
                    }
                });

                const composed = parts.join('\n');

                if (!composed) {
                    event.preventDefault();
                    alert('Please fill the form before posting.');
                    return;
                }

                if (composed.length > 1200) {
                    event.preventDefault();
                    alert('Your details are too long. Please keep it under 1200 characters.');
                    return;
                }

                bodyField.value = composed;
            });
        });
    })();
</script>
@endsection
