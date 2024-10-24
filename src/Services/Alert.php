<?php

namespace Jiannius\Atom\Services;

class Alert
{
    public $alert;

    // to be register in service provider
    public static function boot()
    {
        app()->singleton(self::class);

        \Livewire\Livewire::listen('component.dehydrate', function ($component, $response) {
            $response->effects['dispatches'] ??= [];

            if ($alert = app(self::class)->alert) {
                $response->effects['dispatches'][] = ['event' => 'alert', 'data' => $alert];
            }
        });
    }

    public function make($message, $type = null)
    {
        $alert = ['type' => $type];

        if (is_array($message)) {
            $alert = [
                ...$alert,
                ...$message,
                'title' => t(get($message, 'title')),
                'message' => is_array(get($message, 'message'))
                    ? collect(get($message, 'message'))->map(fn ($val) => t($val))->toArray()
                    : t(get($message, 'message')),
            ];
        }
        else {
            $message = (string) $message;
            $alert['message'] = t($message);
        }

        $this->alert = $alert;

        if (!request()->isLivewireRequest()) {
            session()->put('__alert', $this->alert);
        }
    }
}