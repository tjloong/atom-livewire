<?php

namespace Jiannius\Atom\Services;

class Sheet
{
    public $name;
    public $sheet;

    // to be register in service provider
    public static function boot()
    {
        app()->singleton(self::class);

        \Livewire\Livewire::listen('component.dehydrate', function ($component, $response) {
            $response->effects['dispatches'] ??= [];

            if ($sheet = app(self::class)->sheet) {
                $response->effects['dispatches'][] = get($sheet, 'action') === 'show'
                    ? ['event' => 'sheet-show', 'data' => ['name' => get($sheet, 'name'), 'label' => get($sheet, 'label'), 'data' => get($sheet, 'data')]]
                    : ['event' => 'sheet-back'];
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
    public function show($data = null)
    {
        return $this->make('show', $data);
    }

    // back
    public function back()
    {
        return $this->make('back');
    }

    // make modal
    public function make($action, $data = null)
    {
        $this->sheet = [
            'name' => $this->name,
            'action' => $action,
            'data' => $data,
        ];

        if (!request()->isLivewireRequest()) {
            session()->put('__sheet', $this->sheet);
        }
    }
}