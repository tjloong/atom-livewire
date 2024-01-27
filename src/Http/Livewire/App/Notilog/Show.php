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
            $this->openDrawer('notilog-show');
        }
    }

    // close
    public function close() : void
    {
        $this->emit('setNotilogId');
        $this->closeDrawer('notilog-show');
    }

    // delete
    public function delete() : void
    {
        $this->notilog->delete();
        $this->emit('notilogDeleted');
        $this->close();
    }
}