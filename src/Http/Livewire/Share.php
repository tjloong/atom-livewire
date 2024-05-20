<?php

namespace Jiannius\Atom\Http\Livewire;

use Jiannius\Atom\Component;

class Share extends Component
{
    public $ulid;
    public $share;

    // mount
    public function mount()
    {
        $this->share = model('share')
        ->status('ACTIVE')
        ->where('ulid', $this->ulid)
        ->firstOrFail();
    }
}