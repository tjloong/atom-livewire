<?php

namespace Jiannius\Atom\Http\Livewire\App\PlanPrice;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithPopupNotify;

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
        $this->popup('Plan Price Updated');
    }

    /**
     * Delete
     */
    public function delete()
    {
        if ($this->planPrice->accounts->count()) {
            $this->popup('There are subscribers under this plan price.', 'alert', 'error');
        }
        else {
            $this->planPrice->delete();
            return redirect()->route('app.plan.update', [$this->plan->id])->with('info', 'Plan Price Deleted.');
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