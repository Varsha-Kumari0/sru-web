<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Route;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🏠 Home
Route::get('/', function () {
    return view('welcome', [
        'news' => [],
        'events' => [],
        'jobs' => [],
    ]);
});


// 📊 Profile (after login)
use App\Models\Profile;
use App\Models\Professional;

Route::get('/profile', function () {

    $profile = Profile::where('user_id', auth()->id())->first();
    $experiences = Professional::where('user_id', auth()->id())->get();

    return view('profile.profile', compact('profile', 'experiences'));

})->middleware(['auth'])->name('profile');

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

        $totalChange = 'All time';
        $totalChipText = $weeklyNewCount > 0 ? ('↑ ' . $weeklyNewCount . ' this week') : 'No new this week';
        $batchChipText = $yearsCount > 0 ? ($yearsCount . ' recorded batches') : 'No batch data';
        $messagesChipText = 'View inbox';

        return view('admin.panel', compact(
            'users',
            'totalCount',
            'yearsCount',
            'totalChange',
            'unreadMessagesCount',
            'departmentBreakdown',
            'recentActivity',
            'totalChipText',
            'batchChipText',
            'messagesChipText'
        ));

    })->name('admin.dashboard');

    // Backward-compatible redirect from old alumni URL.
    Route::redirect('/admin/allalumini', '/admin/all-alumini', 301);

    // Admin list page for all registered alumni records.
    Route::get('/admin/all-alumini', function () {
        $users = User::where('role', 'user')
            ->with(['profile', 'professional'])
            ->orderByDesc('id')
            ->get();

        return view('admin.allalumini', compact('users'));
    })->name('admin.allalumini');
    // Persistent audit logs (view + filtered CSV export).
    Route::get('/admin/activity-logs', [AdminController::class, 'activityLogs'])->name('admin.activity-logs');
    Route::get('/admin/activity-logs/export', [AdminController::class, 'exportActivityLogsCsv'])->name('admin.activity-logs.export');
    Route::delete('/admin/alumni/{id}', [AdminController::class, 'deleteAlumni'])->name('admin.alumni.delete');
    Route::get('/admin/alumni/{id}/edit', [AdminController::class, 'editAlumni'])->name('admin.alumni.edit');
    Route::put('/admin/alumni/{id}', [AdminController::class, 'updateAlumni'])->name('admin.alumni.update');
    Route::post('/admin/profile/avatar', [AdminController::class, 'updateAdminAvatar'])->name('admin.profile.avatar');

});


// 🔑 AUTH ROUTES (Laravel Breeze / Jetstream)
require __DIR__.'/auth.php';