<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;

// Home
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/store', [ProfileController::class, 'storeProfile']);
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/create', [ProfileController::class, 'createProfile']);
});

Route::get('/test-profile', function () {
    return view('profile.create');
});
// Admin login page
Route::get('/admin/login', function () {
    return view('auth.login');
});

// Protected admin dashboard
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {

        $users = User::where('role', 'user')->get(); // alumni only

        return view('admin.panel', compact('users'));
    })->name('admin.dashboard');
});

require __DIR__.'/auth.php';
