<?php

namespace Jiannius\Atom\Services;

class Toast
{
    public $toasts = [];

    // to be register in service provider user $this->callAfterResolving
    public static function boot()
    {
        app()->singleton(self::class);

        \Livewire\Livewire::listen('component.dehydrate', function ($component, $response) {
            $response->effects['dispatches'] ??= [];

            foreach (app(self::class)->toasts as $toast) {
                $response->effects['dispatches'][] = ['event' => 'toast-received', 'data' => $toast];
            }
        });
    }

    // make toast
    public function make($message, $type = null)
    {
        $toast = ['type' => $type];

        if (is_array($message)) {
            $toast = [
                ...$toast,
                ...$message,
                'title' => str()->limit(tr(get($message, 'title')), 80),
                'message' => str()->limit(tr(get($message, 'message')), 100),
            ];
        }
        else {
            $message = (string) $message;
            $toast['message'] = str()->limit(tr($message), 100);
        }

        $this->toasts[] = $toast;

        if (!request()->isLivewireRequest()) {
            session()->put('__toasts', $this->toasts);
        }
    }
}