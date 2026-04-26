@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900">Messages</h1>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($conversations as $conversation)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <a href="{{ route('messages.show', $conversation['user']->id) }}" class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-lg">
                                        {{ substr($conversation['user']->name, 0, 1) }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ $conversation['user']->name }}
                                    </p>
                                    <div class="flex items-center space-x-2">
                                        @if($conversation['unread_count'] > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $conversation['unread_count'] }}
                                            </span>
                                        @endif
                                        <p class="text-sm text-gray-500">
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
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No messages</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by connecting with alumni.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection