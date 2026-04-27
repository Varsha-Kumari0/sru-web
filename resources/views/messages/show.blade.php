@extends('layouts.app')

@section('title', 'Message - ' . $user->name)

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
                                    {{ substr($user->name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <h1 class="text-lg font-semibold text-[#1a2d5a]">{{ $user->name }}</h1>
                                @if($user->profile)
                                    <p class="text-sm text-gray-600">{{ $user->profile->current_position ?? 'Alumni' }}</p>
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
                            <p class="text-sm">{{ $message->content }}</p>
                            <p class="text-xs mt-1 opacity-75">{{ $message->created_at->format('M j, g:i A') }}</p>
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
                <form id="message-form" action="{{ route('messages.store', $user->id) }}" method="POST" class="flex space-x-3">
                    @csrf
                    <div class="flex-1">
                        <textarea
                            name="content"
                            id="message-input"
                            rows="1"
                            class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1a2d5a] focus:border-transparent resize-none"
                            placeholder="Type your message..."
                            required
                        ></textarea>
                    </div>
                    <button
                        type="submit"
                        class="bg-[#1a2d5a] hover:bg-[#141d42] text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
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
            messageForm.dispatchEvent(new Event('submit'));
        }
    });

    // Scroll to bottom on load
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
});
</script>
@endsection