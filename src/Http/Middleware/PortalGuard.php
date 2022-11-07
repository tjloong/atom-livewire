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
        // prevent host poisoning
        if ($allowedHosts = collect([config('app.url')])
            ->concat(config('atom.allowed_hosts', []))
            ->filter()
            ->map(fn($host) => str($host)->replaceFirst('http://', '')->toString())
            ->map(fn($host) => str($host)->replaceFirst('https://', '')->toString())
        ) {
            if (!$allowedHosts->contains($request->host())) abort(400);
        }

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
    
                if (!$user->canAccessPortal($portal)) {
                    if ($request->expectsJson()) return abort(401);
                    else return redirect($user->home());
                }
            }
        }

        return $next($request);
    }
}
