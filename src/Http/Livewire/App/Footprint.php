<?php

namespace Jiannius\Atom\Http\Livewire\App;

use Jiannius\Atom\Component;

class Footprint extends Component
{
    public $footprint = [];

    // load
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

    // cleanup
    public function cleanup() : void
    {
        $this->reset('footprint');
    }
}