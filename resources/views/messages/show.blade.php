@extends('layouts.app')

@section('title', 'Message - ' . $user->display_name)

@section('content')
<div class="min-h-screen bg-[#f4f6f9] py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm border border-[#e2e8f0]">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-[#e2e8f0]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <button onclick="window.history.back()" class="text-gray-400 hover:text-[#1a2d5a]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-[#1a2d5a] rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold">
                                    {{ substr($user->display_name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <h1 class="text-lg font-semibold text-[#1a2d5a]">{{ $user->display_name }}</h1>
                                @if(($user->role ?? null) === 'user')
                                    @php
                                        $isOnline = $user->last_seen_at && $user->last_seen_at->gt(now()->subMinutes(2));
                                    @endphp
                                    <p class="text-sm {{ $isOnline ? 'text-green-600 font-medium' : 'text-gray-600' }}">
                                        {{ $isOnline ? 'Online' : 'Last seen ' . ($user->last_seen_at ? $user->last_seen_at->diffForHumans() : 'a long time ago') }}
                                    </p>
                                @elseif(($user->role ?? null) === 'admin')
                                    <p class="text-sm text-gray-600">Administrator</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4 max-h-96">
                @forelse($messages as $message)
                    <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-[#1a2d5a] text-white' : 'bg-[#f4f6f9] text-[#1a2d5a] border border-[#e2e8f0]' }}">
                            @if(!empty($message->content))
                                <p class="text-sm">{{ $message->content }}</p>
                            @endif
                            @if($message->attachment)
                                @php
                                    $ext = strtolower(pathinfo($message->attachment_original_name ?? $message->attachment, PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['jpg','jpeg','png','gif']);
                                @endphp
                                @if($isImage)
                                    <img src="{{ asset('storage/' . $message->attachment) }}" alt="Attachment" class="mt-2 rounded max-w-full max-h-48 object-contain">
                                @else
                                    <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" download="{{ $message->attachment_original_name ?? 'file' }}" class="mt-2 flex items-center gap-2 text-xs underline opacity-90 hover:opacity-100">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                        {{ $message->attachment_original_name ?? 'Download file' }}
                                    </a>
                                @endif
                            @endif
                            <div class="mt-1 flex items-center justify-end gap-1 text-xs opacity-80">
                                <span>{{ $message->created_at->format('M j, g:i A') }}</span>
                                @if($message->sender_id === auth()->id())
                                    <span class="inline-flex items-center {{ $message->is_read ? 'text-sky-300' : 'text-slate-300' }}" title="{{ $message->is_read ? 'Read' : 'Delivered' }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 13l3 3 5-7" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 13l3 3 5-7" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-600">No messages yet. Start the conversation!</p>
                    </div>
                @endforelse
            </div>

            <!-- Message Input -->
            <div class="px-6 py-4 border-t border-[#e2e8f0]">
                <!-- File preview -->
                <div id="file-preview" class="hidden mb-2 flex items-center gap-2 text-sm text-gray-600 bg-[#f4f6f9] rounded-lg px-3 py-2">
                    <svg class="w-4 h-4 flex-shrink-0 text-[#1a2d5a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    <span id="file-name" class="flex-1 truncate"></span>
                    <button type="button" onclick="clearFile()" class="text-gray-400 hover:text-red-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form id="message-form" action="{{ route('messages.store', $user->id) }}" method="POST" enctype="multipart/form-data" class="flex items-end space-x-2">
                    @csrf
                    <input type="file" id="file-input" name="attachment" class="hidden"
                           accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip"
                           onchange="handleFileSelect(this)">
                    <button type="button" onclick="document.getElementById('file-input').click()"
                            class="flex-shrink-0 p-2 text-gray-400 hover:text-[#1a2d5a] hover:bg-[#f4f6f9] rounded-lg transition-colors"
                            title="Attach file">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                    </button>
                    <div class="flex-1">
                        <textarea
                            name="content"
                            id="message-input"
                            rows="1"
                            class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1a2d5a] focus:border-transparent resize-none"
                            placeholder="Type your message..."
                        ></textarea>
                    </div>
                    <button
                        type="submit"
                        class="flex-shrink-0 bg-[#1a2d5a] hover:bg-[#141d42] text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </form>
                <p class="mt-1 text-xs text-gray-400">Attach: jpg, png, gif, pdf, doc, xls, txt, zip (max 10 MB)</p>
            </div>
        </div>
    </div>
</div>

<script>
function handleFileSelect(input) {
    const preview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    if (input.files && input.files[0]) {
        fileName.textContent = input.files[0].name;
        preview.classList.remove('hidden');
    }
}

function clearFile() {
    document.getElementById('file-input').value = '';
    document.getElementById('file-preview').classList.add('hidden');
    document.getElementById('file-name').textContent = '';
}

document.addEventListener('DOMContentLoaded', function() {
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const messagesContainer = document.getElementById('messages-container');

    // Auto-resize textarea
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });

    // Submit form with Enter (but allow Shift+Enter for new lines)
    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            messageForm.requestSubmit();
        }
    });

    // Scroll to bottom on load
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
});
</script>
@endsection