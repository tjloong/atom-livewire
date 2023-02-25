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
            $violatedRoute = $guardedRoutes->first(fn($val) => 
                current_route($val) && !current_route('app.plan.*')
            );

            if ($violatedRoute) {
                $mustHavePlans = collect(array_keys($routeGuard))
                    ->filter(fn($key) => collect($routeGuard[$key])->search($violatedRoute) !== false)
                    ->values();

                $subscribedPlans = $mustHavePlans->filter(fn($val) => $request->user()->hasPlan($val));

                if (!$subscribedPlans->count() && !$request->user()->isTier('root')) {
                    if (Route::has('app.plan.listing')) {
                        return redirect()->route('app.plan.listing');
                    }
                    else abort(403);
                }
            }
        }

        return $next($request);
    }
}
