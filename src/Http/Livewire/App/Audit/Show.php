<?php

namespace Jiannius\Atom\Http\Livewire\App\Audit;

use Jiannius\Atom\Component;

class Show extends Component
{
    public $audit;

    protected $listeners = [
        'showAudit' => 'open',
    ];

    // open
    public function open($id) : void
    {
        if ($this->audit = model('audit')->find($id)) {
            $this->modal();
            $this->dispatchBrowserEvent('audit-id', $id);
        }
    }
}