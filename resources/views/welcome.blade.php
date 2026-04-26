<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SR University Alumni Association</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --navy: #0a1f44;
            --gold: #c9a84c;
            --teal: #0d9488;
            --light-teal: #99f6e4;
            --off-white: #f8f7f4;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--off-white);
        }

        h1,
        h2,
        h3,
        .font-display {
            font-family: 'Playfair Display', serif;
        }

        /* NAV */
        nav {
            background: #fff;
            box-shadow: 0 2px 8px rgba(10, 31, 68, .08);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        /* HERO */
        .hero-bg {
            background: linear-gradient(120deg, rgba(10, 31, 68, .82) 40%, rgba(13, 148, 136, .55) 100%),
                url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1400&q=80') center/cover no-repeat;
            min-height: 460px;
        }

        /* REGISTER BANNER */
        .register-banner {
            background: linear-gradient(90deg, #0a1f44 0%, #0d9488 60%, #a3e635 100%);
        }

        /* SECTION HEADING */
        .section-heading {
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            font-size: .82rem;
            color: #64748b;
            border-bottom: 3px solid var(--gold);
            display: inline-block;
            padding-bottom: 4px;
            margin-bottom: 1.5rem;
        }

        /* CARDS */
        .news-card {
            transition: transform .2s, box-shadow .2s;
        }

        .news-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(10, 31, 68, .12);
        }

        .event-row {
            border-left: 4px solid var(--teal);
        }

        .job-row {
            border-left: 4px solid var(--gold);
        }

        /* TESTIMONIAL */
        .testimonial-bg {
            background: linear-gradient(135deg, #0a1f44 0%, #0d9488 100%);
        }

        /* GALLERY */
        .gallery-img {
            aspect-ratio: 1/1;
            object-fit: cover;
            border-radius: 6px;
            transition: transform .2s;
        }

        .gallery-img:hover {
            transform: scale(1.04);
        }

        /* MEMBER AVATAR */
        .member-avatar {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e2e8f0;
        }

        /* FOOTER */
        footer {
            background: #0a1f44;
        }

        /* CAROUSEL ARROW */
        .arrow-btn {
            background: rgba(255, 255, 255, .18);
            border: none;
            cursor: pointer;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            transition: background .2s;
        }

        .arrow-btn:hover {
            background: rgba(255, 255, 255, .38);
        }

        /* SLIDE DOTS */
        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .4);
            display: inline-block;
            margin: 0 3px;
        }

        .dot.active {
            background: #fff;
        }
    </style>
</head>

<body class="antialiased">

    {{-- ─────────── NAVBAR ─────────── --}}
    <nav>
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-16">
            {{-- Logo --}}
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-full bg-navy flex items-center justify-center"
                    style="background:var(--navy)">
                    <span class="text-white font-display font-bold text-xs">SRU</span>
                </div>
                <div class="leading-tight">
                    <p class="font-bold text-sm" style="color:var(--navy)">SR University</p>
                    <p class="text-xs text-gray-400">Alumni Association</p>
                </div>
            </div>

            {{-- Nav Links --}}
            <ul class="hidden md:flex items-center gap-6 text-xs font-semibold tracking-widest uppercase text-gray-600">
                @foreach(['Home', 'About', 'Events', 'Newsroom', 'Directory', 'Giving', 'Contact'] as $link)
                    <li>
                        <a href="#"
                            class="hover:text-teal-600 transition {{ $loop->first ? 'text-teal-600 border-b-2 border-teal-500 pb-0.5' : '' }}">
                            {{ $link }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <a href="/login"
                class="hidden md:inline-block bg-navy text-white text-xs font-semibold px-4 py-2 rounded-full hover:opacity-90 transition"
                style="background:var(--navy)">
                Login / Register
            </a>
        </div>
    </nav>

    {{-- ─────────── HERO ─────────── --}}
    <section class="hero-bg flex items-center relative overflow-hidden">
        {{-- Prev arrow --}}
        <button class="arrow-btn absolute left-4 top-1/2 -translate-y-1/2 z-10">&#8592;</button>

        <div class="max-w-6xl mx-auto px-12 py-20 w-full">
            <h1 class="text-white text-4xl md:text-5xl font-display leading-tight mb-6">
                Our Journey,<br>
                <span style="color:var(--gold)">Your Legacy.</span><br>
                Reconnect &amp; Grow.
            </h1>
            <a href="#"
                class="inline-block bg-yellow-500 hover:bg-yellow-400 text-white font-semibold text-sm px-7 py-3 rounded-full transition shadow-lg"
                style="background:var(--gold)">
                Join Alumni Portal
            </a>
        </div>

        {{-- Next arrow --}}
        <button class="arrow-btn absolute right-4 top-1/2 -translate-y-1/2 z-10">&#8594;</button>

        {{-- Dots --}}
        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex">
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </section>

    {{-- ─────────── REGISTER BANNER ─────────── --}}
    <section class="register-banner">
        <div class="max-w-6xl mx-auto px-6 py-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-white">
                <p class="text-sm font-light opacity-80">Are you an SRU Alumnus?</p>
                <h2 class="text-2xl md:text-3xl font-display font-bold leading-tight">
                    Join Our Ever-Growing<br>Alumni Network
                </h2>
            </div>
            <a href="#"
                class="bg-white text-navy font-bold text-sm tracking-widest uppercase px-10 py-3 rounded hover:bg-gray-100 transition shadow"
                style="color:var(--navy)">
                Register
            </a>
        </div>
    </section>

    {{-- ─────────── NEWSROOM ─────────── --}}
    <section class="max-w-6xl mx-auto px-6 py-14">
        <div class="text-center mb-8">
            <span class="section-heading">Newsroom Section</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                        ['Alumni Meet 2024: Highlights', '6 hours ago'],
                        ['Start-up Spotlight: SRU Innovators', '6 hours ago'],
                        ['School of Business Newsletter Vol. 3', '6 hours ago'],
                        ['School of Business Newsletter Vol. 3', '6 hours ago'],
                    ] as $news)
                  <div class="news-card bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 cu rsor-pointer">
                        {{-- Placeholder banner --}}
                        <div class="h-40 flex items-center justify-center" style="background: linear-gradient(135deg, #e0f2fe, #ccfbf1)">
                            <svg class="w-12 h-12 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h4l2 3h8a2 2 0 012 2v9a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="p-4">
                            <p class="font-semibold text-sm text-gray-800 leading-snug mb-1">{{ $news[0] }}</p>
                            <p class="text-xs text-gray-400">{{ $news[1] }}</p>
                    </div>
                    </div>
            @endforeach
        </div>
    </section>
    
    {{-- ─────────── UPCOMING EVENTS & JOBS ─────────── --}}
