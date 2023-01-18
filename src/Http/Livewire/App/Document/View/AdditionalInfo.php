<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\View;

use Livewire\Component;

class AdditionalInfo extends Component
{
    public $document;

    protected $listeners = ['refresh' => '$refresh'];

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.view.additional-info');
    }
}