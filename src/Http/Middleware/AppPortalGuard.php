<?php

namespace Jiannius\Atom\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AppPortalGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (($request->is('app') || $request->is('app/*')) && $request->user()) {
            if (!$request->user()->canAccessAppPortal()) return redirect()->route('home');
        }

        return $next($request);
    }
}
