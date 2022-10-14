<?php

namespace Jiannius\Atom\Http\Livewire\App\Billing;

use Livewire\Component;

class Index extends Component
{
    public $account;
    
    /**
     * Mount
     */
    public function mount()
    {
        $this->account = auth()->user()->account;

        breadcrumbs()->home('Billing Management');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.billing');
    }
}