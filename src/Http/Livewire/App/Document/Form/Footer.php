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
    public function mount(): void
    {
        $this->inputs = [
            'note' => $this->document->note,
            'footer' => $this->document->footer,
        ];
    }

    /**
     * Updated inputs
     */
    public function updatedInputs(): void
    {
        $this->emitUp('setDocument', $this->inputs);
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.document.form.footer');
    }
}