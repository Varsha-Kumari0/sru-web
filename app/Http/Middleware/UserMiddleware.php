<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();
        
        // Only allow users with 'user' role
        if ($user->role === 'user') {
            return $next($request);
        }
        
        // If admin, redirect to admin dashboard
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }
        
        // For any other role, redirect to home
        return redirect('/');
    }
}
