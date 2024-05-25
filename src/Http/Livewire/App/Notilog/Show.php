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
    public function open($ulid) : void
    {
        if ($this->notilog = model('notilog')->where('ulid', $ulid)->first()) {
            $this->modal();
        }
    }

    // close
    public function close() : void
    {
        $this->emit('setNotilogId');
        $this->modal(false);
    }

    // delete
    public function delete() : void
    {
        $this->notilog->delete();
        $this->emit('notilogDeleted');
        $this->close();
    }
}