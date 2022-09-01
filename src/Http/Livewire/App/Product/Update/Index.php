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
    public function mount($productId)
    {
        $this->product = model('product')
            ->when(
                model('product')->enabledBelongsToAccountTrait,
                fn($q) => $q->belongsToAccount()
            )
            ->findOrFail($productId);

        breadcrumbs()->push($this->product->name);
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        return [
            ['slug' => 'overview', 'label' => 'Overview'],
            $this->product->type === 'variant'
                ? ['slug' => 'variants', 'label' => 'Variants', 'count' => $this->product->productVariants()->count()]
                : null,
            ['slug' => 'images', 'label' => 'Images', 'count' => $this->product->productImages()->count()],
        ];
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->product->delete();

        return redirect()->route('app.product.listing')->with('info', 'Product Deleted');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.product.update.index');
    }
}
