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
        @include('partials.navbar')

    <div class="p-6">
        @yield('content')
    </div>

    @php($hasCookieConsent = request()->hasCookie('sru_cookie_consent'))

    <div id="cookie-consent-panel"
         class="{{ $hasCookieConsent ? 'hidden' : '' }} fixed inset-x-4 bottom-4 z-50 mx-auto w-auto max-w-2xl rounded-2xl border border-slate-200 bg-white p-4 shadow-2xl md:right-4 md:left-auto md:w-[36rem] md:max-w-[36rem]"
         data-cookie-consent-panel>
        <div class="max-h-[62vh] overflow-y-auto">
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
                                <p class="mt-1">These cookies keep you signed in and protect form submissions from misuse. (Technical names: laravel_session, XSRF-TOKEN)</p>
                            </div>
                            <div class="rounded-lg bg-white p-3">
                                <p class="font-bold text-[#1a2d4a]">Consent record</p>
                                <p class="mt-1">These cookies save your cookie choice so we do not ask you every time. (Technical names: sru_cookie_consent, sru_cookie_preferences)</p>
                            </div>
                            <div class="rounded-lg bg-white p-3">
                                <p class="font-bold text-[#1a2d4a]">Optional preference</p>
                                <p class="mt-1">This cookie remembers how you prefer the dashboard layout, such as compact or comfortable spacing. (Technical name: sru_feed_density)</p>
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
    </div>

    <button type="button"
            class="{{ $hasCookieConsent ? '' : 'hidden' }} fixed bottom-4 left-4 z-40 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-[#1a2d4a] shadow-lg"
            data-cookie-open-settings>
        Cookie settings
    </button>

    <script>
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
                customArea?.classList.add('hidden');
                saveCustomButton?.classList.add('hidden');
                customizeButton?.classList.remove('hidden');
            });
        })();
    </script>

</body>
</html>
