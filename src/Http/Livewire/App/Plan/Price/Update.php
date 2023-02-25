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
        if ($this->price->users->count()) {
            return $this->popup([
                'title' => 'Unable to Delete Price',
                'message' => 'There are subscribers under this plan price.',
            ], 'alert', 'error');
        }

        $this->price->delete();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.plan.price.update');
    }
}