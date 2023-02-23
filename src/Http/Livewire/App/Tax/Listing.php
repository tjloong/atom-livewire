<?php

namespace Jiannius\Atom\Http\Livewire\App\Tax;

use Livewire\Component;

class Listing extends Component
{
    public $onboarding;

    protected $listeners = ['refresh' => '$refresh'];

    /**
     * Get taxes property
     */
    public function getTaxesProperty()
    {
        return model('tax')
            ->when(
                model('tax')->enabledHasTenantTrait, 
                fn($q) => $q->belongsToTenant(),
            )
            ->orderBy('country')
            ->orderBy('region')
            ->orderBy('name')
            ->get();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.tax.listing');
    }
}