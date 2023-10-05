<?php

namespace Jiannius\Atom\Http\Livewire\App\Banner;

use Jiannius\Atom\Component;

class Index extends Component
{
    public $bannerId;

    protected $listeners = [
        'setBannerId',
        'updateBanner' => 'setBannerId',
    ];

    // set banner id
    public function setBannerId($id = null) : void
    {
        $this->fill(['bannerId' => $id ?: null]);
    }
}