<?php

namespace Jiannius\Atom\Http\Livewire\App\PlanPrice;

use Livewire\Component;

class Update extends Component
{
    public $plan;
    public $planPrice;

    protected $listeners = ['saved'];

    /**
     * Mount
     */
    public function mount($planPrice)
    {
        $this->planPrice = model('plan-price')->findOrFail($planPrice);
        $this->plan = $this->planPrice->plan;

        breadcrumbs()->push($this->planPrice->name);
    }

    /**
     * Saved
     */
    public function saved()
    {
        $this->dispatchBrowserEvent('toast', ['message' => 'Plan Price Updated', 'type' => 'success']);
    }

    /**
     * Delete
     */
    public function delete()
    {
        if ($this->planPrice->accounts->count()) {
            $this->dispatchBrowserEvent('alert', ['message' => 'There are subscribers under this plan price.', 'type' => 'error']);
        }
        else {
            $this->planPrice->delete();

            session()->flash('flash', 'Plan Price Deleted');

            return redirect()->route('app.plan.update', [$this->plan->id]);
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.plan-price.update');
    }
}