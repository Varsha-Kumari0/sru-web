<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserLastSeen
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! Auth::check()) {
            return $response;
        }

        if (! $request->isMethod('GET') || $request->expectsJson()) {
            return $response;
        }

        $userId = (int) Auth::id();
        $lockKey = 'users:last-seen-updated:' . $userId;

        if (! Cache::add($lockKey, true, now()->addMinutes(5))) {
            return $response;
        }

        User::query()->whereKey($userId)->update([
            'last_seen_at' => now(),
        ]);

        return $response;
    }
}
