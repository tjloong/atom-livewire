<?php

namespace Jiannius\Atom\Http\Livewire\Web\Shop;

use Livewire\Component;

class Listing extends Component
{
    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('web.shop.listing');
    }
}