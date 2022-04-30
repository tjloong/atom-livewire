<?php

namespace Jiannius\Atom\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;

class Locale
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
        $config = config('atom.locales');
        $cookie = $request->cookie('locale');
        $locale = collect(explode('/', $request->path()))->filter()->first() ?? $cookie ?? head($config);

        if (in_array($locale, $config)) {
            app()->setLocale($locale);
            if (!$cookie || $cookie !== $locale) Cookie::queue('locale', $locale, 60 * 24 * 30);
        }
        else if ($cookie) app()->setLocale($cookie);

        return $next($request);
    }
}
