<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;

// Home
Route::get('/', function () {
    return view('welcome');
});

<<<<<<< HEAD
// Admin login page
Route::get('/admin/login', function () {
    return view('admin.login');
});

// Protected admin dashboard
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {

        $users = User::where('role', 'user')->get(); // alumni only

        return view('admin.panel', compact('users'));
    });
});
});
=======
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
>>>>>>> 8ee68d96137b7c70309db3b082c7dd2ac6ee0dbd
