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
            $status = data_get($user->account, 'status');

            if ($status === 'blocked') {
                dd('Your account is blocked');
            }
            else {
                $paths = explode('/', $request->path());
                $portal = head($paths);
    
                if (!$user->canAccessPortal($portal)) return redirect($user->home());
            }
        }

        return $next($request);
    }
}
