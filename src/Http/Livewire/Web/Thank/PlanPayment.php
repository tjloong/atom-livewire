<?php

namespace Jiannius\Atom\Http\Livewire\Web\Thank;

use Livewire\Component;

class PlanPayment extends Component
{
    public $status;

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('web.thank.payment');
    }
}