<?php

namespace Jiannius\Atom\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserLastActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!$request->user()) return $next($request);

        $lastActiveAt = $request->user()->last_active_at;

        if (!$lastActiveAt || $lastActiveAt->diffInMinutes(now()) >= 5) {
            $request->user()->update(['last_active_at' => now()]);
        }

        return $next($request);
    }
}
