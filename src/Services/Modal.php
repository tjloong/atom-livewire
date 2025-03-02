<?php

namespace Jiannius\Atom\Services;

class Modal
{
    public $name;
    public $modals = [];

    // to be register in service provider
    public static function boot()
    {
        app()->singleton(self::class);

        \Livewire\Livewire::listen('component.dehydrate', function ($component, $response) {
            $response->effects['dispatches'] ??= [];

            foreach (app(self::class)->modals as $name => $modal) {
                $action = get($modal, 'action');
                $data = get($modal, 'data');
                $variant = get($modal, 'variant');

                $response->effects['dispatches'][] = $action === 'show'
                    ? ['event' => 'modal-show', 'data' => ['name' => $name, 'data' => $data, 'variant' => $variant]]
                    : ['event' => 'modal-close', 'data' => ['name' => $name]];
            }
        });
    }

    public function name($name)
    {
        $this->name = $name ?? 'modal';

        return $this;
    }

    public function show($data = [])
    {
        return $this->make('show', $data);
    }

    public function slide($data = [])
    {
        return $this->make('show', $data, 'slide');
    }

    public function slideLeft($data = [])
    {
        return $this->make('show', $data, 'slide-left');
    }

    public function full($data = [])
    {
        return $this->make('show', $data, 'full');
    }

    public function close()
    {
        return $this->make('close');
    }

    public function make($action, $data = [], $variant = null)
    {
        $this->modals[$this->name] = [
            'action' => $action,
            'data' => $data,
            'variant' => $variant,
        ];

        if (!request()->isLivewireRequest()) {
            session()->put('__modals', $this->modals);
        }
    }
}
