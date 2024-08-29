<?php

namespace Jiannius\Atom\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Bootstrap
{
    // handle an incoming request.
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        if (!app()->environment('local')) {
            // redirect http to https
            if (!$request->secure()) {
                return redirect()->secure($request->getRequestUri(), 301);
            }

            $this->preventHostPoisoning($request);

            // add HSTS header
            if (config('atom.hsts') && !$request->is('livewire/*') && !$request->is('__*')) {
                $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubdomains');
            }
        }

        optional($request->user())->ping();

        return $response;
    }

    // prevent host poisoning
    public function preventHostPoisoning($request) : void
    {
        if ($allowedHosts = collect([config('app.url')])
            ->concat(config('atom.allowed_hosts', []))
            ->filter()
            ->map(fn($host) => str($host)->replaceFirst('http://', '')->toString())
            ->map(fn($host) => str($host)->replaceFirst('https://', '')->toString())
        ) {
            if ($allowedHosts->contains('*') && !str($request->host())->is('*.'.config('app.url'))) abort(400);
            if (!$allowedHosts->contains($request->host())) abort(400);
        }
    }
}
