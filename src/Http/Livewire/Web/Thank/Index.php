<?php

namespace Jiannius\Atom\Http\Livewire\Web\Thank;

use Livewire\Component;

class Index extends Component
{
    public $slug;
    public $params;

    /**
     * Mount
     */
    public function mount($slug): void
    {
        $this->slug = $slug;
        $this->params = request()->query();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('web.thank');
    }
}