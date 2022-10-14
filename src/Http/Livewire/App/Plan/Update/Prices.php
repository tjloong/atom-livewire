<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan\Update;

use Livewire\Component;

class Prices extends Component
{
    public $plan;

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.plan.update.prices');
    }
}