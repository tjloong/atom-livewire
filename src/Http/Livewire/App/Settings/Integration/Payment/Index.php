<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Integration\Payment;

use Livewire\Component;

class Index extends Component
{
    public $provider;
    
    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.settings.integration.payment');
    }
}