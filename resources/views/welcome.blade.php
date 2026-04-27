<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SR University Alumni Association</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --navy: #0a1f44;
            --gold: #c9a84c;
            --teal: #0d9488;
            --mint: #ccfbf1;
            --off: #f8f7f4;
            --ink: #172034;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background: radial-gradient(circle at 10% 0%, #ffffff 0%, #f8f7f4 55%);
            color: var(--ink);
        }

        .font-display {
            font-family: 'Playfair Display', serif;
        }

        .site-shell {
            background-image: linear-gradient(120deg, rgba(201, 168, 76, 0.06) 0%, rgba(13, 148, 136, 0.05) 60%, rgba(10, 31, 68, 0.05) 100%);
        }

        .section-heading {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            font-weight: 800;
            color: #5b677d;
            border-bottom: 3px solid var(--gold);
            display: inline-block;
            padding-bottom: 6px;
        }

        .glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.35);
        }

        .hover-lift {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 35px rgba(15, 23, 42, 0.12);
        }

        .carousel {
            position: relative;
        }

        .carousel-track {
            display: flex;
            transition: transform 0.55s ease;
        }

        .carousel-slide {
            min-width: 100%;
        }

        .arrow-btn {
            width: 40px;
            height: 40px;
            border-radius: 9999px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.1rem;
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .arrow-btn:hover {
            transform: translateY(-1px);
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 9999px;
            border: none;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.45);
            transition: all 0.2s ease;
        }

        .dot.active {
            width: 24px;
            background: #ffffff;
        }

        .reveal {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body class="antialiased">
    @php
        $newsItems = [
            ['Alumni Meet 2026 Highlights', '6 hours ago'],
            ['Startup Spotlight: SRU Innovators', '1 day ago'],
            ['School of Business Newsletter Vol. 4', '2 days ago'],
            ['Research Grants Announced for Alumni Mentors', '3 days ago'],
        ];

        $eventItems = [
            ['Networking Mixer', 'Oct 12', 'Sangareddy Campus, Hyderabad'],
            ['Annual Homecoming', 'Nov 05', 'Main Auditorium, SRU'],
            ['Webinar: Future of Tech', 'Oct 20', 'Online - Zoom'],
        ];

        $jobItems = [
            ['Technical Lead', 'TechCorp', 'Full-time · Hyderabad'],
            ['Marketing Manager', 'ArcelorMittal', 'Full-time · Mumbai'],
            ['Data Engineer', 'Aster Labs', 'Contract · Pune'],
            ['Product Analyst', 'Bluestone Digital', 'Remote · Bangalore'],
        ];

        $testimonialItems = [
            ['Priya Sharma', "'12", 'B.Tech', 'SRU gave me mentors, confidence, and a global network that still supports my work.'],
            ['Rahul Verma', "'14", 'MBA', 'The alumni ecosystem helped me hire my first core team and scale faster.'],
            ['Megha Iyer', "'16", 'B.Com', 'Events and peer circles made career transitions easy and structured.'],
        ];
    @endphp

    <div class="site-shell min-h-screen">
        <nav class="sticky top-0 z-50 bg-white/95 border-b border-slate-200/80 backdrop-blur">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="/" class="flex items-center gap-3">
                    <span
                        class="w-10 h-10 rounded-full inline-flex items-center justify-center text-white font-bold text-sm"
                        style="background: var(--navy)">SRU</span>
                    <span>
                        <span class="block text-sm font-bold" style="color: var(--navy)">SR University</span>
                        <span class="block text-[11px] text-slate-500">Alumni Association</span>
                    </span>
                </a>

                <button id="mobile-menu-btn" class="md:hidden text-slate-700 font-semibold">Menu</button>

                <ul class="hidden md:flex items-center gap-6 text-xs font-bold tracking-wider uppercase text-slate-600">
                    <li><a class="hover:text-teal-600" href="/">Home</a></li>
                    <li><a class="hover:text-teal-600" href="#about">About</a></li>
                    <li><a class="hover:text-teal-600" href="/events">Events</a></li>
                    <li><a class="hover:text-teal-600" href="/newsroom">Newsroom</a></li>
                    <li><a class="hover:text-teal-600" href="#directory">Directory</a></li>
                    <li><a class="hover:text-teal-600" href="#giving">Giving</a></li>
                    <li><a class="hover:text-teal-600" href="#contact">Contact</a></li>
                </ul>

                <div>
                    @auth
                        @php
                            $displayName = auth()->user()->name ?? 'Profile';
                            $avatarInitial = strtoupper(substr($displayName, 0, 1));
                        @endphp
                        <a href="{{ route('profile') }}"
                            class="hidden md:inline-flex items-center gap-2 px-3 py-2 rounded-full border text-xs font-semibold"
                            style="border-color: #dbe5f5; color: var(--navy); background: #ffffff;">
                            <span class="w-6 h-6 rounded-full inline-flex items-center justify-center text-white text-[11px] font-bold"
                                style="background: var(--navy)">{{ $avatarInitial }}</span>
                            <span class="max-w-[130px] truncate">{{ $displayName }}</span>
                        </a>
                    @else
                        <a href="/register"
                            class="hidden md:inline-flex items-center px-4 py-2 rounded-full text-white text-xs font-semibold"
                            style="background: var(--navy)">
                            Register
                        </a>
                        <a href="/login"
                            class="hidden md:inline-flex items-center px-4 py-2 rounded-full text-white text-xs font-semibold"
                            style="background: var(--navy)">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
            <div id="mobile-menu" class="md:hidden hidden border-t border-slate-200 bg-white">
                <div class="px-4 py-3 space-y-2 text-sm font-semibold text-slate-700">
                    <a href="/" class="block">Home</a>
                    <a href="#about" class="block">About</a>
                    <a href="/events" class="block">Events</a>
                    <a href="/newsroom" class="block">Newsroom</a>
                    <a href="#directory" class="block">Directory</a>
                    <a href="#giving" class="block">Giving</a>
                    <a href="#contact" class="block">Contact</a>
                    @auth
                        @php
                            $mobileDisplayName = auth()->user()->name ?? 'Profile';
                            $mobileAvatarInitial = strtoupper(substr($mobileDisplayName, 0, 1));
                        @endphp
                        <a href="{{ route('profile') }}"
                            class="inline-flex items-center gap-2 mt-2 px-4 py-2 rounded-full border"
                            style="border-color: #dbe5f5; color: var(--navy); background: #ffffff;">
                            <span class="w-6 h-6 rounded-full inline-flex items-center justify-center text-white text-[11px] font-bold"
                                style="background: var(--navy)">{{ $mobileAvatarInitial }}</span>
                            <span class="max-w-[160px] truncate">{{ $mobileDisplayName }}</span>
                        </a>
                    @else
                        <a href="/register" class="inline-block mt-2 px-4 py-2 rounded-full text-white"
                            style="background: var(--navy)">Register</a>
                        <a href="/login" class="inline-block mt-2 px-4 py-2 rounded-full text-white"
                            style="background: var(--navy)">Login</a>
                    @endauth
                </div>
            </div>
        </nav>

        <section class="relative overflow-hidden">
            <div class="carousel" data-carousel data-autoplay="5500" id="hero-carousel">
                <div class="overflow-hidden">
                    <div class="carousel-track">
                        <article class="carousel-slide min-h-[500px] md:min-h-[560px] flex items-center"
                            style="background: linear-gradient(110deg, rgba(10,31,68,.86), rgba(13,148,136,.58)), url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1600&q=80') center/cover no-repeat;">
                            <div class="max-w-7xl mx-auto px-6 sm:px-10 w-full reveal">
                                <h1
                                    class="font-display text-white text-4xl sm:text-5xl md:text-6xl leading-tight max-w-3xl">
                                    Our Journey, <span style="color: var(--gold)">Your Legacy.</span> Reconnect and
                                    Grow.</h1>
                                <p class="text-white/85 mt-5 max-w-xl">Meet alumni, discover opportunities, and give
                                    back to the next generation of SRU leaders.</p>
                                <div class="mt-8 flex flex-wrap gap-3">
                                    <a href="/login" class="px-7 py-3 rounded-full text-sm font-bold text-white"
                                        style="background: var(--gold)">Join Alumni Portal</a>
                                    <a href="/newsroom"
                                        class="px-7 py-3 rounded-full text-sm font-bold bg-white/15 text-white border border-white/30">Explore
                                        Newsroom</a>
                                </div>
                            </div>
                        </article>

                        <article class="carousel-slide min-h-[500px] md:min-h-[560px] flex items-center"
                            style="background: linear-gradient(110deg, rgba(10,31,68,.86), rgba(13,148,136,.58)), url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1600&q=80') center/cover no-repeat;">
                            <div class="max-w-7xl mx-auto px-6 sm:px-10 w-full reveal">
                                <h2
                                    class="font-display text-white text-4xl sm:text-5xl md:text-6xl leading-tight max-w-3xl">
                                    Build Meaningful Connections Across Batches.</h2>
                                <p class="text-white/85 mt-5 max-w-xl">From mentorship to partnerships, your alumni
                                    network can be your strongest advantage.</p>
                            </div>
                        </article>

                        <article class="carousel-slide min-h-[500px] md:min-h-[560px] flex items-center"
                            style="background: linear-gradient(110deg, rgba(10,31,68,.86), rgba(13,148,136,.58)), url('https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=1600&q=80') center/cover no-repeat;">
                            <div class="max-w-7xl mx-auto px-6 sm:px-10 w-full reveal">
                                <h2
                                    class="font-display text-white text-4xl sm:text-5xl md:text-6xl leading-tight max-w-3xl">
                                    Careers, Events, and Stories in One Place.</h2>
                                <p class="text-white/85 mt-5 max-w-xl">Stay updated through jobs, curated events, and
                                    campus stories from across the world.</p>
                            </div>
                        </article>
                    </div>
                </div>

                <button class="arrow-btn absolute left-4 md:left-8 top-1/2 -translate-y-1/2 text-white bg-white/20"
                    data-prev type="button">&#8592;</button>
                <button class="arrow-btn absolute right-4 md:right-8 top-1/2 -translate-y-1/2 text-white bg-white/20"
                    data-next type="button">&#8594;</button>

                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2" data-dots></div>
            </div>
        </section>

        <!-- <section class="bg-slate-900 text-white">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 py-2.5 flex items-center justify-center gap-2 text-xs sm:text-sm">
                <span class="uppercase tracking-[0.12em] text-white/75">Next photo in</span>
                <span data-carousel-timer="hero-carousel" class="font-bold px-2 py-0.5 rounded bg-white/20">6s</span>
            </div>
        </section> -->

        <section id="about" class="bg-gradient-to-r from-slate-900 via-teal-700 to-lime-500">
            <div
                class="max-w-7xl mx-auto px-6 sm:px-8 py-8 md:py-9 flex flex-col md:flex-row items-start md:items-center justify-between gap-5 text-white">
                <div class="reveal">
                    <p class="text-sm text-white/80">Are you an SRU alumnus?</p>
                    <h2 class="font-display text-3xl leading-tight">Join Our Ever-Growing Alumni Network</h2>
                </div>
                <a href="/register" class="shrink-0 bg-white text-sm font-extrabold px-8 py-3 rounded-md tracking-wide"
                    style="color: var(--navy)">Register Now</a>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-6 sm:px-8 py-16">
            <div class="text-center mb-10 reveal">
                <span class="section-heading">Newsroom</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                @foreach($newsItems as $item)
                    <article class="glass rounded-2xl overflow-hidden hover-lift reveal">
                        <div
                            class="h-40 bg-gradient-to-br from-sky-100 via-cyan-100 to-teal-100 flex items-center justify-center">
                            <span class="text-sm font-bold text-teal-700">SRU Update</span>
                        </div>
                        <div class="p-5">
                            <h3 class="font-semibold text-[15px] leading-6">{{ $item[0] }}</h3>
                            <p class="text-xs text-slate-500 mt-2">{{ $item[1] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="py-16 bg-white/80 border-y border-slate-200/70">
            <div class="max-w-7xl mx-auto px-6 sm:px-8">
                <div class="text-center mb-10 reveal">
                    <span class="section-heading">Upcoming Events and Jobs</span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="glass rounded-2xl p-6 reveal">
                        <h3 class="font-bold text-slate-800 mb-5 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
                            Upcoming Events
                        </h3>
                        <div class="space-y-4">
                            @foreach($eventItems as $event)
                                <div
                                    class="border-l-4 border-teal-500 bg-slate-50 rounded-r-xl px-5 py-4 flex items-center justify-between gap-4 hover-lift">
                                    <div>
                                        <p class="font-semibold text-sm">{{ $event[0] }}</p>
                                        <p class="text-xs text-slate-500 mt-1">{{ $event[2] }}</p>
                                    </div>
                                    <span
                                        class="text-xs font-bold px-3 py-1.5 rounded-full bg-teal-50 text-teal-700 whitespace-nowrap">{{ $event[1] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div id="directory" class="glass rounded-2xl p-6 reveal">
                        <h3 class="font-bold text-slate-800 mb-5 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full" style="background: var(--gold)"></span>
                            Curated Job Openings from Partners
                        </h3>
                        <div class="space-y-4">
                            @foreach($jobItems as $job)
                                <div class="border-l-4 rounded-r-xl px-5 py-4 flex items-center justify-between gap-4 hover-lift"
                                    style="border-color: var(--gold); background: #fffaf0;">
                                    <div>
                                        <p class="font-semibold text-sm">{{ $job[0] }}</p>
                                        <p class="text-xs text-slate-500 mt-1">{{ $job[2] }}</p>
                                    </div>
                                    <span class="text-xs font-bold px-3 py-1.5 rounded-full"
                                        style="background: #fff2cf; color: #8d6a00;">{{ $job[1] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16" style="background: linear-gradient(135deg, #0a1f44 0%, #0d9488 100%);">
            <div class="max-w-7xl mx-auto px-6 sm:px-8">
                <div class="text-center mb-10 reveal">
                    <span
                        class="inline-block text-white text-xs tracking-[0.14em] uppercase font-extrabold border-b-2 pb-1"
                        style="border-color: var(--gold)">Testimonial Carousel</span>
                </div>

                <div class="carousel" data-carousel data-autoplay="6500">
                    <div class="overflow-hidden rounded-2xl">
                        <div class="carousel-track">
                            @foreach($testimonialItems as $testimonial)
                                <article class="carousel-slide">
                                    <div
                                        class="glass bg-white/10 text-white p-8 md:p-10 min-h-[280px] flex flex-col justify-center">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-14 h-14 rounded-full bg-teal-300 text-teal-900 font-bold text-xl inline-flex items-center justify-center">
                                                {{ strtoupper(substr($testimonial[0], 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold">{{ $testimonial[0] }} {{ $testimonial[1] }}</p>
                                                <p class="text-sm text-white/80">{{ $testimonial[2] }}</p>
                                            </div>
                                        </div>
                                        <p class="mt-6 text-lg leading-8 text-white/95">"{{ $testimonial[3] }}"</p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <button class="arrow-btn absolute left-3 top-1/2 -translate-y-1/2 bg-white/20 text-white" data-prev
                        type="button">&#8592;</button>
                    <button class="arrow-btn absolute right-3 top-1/2 -translate-y-1/2 bg-white/20 text-white" data-next
                        type="button">&#8594;</button>
                    <div class="mt-5 flex justify-center gap-2" data-dots></div>
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-6 sm:px-8 py-16">
            <div class="text-center mb-10 reveal">
                <span class="section-heading">Gallery Carousel</span>
            </div>

            <div class="carousel reveal" data-carousel data-autoplay="5000">
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white p-4">
                    <div class="carousel-track">
                        @for($page = 0; $page < 3; $page++)
                            <div class="carousel-slide grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                                @for($i = 0; $i < 10; $i++)
                                    <div
                                        class="aspect-square rounded-lg bg-gradient-to-br from-teal-100 to-sky-100 flex items-center justify-center text-teal-700 text-xs font-semibold">
                                        Campus {{ ($page * 10) + $i + 1 }}</div>
                                @endfor
                            </div>
                        @endfor
                    </div>
                </div>

                <button class="arrow-btn absolute left-2 top-1/2 -translate-y-1/2 bg-slate-800 text-white" data-prev
                    type="button">&#8592;</button>
                <button class="arrow-btn absolute right-2 top-1/2 -translate-y-1/2 bg-slate-800 text-white" data-next
                    type="button">&#8594;</button>
                <div class="mt-5 flex justify-center gap-2" data-dots></div>
            </div>
        </section>

        <section id="giving" class="bg-white/90 border-t border-slate-200 py-14">
            <div class="max-w-7xl mx-auto px-6 sm:px-8">
                <div class="text-center mb-10 reveal">
                    <span class="section-heading">Member Images</span>
                </div>
                <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-10 gap-4">
                    @for($i = 0; $i < 20; $i++)
                        @php
                            $colors = ['bg-blue-100', 'bg-teal-100', 'bg-yellow-100', 'bg-rose-100', 'bg-emerald-100'];
                            $letters = range('A', 'T');
                        @endphp
                        <div
                            class="w-14 h-14 sm:w-16 sm:h-16 rounded-full {{ $colors[$i % count($colors)] }} border border-slate-200 flex items-center justify-center font-bold text-slate-700 mx-auto reveal">
                            {{ $letters[$i] }}
                        </div>
                    @endfor
                </div>
            </div>
        </section>

        <footer id="contact" class="bg-slate-900 text-slate-300 py-12">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 grid grid-cols-1 md:grid-cols-3 gap-10">
                <div>
                    <div class="flex items-center gap-3">
                        <span
                            class="w-10 h-10 rounded-full bg-white/10 inline-flex items-center justify-center text-white font-bold text-sm">SRU</span>
                        <div>
                            <p class="text-white font-display text-xl">SRU</p>
                            <p class="text-xs text-slate-400">Alumni Association</p>
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-slate-400">Building lifelong bonds through opportunities, mentorship,
                        and alumni-led initiatives.</p>
                </div>

                <div>
                    <p class="text-white text-xs tracking-[0.14em] uppercase font-bold mb-3">Quick Links</p>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/" class="hover:text-white">Home</a></li>
                        <li><a href="/newsroom" class="hover:text-white">Newsroom</a></li>
                        <li><a href="/events" class="hover:text-white">Events</a></li>
                        <li><a href="#directory" class="hover:text-white">Directory</a></li>
                    </ul>
                </div>

                <div>
                    <p class="text-white text-xs tracking-[0.14em] uppercase font-bold mb-3">Campus Map</p>
                    <div
                        class="h-32 rounded-lg bg-white/10 border border-white/10 flex items-center justify-center text-xs text-slate-400">
                        Map Embed Placeholder
                    </div>
                </div>
            </div>
            <div class="text-center text-xs text-slate-500 mt-10 border-t border-white/10 pt-6">
                &copy; 2026 SR University Alumni Association. All rights reserved.
            </div>
        </footer>
    </div>

    <script>
        const menuButton = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (menuButton && mobileMenu) {
            menuButton.addEventListener('click', function () {
                mobileMenu.classList.toggle('hidden');
            });
        }

        function setupCarousel(root) {
            const track = root.querySelector('.carousel-track');
            const slides = Array.from(root.querySelectorAll('.carousel-slide'));
            const prevBtn = root.querySelector('[data-prev]');
            const nextBtn = root.querySelector('[data-next]');
            const dotsContainer = root.querySelector('[data-dots]');
            const autoplayMs = Number(root.dataset.autoplay || 0);
            const timerBadge = root.id ? document.querySelector('[data-carousel-timer="' + root.id + '"]') : null;

            if (!track || slides.length < 2) {
                return;
            }

            let index = 0;
            let timerId = null;
            let countdownId = null;
            let countdownMs = autoplayMs;

            const dots = slides.map(function (_, dotIndex) {
                if (!dotsContainer) {
                    return null;
                }

                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'dot' + (dotIndex === 0 ? ' active' : '');
                button.setAttribute('aria-label', 'Go to slide ' + (dotIndex + 1));
                button.addEventListener('click', function () {
                    goTo(dotIndex);
                    restartAutoplay();
                });
                dotsContainer.appendChild(button);
                return button;
            });

            function update() {
                track.style.transform = 'translateX(-' + (index * 100) + '%)';
                dots.forEach(function (dot, dotIndex) {
                    if (dot) {
                        dot.classList.toggle('active', dotIndex === index);
                    }
                });
            }

            function goTo(nextIndex) {
                index = (nextIndex + slides.length) % slides.length;
                update();
            }

            function startAutoplay() {
                if (autoplayMs <= 0) {
                    return;
                }

                countdownMs = autoplayMs;
                updateTimerText();

                timerId = window.setInterval(function () {
                    goTo(index + 1);
                    countdownMs = autoplayMs;
                    updateTimerText();
                }, autoplayMs);

                countdownId = window.setInterval(function () {
                    countdownMs = Math.max(0, countdownMs - 250);
                    updateTimerText();
                }, 250);
            }

            function stopAutoplay() {
                if (timerId) {
                    window.clearInterval(timerId);
                    timerId = null;
                }

                if (countdownId) {
                    window.clearInterval(countdownId);
                    countdownId = null;
                }
            }

            function updateTimerText() {
                if (!timerBadge || autoplayMs <= 0) {
                    return;
                }

                const seconds = Math.max(1, Math.ceil(countdownMs / 1000));
                timerBadge.textContent = seconds + 's';
            }

            function restartAutoplay() {
                stopAutoplay();
                startAutoplay();
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', function () {
                    goTo(index - 1);
                    restartAutoplay();
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function () {
                    goTo(index + 1);
                    restartAutoplay();
                });
            }

            root.addEventListener('mouseenter', stopAutoplay);
            root.addEventListener('mouseleave', startAutoplay);

            update();
            startAutoplay();
        }

        document.querySelectorAll('[data-carousel]').forEach(setupCarousel);

        const revealItems = document.querySelectorAll('.reveal');
        const revealObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.16 });

        revealItems.forEach(function (item) {
            revealObserver.observe(item);
        });
    </script>
</body>

</html>