<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\FeedPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminEngageController extends Controller
{
    private array $postTypes = [
        'opportunity' => 'Opportunity',
        'meetup' => 'Meetup',
        'memory' => 'Memory',
        'mentoring' => 'Mentoring',
        'update' => 'Update',
    ];

    public function create(): View
    {
        $actor = Auth::user();

        $recentPosts = FeedPost::query()
            ->with('user')
            ->latest('updated_at')
            ->limit(6)
            ->get(['id', 'user_id', 'post_type', 'body', 'created_at', 'updated_at']);

        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_engage_create_opened',
            ($actor?->name ?? 'Admin') . ' opened create engage page',
            []
        );

        return view('admin.engage.engage-create', [
            'postTypes' => $this->postTypes,
            'recentPosts' => $recentPosts,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'post_type' => 'required|in:' . implode(',', array_keys($this->postTypes)),
            'body' => 'required|string|min:10|max:1200',
        ]);

        $post = FeedPost::query()->create([
            'user_id' => Auth::id(),
            'post_type' => $validated['post_type'],
            'body' => trim($validated['body']),
        ]);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_engage_created',
            ($actor?->name ?? 'Admin') . ' created engage post #' . $post->id,
            [
                'post_id' => $post->id,
                'post_type' => $post->post_type,
                'body_preview' => substr($post->body, 0, 120),
            ]
        );

        return redirect()
            ->route('admin.engage.manage')
            ->with('success', 'Engage post created successfully.');
    }

    public function manage(): View
    {
        $posts = FeedPost::query()
            ->with('user')
            ->latest('updated_at')
            ->get();

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_engage_manage_opened',
            ($actor?->name ?? 'Admin') . ' opened manage engage page',
            []
        );

        return view('admin.engage.engage-manage', [
            'posts' => $posts,
            'postTypes' => $this->postTypes,
        ]);
    }

    public function edit(int $id): View
    {
        $post = FeedPost::query()->with('user')->findOrFail($id);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_engage_edit_opened',
            ($actor?->name ?? 'Admin') . ' opened engage edit for post #' . $post->id,
            [
                'post_id' => $post->id,
            ]
        );

        return view('admin.engage.engage-edit', [
            'post' => $post,
            'postTypes' => $this->postTypes,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $post = FeedPost::query()->findOrFail($id);

        $validated = $request->validate([
            'post_type' => 'required|in:' . implode(',', array_keys($this->postTypes)),
            'body' => 'required|string|min:10|max:1200',
        ]);

        $post->update([
            'post_type' => $validated['post_type'],
            'body' => trim($validated['body']),
        ]);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_engage_updated',
            ($actor?->name ?? 'Admin') . ' updated engage post #' . $post->id,
            [
                'post_id' => $post->id,
                'post_type' => $post->post_type,
                'body_preview' => substr($post->body, 0, 120),
            ]
        );

        return redirect()
            ->route('admin.engage.manage')
            ->with('success', 'Engage post updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $post = FeedPost::query()->findOrFail($id);
        $postId = $post->id;
        $postType = $post->post_type;
        $postPreview = substr($post->body, 0, 120);

        $post->delete();

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_engage_deleted',
            ($actor?->name ?? 'Admin') . ' deleted engage post #' . $postId,
            [
                'post_id' => $postId,
                'post_type' => $postType,
                'body_preview' => $postPreview,
            ]
        );

        return redirect()
            ->route('admin.engage.manage')
            ->with('success', 'Engage post deleted successfully.');
    }
}
