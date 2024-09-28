<?php

namespace Jiannius\Atom\Services;

class Confirm
{
    public $confirm;

    // to be register in service provider
    public static function boot()
    {
        app()->singleton(self::class);

        \Livewire\Livewire::listen('component.dehydrate', function ($component, $response) {
            $response->effects['dispatches'] ??= [];

            if ($confirm = app(self::class)->confirm) {
                $response->effects['dispatches'][] = ['event' => 'confirm', 'data' => [
                    ...$confirm,
                    'livewireId' => $component->id,
                ]];
            }
        });
    }

    // make confirm
    public function make($message, $type = null)
    {
        $confirm = ['type' => $type];

        if (is_array($message)) $confirm = [...$confirm, ...$message];
        else $confirm['message'] = (string) $message;

        $this->confirm = $confirm;

        return $this;
    }

    // on accept
    public function onAccept($callback)
    {
        $this->confirm = [
            ...$this->confirm,
            'onAccept' => $callback,
        ];

        return $this;
    }

    // on cancel
    public function onCancel($callback)
    {
        $this->confirm = [
            ...$this->confirm,
            'onCancel' => $callback,
        ];

        return $this;
    }
}