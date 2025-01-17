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

                $response->effects['dispatches'][] = $action === 'show'
                    ? ['event' => 'modal-show', 'data' => ['name' => $name, 'data' => $data,]]
                    : ['event' => 'modal-close', 'data' => ['name' => $name]];
            }
        });
    }

    // set name
    public function name($name)
    {
        $this->name = $name ?? 'modal';

        return $this;
    }

    // show
    public function show($data = [])
    {
        return $this->make('show', $data);
    }

    // close
    public function close()
    {
        return $this->make('close');
    }

    // make modal
    public function make($action, $data = [])
    {
        $this->modals[$this->name] = [
            'action' => $action,
            'data' => $data,
        ];

        if (!request()->isLivewireRequest()) {
            session()->put('__modals', $this->modals);
        }
    }
}