<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminEngageController;
use App\Http\Controllers\AdminJobOpportunityController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryAdminController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\JobOpportunityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SkillController;
use App\Models\ActivityLog;
use App\Models\Event;
use App\Models\JobOpportunity;
use App\Models\FeedComment;
use App\Models\FeedPost;
use App\Models\FeedReaction;
use App\Models\FeedShare;
use App\Models\News;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AchievementController;

if (! function_exists('resolveFeedTarget')) {
    function resolveFeedTarget(string $feedType, int $feedId): bool
    {
        return match ($feedType) {
            'post' => FeedPost::query()->whereKey($feedId)->exists(),
            'news' => News::query()->whereKey($feedId)->exists(),
            'event' => Event::query()->whereKey($feedId)->exists(),
            'testimonial' => Testimonial::query()->whereKey($feedId)->exists(),
            default => false,
        };
    }
}

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🏠 Home
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()?->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dashboard');
    }

    $news = News::query()
        ->latest('published_at')
        ->take(4)
        ->get();

    $events = Event::query()
        ->where('start_at', '>=', now())
        ->orderBy('start_at', 'asc')
        ->take(3)
        ->get();

    $jobs = JobOpportunity::query()
        ->latest()
        ->take(4)
        ->get();

    return view('welcome', [
        'news' => $news,
        'events' => $events,
        'jobs' => $jobs,
    ]);
});


// 📊 Profile (after login)
use App\Models\Profile;
use App\Models\Professional;

Route::get('/profile', [ProfileController::class, 'showProfile'])
    ->middleware(['auth'])
    ->name('profile');

Route::get('/newsroom', [NewsController::class, 'index'])->name('newsroom');
Route::get('/newsroom/{id}', [NewsController::class, 'show'])->name('news.show');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
Route::get('/jobs', [JobOpportunityController::class, 'index'])->middleware('jobs.auth')->name('jobs.index');
Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
Route::view('/about', 'pages.about')->name('about');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/gallery/albums/{id}', [GalleryController::class, 'albumShow'])->name('gallery.album.show');
Route::get('/gallery/videos/{id}', [GalleryController::class, 'videoShow'])->name('gallery.video.show');
Route::get('/engage', function () {
    $actor = Auth::user();
    ActivityLog::record(
        $actor?->id,
        $actor?->id,
        'engage_viewed',
        ($actor?->name ?? 'Guest') . ' viewed engage page'
    );

    return view('pages.engage');
})->name('engage');

Route::get('/engage/mentor-students', function () {
    $actor = Auth::user();
    ActivityLog::record(
        $actor?->id,
        $actor?->id,
        'engage_mentor_viewed',
        ($actor?->name ?? 'Guest') . ' viewed engage mentor page'
    );

    return view('pages.mentor-students');
})->name('engage.mentor');

Route::get('/engage/host-event', function () {
    $actor = Auth::user();
    ActivityLog::record(
        $actor?->id,
        $actor?->id,
        'engage_host_viewed',
        ($actor?->name ?? 'Guest') . ' viewed engage host-event page'
    );

    return view('pages.host-event');
})->name('engage.host');

Route::get('/engage/share-opportunities', function () {
    $actor = Auth::user();
    ActivityLog::record(
        $actor?->id,
        $actor?->id,
        'engage_share_viewed',
        ($actor?->name ?? 'Guest') . ' viewed engage share-opportunities page'
    );

    return view('pages.share-opportunities');
})->name('engage.share');
Route::view('/contact', 'pages.contact')->name('contact');
// Route::view('/jobs', 'pages.jobs')->name('jobs.index');
Route::middleware('auth')->group(function () {
    Route::get('/achievements/create', [AchievementController::class, 'create'])->name('achievements.create');
    Route::post('/achievements', [AchievementController::class, 'store'])->name('achievements.store');
});

