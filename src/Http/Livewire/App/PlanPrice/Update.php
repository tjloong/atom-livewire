<?php

namespace Jiannius\Atom\Http\Livewire\App\PlanPrice;

use Livewire\Component;

class Update extends Component
{
    public $plan;
    public $price;

    protected $listeners = ['saved'];

    /**
     * Mount
     */
    public function mount($id)
    {
        $this->price = model('plan-price')->findOrFail($id);
        $this->plan = $this->price->plan;

        breadcrumbs()->push(
            str($this->price->recurring)->headline().' '.currency($this->price->amount, $this->price->currency)
        );
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
        if (enabled_module('tenants') && $this->price->tenants->count()) {
            $this->dispatchBrowserEvent('alert', ['message' => 'There are tenants subscribed under this plan price.', 'type' => 'error']);
        }
        else if ($this->price->users->count()) {
            $this->dispatchBrowserEvent('alert', ['message' => 'There are users subscribed under this plan price.', 'type' => 'error']);
        }
        else {
            $this->price->delete();

            session()->flash('flash', 'Plan Price Deleted');

            return redirect()->route('plan.update', [$this->plan->id]);
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