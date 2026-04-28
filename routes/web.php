<?php

use App\Http\Controllers\AdminController;
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
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\NewsController;

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
Route::get('/events', [\App\Http\Controllers\EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [\App\Http\Controllers\EventController::class, 'show'])->name('events.show');
Route::get('/jobs', [JobOpportunityController::class, 'index'])->name('jobs.index');
Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
Route::view('/about', 'pages.about')->name('about');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/gallery/albums/{id}', [GalleryController::class, 'albumShow'])->name('gallery.album.show');
Route::view('/engage', 'pages.engage')->name('engage');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/jobs', 'pages.jobs')->name('jobs.index');

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user?->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    $profile = $user->profile;

    $latestMembers = User::query()
        ->where('role', 'user')
        ->with('profile')
        ->latest()
        ->take(6)
        ->get();

    $memberCount = User::query()
        ->where('role', 'user')
        ->count();

    $upcomingEvents = Event::query()
        ->where('start_at', '>=', now())
        ->orderBy('start_at')
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
        ->whereIn('feed_type', $feedTypes)
        ->get()
        ->groupBy(fn ($item) => $item->feed_type . ':' . $item->feed_id)
        ->map(fn ($items) => $items->count());

    $commentCounts = FeedComment::query()
        ->whereIn('feed_type', $feedTypes)
        ->get()
        ->groupBy(fn ($item) => $item->feed_type . ':' . $item->feed_id)
        ->map(fn ($items) => $items->count());

    $shareCounts = FeedShare::query()
        ->whereIn('feed_type', $feedTypes)
        ->get()
        ->groupBy(fn ($item) => $item->feed_type . ':' . $item->feed_id)
        ->map(fn ($items) => $items->count());

    $viewerReactionKeys = FeedReaction::query()
        ->where('user_id', $user->id)
        ->whereIn('feed_type', $feedTypes)
        ->get()
        ->mapWithKeys(fn ($item) => [$item->feed_type . ':' . $item->feed_id => true]);

    $commentGroups = FeedComment::query()
        ->with('user')
        ->whereIn('feed_type', $feedTypes)
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
        'commentGroups'
    ));
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
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

        $post->load(['user.profile']);

        if ($request->expectsJson()) {
            $authorName = $post->user?->profile?->full_name ?: ($post->user?->name ?? 'Alumni');

            return response()->json([
                'post' => [
                    'id' => $post->id,
                    'feed_type' => 'post',
                    'feed_id' => $post->id,
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
            $existing->delete();
            $count = FeedReaction::query()
                ->where('feed_type', $feedType)
                ->where('feed_id', $feedId)
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

        $count = FeedReaction::query()
            ->where('feed_type', $feedType)
            ->where('feed_id', $feedId)
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

        $comment->load('user');

        $count = FeedComment::query()
            ->where('feed_type', $feedType)
            ->where('feed_id', $feedId)
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

        $count = FeedShare::query()
            ->where('feed_type', $feedType)
            ->where('feed_id', $feedId)
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

        $users = User::where('role', 'user')
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
            ->with(['profile', 'professional']);

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
                ->whereNotNull('branch')
                ->where('branch', '!=', '')
                ->distinct()
                ->orderBy('branch')
                ->pluck('branch');
        }

        if ($selectedFilterBy === 'graduation_year') {
            $filterValues = Profile::query()
                ->whereNotNull('passing_year')
                ->where('passing_year', '!=', '')
                ->distinct()
                ->orderByDesc('passing_year')
                ->pluck('passing_year')
                ->map(fn ($year) => (string) $year);
        }

        if ($selectedFilterBy === 'organization') {
            $filterValues = Professional::query()
                ->whereNotNull('organization')
                ->where('organization', '!=', '')
                ->distinct()
                ->orderBy('organization')
                ->pluck('organization');
        }

        if ($selectedFilterBy === 'role') {
            $filterValues = Professional::query()
                ->whereNotNull('role')
                ->where('role', '!=', '')
                ->distinct()
                ->orderBy('role')
                ->pluck('role');
        }

        if ($selectedFilterBy === 'location') {
            $filterValues = Professional::query()
                ->whereNotNull('location')
                ->where('location', '!=', '')
                ->distinct()
                ->orderBy('location')
                ->pluck('location');
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
