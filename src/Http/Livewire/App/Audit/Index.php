<?php

namespace Jiannius\Atom\Http\Livewire\App\Audit;

use Jiannius\Atom\Component;

class Index extends Component
{
    public $auditId;

    protected $listeners = [
        'setAuditId',
        'showAudit' => 'setAuditId',
    ];

    // set audit id
    public function setAuditId($id = null) : void
    {
        $this->fill(['auditId' => $id ?: null]);
    }
}