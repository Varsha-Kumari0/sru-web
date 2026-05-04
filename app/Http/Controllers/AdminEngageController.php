<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Event;
use App\Models\FeedComment;
use App\Models\FeedPost;
use App\Models\FeedReaction;
use App\Models\GalleryAlbum;
use App\Models\GalleryVideo;
use App\Models\JobOpportunity;
use App\Models\News;
use App\Models\Testimonial;
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
            ->with(['user.profile'])
            ->latest('updated_at')
            ->get(['id', 'user_id', 'post_type', 'body', 'updated_at']);

        $news = News::query()
            ->latest('updated_at')
            ->get(['id', 'title', 'excerpt', 'updated_at']);

        $events = Event::query()
            ->latest('updated_at')
            ->get(['id', 'title', 'excerpt', 'location', 'updated_at']);

        $testimonials = Testimonial::query()
            ->latest('updated_at')
            ->get(['id', 'name', 'position', 'company', 'content', 'status', 'updated_at']);

        $feedItems = collect();

        foreach ($posts as $post) {
            $feedItems->push([
                'feed_type' => 'post',
                'feed_id' => $post->id,
                'kind' => $this->postTypes[$post->post_type] ?? ucfirst((string) $post->post_type),
                'title' => 'Shared by ' . ($post->user?->display_name ?? $post->user?->name ?? 'Unknown User'),
                'body' => $post->body,
                'owner' => $post->user?->display_name ?? $post->user?->name ?? 'Unknown User',
                'updated_at' => $post->updated_at,
                'can_delete_source' => true,
                'source_delete_route' => route('admin.engage.delete', $post->id),
            ]);
        }

        foreach ($news as $item) {
            $feedItems->push([
                'feed_type' => 'news',
                'feed_id' => $item->id,
                'kind' => 'News',
                'title' => $item->title,
                'body' => $item->excerpt,
                'owner' => 'SRU Newsroom',
                'updated_at' => $item->updated_at,
                'can_delete_source' => false,
                'source_delete_route' => null,
            ]);
        }

        foreach ($events as $item) {
            $feedItems->push([
                'feed_type' => 'event',
                'feed_id' => $item->id,
                'kind' => 'Event',
                'title' => $item->title,
                'body' => trim(($item->excerpt ?? '') . ' ' . ($item->location ? 'Venue: ' . $item->location : '')),
                'owner' => 'Events Desk',
                'updated_at' => $item->updated_at,
                'can_delete_source' => false,
                'source_delete_route' => null,
            ]);
        }

        foreach ($testimonials as $item) {
            $feedItems->push([
                'feed_type' => 'testimonial',
                'feed_id' => $item->id,
                'kind' => 'Testimonial',
                'title' => $item->name . ($item->position ? ' • ' . $item->position : ''),
                'body' => $item->content,
                'owner' => $item->name,
                'updated_at' => $item->updated_at,
                'can_delete_source' => false,
                'source_delete_route' => null,
            ]);
        }

        $feedKeys = $feedItems
            ->map(fn (array $item) => $item['feed_type'] . ':' . $item['feed_id'])
            ->values();

        $commentsCountMap = FeedComment::query()
            ->get()
            ->groupBy(fn ($item) => $item->feed_type . ':' . $item->feed_id)
            ->map(fn ($items) => $items->count());

        $likesCountMap = FeedReaction::query()
            ->where('reaction', 'like')
            ->get()
            ->groupBy(fn ($item) => $item->feed_type . ':' . $item->feed_id)
            ->map(fn ($items) => $items->count());

        $items = $feedItems
            ->filter(fn (array $item) => $feedKeys->contains($item['feed_type'] . ':' . $item['feed_id']))
            ->map(function (array $item) use ($commentsCountMap, $likesCountMap) {
                $key = $item['feed_type'] . ':' . $item['feed_id'];
                $item['comments_count'] = (int) ($commentsCountMap[$key] ?? 0);
                $item['likes_count'] = (int) ($likesCountMap[$key] ?? 0);

                return $item;
            })
            ->sortByDesc('updated_at')
            ->values();

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_engage_manage_opened',
            ($actor?->name ?? 'Admin') . ' opened manage engage page',
            []
        );

        return view('admin.engage.engage-manage', [
            'items' => $items,
            'postTypes' => $this->postTypes,
        ]);
    }

    public function edit(int $id): View
    {
        return $this->reviewFeed('post', $id);
    }

    public function reviewFeed(string $feedType, int $feedId): View
    {
        $sourceData = $this->loadFeedSourceData($feedType, $feedId);
        $comments = FeedComment::query()
            ->with('user.profile')
            ->where('feed_type', $feedType)
            ->where('feed_id', $feedId)
            ->latest()
            ->get();
        $reactions = FeedReaction::query()
            ->with('user.profile')
            ->where('feed_type', $feedType)
            ->where('feed_id', $feedId)
            ->latest()
            ->get();

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_engage_edit_opened',
            ($actor?->name ?? 'Admin') . ' opened engage moderation view for ' . $feedType . ' #' . $feedId,
            [
                'feed_type' => $feedType,
                'feed_id' => $feedId,
            ]
        );

        return view('admin.engage.engage-edit', [
            'feedType' => $feedType,
            'feedId' => $feedId,
            'sourceData' => $sourceData,
            'comments' => $comments,
            'reactions' => $reactions,
            'canDeleteSource' => $feedType === 'post',
            'postTypes' => $this->postTypes,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $post = FeedPost::query()->findOrFail($id);
        $actor = Auth::user();

        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_engage_update_blocked',
            ($actor?->name ?? 'Admin') . ' attempted to edit engage post #' . $post->id . ' but editing is disabled',
            [
                'post_id' => $post->id,
            ]
        );

        return redirect()
            ->route('admin.engage.review', $post->id)
            ->with('error', 'Editing engage posts is disabled. You can review and delete comments or likes only.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $post = FeedPost::query()->findOrFail($id);
        $postId = $post->id;
        $postType = $post->post_type;
        $postPreview = substr($post->body, 0, 120);

        FeedComment::query()
            ->where('feed_type', 'post')
            ->where('feed_id', $postId)
            ->delete();

        FeedReaction::query()
            ->where('feed_type', 'post')
            ->where('feed_id', $postId)
            ->delete();

        FeedPost::query()->whereKey($postId)->delete();

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

    public function destroyComment(int $comment): RedirectResponse
    {
        $commentModel = FeedComment::query()->with(['user.profile'])->findOrFail($comment);
        $feedId = (int) $commentModel->feed_id;
        $feedType = (string) $commentModel->feed_type;
        $commentAuthor = $commentModel->user?->display_name ?? 'Unknown User';
        $commentPreview = substr($commentModel->body, 0, 120);

        FeedComment::query()->whereKey($commentModel->id)->delete();

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_engage_comment_deleted',
            ($actor?->name ?? 'Admin') . ' deleted a comment from ' . $feedType . ' #' . $feedId,
            [
                'feed_id' => $feedId,
                'feed_type' => $feedType,
                'comment_id' => $commentModel->id,
                'comment_author' => $commentAuthor,
                'body_preview' => $commentPreview,
            ]
        );

        return redirect()
            ->route('admin.engage.feed.review', [$feedType, $feedId])
            ->with('success', 'Comment deleted successfully.');
    }

    public function destroyReaction(int $reaction): RedirectResponse
    {
        $reactionModel = FeedReaction::query()->with(['user.profile'])->findOrFail($reaction);
        $feedId = (int) $reactionModel->feed_id;
        $feedType = (string) $reactionModel->feed_type;
        $reactionAuthor = $reactionModel->user?->display_name ?? 'Unknown User';

        FeedReaction::query()->whereKey($reactionModel->id)->delete();

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_engage_reaction_deleted',
            ($actor?->name ?? 'Admin') . ' deleted a reaction from ' . $feedType . ' #' . $feedId,
            [
                'feed_id' => $feedId,
                'feed_type' => $feedType,
                'reaction_id' => $reactionModel->id,
                'reaction_author' => $reactionAuthor,
                'reaction' => $reactionModel->reaction,
            ]
        );

        return redirect()
            ->route('admin.engage.feed.review', [$feedType, $feedId])
            ->with('success', 'Reaction deleted successfully.');
    }

    private function loadFeedSourceData(string $feedType, int $feedId): array
    {
        return match ($feedType) {
            'post' => $this->loadPostSourceData($feedId),
            'news' => $this->loadNewsSourceData($feedId),
            'event' => $this->loadEventSourceData($feedId),
            'testimonial' => $this->loadTestimonialSourceData($feedId),
            'job' => $this->loadJobSourceData($feedId),
            'gallery_album' => $this->loadGalleryAlbumSourceData($feedId),
            'gallery_video' => $this->loadGalleryVideoSourceData($feedId),
            default => abort(404),
        };
    }

    private function loadPostSourceData(int $feedId): array
    {
        $post = FeedPost::query()->with('user.profile')->findOrFail($feedId);

        return [
            'kind' => $this->postTypes[$post->post_type] ?? ucfirst((string) $post->post_type),
            'title' => 'Shared by ' . ($post->user?->display_name ?? $post->user?->name ?? 'Unknown User'),
            'body' => (string) $post->body,
            'owner' => $post->user?->display_name ?? $post->user?->name ?? 'Unknown User',
            'updated_at' => $post->updated_at,
        ];
    }

    private function loadNewsSourceData(int $feedId): array
    {
        $news = News::query()->findOrFail($feedId);

        return [
            'kind' => 'News',
            'title' => (string) $news->title,
            'body' => (string) ($news->excerpt ?? ''),
            'owner' => 'SRU Newsroom',
            'updated_at' => $news->updated_at,
        ];
    }

    private function loadEventSourceData(int $feedId): array
    {
        $event = Event::query()->findOrFail($feedId);

        return [
            'kind' => 'Event',
            'title' => (string) $event->title,
            'body' => trim((string) ($event->excerpt ?? '') . ' ' . ($event->location ? 'Venue: ' . $event->location : '')),
            'owner' => 'Events Desk',
            'updated_at' => $event->updated_at,
        ];
    }

    private function loadTestimonialSourceData(int $feedId): array
    {
        $testimonial = Testimonial::query()->findOrFail($feedId);

        return [
            'kind' => 'Testimonial',
            'title' => (string) $testimonial->name . ($testimonial->position ? ' • ' . $testimonial->position : ''),
            'body' => (string) ($testimonial->content ?? ''),
            'owner' => (string) ($testimonial->name ?? 'Alumni'),
            'updated_at' => $testimonial->updated_at,
        ];
    }

    private function loadJobSourceData(int $feedId): array
    {
        $job = JobOpportunity::query()->findOrFail($feedId);

        return [
            'kind' => 'Job',
            'title' => (string) $job->title,
            'body' => trim((string) ($job->description ?? '') . ' ' . ($job->company_name ? 'Company: ' . $job->company_name : '')),
            'owner' => (string) ($job->company_name ?? 'Career Desk'),
            'updated_at' => $job->updated_at,
        ];
    }

    private function loadGalleryAlbumSourceData(int $feedId): array
    {
        $album = GalleryAlbum::query()->findOrFail($feedId);

        return [
            'kind' => 'Gallery Album',
            'title' => (string) $album->title,
            'body' => (string) ($album->summary ?? ''),
            'owner' => 'Gallery Desk',
            'updated_at' => $album->updated_at,
        ];
    }

    private function loadGalleryVideoSourceData(int $feedId): array
    {
        $video = GalleryVideo::query()->findOrFail($feedId);

        return [
            'kind' => 'Gallery Video',
            'title' => (string) $video->title,
            'body' => (string) ($video->summary ?? ''),
            'owner' => 'Gallery Desk',
            'updated_at' => $video->updated_at,
        ];
    }
}
