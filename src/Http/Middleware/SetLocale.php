<?php

namespace Jiannius\Atom\Http\Middleware;

use Closure;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locales = config('atom.locales');
        $cookie = $request->cookie('locale');
        $locale = $cookie ?? head($locales);

        app()->setLocale($locale);

        return $next($request);
    }
}
