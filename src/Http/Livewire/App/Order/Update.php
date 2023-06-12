<?php

namespace Jiannius\Atom\Http\Livewire\App\Order;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithPopupNotify;
    
    public $order;

    protected $listeners = ['refresh' => '$refresh'];

    protected $rules = ['order.remark' => 'nullable'];

    /**
     * Mount
     */
    public function mount($id): void
    {
        $this->order = model('order')->findOrFail($id);

        breadcrumbs()->push($this->order->number);
    }

    /**
     * Get payment property
     */
    public function getPaymentProperty()
    {
        return $this->order->payments()->where('status', 'success')->first();
    }

    /**
     * Update order remark
     */
    public function updatedOrderRemark()
    {
        $this->order->save();
    }

    /**
     * Mark
     */
    public function mark($status, $bool = true)
    {
        if ($status === 'closed') {
            $this->order->fill(['closed_at' => $bool ? now() : null])->save();
        }
        else if ($status === 'shipped') {
            $this->order->fill(['shipped_at' => $bool ? now() : null])->save();
        }

        $this->emit('refresh');
    }

    /**
     * Delete
     */
    public function delete(): mixed
    {
        if ($this->order->status === 'pending') {
            $this->order->delete();
        }

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.order.update');
    }
}