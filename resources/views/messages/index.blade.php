@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="min-h-screen bg-[#f4f6f9] py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm border border-[#e2e8f0]">
            <div class="px-6 py-4 border-b border-[#e2e8f0] flex items-center justify-between gap-3">
                <h1 class="text-2xl font-bold text-[#1a2d5a]">Messages</h1>
                <div class="flex items-center gap-2">
                    @if(!empty($adminUser))
                        <a href="{{ $adminChatUrl }}" class="rounded-lg border border-[#1a2d5a] px-4 py-2 text-sm font-semibold text-[#1a2d5a] hover:bg-[#f4f6f9]">
                            Message Admin
                        </a>
                    @endif
                    <button type="button" id="open-new-message-modal" class="rounded-lg bg-[#1a2d5a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#141d42]">
                        New Message
                    </button>
                </div>
            </div>

            <div class="divide-y divide-[#e2e8f0]">
                @forelse($conversations as $conversation)
                    <div class="p-6 hover:bg-[#f4f6f9] transition-colors">
                        <a href="{{ $conversation['chat_url'] }}" class="flex items-center space-x-4">
                            @php
                                $conversationAvatar = $conversation['user']->profile?->profile_photo
                                    ? asset('storage/' . $conversation['user']->profile->profile_photo)
                                    : ($conversation['user']->avatar ? asset('storage/' . $conversation['user']->avatar) : null);
                            @endphp
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-[#1a2d5a] rounded-full flex items-center justify-center">
                                    @if($conversationAvatar)
                                        <img src="{{ $conversationAvatar }}" alt="{{ $conversation['user']->display_name }}" class="w-12 h-12 rounded-full object-contain bg-white p-0.5">
                                    @else
                                        <span class="text-white font-semibold text-lg">
                                            {{ substr($conversation['user']->display_name, 0, 1) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-lg font-semibold text-[#1a2d5a]">
                                        {{ $conversation['user']->display_name }}
                                    </p>
                                    <div class="flex items-center space-x-2">
                                        @if($conversation['unread_count'] > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#f4f6f9] text-[#c0006a] border border-[#e2e8f0]">
                                                {{ $conversation['unread_count'] }}
                                            </span>
                                        @endif
                                        <p class="text-sm text-gray-600">
                                            {{ $conversation['latest_message']->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>

                                <p class="text-sm text-gray-600 mt-1 truncate">
                                    {{ Str::limit($conversation['latest_message']->content, 100) }}
                                </p>

                                @if($conversation['user']->profile)
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $conversation['user']->profile->current_position ?? 'Alumni' }}
                                    </p>
                                @endif
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-[#1a2d5a]">No messages</h3>
                        <p class="mt-1 text-sm text-gray-600">Get started by connecting with alumni.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div id="new-message-modal" class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/40" data-close-modal></div>
    <div class="relative mx-auto mt-24 w-[92%] max-w-xl rounded-2xl bg-white p-5 shadow-2xl border border-[#e2e8f0]">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-lg font-bold text-[#1a2d5a]">Start a New Message</h2>
            <button type="button" class="text-gray-500 hover:text-[#1a2d5a]" data-close-modal>
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <p class="mt-2 text-sm text-gray-600">Search by name or passing year.</p>

        <div class="mt-4">
            <input
                id="new-message-search"
                type="text"
                class="w-full rounded-lg border border-[#e2e8f0] px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2d5a]"
                placeholder="Type name or passing year..."
                autocomplete="off"
            >
        </div>

        <div id="new-message-results" class="mt-4 max-h-72 overflow-y-auto space-y-2"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const openButton = document.getElementById('open-new-message-modal');
    const modal = document.getElementById('new-message-modal');
    const searchInput = document.getElementById('new-message-search');
    const resultsContainer = document.getElementById('new-message-results');
    const closeTargets = modal ? modal.querySelectorAll('[data-close-modal]') : [];
    let debounceTimer = null;

    function renderUsers(users) {
        if (!resultsContainer) {
            return;
        }

        if (!users.length) {
            resultsContainer.innerHTML = '<p class="text-sm text-gray-500 p-2">No users found.</p>';
            return;
        }

        resultsContainer.innerHTML = users.map(function (user) {
            const batchText = user.batch || 'Batch not set';
            const avatar = user.avatar_url
                ? `<img src="${user.avatar_url}" alt="${user.name}" class="w-10 h-10 rounded-full object-contain bg-white p-0.5">`
                : `<div class="w-10 h-10 rounded-full bg-[#1a2d5a] text-white flex items-center justify-center text-sm font-semibold">${(user.name || 'U').charAt(0)}</div>`;

            return `
                <a href="${user.chat_url}" class="block rounded-lg border border-[#e2e8f0] px-3 py-3 hover:bg-[#f4f6f9]">
                    <div class="flex items-center gap-3">
                        ${avatar}
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-[#1a2d5a] truncate">${user.name}</p>
                            <p class="text-xs text-gray-600 mt-1">${batchText}</p>
                        </div>
                    </div>
                </a>
            `;
        }).join('');
    }

    function fetchUsers(query) {
        const url = `{{ route('messages.users.search') }}?q=${encodeURIComponent(query || '')}`;

        fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                renderUsers(Array.isArray(data.users) ? data.users : []);
            })
            .catch(function () {
                if (resultsContainer) {
                    resultsContainer.innerHTML = '<p class="text-sm text-red-600 p-2">Unable to load users. Please try again.</p>';
                }
            });
    }

    function openModal() {
        if (!modal) {
            return;
        }

        modal.classList.remove('hidden');
        if (searchInput) {
            searchInput.value = '';
            searchInput.focus();
        }
        fetchUsers('');
    }

    function closeModal() {
        if (!modal) {
            return;
        }

        modal.classList.add('hidden');
    }

    openButton?.addEventListener('click', openModal);
    closeTargets.forEach(function (element) {
        element.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    searchInput?.addEventListener('input', function () {
        const query = this.value || '';
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            fetchUsers(query);
        }, 250);
    });
});
</script>
@endsection