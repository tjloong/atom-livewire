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
    public function open($args) : void
    {
        if ($this->audit = model('audit')->find(get($args, 'id'))) {
            $this->modal();
        }
    }
}