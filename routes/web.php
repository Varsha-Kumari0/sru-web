<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🏠 Home
Route::get('/', function () {
    return view('welcome');
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
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

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
            ->get();

        $totalCount = $users->count();
        $activeCount = $users->filter(function ($user) {
            return strtolower($user->profile?->status ?? '') === 'active';
        })->count();
        $pendingCount = $users->filter(function ($user) {
            return strtolower($user->profile?->status ?? 'pending') === 'pending';
        })->count();
        $yearsCount = $users
            ->pluck('profile.passing_year')
            ->filter(fn ($year) => !empty($year) && $year !== '—')
            ->unique()
            ->count();

        $totalChange = 'All time';
        $activeChange = 'Verified members';

        return view('admin.panel', compact(
            'users',
            'totalCount',
            'activeCount',
            'pendingCount',
            'yearsCount',
            'totalChange',
            'activeChange'
        ));

    })->name('admin.dashboard');

    Route::get('/admin/allalumini', function () {
        $users = User::where('role', 'user')
            ->with(['profile', 'professional'])
            ->orderByDesc('id')
            ->get();

        $pendingCount = $users->filter(function ($user) {
            return strtolower($user->profile?->status ?? 'pending') === 'pending';
        })->count();

        return view('admin.allalumini', compact('users', 'pendingCount'));
    })->name('admin.allalumini');

    Route::put('/admin/alumni/{id}/approve', [AdminController::class, 'approveAlumni'])->name('admin.alumni.approve');
    Route::delete('/admin/alumni/{id}', [AdminController::class, 'deleteAlumni'])->name('admin.alumni.delete');

});


// 🔑 AUTH ROUTES (Laravel Breeze / Jetstream)
require __DIR__.'/auth.php';