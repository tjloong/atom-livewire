<?php

namespace Jiannius\Atom\Http\Livewire\App\Notilog;

use Jiannius\Atom\Component;

class Index extends Component
{
    public $notilogId;

    protected $listeners = [
        'setNotilogId',
        'showNotilog' => 'setNotilogId',
    ];

    // set notilog id
    public function setNotilogId($id = null) : void
    {
        $this->fill(['notilogId' => $id ?: null]);
    }
}