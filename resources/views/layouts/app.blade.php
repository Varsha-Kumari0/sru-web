<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    @php($hasCookieConsent = request()->hasCookie('sru_cookie_consent'))

    <div id="cookie-consent-panel"
         class="{{ $hasCookieConsent ? 'hidden' : '' }} fixed inset-x-4 bottom-4 z-50 mx-auto max-w-4xl rounded-2xl border border-slate-200 bg-white p-5 shadow-2xl"
         data-cookie-consent-panel>
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#2a9d8f]">Cookie Preferences</p>
                <h2 class="mt-2 text-lg font-bold text-[#1a2d4a]">Choose how SRU Alumni uses cookies</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Essential cookies keep login, CSRF protection, and sessions working. Optional preference cookies can remember harmless interface choices, like dashboard feed density.
                </p>

                <div class="mt-4 hidden rounded-xl bg-slate-50 p-4" data-cookie-custom>
                    <div class="mb-4 grid gap-3 text-xs text-slate-600 md:grid-cols-3">
                        <div class="rounded-lg bg-white p-3">
                            <p class="font-bold text-[#1a2d4a]">Essential</p>
                            <p class="mt-1"><code>laravel_session</code>, <code>XSRF-TOKEN</code> keep login and forms secure.</p>
                        </div>
                        <div class="rounded-lg bg-white p-3">
                            <p class="font-bold text-[#1a2d4a]">Consent record</p>
                            <p class="mt-1"><code>sru_cookie_consent</code>, <code>sru_cookie_preferences</code> remember this choice.</p>
                        </div>
                        <div class="rounded-lg bg-white p-3">
                            <p class="font-bold text-[#1a2d4a]">Optional preference</p>
                            <p class="mt-1"><code>sru_feed_density</code> remembers compact or comfortable dashboard spacing.</p>
                        </div>
                    </div>
                    <label class="flex items-start gap-3">
                        <input type="checkbox" class="mt-1 w-4 h-4 rounded border-slate-300 text-[#2a9d8f] focus:ring-[#2a9d8f]" data-cookie-preferences-checkbox @checked(request()->cookie('sru_cookie_preferences') === '1')>
                        <span>
                            <span class="block text-sm font-bold text-[#1a2d4a]">Preference cookies</span>
                            <span class="block text-xs leading-5 text-slate-500">Remember display settings such as comfortable or compact feed spacing.</span>
                        </span>
                    </label>
                    <p class="mt-3 text-xs text-slate-500">Essential cookies are always active because the app cannot securely log you in without them.</p>
                </div>
            </div>

            <div class="flex shrink-0 flex-col gap-2 sm:flex-row lg:flex-col">
                <button type="button" class="cursor-pointer rounded-xl bg-[#1a2d4a] px-4 py-2 text-sm font-bold text-white transition-colors hover:bg-[#0d1428] active:bg-[#0a1020]" data-cookie-accept-all>Accept all</button>
                <button type="button" class="cursor-pointer rounded-xl border border-[#2a9d8f] px-4 py-2 text-sm font-bold text-[#2a9d8f] transition-colors hover:bg-[#2a9d8f] hover:text-white active:bg-[#1f7a6d]" data-cookie-essential>Essential only</button>
                <button type="button" class="cursor-pointer rounded-xl border border-slate-300 px-4 py-2 text-sm font-bold text-slate-700 transition-colors hover:bg-slate-100 active:bg-slate-200" data-cookie-customize>Customize</button>
                <button type="button" class="hidden cursor-pointer rounded-xl bg-[#2a9d8f] px-4 py-2 text-sm font-bold text-white transition-colors hover:bg-[#1f7a6d] active:bg-[#16564f]" data-cookie-save-custom>Save choices</button>
            </div>
        </div>
    </div>

    <button type="button"
            class="{{ $hasCookieConsent ? '' : 'hidden' }} fixed bottom-4 left-4 z-40 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-[#1a2d4a] shadow-lg"
            data-cookie-open-settings>
        Cookie settings
    </button>

    <script>
        const menuButton = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (menuButton && mobileMenu) {
            menuButton.addEventListener('click', function () {
                mobileMenu.classList.toggle('hidden');
            });
        }

        (function () {
            const panel = document.querySelector('[data-cookie-consent-panel]');
            const customArea = document.querySelector('[data-cookie-custom]');
            const checkbox = document.querySelector('[data-cookie-preferences-checkbox]');
            const customizeButton = document.querySelector('[data-cookie-customize]');
            const saveCustomButton = document.querySelector('[data-cookie-save-custom]');
            const openSettingsButton = document.querySelector('[data-cookie-open-settings]');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

            if (checkbox) {
                checkbox.checked = {{ request()->cookie('sru_cookie_preferences') === '1' ? 'true' : 'false' }};
            }

            async function saveCookieConsent(payload) {
                try {
                    const response = await fetch('{{ route('cookie-consent.store') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify(payload),
                    });

                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({ message: 'Unknown error' }));
                        console.error('Cookie consent save failed:', errorData);
                        alert('Failed to save cookie preferences: ' + (errorData.message || 'Unknown error'));
                        return;
                    }

                    const data = await response.json();
                    panel?.classList.add('hidden');
                    openSettingsButton?.classList.remove('hidden');
                    window.dispatchEvent(new CustomEvent('sru:cookies-updated', {
                        detail: {
                            preferences: payload.level === 'all' || Boolean(payload.preferences),
                        },
                    }));
                } catch (error) {
                    console.error('Network error:', error);
                    alert('Network error while saving cookie preferences. Please try again.');
                }
            }

            document.querySelector('[data-cookie-accept-all]')?.addEventListener('click', function () {
                saveCookieConsent({ level: 'all', preferences: true });
            });

            document.querySelector('[data-cookie-essential]')?.addEventListener('click', function () {
                saveCookieConsent({ level: 'essential', preferences: false });
            });

            customizeButton?.addEventListener('click', function () {
                customArea?.classList.remove('hidden');
                saveCustomButton?.classList.remove('hidden');
                customizeButton.classList.add('hidden');
            });

            saveCustomButton?.addEventListener('click', function () {
                console.log('Save custom clicked, checkbox checked:', checkbox?.checked);
                saveCookieConsent({
                    level: 'custom',
                    preferences: Boolean(checkbox?.checked),
                });
            });

            openSettingsButton?.addEventListener('click', function () {
                console.log('Open settings clicked');
                panel?.classList.remove('hidden');
                customArea?.classList.remove('hidden');
                saveCustomButton?.classList.remove('hidden');
                customizeButton?.classList.add('hidden');
            });
        })();
    </script>

</body>
</html>
