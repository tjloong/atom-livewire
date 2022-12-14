<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan\Price;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithPopupNotify;

    public $plan;
    public $price;

    /**
     * Mount
     */
    public function mount($planId, $priceId)
    {
        $this->plan = model('plan')->findOrFail($planId);
        $this->price = $this->plan->prices()->findOrFail($priceId);

        breadcrumbs()->push($this->price->name);
    }

    /**
     * Delete
     */
    public function delete()
    {
        if ($this->price->accounts->count()) {
            $this->popup('There are subscribers under this plan price.', 'alert', 'error');
        }
        else {
            $this->price->delete();

            return redirect()->route('app.plan.price.listing', [
                'planId' => $this->plan->id,
                'priceId' => $this->price->id,
            ])->with('info', 'Plan Price Deleted.');
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.plan.price.update');
    }
}