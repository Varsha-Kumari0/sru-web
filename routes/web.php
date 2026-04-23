<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (default)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| User (Alumni) Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Profile form page (MAIN PAGE after login)
    Route::get('/profile/create', [ProfileController::class, 'createProfile'])
        ->name('profile.create');

    // Save profile
    Route::post('/profile/store', [ProfileController::class, 'storeProfile'])
        ->name('profile.store');

    // Default Laravel profile routes (leave as is)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Admin login page
Route::get('/admin/login', function () {
    return view('auth.login');
});

// Admin dashboard (protected)
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin/dashboard', function () {

        $users = User::where('role', 'user')->get(); // only alumni users

        return view('admin.panel', compact('users'));

    })->name('admin.dashboard');
});


/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';