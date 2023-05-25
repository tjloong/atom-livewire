<?php

namespace Jiannius\Atom\Http\Livewire\App\Shipping;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithPopupNotify;

    public $rate;

    protected $listeners = ['submitted'];

    /**
     * Mount
     */
    public function mount($rateId): void
    {
        $this->rate = model('shipping_rate')->findOrFail($rateId);

        breadcrumbs()->push($this->rate->name);
    }

    /**
     * Submitted
     */
    public function submitted(): mixed
    {
        return $this->popup('Shipping Rate Updated.');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.shipping.update');
    }
}
