<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\Form;

use Livewire\Component;

class Footer extends Component
{
    public $inputs;
    public $document;
    public $settings;

    /**
     * Mount
     */
    public function mount()
    {
        $this->inputs = [
            'note' => $this->document->note,
            'footer' => $this->document->footer,
        ];
    }

    /**
     * Updated inputs
     */
    public function updatedInputs()
    {
        $this->emitUp('setDocument', $this->inputs);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.form.footer');
    }
}