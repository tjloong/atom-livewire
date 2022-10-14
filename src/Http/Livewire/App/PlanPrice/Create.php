<?php

namespace Jiannius\Atom\Http\Livewire\App\PlanPrice;

use Livewire\Component;

class Create extends Component
{
    public $plan;
    public $planPrice;

    protected $listeners = ['saved'];

    /**
     * Mount
     */
    public function mount($plan)
    {
        $this->plan = model('plan')->findOrFail($plan);

        $this->planPrice = model('plan-price');
        $this->planPrice->plan_id = $this->plan->id;

        breadcrumbs()->push('Create Plan Price');
    }

    /**
     * Saved
     */
    public function saved()
    {
        return redirect()->route('app.plan.update', [$this->plan->id]);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.plan-price.create');
    }
}