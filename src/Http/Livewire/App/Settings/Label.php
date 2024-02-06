<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings;

use Jiannius\Atom\Component;

class Label extends Component
{
    public $type;

    // get labels property
    public function getLabelsProperty() : mixed
    {
        return model('label')
            ->where('type', $this->type)
            ->orderBy('seq')
            ->orderBy('id')
            ->get();
    }
}