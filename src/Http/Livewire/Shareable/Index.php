<?php

namespace Jiannius\Atom\Http\Livewire\Shareable;

use Livewire\Component;

class Index extends Component
{
    public $shareable;

    /**
     * Mount
     */
    public function mount($uuid)
    {
        if (!enabled_module('shareables')) abort('404');

        $this->shareable = model('shareable')
            ->status('active')
            ->where('uuid', $uuid)
            ->firstOrFail();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('shareable');
    }
}