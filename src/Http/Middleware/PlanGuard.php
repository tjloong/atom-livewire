<?php

namespace Jiannius\Atom\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class PlanGuard
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
        if (enabled_module('plans')) {
            $routeGuard = model('plan')->routeGuard();
            $guardedRoutes = collect($routeGuard)->values()->flatten()->unique();

            if ($route = $guardedRoutes->first(fn($val) => current_route($val) && !current_route('*billing*'))) {
                $mustHavePlans = collect(array_keys($routeGuard))
                    ->filter(fn($key) => collect($routeGuard[$key])->search($route) !== false)
                    ->values();

                $subscribedPlans = $mustHavePlans->filter(fn($val) => $request->user()->hasPlan($val)); 

                if (!$subscribedPlans->count() && !$request->user()->is_root) {
                    if (Route::has('app.billing.plan')) return redirect()->route('app.billing.plan');
                    else abort(403);
                }
            }
        }

        return $next($request);
    }
}
