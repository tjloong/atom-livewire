<?php

namespace Jiannius\Atom\Http\Livewire\App\Share;

use Jiannius\Atom\Component;

class Index extends Component
{
    public $ulid;
    public $share;

    // mount
    public function mount(): void
    {
        $this->share = model('share')
            ->status('ACTIVE')
            ->where('ulid', $this->ulid)
            ->firstOrFail();
    }
}