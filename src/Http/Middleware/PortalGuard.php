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
        if ((bool)$request->query('webview')) {
            session(['webview' => $request->userAgent()]);
        }

        if ($user = $request->user()) {
            if ($user->status === 'blocked') {
                dd('Your account is blocked');
            }
            else {
                $user->fill(['last_active_at' => now()])->saveQuietly();

                $paths = explode('/', $request->path());
                $portal = head($paths);
    
                if (!$user->canAccessPortal($portal)) {
                    if ($request->expectsJson()) return abort(401);
                    else return redirect($user->home());
                }
            }
        }

        if (!session('settings')) {
            session(['settings' => model('site_setting')->generate()]);
        }

        if (enabled_module('tenants') && !session('tenant') && ($tenant = model('tenant')->current()->first())) {
            session(['tenant' => $tenant]);
        }

        return $next($request);
    }
}
