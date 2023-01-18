<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\View;

use Livewire\Component;

class Action extends Component
{
    public $document;

    /**
     * Toggle sent
     */
    public function toggleSent()
    {
        $this->document->fill([
            'last_sent_at' => $this->document->last_sent_at
                ? null
                : now(),
        ])->saveQuietly();

        $this->emit('refresh');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.view.action');
    }
}