<?php

namespace Jiannius\Atom\Http\Livewire\App\Product;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithPopupNotify;
    
    public $product;

    protected $listeners = ['submitted'];

    /**
     * Mount
     */
    public function mount($productId)
    {
        $this->product = model('product')->readable()->findOrFail($productId);

        breadcrumbs()->push($this->product->name);
    }

    /**
     * Delete
     */
    public function delete(): mixed
    {
        $this->product->delete();

        return breadcrumbs()->back();
    }

    /**
     * Submitted
     */
    public function submitted(): mixed
    {
        return $this->popup('Product Updated.');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.product.update');
    }
}
