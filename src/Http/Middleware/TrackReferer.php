<?php

namespace Jiannius\Atom\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;

class TrackReferer
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, $days = 7): mixed
    {
        if ($request->user()) return $next($request);

        $ref = request()->query('ref');
        $cookie = request()->cookie('_ref');
        $duration = $days * 24 * 60;

        if ($ref && $ref !== $cookie) {
            Cookie::queue('_ref', $ref, $duration);
        }
        else if ($cookie && !$ref && current_route('web.*')) {
            return redirect($request->fullUrlWithQuery(
                array_merge($request->query(), ['ref' => $cookie])
            ));
        }

        return $next($request);
    }
}