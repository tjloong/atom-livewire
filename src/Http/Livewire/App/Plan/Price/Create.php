<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan\Price;

use Livewire\Component;

class Create extends Component
{
    public $plan;
    public $price;

    /**
     * Mount
     */
    public function mount($planId)
    {
        $this->plan = model('plan')->findOrFail($planId);
        $this->price = model('plan-price')->fill([
            'plan_id' => $this->plan->id,
        ]);

        breadcrumbs()->push('Create Plan Price');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.plan.price.create');
    }
}