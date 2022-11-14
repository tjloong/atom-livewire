<?php

namespace Jiannius\Atom\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SiteSecurity
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
        if (app()->environment('local')) return $next($request);

        // redirect http to https
        if (!$request->secure()) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        // prevent host poisoning
        if ($allowedHosts = collect([config('app.url')])
            ->concat(config('atom.allowed_hosts', []))
            ->filter()
            ->map(fn($host) => str($host)->replaceFirst('http://', '')->toString())
            ->map(fn($host) => str($host)->replaceFirst('https://', '')->toString())
        ) {
            if (!$allowedHosts->contains($request->host())) abort(400);
        }

        // add HSTS header
        $response = $next($request);

        if (!$request->is('livewire/*') && !$request->is('__*')) {
            $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubdomains');
        }

        return $response;
    }
}
