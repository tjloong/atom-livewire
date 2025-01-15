<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings;

use Livewire\Component;

class Label extends Component
{
    public $slug;

    protected $listeners = [
        'closeLabel' => '$refresh',
    ];

    // get labels property
    public function getLabelsProperty() : mixed
    {
        return model('label')
            ->where('type', $this->slug)
            ->orderBy('seq')
            ->orderBy('id')
            ->get();
    }
}