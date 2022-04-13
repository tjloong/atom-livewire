<?php

namespace Jiannius\Atom\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PortalGuard
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
        $paths = explode('/', $request->path());
        $portal = head($paths);

        if (!$request->user()->canAccessPortal($portal)) return redirect()->route('page');

        return $next($request);
    }
}
