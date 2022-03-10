<?php

namespace Jiannius\Atom\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BillingPortalGuard
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
        if (($request->is('billing') || $request->is('billing/*')) && $request->user()) {
            if (!$request->user()->canAccessBillingPortal()) return redirect()->route('home');
        }

        return $next($request);
    }
}
