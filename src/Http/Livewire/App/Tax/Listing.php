<?php

namespace Jiannius\Atom\Http\Livewire\App\Tax;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Listing extends Component
{
    public $onboarding;

    protected $listeners = ['refresh' => '$refresh'];

    /**
     * Get taxes property
     */
    public function getTaxesProperty(): Collection
    {
        return model('tax')
            ->readable()
            ->orderBy('country')
            ->orderBy('region')
            ->orderBy('name')
            ->get();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.tax.listing');
    }
}