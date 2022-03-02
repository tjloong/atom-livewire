<?php

namespace Jiannius\Atom\Http\Livewire\App\PlanPrice;

use Livewire\Component;

class Create extends Component
{
    public $plan;
    public $price;

    protected $listeners = ['saved'];

    /**
     * Mount
     */
    public function mount($id)
    {
        $this->plan = model('plan')->findOrFail($id);

        $this->price = model('plan-price');
        $this->price->plan_id = $this->plan->id;

        breadcrumbs()->push('Create Plan Price');
    }

    /**
     * Saved
     */
    public function saved()
    {
        return redirect()->route('plan.update', [$this->plan->id]);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.plan-price.create');
    }
}