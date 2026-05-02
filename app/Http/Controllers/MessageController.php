<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

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
                $user = User::query()->with('profile')->find($otherUserId);
                $latestMessage = $messages->first();
                $unreadCount = $messages->where('receiver_id', $userId)->where('is_read', false)->count();

                return [
                    'user' => $user,
                    'latest_message' => $latestMessage,
                    'unread_count' => $unreadCount,
                    'chat_url' => route('messages.show', ['userToken' => $this->encodeUserToken((int) $otherUserId)]),
                ];
            });

        $adminUser = User::query()
            ->where('role', 'admin')
            ->where('id', '!=', $userId)
            ->first();

        $adminChatUrl = $adminUser
            ? route('messages.show', ['userToken' => $this->encodeUserToken((int) $adminUser->id)])
            : null;

        return view('messages.index', compact('conversations', 'adminUser', 'adminChatUrl'));
    }

    public function show(string $userToken): View
    {
        $user = $this->resolveUserFromToken($userToken);
        $user->load('profile');
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
                ($actor?->display_name ?? 'Alumni') . ' read messages from ' . ($user->display_name ?? 'User'),
                [
                    'counterpart_user_id' => $user->id,
                    'counterpart_name' => $user->display_name,
                    'messages_marked_read' => $markedAsRead,
                ]
            );
        }

        // Get conversation
        $messages = Message::query()->betweenUsers($currentUserId, $user->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('messages.show', compact('user', 'messages', 'userToken'));
    }

    public function store(Request $request, string $userToken): RedirectResponse|JsonResponse
    {
        $user = $this->resolveUserFromToken($userToken);
        $senderId = Auth::id();

        $request->validate([
            'content'    => 'nullable|string|max:1000',
            'subject'    => 'nullable|string|max:255',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt,zip',
        ]);

        if (empty(trim((string) $request->input('content', ''))) && !$request->hasFile('attachment')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Please enter a message or attach a file.',
                ], 422);
            }

            return redirect()->back()->withErrors(['content' => 'Please enter a message or attach a file.']);
        }

        $attachmentPath = null;
        $attachmentOriginalName = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentOriginalName = $file->getClientOriginalName();
            $attachmentPath = $file->store('message-attachments', 'public');
        }

        $message = Message::create([
            'sender_id'               => $senderId,
            'receiver_id'             => $user->id,
            'subject'                 => $request->input('subject'),
            'content'                 => $request->input('content', ''),
            'attachment'              => $attachmentPath,
            'attachment_original_name' => $attachmentOriginalName,
            'is_read'                 => false,
        ]);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $user->id,
            'message_sent',
            ($actor?->display_name ?? 'Alumni') . ' sent a message to ' . ($user->display_name ?? 'User'),
            [
                'message_id'       => $message->id,
                'receiver_user_id' => $user->id,
                'receiver_name'    => $user->display_name,
                'has_subject'      => !empty($request->input('subject')),
                'has_attachment'   => $attachmentPath !== null,
            ]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message->load(['sender']),
                'attachment_url' => $attachmentPath ? asset('storage/' . $attachmentPath) : null,
                'attachment_name' => $attachmentOriginalName,
            ]);
        }

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    public function storeAjax(Request $request, User $user): JsonResponse
    {
        $senderId = Auth::id();

        $request->validate([
            'content'    => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt,zip',
        ]);

        if (empty(trim((string) $request->input('content', ''))) && !$request->hasFile('attachment')) {
            return response()->json(['success' => false, 'error' => 'Please enter a message or attach a file.'], 422);
        }

        $attachmentPath = null;
        $attachmentOriginalName = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentOriginalName = $file->getClientOriginalName();
            $attachmentPath = $file->store('message-attachments', 'public');
        }

        $message = Message::create([
            'sender_id'               => $senderId,
            'receiver_id'             => $user->id,
            'content'                 => $request->input('content', ''),
            'attachment'              => $attachmentPath,
            'attachment_original_name' => $attachmentOriginalName,
            'is_read'                 => false,
        ]);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $user->id,
            'message_sent',
            ($actor?->display_name ?? 'Alumni') . ' sent a message to ' . ($user->display_name ?? 'User'),
            [
                'message_id'       => $message->id,
                'receiver_user_id' => $user->id,
                'receiver_name'    => $user->display_name,
                'has_subject'      => false,
                'has_attachment'   => $attachmentPath !== null,
            ]
        );

        return response()->json([
            'success'    => true,
            'message'    => $message->load(['sender']),
            'attachment_url'  => $attachmentPath ? asset('storage/' . $attachmentPath) : null,
            'attachment_name' => $attachmentOriginalName,
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

    public function searchUsers(Request $request): JsonResponse
    {
        $queryText = trim((string) $request->query('q', ''));
        $currentUserId = Auth::id();

        $users = User::query()
            ->with('profile')
            ->where('id', '!=', $currentUserId)
            ->when($queryText !== '', function ($query) use ($queryText) {
                $query->where(function ($nested) use ($queryText) {
                    $nested->where('name', 'like', '%' . $queryText . '%')
                        ->orWhereHas('profile', function ($profileQuery) use ($queryText) {
                            $profileQuery->where('full_name', 'like', '%' . $queryText . '%')
                                ->orWhere('passing_year', 'like', '%' . $queryText . '%');
                        });
                });
            })
            ->orderBy('name')
            ->limit(25)
            ->get()
            ->map(function (User $user) {
                return [
                    'id' => $user->id,
                    'name' => $user->display_name,
                    'avatar_url' => $user->profile?->profile_photo
                        ? asset('storage/' . $user->profile->profile_photo)
                        : ($user->avatar ? asset('storage/' . $user->avatar) : null),
                    'passing_year' => $user->profile?->passing_year,
                    'batch' => $user->profile?->passing_year ? ('Batch ' . $user->profile->passing_year) : null,
                    'chat_url' => route('messages.show', ['userToken' => $this->encodeUserToken((int) $user->id)]),
                ];
            })
            ->values();

        return response()->json([
            'users' => $users,
        ]);
    }

    private function encodeUserToken(int $userId): string
    {
        return User::messageTokenFor($userId);
    }

    private function resolveUserFromToken(string $userToken): User
    {
        try {
            $decryptedUserId = (int) Crypt::decryptString($userToken);
        } catch (DecryptException) {
            abort(404);
        }

        $user = User::query()->find($decryptedUserId);
        if (!$user) {
            abort(404);
        }

        return $user;
    }
}
