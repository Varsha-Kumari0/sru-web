<nav class="sticky top-0 z-50 bg-white/95 border-b border-slate-200/80 backdrop-blur">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 grid grid-cols-[auto_auto] md:grid-cols-[auto_minmax(0,1fr)_auto] items-center gap-4">
        <a href="{{ url('/') }}" class="flex shrink-0 items-center gap-3">
            <span class="w-10 h-10 rounded-full inline-flex items-center justify-center text-white font-bold text-sm bg-[#0a1f44]">SRU</span>
            <span>
                <span class="block text-sm font-bold text-[#0a1f44]">SR University</span>
                <span class="block text-[11px] text-slate-500">Alumni Association</span>
            </span>
        </a>

        <button id="mobile-menu-btn" class="justify-self-end md:hidden text-slate-700 font-semibold" type="button">Menu</button>

        <ul class="hidden md:flex min-w-0 items-center justify-center gap-3 text-[10px] font-bold tracking-wider uppercase text-slate-600 lg:gap-5 lg:text-[11px] xl:gap-6 xl:text-xs">
            <li><a class="hover:text-teal-600" href="{{ url('/') }}">Home</a></li>
            <li><a class="hover:text-teal-600" href="{{ route('about') }}">About</a></li>
            <li><a class="hover:text-teal-600" href="{{ route('events.index') }}">Events</a></li>
            <li><a class="hover:text-teal-600" href="{{ route('newsroom') }}">Newsroom</a></li>
            <li><a class="hover:text-teal-600" href="{{ route('gallery') }}">Gallery</a></li>
            <li><a class="hover:text-teal-600" href="{{ route('jobs.index') }}">Jobs</a></li>
            <li><a class="hover:text-teal-600" href="{{ route('engage') }}">Engage</a></li>
            <li><a class="hover:text-teal-600" href="{{ route('contact') }}">Contact</a></li>
        </ul>

        <div class="hidden shrink-0 justify-self-end whitespace-nowrap md:flex md:items-center">
            @auth
                @php
                    $user = auth()->user();
                    $profileName = trim((string) ($user->profile?->full_name ?? ''));
                    $accountName = trim((string) ($user->name ?? ''));
                    $displayName = ($profileName !== '' && ($accountName === '' || $accountName === 'Alumni User'))
                        ? $profileName
                        : ($accountName !== '' ? $accountName : 'Profile');
                    $avatarInitial = strtoupper(substr($displayName, 0, 1));
                    $unreadMessageCount = \App\Models\Message::query()
                        ->where('receiver_id', $user->id)
                        ->where('is_read', false)
                        ->count();
                @endphp

                <a href="{{ route('messages.index') }}"
                   class="relative mr-3 inline-flex h-10 w-10 items-center justify-center rounded-full text-[#0a1f44] hover:bg-slate-100"
                   aria-label="Messages">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 6.5h16v11H4z" />
                        <path d="m4 7 8 6 8-6" />
                    </svg>
                    @if($unreadMessageCount > 0)
                        <span class="absolute right-0 top-0 inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-[#f25555] px-1 text-[10px] font-bold leading-none text-white">
                            {{ $unreadMessageCount > 9 ? '9+' : $unreadMessageCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('profile') }}"
                   class="inline-flex items-center gap-2 px-3 py-2 rounded-full border text-xs font-semibold border-[#dbe5f5] text-[#0a1f44] bg-white">
                    <span class="w-6 h-6 rounded-full inline-flex items-center justify-center text-white text-[11px] font-bold bg-[#0a1f44]">{{ $avatarInitial }}</span>
                    <span class="max-w-[90px] truncate lg:max-w-[110px] xl:max-w-[130px]">{{ $displayName }}</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="ml-2 px-3 py-2 rounded-full text-white text-xs font-semibold bg-[#0a1f44] hover:bg-[#c0006a] transition-colors">Logout</button>
                </form>
            @else
                <a href="{{ route('register') }}"
                   class="inline-flex items-center px-4 py-2 rounded-full text-white text-xs font-semibold bg-[#0a1f44]">Register</a>
                <a href="{{ route('login') }}"
                   class="ml-2 inline-flex items-center px-4 py-2 rounded-full text-white text-xs font-semibold bg-[#0a1f44]">Login</a>
            @endauth
        </div>
    </div>

    <div id="mobile-menu" class="md:hidden hidden border-t border-slate-200 bg-white">
        <div class="px-4 py-3 space-y-2 text-sm font-semibold text-slate-700">
            <a href="{{ url('/') }}" class="block">Home</a>
            <a href="{{ route('about') }}" class="block">About</a>
            <a href="{{ route('events.index') }}" class="block">Events</a>
            <a href="{{ route('newsroom') }}" class="block">Newsroom</a>
            <a href="{{ route('gallery') }}" class="block">Gallery</a>
            <a href="{{ route('jobs.index') }}" class="block">Jobs</a>
            <a href="{{ route('engage') }}" class="block">Engage</a>
            <a href="{{ route('contact') }}" class="block">Contact</a>

            @auth
                <a href="{{ route('messages.index') }}" class="block">Messages</a>

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
                   class="inline-flex items-center gap-2 mt-2 px-4 py-2 rounded-full border border-[#dbe5f5] text-[#0a1f44] bg-white">
                    <span class="w-6 h-6 rounded-full inline-flex items-center justify-center text-white text-[11px] font-bold bg-[#0a1f44]">{{ $avatarInitial }}</span>
                    <span class="max-w-[160px] truncate">{{ $displayName }}</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="block mt-2">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 rounded-xl bg-[#0a1f44] text-white font-bold">Logout</button>
                </form>
            @else
                <a href="{{ route('register') }}" class="inline-block mt-2 px-4 py-2 rounded-full text-white bg-[#0a1f44]">Register</a>
                <a href="{{ route('login') }}" class="inline-block mt-2 px-4 py-2 rounded-full text-white bg-[#0a1f44]">Login</a>
            @endauth
        </div>
    </div>

    <script>
        (function () {
            const menuButton = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');

            if (!menuButton || !mobileMenu) {
                return;
            }

            menuButton.addEventListener('click', function () {
                mobileMenu.classList.toggle('hidden');
            });
        })();
    </script>
</nav>