Route::post('/cookie-consent', function (Request $request) {
    $validated = $request->validate([
        'level' => ['required', 'string', 'in:essential,all,custom'],
        'preferences' => ['nullable', 'boolean'],
    ]);

    $allowPreferences = $validated['level'] === 'all'
        || ($validated['level'] === 'custom' && $request->boolean('preferences'));

    $response = response()->json([
        'message' => 'Cookie preferences saved.',
        'preferences' => $allowPreferences,
    ]);

    $minutes = 60 * 24 * 180;

    $response->withCookie(Cookie::make('sru_cookie_consent', $validated['level'], $minutes, null, null, false, false, false, 'Lax'));
    $response->withCookie(Cookie::make('sru_cookie_preferences', $allowPreferences ? '1' : '0', $minutes, null, null, false, false, false, 'Lax'));

    if (! $allowPreferences) {
        Cookie::queue(Cookie::forget('sru_feed_density'));
    }

    return $response;
})->name('cookie-consent.store');

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user?->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    $profile = $user->profile;
    $lastSessionAction = session('dashboard_last_action', 'No feed action yet in this session.');
    $feedDensity = request()->cookie('sru_feed_density', 'comfortable');
    $feedDensity = in_array($feedDensity, ['comfortable', 'compact'], true) ? $feedDensity : 'comfortable';

    $latestMembers = User::query()
        ->where('role', 'user')
        ->with('profile')
        ->latest()
        ->take(6)
        ->get();

    $memberCount = User::query()
        ->where('role', 'user')
        ->get()
        ->count();

    $upcomingEvents = Event::query()
        ->where('start_at', '>=', now())
        ->orderBy('start_at', 'asc')
        ->take(3)
        ->get();

    $latestNews = News::query()
        ->latest('published_at')
        ->latest('created_at')
        ->take(3)
        ->get();

    $latestTestimonials = Testimonial::query()
        ->where('status', 'active')
        ->latest()
        ->take(2)
        ->get();

    $latestPosts = FeedPost::query()
        ->with(['user.profile'])
        ->latest()
        ->take(10)
        ->get();

    $feedTypes = ['post', 'news', 'event', 'testimonial'];

    $reactionCounts = FeedReaction::query()
        ->where(function ($query) use ($feedTypes) {
            foreach ($feedTypes as $index => $feedType) {
                if ($index === 0) {
                    $query->where('feed_type', $feedType);
                } else {
                    $query->orWhere('feed_type', $feedType);
                }
            }
        })
        ->get()
        ->groupBy(fn ($item) => $item->feed_type . ':' . $item->feed_id)
        ->map(fn ($items) => $items->count());

    $commentCounts = FeedComment::query()
        ->where(function ($query) use ($feedTypes) {
            foreach ($feedTypes as $index => $feedType) {
                if ($index === 0) {
                    $query->where('feed_type', $feedType);
                } else {
                    $query->orWhere('feed_type', $feedType);
                }
            }
        })
        ->get()
        ->groupBy(fn ($item) => $item->feed_type . ':' . $item->feed_id)
        ->map(fn ($items) => $items->count());

    $shareCounts = FeedShare::query()
        ->where(function ($query) use ($feedTypes) {
            foreach ($feedTypes as $index => $feedType) {
                if ($index === 0) {
                    $query->where('feed_type', $feedType);
                } else {
                    $query->orWhere('feed_type', $feedType);
                }
            }
        })
        ->get()
        ->groupBy(fn ($item) => $item->feed_type . ':' . $item->feed_id)
        ->map(fn ($items) => $items->count());

    $viewerReactionKeys = FeedReaction::query()
        ->where('user_id', $user->id)
        ->where(function ($query) use ($feedTypes) {
            foreach ($feedTypes as $index => $feedType) {
                if ($index === 0) {
                    $query->where('feed_type', $feedType);
                } else {
                    $query->orWhere('feed_type', $feedType);
                }
            }
        })
        ->get()
        ->mapWithKeys(fn ($item) => [$item->feed_type . ':' . $item->feed_id => true]);

    $commentGroups = FeedComment::query()
        ->with('user')
        ->where(function ($query) use ($feedTypes) {
            foreach ($feedTypes as $index => $feedType) {
                if ($index === 0) {
                    $query->where('feed_type', $feedType);
                } else {
                    $query->orWhere('feed_type', $feedType);
                }
            }
        })
        ->latest()
        ->get()
        ->groupBy(fn ($item) => $item->feed_type . ':' . $item->feed_id);

    return view('dashboard', compact(
        'user',
        'profile',
        'latestMembers',
        'memberCount',
        'upcomingEvents',
        'latestNews',
        'latestTestimonials',
        'latestPosts',
        'reactionCounts',
        'commentCounts',
        'shareCounts',
        'viewerReactionKeys',
        'commentGroups',
        'lastSessionAction',
        'feedDensity'
    ));
})->middleware(['auth'])->name('dashboard');

