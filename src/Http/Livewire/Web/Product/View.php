<?php

namespace Jiannius\Atom\Http\Livewire\Web\Product;

use Livewire\Component;

class View extends Component
{
    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('web.product.view');
    }
}