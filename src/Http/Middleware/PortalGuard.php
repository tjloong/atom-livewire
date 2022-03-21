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
        foreach ([
            'app', 
            'billing', 
            'ticketing', 
            'onboarding',
        ] as $prefix) {
            if ((
                $request->is($prefix) 
                || $request->is($prefix.'/') 
                || $request->is($prefix.'/*')
            ) && $request->user()) {
                $method = str()->studly('can-access-'.$prefix.'-portal');
                if (!$request->user()->$method()) return redirect()->route('page');
            }
        }

        return $next($request);
    }
}
