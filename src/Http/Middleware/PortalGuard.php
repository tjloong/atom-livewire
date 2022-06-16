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

        // redirect ticketing/* to app/ticketing/* if user can access app portal
        if ($request->user()->canAccessPortal('app') && (
            current_route('ticketing.*')
            || current_route('billing*')
            || current_route('account')
        )) {
            return redirect()->route('app.'.current_route(), array_merge(
                $request->route()->parameters(),
                $request->query()
            ));
        }

        return $next($request);
    }
}
