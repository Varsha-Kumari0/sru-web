<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Profile;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        ActivityLog::record(
            $user?->id,
            $user?->id,
            'user_logged_in',
            ($user?->name ?? 'User') . ' logged in',
            [
                'role' => $user?->role,
                'email' => $user?->email,
            ]
        );

        // admin
        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // check profile
        $profile = Profile::where('user_id', $user->id)->first();

        if ($profile) {
            return redirect()->route('profile');
        }

        return redirect()->route('profile.create');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        ActivityLog::record(
            $user?->id,
            $user?->id,
            'user_logged_out',
            ($user?->name ?? 'User') . ' logged out',
            [
                'role' => $user?->role,
                'email' => $user?->email,
            ]
        );

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}