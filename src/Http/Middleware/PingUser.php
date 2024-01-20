<?php

namespace Jiannius\Atom\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PingUser
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!$request->user()) return $next($request);

        if ($request->user()->isRecentlyActive('5 minutes')) {
            $request->user()->ping();
        }

        return $next($request);
    }
}
