<?php

namespace Jiannius\Atom\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;

class TrackReferer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $days = 7)
    {
        $ref = request()->query('ref');
        $cookie = request()->cookie('_ref');
        $duration = $days * 24 * 60;

        // ref is trackable
        if ($this->isTrackable($ref) && $ref !== $cookie) {
            Cookie::queue('_ref', $ref, $duration);
        }
        // got ref cookie, append the ref={cookie} to url
        else if ($cookie) {
            return redirect($request->fullUrlWithQuery(
                array_merge($request->query(), ['ref' => $cookie])
            ));
        }

        return $next($request);
    }

    /**
     * Check ref is trackable
     * 
     * @param string $ref
     * @return boolean
     */
    public function isTrackable($ref = null)
    {
        return !request()->user()
            && !is_null($ref)
            && !(str($ref)->is('page') || str($ref)->is('page-*'))
            && !(str($ref)->is('navbar') || str($ref)->is('navbar-*'))
            && !(str($ref)->is('footer') || str($ref)->is('footer-*'));
    }
}