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

        breadcrumbs()->push($this->price->name);
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
        if ($this->price->accounts->count()) {
            $this->dispatchBrowserEvent('alert', ['message' => 'There are subscribers under this plan price.', 'type' => 'error']);
        }
        else {
            $this->price->delete();

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