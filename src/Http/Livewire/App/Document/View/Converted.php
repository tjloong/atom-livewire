<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\View;

use Livewire\Component;

class Converted extends Component
{
    public $document;

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.view.converted');
    }
}