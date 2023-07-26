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
        optional($request->user())->update(['last_active_at' => now()]);

        return $next($request);
    }
}
