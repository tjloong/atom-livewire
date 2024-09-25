<?php

namespace Jiannius\Atom\Services;

use Livewire\Livewire;

class Toast
{
    public const SINGLETON = true;

    public $toasts = [];

    // relay to livewire
    public static function relayToLivewire()
    {
        Livewire::listen('component.dehydrate', function ($component, $response) {
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
        $this->toasts[] = ['message' => $message, 'type' => $type];

        return $this;
    }
}