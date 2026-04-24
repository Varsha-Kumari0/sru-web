<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect('/admin/login');
        }

        $user = auth()->user();
        
        // Only allow users with 'admin' role
        if ($user->role === 'admin') {
            return $next($request);
        }
        
        // If regular user, redirect to profile
        if ($user->role === 'user') {
            return redirect('/profile');
        }
        
        // For any other role, redirect to login
        return redirect('/admin/login');
    }
}
