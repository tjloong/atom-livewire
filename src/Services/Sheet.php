<?php

namespace Jiannius\Atom\Services;

class Sheet
{
    public $name;
    public $footer;
    public $sheets = [];

    // to be register in service provider
    public static function boot()
    {
        app()->singleton(self::class);

        \Livewire\Livewire::listen('component.dehydrate', function ($component, $response) {
            $response->effects['dispatches'] ??= [];

            if ($sheets = app(self::class)->sheets) {
                foreach ($sheets as $sheet) {
                    $response->effects['dispatches'][] = match (get($sheet, 'action')) {
                        'show' => [
                            'event' => 'sheet-show',
                            'data' => [
                                'name' => get($sheet, 'name'),
                                'label' => get($sheet, 'label'),
                                'silent' => get($sheet, 'silent'),
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
                            'data' => [
                                'silent' => get($sheet, 'silent'),
                            ],
                        ],
                        'refresh' => [
                            'event' => 'sheet-refresh',
                            'data' => [
                                'name' => get($sheet, 'name'),
                            ],
                        ],
                    };
                }
            }
        });
    }

    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    // for defining sheet footer in admin panel
    // in atom:panel, use <x-slot:sheet-footer/> to define the footer
    public function footer($footer)
    {
        session()->put('__sheet_footer', $footer);

        return $this;
    }

    public function show($data = null, $label = null, $silent = false)
    {
        return $this->make('show', $data, $label, $silent);
    }

    public function label($label)
    {
        return $this->make('label', null, $label);
    }

    public function back()
    {
        return $this->make('back');
    }

    public function close($silent = false)
    {
        return $this->make('back', silent: $silent);
    }

    public function refresh()
    {
        return $this->make('refresh');
    }

    public function make($action, $data = null, $label = null, $silent = false)
    {
        $this->sheets[] = [
            'name' => $this->name,
            'label' => $label,
            'action' => $action,
            'silent' => $silent,
            'data' => $data,
        ];

        if (!request()->isLivewireRequest()) {
            session()->put('__sheets', $this->sheets);
        }
    }
}