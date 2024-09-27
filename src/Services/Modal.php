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

            foreach (app(self::class)->modals as $name => $action) {
                $response->effects['dispatches'][] = $action === 'show'
                    ? ['event' => 'modal-show', 'data' => ['name' => $name]]
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
    public function show()
    {
        return $this->make('show');
    }

    // close
    public function close()
    {
        return $this->make('close');
    }

    // make modal
    public function make($action)
    {
        $this->modals[$this->name] = $action;

        if (!request()->isLivewireRequest()) {
            session()->put('__modals', $this->modals);
        }
    }
}