Route::get('/feed', function () {
    return redirect()->route('dashboard');
})->middleware(['auth'])->name('feed');

Route::middleware(['auth'])->group(function () {
    Route::post('/dashboard/preferences/feed-density', function (Request $request) {
        $validated = $request->validate([
            'density' => ['required', 'string', 'in:comfortable,compact'],
        ]);

        if ($request->cookie('sru_cookie_preferences') !== '1') {
            return response()->json([
                'message' => 'Preference cookies must be enabled before saving dashboard display preferences.',
            ], 403);
        }

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'dashboard_feed_density_updated',
            ($actor?->name ?? 'Alumni') . ' updated feed density preference to ' . $validated['density'],
            [
                'density' => $validated['density'],
            ]
        );

        return response()
            ->json([
                'message' => 'Feed density saved.',
                'density' => $validated['density'],
            ])
            ->withCookie(Cookie::make('sru_feed_density', $validated['density'], 60 * 24 * 180, null, null, false, false, false, 'Lax'));
    })->name('dashboard.preferences.feed-density');

    Route::post('/dashboard/feed/posts', function (Request $request) {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:1200'],
            'post_type' => ['required', 'string', 'in:opportunity,meetup,memory,mentoring,update'],
        ]);

        $post = FeedPost::create([
            'user_id' => Auth::id(),
            'post_type' => $validated['post_type'],
            'body' => $validated['body'],
        ]);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'feed_post_created',
            ($actor?->name ?? 'Alumni') . ' created a feed post',
            [
                'post_id' => $post->id,
                'post_type' => $post->post_type,
                'body_preview' => substr($post->body, 0, 120),
            ]
        );

        $post->load(['user.profile']);
        session(['dashboard_last_action' => 'Posted a ' . $validated['post_type'] . ' update.']);

        if ($request->expectsJson()) {
            $authorName = $post->user?->profile?->full_name ?: ($post->user?->name ?? 'Alumni');

            return response()->json([
                'post' => [
                    'id' => $post->id,
                    'feed_type' => 'post',
                    'feed_id' => $post->id,
                    'raw_type' => $post->post_type,
                    'kind' => ucwords($post->post_type),
                    'source' => $authorName,
                    'time' => 'Just now',
                    'title' => 'Shared by ' . $authorName,
                    'body' => $post->body,
                    'href' => route('profile'),
                    'cta' => 'View profile',
                    'accent' => '#2a9d8f',
                    'like_url' => route('dashboard.feed.like', ['post', $post->id]),
                    'comment_url' => route('dashboard.feed.comments.store', ['post', $post->id]),
                    'share_url' => route('dashboard.feed.share', ['post', $post->id]),
                ],
            ], 201);
        }

        return back()->with('status', 'Post shared.');
    })->name('dashboard.feed.posts.store');

    Route::post('/dashboard/feed/{feedType}/{feedId}/like', function (string $feedType, int $feedId) {
        abort_unless(resolveFeedTarget($feedType, $feedId), 404);

        $attributes = [
            'user_id' => Auth::id(),
            'feed_type' => $feedType,
            'feed_id' => $feedId,
            'reaction' => 'like',
        ];

        $existing = FeedReaction::query()->where($attributes)->first();

        if ($existing) {
            FeedReaction::query()->whereKey($existing->getKey())->delete();
            session(['dashboard_last_action' => 'Removed a reaction.']);

            $actor = Auth::user();
            ActivityLog::record(
                $actor?->id,
                $actor?->id,
                'feed_reaction_removed',
                ($actor?->name ?? 'Alumni') . ' removed a reaction from feed item',
                [
                    'feed_type' => $feedType,
                    'feed_id' => $feedId,
                    'reaction' => 'like',
                ]
            );

            $count = FeedReaction::query()
                ->where('feed_type', $feedType)
                ->where('feed_id', $feedId)
                ->get()
                ->count();

            if (request()->expectsJson()) {
                return response()->json([
                    'liked' => false,
                    'count' => $count,
                ]);
            }

            return back()->with('status', 'Reaction removed.');
        }

        FeedReaction::create($attributes);
        session(['dashboard_last_action' => 'Liked a feed item.']);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'feed_reaction_added',
            ($actor?->name ?? 'Alumni') . ' liked a feed item',
            [
                'feed_type' => $feedType,
                'feed_id' => $feedId,
                'reaction' => 'like',
            ]
        );

        $count = FeedReaction::query()
            ->where('feed_type', $feedType)
            ->where('feed_id', $feedId)
            ->get()
            ->count();

        if (request()->expectsJson()) {
            return response()->json([
                'liked' => true,
                'count' => $count,
            ]);
        }

        return back()->with('status', 'Reaction added.');
    })->name('dashboard.feed.like');

    Route::post('/dashboard/feed/{feedType}/{feedId}/comments', function (Request $request, string $feedType, int $feedId) {
        abort_unless(resolveFeedTarget($feedType, $feedId), 404);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:500'],
        ]);

        $comment = FeedComment::create([
            'user_id' => Auth::id(),
            'feed_type' => $feedType,
            'feed_id' => $feedId,
            'body' => $validated['body'],
        ]);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'feed_comment_added',
            ($actor?->name ?? 'Alumni') . ' commented on a feed item',
            [
                'comment_id' => $comment->id,
                'feed_type' => $feedType,
                'feed_id' => $feedId,
                'body_preview' => substr($comment->body, 0, 120),
            ]
        );

        $comment->load('user');
        session(['dashboard_last_action' => 'Commented on a feed item.']);

        $count = FeedComment::query()
            ->where('feed_type', $feedType)
            ->where('feed_id', $feedId)
            ->get()
            ->count();

        if ($request->expectsJson()) {
            return response()->json([
                'count' => $count,
                'comment' => [
                    'author' => $comment->user?->name ?? 'Alumni',
                    'body' => $comment->body,
                ],
            ], 201);
        }

        return back()->with('status', 'Comment posted.');
    })->name('dashboard.feed.comments.store');

    Route::post('/dashboard/feed/{feedType}/{feedId}/share', function (string $feedType, int $feedId) {
        abort_unless(resolveFeedTarget($feedType, $feedId), 404);

        FeedShare::create([
            'user_id' => Auth::id(),
            'feed_type' => $feedType,
            'feed_id' => $feedId,
        ]);
        session(['dashboard_last_action' => 'Shared a feed item.']);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'feed_shared',
            ($actor?->name ?? 'Alumni') . ' shared a feed item',
            [
                'feed_type' => $feedType,
                'feed_id' => $feedId,
            ]
        );

        $count = FeedShare::query()
            ->where('feed_type', $feedType)
            ->where('feed_id', $feedId)
            ->get()
            ->count();

        if (request()->expectsJson()) {
            return response()->json([
                'count' => $count,
            ]);
        }

        return back()->with('status', 'Shared to your alumni activity.');
    })->name('dashboard.feed.share');
});

