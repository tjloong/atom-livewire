<?php

namespace Jiannius\Atom\Http\Livewire\App\Product\Update;

use Livewire\Component;

class Index extends Component
{
    public $tab = 'overview';
    public $product;

    protected $queryString = ['tab'];

    /**
     * Mount
     */
    public function mount($product)
    {
        $this->product = model('product')->findOrFail($product);

        breadcrumbs()->push($this->product->name);
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        return [
            'overview',
            'images',
            $this->product->type === 'variant' ? 'variants' : null,
        ];
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->product->delete();

        session()->flash('flash', __('Product Deleted'));

        return redirect()->route('app.product.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.product.update.index');
    }
}
