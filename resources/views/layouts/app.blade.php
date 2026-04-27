<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SRU Alumni')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#f2f0e8] text-slate-800">
    <header>
        <nav class="sticky top-0 z-50 bg-white/95 border-b border-slate-200/80 backdrop-blur">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-full inline-flex items-center justify-center text-white font-bold text-sm bg-[#0a1f44]">SRU</span>
                    <span>
                        <span class="block text-sm font-bold text-[#0a1f44]">SR University</span>
                        <span class="block text-[11px] text-slate-500">Alumni Association</span>
                    </span>
                </a>

                <button id="mobile-menu-btn" class="md:hidden text-slate-700 font-semibold" type="button">Menu</button>

                <ul class="hidden md:flex items-center gap-6 text-xs font-bold tracking-wider uppercase text-slate-600">
                    <li><a class="hover:text-teal-600" href="{{ url('/') }}">Home</a></li>
                    <li><a class="hover:text-teal-600" href="{{ route('about') }}">About</a></li>
                    <li><a class="hover:text-teal-600" href="{{ route('events.index') }}">Events</a></li>
                    <li><a class="hover:text-teal-600" href="{{ route('newsroom') }}">Newsroom</a></li>
                    <li><a class="hover:text-teal-600" href="{{ route('gallery') }}">Gallery</a></li>
                    <li><a class="hover:text-teal-600" href="{{ route('engage') }}">Engage</a></li>
                    <li><a class="hover:text-teal-600" href="{{ route('contact') }}">Contact</a></li>
                </ul>

                <div>
                    @auth
                        @php
                            $user = auth()->user();
                            $profileName = trim((string) ($user->profile?->full_name ?? ''));
                            $accountName = trim((string) ($user->name ?? ''));
                            $displayName = ($profileName !== '' && ($accountName === '' || $accountName === 'Alumni User'))
                                ? $profileName
                                : ($accountName !== '' ? $accountName : 'Profile');
                            $avatarInitial = strtoupper(substr($displayName, 0, 1));
                        @endphp
                        <a href="{{ route('profile') }}"
                            class="hidden md:inline-flex items-center gap-2 px-3 py-2 rounded-full border text-xs font-semibold border-[#dbe5f5] text-[#0a1f44] bg-white">
                            <span class="w-6 h-6 rounded-full inline-flex items-center justify-center text-white text-[11px] font-bold bg-[#0a1f44]">{{ $avatarInitial }}</span>
                            <span class="max-w-[130px] truncate">{{ $displayName }}</span>
                        </a>
                        <form method="POST" action="/logout" class="hidden md:inline">
                            @csrf
                            <button type="submit" class="ml-2 px-3 py-2 rounded-full text-white text-xs font-semibold bg-[#0a1f44] hover:bg-[#c0006a] transition-colors">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('register') }}"
                            class="hidden md:inline-flex items-center px-4 py-2 rounded-full text-white text-xs font-semibold bg-[#0a1f44]">
                            Register
                        </a>
                        <a href="{{ route('login') }}"
                            class="hidden md:inline-flex items-center px-4 py-2 rounded-full text-white text-xs font-semibold bg-[#0a1f44]">
                            Login
                        </a>
                    @endauth
                </div>
            </div>

    <div class="lg:hidden flex items-center space-x-5 text-sm">
        <a href="{{ route('about') }}" class="hover:underline">About</a>
        <a href="{{ route('testimonials.index') }}" class="hover:underline">Testimonials</a>
        <a href="{{ route('gallery') }}" class="hover:underline">Gallery</a>
        <a href="{{ route('engage') }}" class="hover:underline">Engage</a>
        <a href="{{ route('newsroom') }}" class="hover:underline">Newsroom</a>
        <a href="{{ route('events.index') }}" class="hover:underline">Events</a>
        <a href="{{ route('jobs.index') }}" class="hover:underline">Jobs</a>
        <a href="{{ route('contact') }}" class="hover:underline">Contact</a>
        <a href="/profile" class="hover:underline">Profile</a>

        <form method="POST" action="/logout" class="inline">
            @csrf
            <button class="hover:underline">Logout</button>
        </form>
    </div>

</nav>

    <div class="p-6">
        @yield('content')
    </div>

    <script>
        const menuButton = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (menuButton && mobileMenu) {
            menuButton.addEventListener('click', function () {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>

</body>
</html>
