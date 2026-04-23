<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\AdminController;

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
use App\Models\Profile;
use App\Models\Professional;

Route::get('/dashboard', function () {

    $user = auth()->user();

    $profile = Profile::where('user_id', $user->id)->first();
    $experiences = Professional::where('user_id', $user->id)
        ->orderBy('from', 'desc')
        ->get();

    return view('dashboard', compact('profile', 'experiences'));

})->middleware(['auth'])->name('dashboard');


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

    Route::delete('/admin/alumni/{id}', [AdminController::class, 'deleteAlumni'])->name('admin.alumni.delete');

    Route::get('/admin/dashboard', function () {

        $users = User::where('role', 'user')->with('profile', 'professional')->get(); // only alumni users

        // Map users with profile and professional data
        $users = $users->map(function($user) {
            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->profile?->mobile,
                'full_name' => $user->profile?->full_name,
                'department' => $user->professional?->industry,
                'graduation_year' => $user->profile?->passing_year,
                'location' => $user->profile?->city,
                'status' => $user->status,
                'created_at' => $user->created_at,
                // Profile data
                'city' => $user->profile?->city,
                'country' => $user->profile?->country,
                'degree' => $user->profile?->degree,
                'branch' => $user->profile?->branch,
                'current_status' => $user->profile?->current_status,
                'company' => $user->profile?->company,
                // Professional data
                'organization' => $user->professional?->organization,
                'industry' => $user->professional?->industry,
                'role' => $user->professional?->role,
                'from' => $user->professional?->from,
                'to' => $user->professional?->to,
                'pro_location' => $user->professional?->location,
            ];
        });

        // Calculate stats
        $totalCount = $users->count();
        $activeCount = $users->where('status', 'Active')->count();
        $inactiveCount = $users->where('status', 'Inactive')->count();
        $pendingCount = $users->where('status', 'Pending')->count();
        $yearsCount = $users->pluck('graduation_year')->unique()->filter()->count();

        // Calculate active change vs last month
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $lastMonth = now()->subMonth()->month;
        $lastYear = now()->subMonth()->year;

        $currentActive = Profile::where('status', 'active')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $lastMonthActive = Profile::where('status', 'active')
            ->whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastYear)
            ->count();

        $activeChange = '';
        if ($lastMonthActive > 0) {
            $percent = (($currentActive - $lastMonthActive) / $lastMonthActive) * 100;
            $symbol = $percent >= 0 ? '↑' : '↓';
            $activeChange = $symbol . ' ' . number_format(abs($percent), 1) . '% vs last month';
        } else {
            $activeChange = '+ ' . $currentActive . ' this month';
        }

        // Calculate total change (new registrations this month)
        $newThisMonth = User::where('role', 'user')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $totalChange = '+ ' . $newThisMonth . ' this month';

        return view('admin.panel', compact('users', 'totalCount', 'activeCount', 'inactiveCount', 'pendingCount', 'yearsCount', 'activeChange', 'totalChange'));

    })->name('admin.dashboard');

    Route::put('/admin/alumni/{id}/approve', [AdminController::class, 'approveAlumni'])->name('admin.alumni.approve');
});


/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';