<section class="bg-white border-y border-gray-100 py-14">
        <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-8">
                <span class="section-heading">Upcoming Events &amp; Jobs</span>
            </div>
    
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
    
                {{-- ── EVENTS COLUMN ── --}}
                <div>
                    <h3 class="text-base font-bold text-gray-700 mb-4 flex items-center gap-2">
                        <spa
                           n class="inline-block w-3 h-3 rounded-full bg-teal-500"></span>
                        Upcoming Events
                    </h3>
                    <div class="flex flex-col gap-4">
                        @foreach([
                                ['Networking Mixer', 'Oct 12', 'Sangareddy Campus, Hyderabad'],
                                ['Annual Homecoming', 'Nov 5', 'Main Auditorium, SRU'],
                                ['Webinar: Future of Tech', 'Oct 20', 'Online – Zoom'],
                            ] as $event)
                            <div class="event-row bg-gray-50 rounded-lg px-5 py-4 flex items-center justify-between hover:shadow-md transition">
                                <div>
                                    <p class="font-semibold text-sm text-gray-800">{{ $event[0] }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $event[2] }}</p>
                                </div>
                                <span class="text-xs font-bold px-3 py-1.5 rounded-full bg-teal-50 text-teal-700 whitespace-nowrap">
                                    {{ $event[1] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
    

                                           {{-- ── JOBS COLUMN ── --}}
                <div>
                    <h3 class="text-base font-bold text-gray-700 mb-4 flex items-center gap-2">
                        <span class="inline-block w-3 h-3 rounded-full bg-yellow-500"></span>
                        Curated J
                               ob Openings from Partners
                    </h3>
                    <div class="flex flex-col gap-4">
                        @foreach([
                                ['Technical Lead', 'TechCorp', 'Full-time · Hyderabad'],
                                ['Marketing Manager', 'ArcelorMittal', 'Full-time · Mumbai'],
                                ['Marketing Manager', 'ArcelorMittal', 'Contract · Pune'],
                                ['Marketing Manager', 'ArcelorMittal', 'Remote · Bangalore'],
                            ] as $job)
                            <div class="job-row bg-gray-50 rounded-lg px-5 py-4 flex items-center justify-between hover:shadow-md transition">
                                <div>
                                <p class="font-semibold text-sm text-gray-800">{{ $job[0] }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $job[2] }}</p>
                                </div>
                                <span class="text-xs font-bold px-3 py-1.5 rounded-full bg-yellow-50 text-yellow-700 whitespace-nowrap">
                                    {{ $job[1] }}
                                </span>

                                           </div>
                        @endforeach
                    </div>
            </div>
    
            </div>
    </div>
    </section>
    
    {{-- ─────────── TESTIMONIAL CAROUSEL ─────────── --}}
    <section class="testimonial-bg py-14">
        <div class="max-w-6xl mx
                               -auto px-6">
            <div class="text-center mb-8">
                <span class="text-white font-bold tracking-widest uppercase text-xs border-b-2 pb-1" style="border-color:var(--gold)">
                    Testimonial Carousel
                </span>
            </div>
    
            <div class="relative flex items-center gap-2">
                <button class="arrow-btn flex-shrink-0">&#8592;</button>
    

                                           <div class="flex gap-6 overflow-hidden w-full">
                    @foreach([
                            ['Priya Sharma', "'12", 'B.Tech'],
                            ['Priya Sharma', "'12", 'MBA'],
                            ['Priya Sharma', "'12", 'B.Com'],
                        ] as $t)
                        <div class="flex-1 bg-white/10 backdrop-blur rounded-xl p-6 text-white min-w-0">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-14 h-14 rounded-full bg-teal-300 flex-shrink-0 flex items-center justify-center text-teal-900 font-bold text-lg">
                                {{ substr($t[0], 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-sm">{{ $t[0] }} {{ $t[1] }}</p>
                                    <p class="text-xs opacity-60">{{ $t[2] }}</p>
                                </div>
                        </div>
                            <p class="text-sm opacity-90 leading-relaxed italic">
                                "SRU provided the platform for my career growth — the network and mentors here are unmatched."
                        </p>
                        </div>
                    @endforeach
                </di
                   v>
    
                <button class="arrow-btn flex-shrink-0">&#8594;</button>
          </div>
         </div>
    </section>
    
    {{-- ─────────── GALLERY CAROUSEL ─────────── --}}
<section class="max-w-6xl mx-auto px-6 py-14">
        <div class="text-center mb-8">
            <span class="section-heading">Gallery Carousel</span>
        </div>

        <div class="relative flex items-center gap-3">
            <button class="arrow-btn bg-gray-200 text-gray-700 flex-shrink-0">&#8592;</button>
    
            <div class="grid grid-cols-3 sm:grid-cols-5 gap-3 flex-1">
                @for($i = 0; $i < 10; $i++)
                        <div class="gallery-img bg-gradient-to-br from-teal-100 to-blue-100 flex items-center justify-center rounded-lg overflow-hidden aspect-square">
                        <svg class="w-8 h-8 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                    </div>
                @endfor
        </div>
    
            <button class="arrow-btn bg-gray-200 text-gray-700 flex-shrink-0">&#8594;</button>
        </div>
    </section>
    
    {{-- ─────────── MEMBER IMAGES ─────────── --}}
    <section class="bg-white border-t border-gray-100 py-12">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-8">
                <span class="section-heading">Member Images</span>
        </div>
    
            <div class="flex flex-wrap justify-center gap-4">
                @for($i = 0; $i < 20; $i++)
                    @php
                        $colors = ['bg-blue-100', 'bg-teal-100', 'bg-yellow-100', 'bg-rose-100', 'bg-purple-100'];
                        $c = $colors[$i % count($colors)];
                        $names = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T'];
                    @endphp
                        <div class="flex flex-col items-center gap-1">
                            <div class="member-avatar {{ $c }} flex items-center justify-center font-bold text-gray-600">
                                {{ $names[$i] }}
                            </div>
                        </div>
                @endfor
            </div>

                               </div>
    </section>
    
    {{-- ─────────── FOOTER ─────────── --}}
    <footer class="py-10">
    <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-10 text-sm text-gray-300">
    
            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                        <span class="text-white font-display font-bold text-xs">SRU</span>
                    </div>
                    <span class="text-white font-display text-lg font-bold">SRU</span>
                </div>
            <div class="flex gap-3 mt-4">
                    @foreach(['f', 'in', 'tw', 'ig'] as $s)
                        <a href="#" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white text-xs hover:bg-white/20 transition">
                            {{ strtoupper(substr($s, 0, 1)) }}
                        </a>
                    @endforeach
                </div>
            </div>
    
        {{-- Quick Links --}}
            <div>
                <p class="font-bold text-white mb-3 tracking-widest uppercase text-xs">Quick Links</p>
                <ul class="space-y-1.5">
                    @foreach(['Home', 'About', 'News', 'Campus Map', 'Directory', 'Giving', 'Contact'] as $l)
                        <li><a href="#" class="hover:text-white transition">{{ $l }}</a></li>

                     @endforeach
            </ul>
        </div>

        {{-- Campus Map --}}
        <div>
            <p class="font-bold text-white mb-3 tracking-widest uppercase text-xs">Campus Map</p>
            <div class="w-full h-32 rounded-lg bg-white/10 flex items-center justify-center text-white/50 text-xs">
                Map Embed
            </div>
        </div>
    </div>

    <div class="text-center text-gray-500 text-xs mt-10 border-t border-white/10 pt-6">
        © 2024 SR University Alumni Association. All Rights Reserved.
    </div>
</footer>

</body>
</html>