// 🔐 AUTH REQUIRED ROUTES
Route::middleware(['auth'])->group(function () {

    // ✅ CREATE PROFILE
    Route::get('/profile/create', [ProfileController::class, 'createProfile'])->name('profile.create');

    // ✅ STORE PROFILE
    Route::post('/profile/store', [ProfileController::class, 'storeProfile'])->name('profile.store');

    // ✅ EDIT PROFILE (FIXED 🔥)
    Route::get('/profile/edit', [ProfileController::class, 'editProfile'])->name('profile.edit');

    // ✅ UPDATE PROFILE (FIXED 🔥)
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');

    // 📨 MESSAGES
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/unread/count', [MessageController::class, 'getUnreadCount'])->name('messages.unread.count');

    // 🛠️ SKILLS
    Route::resource('skills', SkillController::class)->except(['show']);
    Route::post('/skills/{skill}/endorse', [SkillController::class, 'endorse'])->name('skills.endorse');
    Route::delete('/skills/{skill}/endorse', [SkillController::class, 'removeEndorsement'])->name('skills.remove-endorsement');

    // 💼 JOBS AND INTERNSHIPS
    Route::get('/jobs/create', [JobOpportunityController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [JobOpportunityController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{job}/edit', [JobOpportunityController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{job}', [JobOpportunityController::class, 'update'])->name('jobs.update');

    // 📝 BIO EDITING
    Route::get('/profile/edit-bio', [ProfileController::class, 'editBio'])->name('profile.edit-bio');
    Route::put('/profile/update-bio', [ProfileController::class, 'updateBio'])->name('profile.update-bio');

    // ❌ REMOVE OLD DEFAULT PROFILE ROUTES (they cause confusion)
    // Route::get('/profile', ...)
    // Route::patch('/profile', ...)
});


// 🧪 TEST ROUTE (optional)
Route::get('/test-profile', function () {
    return view('profile.create');
});


// 🔐 ADMIN LOGIN PAGE
Route::get('/admin/login', function () {
    return view('auth.login');
});


// 🛡 ADMIN PANEL
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin/dashboard', function () {

        $users = User::query()->where('role', 'user')
            ->with(['profile', 'professional'])
            ->orderByDesc('created_at')
            ->get();

        $totalCount = $users->count();
        $weeklyNewCount = $users->filter(fn ($user) => $user->created_at?->isCurrentWeek())->count();
        $yearsCount = $users
            ->pluck('profile.passing_year')
            ->filter(fn ($year) => !empty($year) && $year !== '—')
            ->unique()
            ->count();
 
        // Placeholder until a dedicated messages module is connected.
        $unreadMessagesCount = 0;

        $departmentBreakdown = $users
            ->groupBy(fn ($user) => $user->profile?->branch ?: 'Unspecified')
            ->map(function ($group, $department) use ($totalCount) {
                $count = $group->count();

                return [
                    'department' => $department,
                    'count' => $count,
                    'percent' => $totalCount > 0 ? (int) round(($count / $totalCount) * 100) : 0,
                ];
            })
            ->sortByDesc('count')
            ->take(6)
            ->values();

        $recentActivity = ActivityLog::query()
            ->latest('created_at')
            ->take(8)
            ->get()
            ->map(fn ($item) => [
                'type' => in_array($item->action, ['user_registered', 'profile_created'], true) ? 'registered' : 'updated',
                'text' => $item->description,
                'time' => $item->created_at?->diffForHumans() ?? 'just now',
            ]);

        $latestNews = News::query()
            ->latest('updated_at')
            ->take(5)
            ->get(['id', 'title', 'excerpt', 'updated_at', 'published_at']);

        $upcomingEvents = Event::query()
            ->where('start_at', '>=', now())
            ->orderBy('start_at', 'asc')
            ->take(5)
            ->get(['id', 'title', 'excerpt', 'event_type', 'start_at', 'location']);

        $totalChange = 'All time';
        $totalChipText = $weeklyNewCount > 0 ? ('↑ ' . $weeklyNewCount . ' this week') : 'No new this week';
        $batchChipText = $yearsCount > 0 ? ($yearsCount . ' recorded batches') : 'No batch data';
        $messagesChipText = 'View inbox';

        return view('admin.dashboard.panel', compact(
            'users',
            'totalCount',
            'yearsCount',
            'totalChange',
            'unreadMessagesCount',
            'departmentBreakdown',
            'recentActivity',
            'latestNews',
            'upcomingEvents',
            'totalChipText',
            'batchChipText',
            'messagesChipText'
        ));

    })->name('admin.dashboard');

    // Backward-compatible redirect from old alumni URL.
    Route::redirect('/admin/allalumini', '/admin/all-alumini', 301);

    // Admin list page for all registered alumni records.
    Route::get('/admin/all-alumini', function () {
        $selectedFilterBy = request('filter_by', 'all');
        $allowedFilters = ['all', 'branch', 'graduation_year', 'organization', 'role', 'location'];
        $selectedFilterBy = in_array($selectedFilterBy, $allowedFilters, true) ? $selectedFilterBy : 'all';
        $selectedFilterValue = trim((string) request('filter_value', ''));

        // Backward compatibility: map previous organization-only query param.
        if ($selectedFilterBy === 'organization' && $selectedFilterValue === '') {
            $selectedFilterValue = trim((string) request('organization', ''));
        }

        $usersQuery = User::query()
            ->where('role', 'user')
            ->with(['profile', 'professional', 'skills']);

        if ($selectedFilterBy !== 'all' && $selectedFilterValue !== '') {
            if ($selectedFilterBy === 'branch') {
                $usersQuery->whereHas('profile', function ($query) use ($selectedFilterValue) {
                    $query->where('branch', $selectedFilterValue);
                });
            }

            if ($selectedFilterBy === 'graduation_year') {
                $usersQuery->whereHas('profile', function ($query) use ($selectedFilterValue) {
                    $query->where('passing_year', $selectedFilterValue);
                });
            }

            if ($selectedFilterBy === 'organization') {
                $usersQuery->whereHas('professional', function ($query) use ($selectedFilterValue) {
                    $query->where('organization', $selectedFilterValue);
                });
            }

            if ($selectedFilterBy === 'role') {
                $usersQuery->whereHas('professional', function ($query) use ($selectedFilterValue) {
                    $query->where('role', $selectedFilterValue);
                });
            }

            if ($selectedFilterBy === 'location') {
                $usersQuery->whereHas('professional', function ($query) use ($selectedFilterValue) {
                    $query->where('location', $selectedFilterValue);
                });
            }
        }

        $users = $usersQuery
            ->orderByDesc('id')
            ->get();

        $filterValues = collect();

        if ($selectedFilterBy === 'branch') {
            $filterValues = Profile::query()
                ->distinct()
                ->pluck('branch')
                ->filter(fn ($branch) => !is_null($branch) && $branch !== '')
                ->sort()
                ->values();
        }

        if ($selectedFilterBy === 'graduation_year') {
            $filterValues = Profile::query()
                ->distinct()
                ->pluck('passing_year')
                ->filter(fn ($year) => !is_null($year) && $year !== '')
                ->sortDesc()
                ->values()
                ->map(fn ($year) => (string) $year);
        }

        if ($selectedFilterBy === 'organization') {
            $filterValues = Professional::query()
                ->distinct()
                ->pluck('organization')
                ->filter(fn ($organization) => !is_null($organization) && $organization !== '')
                ->sort()
                ->values();
        }

        if ($selectedFilterBy === 'role') {
            $filterValues = Professional::query()
                ->distinct()
                ->pluck('role')
                ->filter(fn ($role) => !is_null($role) && $role !== '')
                ->sort()
                ->values();
        }

        if ($selectedFilterBy === 'location') {
            $filterValues = Professional::query()
                ->distinct()
                ->pluck('location')
                ->filter(fn ($location) => !is_null($location) && $location !== '')
                ->sort()
                ->values();
        }

        return view('admin.alumni.allalumini', compact('users', 'filterValues', 'selectedFilterBy', 'selectedFilterValue'));
    })->name('admin.allalumini');

    // CSV export for alumni list with same filters as the list page.
    Route::get('/admin/all-alumini/export', function () {
        $selectedFilterBy = request('filter_by', 'all');
        $allowedFilters = ['all', 'branch', 'graduation_year', 'organization', 'role', 'location'];
        $selectedFilterBy = in_array($selectedFilterBy, $allowedFilters, true) ? $selectedFilterBy : 'all';
        $selectedFilterValue = trim((string) request('filter_value', ''));

        if ($selectedFilterBy === 'organization' && $selectedFilterValue === '') {
            $selectedFilterValue = trim((string) request('organization', ''));
        }

        $usersQuery = User::query()
            ->where('role', 'user')
            ->with(['profile', 'professional'])
            ->orderByDesc('id');

        if ($selectedFilterBy !== 'all' && $selectedFilterValue !== '') {
            if ($selectedFilterBy === 'branch') {
                $usersQuery->whereHas('profile', function ($query) use ($selectedFilterValue) {
                    $query->where('branch', $selectedFilterValue);
                });
            }

            if ($selectedFilterBy === 'graduation_year') {
                $usersQuery->whereHas('profile', function ($query) use ($selectedFilterValue) {
                    $query->where('passing_year', $selectedFilterValue);
                });
            }

            if ($selectedFilterBy === 'organization') {
                $usersQuery->whereHas('professional', function ($query) use ($selectedFilterValue) {
                    $query->where('organization', $selectedFilterValue);
                });
            }

            if ($selectedFilterBy === 'role') {
                $usersQuery->whereHas('professional', function ($query) use ($selectedFilterValue) {
                    $query->where('role', $selectedFilterValue);
                });
            }

            if ($selectedFilterBy === 'location') {
                $usersQuery->whereHas('professional', function ($query) use ($selectedFilterValue) {
                    $query->where('location', $selectedFilterValue);
                });
            }
        }

        $users = $usersQuery->get();
        $filename = 'sru_alumni_export_' . now()->format('Y-m-d') . '.csv';

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'alumni_exported_csv',
            ($actor?->name ?? 'Admin') . ' exported alumni CSV',
            [
                'filter_by' => $selectedFilterBy,
                'filter_value' => $selectedFilterValue,
                'exported_count' => $users->count(),
            ]
        );

        return response()->streamDownload(function () use ($users) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID', 'Account Name', 'Email',
                'Full Name', 'Father Name', 'Phone', 'City', 'Country',
                'Degree', 'Branch / Specialization', 'Graduation Year',
                'Current Status', 'Company',
                'LinkedIn', 'Facebook', 'Instagram', 'Twitter',
                'Organization', 'Industry', 'Role',
                'Work From', 'Work To', 'Work Location',
                'Registered',
            ]);

            foreach ($users as $u) {
                fputcsv($handle, [
                    $u->id,
                    $u->name,
                    $u->email,
                    $u->profile?->full_name ?? $u->name,
                    $u->profile?->father_name ?? '-',
                    $u->profile?->mobile ?? '-',
                    $u->profile?->city ?? '-',
                    $u->profile?->country ?? '-',
                    $u->profile?->degree ?? '-',
                    $u->profile?->branch ?? '-',
                    $u->profile?->passing_year ?? '-',
                    $u->profile?->current_status ?? '-',
                    $u->profile?->company ?? '-',
                    $u->profile?->linkedin ?? '-',
                    $u->profile?->facebook ?? '-',
                    $u->profile?->instagram ?? '-',
                    $u->profile?->twitter ?? '-',
                    $u->professional?->organization ?? '-',
                    $u->professional?->industry ?? '-',
                    $u->professional?->role ?? '-',
                    $u->professional?->from ?? '-',
                    $u->professional?->to ?? '-',
                    $u->professional?->location ?? '-',
                    $u->created_at?->format('d M Y') ?? '-',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    })->name('admin.allalumini.export');

    // Persistent audit logs (view + filtered CSV export).
    Route::get('/admin/activity-logs', [AdminController::class, 'activityLogs'])->name('admin.activity-logs');
    Route::get('/admin/activity-logs/export', [AdminController::class, 'exportActivityLogsCsv'])->name('admin.activity-logs.export');
    Route::get('/admin/news/new', [NewsController::class, 'adminCreate'])->name('admin.news.create');
    Route::get('/admin/news/manage', [NewsController::class, 'adminManage'])->name('admin.news.manage');
    Route::get('/admin/news/{id}/edit', [NewsController::class, 'adminEdit'])->name('admin.news.edit');
    Route::post('/admin/news', [NewsController::class, 'adminStore'])->name('admin.news.store');
    Route::put('/admin/news/{id}', [NewsController::class, 'adminUpdate'])->name('admin.news.update');
    Route::delete('/admin/news/{id}', [NewsController::class, 'adminDestroy'])->name('admin.news.delete');
    Route::get('/admin/events/new', [EventController::class, 'adminCreate'])->name('admin.events.create');
    Route::post('/admin/events', [EventController::class, 'adminStore'])->name('admin.events.store');
    Route::get('/admin/events/manage', [EventController::class, 'adminManage'])->name('admin.events.manage');
    Route::get('/admin/events/{id}/edit', [EventController::class, 'adminEdit'])->name('admin.events.edit');
    Route::put('/admin/events/{id}', [EventController::class, 'adminUpdate'])->name('admin.events.update');
    Route::delete('/admin/events/{id}', [EventController::class, 'adminDestroy'])->name('admin.events.delete');
    Route::get('/admin/jobs/new', [AdminJobOpportunityController::class, 'create'])->name('admin.jobs.create');
    Route::post('/admin/jobs', [AdminJobOpportunityController::class, 'store'])->name('admin.jobs.store');
    Route::get('/admin/jobs/manage', [AdminJobOpportunityController::class, 'manage'])->name('admin.jobs.manage');
    Route::get('/admin/jobs/{id}/edit', [AdminJobOpportunityController::class, 'edit'])->name('admin.jobs.edit');
    Route::put('/admin/jobs/{id}', [AdminJobOpportunityController::class, 'update'])->name('admin.jobs.update');
    Route::delete('/admin/jobs/{id}', [AdminJobOpportunityController::class, 'destroy'])->name('admin.jobs.delete');
    Route::get('/admin/engage/new', [AdminEngageController::class, 'create'])->name('admin.engage.create');
    Route::post('/admin/engage', [AdminEngageController::class, 'store'])->name('admin.engage.store');
    Route::get('/admin/engage/manage', [AdminEngageController::class, 'manage'])->name('admin.engage.manage');
    Route::get('/admin/engage/{id}/review', [AdminEngageController::class, 'edit'])->name('admin.engage.review');
    Route::put('/admin/engage/{id}', [AdminEngageController::class, 'update'])->name('admin.engage.update');
    Route::delete('/admin/engage/{id}', [AdminEngageController::class, 'destroy'])->name('admin.engage.delete');
    Route::delete('/admin/engage/comments/{comment}', [AdminEngageController::class, 'destroyComment'])->name('admin.engage.comments.delete');
    Route::delete('/admin/engage/reactions/{reaction}', [AdminEngageController::class, 'destroyReaction'])->name('admin.engage.reactions.delete');
    Route::get('/admin/gallery/new', [GalleryAdminController::class, 'adminCreate'])->name('admin.gallery.create');
    Route::get('/admin/gallery/manage', [GalleryAdminController::class, 'adminManage'])->name('admin.gallery.manage');
    Route::get('/admin/gallery/{section}/{id}/edit', [GalleryAdminController::class, 'adminEdit'])->name('admin.gallery.edit');
    Route::post('/admin/gallery/{section}', [GalleryAdminController::class, 'adminStore'])->name('admin.gallery.store');
    Route::put('/admin/gallery/{section}/{id}', [GalleryAdminController::class, 'adminUpdate'])->name('admin.gallery.update');
    Route::delete('/admin/gallery/{section}/{id}', [GalleryAdminController::class, 'adminDestroy'])->name('admin.gallery.delete');
    Route::delete('/admin/alumni/{id}', [AdminController::class, 'deleteAlumni'])->name('admin.alumni.delete');
    Route::get('/admin/alumni/{id}/edit', [AdminController::class, 'editAlumni'])->name('admin.alumni.edit');
    Route::put('/admin/alumni/{id}', [AdminController::class, 'updateAlumni'])->name('admin.alumni.update');
    Route::post('/admin/profile/avatar', [AdminController::class, 'updateAdminAvatar'])->name('admin.profile.avatar');

});


// 🔑 AUTH ROUTES (Laravel Breeze / Jetstream)
require __DIR__.'/auth.php';
