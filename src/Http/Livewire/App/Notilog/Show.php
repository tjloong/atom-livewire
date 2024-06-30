<?php

namespace Jiannius\Atom\Http\Livewire\App\Notilog;

use Jiannius\Atom\Component;

class Show extends Component
{
    public $notilog;

    protected $listeners = [
        'showNotilog' => 'open',
    ];

    // open
    public function open($args) : void
    {
        if ($this->notilog = model('notilog')->where('ulid', get($args, 'id'))->first()) {
            $this->modal();
        }
    }

    // delete
    public function delete() : void
    {
        $this->notilog->delete();
        $this->modal(false);
    }
}