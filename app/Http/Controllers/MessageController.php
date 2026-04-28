<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();

        // Get all conversations (unique users who have messaged with current user)
        $conversations = Message::query()->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) use ($userId) {
                return $message->sender_id === $userId ? $message->receiver_id : $message->sender_id;
            })
            ->map(function ($messages, $otherUserId) use ($userId) {
                $user = User::query()->find($otherUserId);
                $latestMessage = $messages->first();
                $unreadCount = $messages->where('receiver_id', $userId)->where('is_read', false)->count();

                return [
                    'user' => $user,
                    'latest_message' => $latestMessage,
                    'unread_count' => $unreadCount,
                ];
            });

        return view('messages.index', compact('conversations'));
    }

    public function show(User $user): View
    {
        $currentUserId = Auth::id();

        // Mark messages as read
        $markedAsRead = Message::query()->where('sender_id', $user->id)
            ->where('receiver_id', $currentUserId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        if ($markedAsRead > 0) {
            $actor = Auth::user();
            ActivityLog::record(
                $actor?->id,
                $user->id,
                'messages_marked_read',
                ($actor?->name ?? 'Alumni') . ' read messages from ' . ($user->name ?? 'User'),
                [
                    'counterpart_user_id' => $user->id,
                    'counterpart_name' => $user->name,
                    'messages_marked_read' => $markedAsRead,
                ]
            );
        }

        // Get conversation
        $messages = Message::query()->betweenUsers($currentUserId, $user->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('messages.show', compact('user', 'messages'));
    }

    public function store(Request $request, User $user): RedirectResponse
    {
        $senderId = Auth::id();

        $request->validate([
            'content' => 'required|string|max:1000',
            'subject' => 'nullable|string|max:255',
        ]);

        $message = Message::create([
            'sender_id' => $senderId,
            'receiver_id' => $user->id,
            'subject' => $request->input('subject'),
            'content' => $request->input('content'),
            'is_read' => false,
        ]);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $user->id,
            'message_sent',
            ($actor?->name ?? 'Alumni') . ' sent a message to ' . ($user->name ?? 'User'),
            [
                'message_id' => $message->id,
                'receiver_user_id' => $user->id,
                'receiver_name' => $user->name,
                'has_subject' => !empty($request->input('subject')),
            ]
        );

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    public function storeAjax(Request $request, User $user): JsonResponse
    {
        $senderId = Auth::id();

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => $senderId,
            'receiver_id' => $user->id,
            'content' => $request->input('content'),
            'is_read' => false,
        ]);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $user->id,
            'message_sent',
            ($actor?->name ?? 'Alumni') . ' sent a message to ' . ($user->name ?? 'User'),
            [
                'message_id' => $message->id,
                'receiver_user_id' => $user->id,
                'receiver_name' => $user->name,
                'has_subject' => false,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => $message->load(['sender']),
        ]);
    }

    public function getUnreadCount(): JsonResponse
    {
        $unreadCount = Message::query()->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->get()
            ->count();

        return response()->json(['unread_count' => $unreadCount]);
    }
}
