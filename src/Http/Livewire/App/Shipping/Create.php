<?php

namespace Jiannius\Atom\Http\Livewire\App\Shipping;

use Livewire\Component;

class Create extends Component
{
    public $rate;

    protected $listeners = ['submitted'];

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->rate = model('shipping_rate')->fill([
            'is_active' => true,
        ]);

        breadcrumbs()->push('Create Shipping Rate');
    }

    /**
     * Submitted
     */
    public function submitted(): mixed
    {
        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.shipping.create');
    }
}
