<?php

namespace Jiannius\Atom\Livewire;

use Jiannius\Atom\Traits\Livewire\AtomComponent;
use Livewire\Component;

class Footprint extends Component
{
    use AtomComponent;

    public $footprint = [];

    public function load($data) : void
    {
        $id = get($data, 'id');
        $model = get($data, 'model');
        $model = app($model)->find($id);

        $this->footprint = collect($model->footprint)->map(fn($value, $key) => [
            'timestamp' => $model->footprint($key.'.timestamp')->pretty('datetime'),
            'description' => $model->footprint($key.'.description'),
        ])->sortByDesc('timestamp')->values()->all();
    }

    public function cleanup() : void
    {
        $this->reset('footprint');
    }
}