<?php

namespace Jiannius\Atom\Http\Livewire\Shareable;

use Livewire\Component;

class Index extends Component
{
    public $shareable;

    /**
     * Mount
     */
    public function mount($id)
    {
        if (!enabled_module('shareables')) abort('404');

        $this->shareable = model('shareable')->status('active')->when(
            is_numeric($id),
            fn($q) => $q->where('id', $id),
            fn($q) => $q->where('uuid', $id),
        )->firstOrFail();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('shareable');
    }
}