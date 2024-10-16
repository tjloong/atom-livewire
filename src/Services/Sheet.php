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
                $response->effects['dispatches'][] = match (get($sheet, 'action')) {
                    'show' => [
                        'event' => 'sheet-show',
                        'data' => [
                            'name' => get($sheet, 'name'),
                            'label' => get($sheet, 'label'),
                            'data' => get($sheet, 'data'),
                        ],
                    ],
                    'label' => [
                        'event' => 'sheet-label',
                        'data' => [
                            'name' => get($sheet, 'name'),
                            'label' => get($sheet, 'label'),
                        ],
                    ],
                    'back' => [
                        'event' => 'sheet-back',
                    ],
                };
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
    public function show($data = null, $label = null)
    {
        return $this->make('show', $data, $label);
    }

    // label
    public function label($label)
    {
        return $this->make('label', null, $label);
    }

    // back
    public function back()
    {
        return $this->make('back');
    }

    // make modal
    public function make($action, $data = null, $label = null)
    {
        $this->sheet = [
            'name' => $this->name,
            'label' => $label,
            'action' => $action,
            'data' => $data,
        ];

        if (!request()->isLivewireRequest()) {
            session()->put('__sheet', $this->sheet);
        }
    }
}