<?php

namespace Jiannius\Atom\Services;

class Toast
{
    public const SINGLETON = true;

    public $toasts = [];

    // to be register in service provider user $this->callAfterResolving
    public static function boot()
    {
        \Livewire\Livewire::listen('component.dehydrate', function ($component, $response) {
            $response->effects['dispatches'] ??= [];

            if ($toasts = atom()->toast()->toasts) {
                foreach ($toasts as $toast) {
                    $response->effects['dispatches'][] = ['event' => 'toast-received', 'data' => $toast];
                }
            }
        });
    }

    // info
    public function info($message)
    {
        return $this->make($message, 'info');
    }

    // error
    public function error($message)
    {
        return $this->make($message, 'error');
    }

    // warning
    public function warning($message)
    {
        return $this->make($message, 'warning');
    }

    // success
    public function success($message)
    {
        return $this->make($message, 'success');
    }

    // make
    public function make($message, $type = null)
    {
        $toast = ['type' => $type];

        if (is_string($message)) $toast['message'] = str()->limit(tr($message), 100);
        else {
            $toast = [
                ...$toast,
                ...$message,
                'title' => str()->limit(tr(get($message, 'title')), 80),
                'message' => str()->limit(tr(get($message, 'message')), 100),
            ];
        }

        $this->toasts[] = $toast;

        if (!request()->isLivewireRequest()) {
            session()->put('__toasts', $this->toasts);
        }

        return $this;
    }
}