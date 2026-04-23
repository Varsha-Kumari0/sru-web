<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

// Home
Route::get('/', function () {
    return view('welcome');
});

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