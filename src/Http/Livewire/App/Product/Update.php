<?php

namespace Jiannius\Atom\Http\Livewire\App\Product;

use Livewire\Component;

class Update extends Component
{
    public $tab;
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

        $this->tab = $this->tab ?? data_get($this->tabs[0], 'slug');

        breadcrumbs()->push($this->product->name);
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        return [
            ['slug' => 'info', 'label' => 'Product Information', 'icon' => 'circle-info', 'livewire' => 'app.product.form.info'],
            $this->product->type === 'variant'
                ? ['slug' => 'variant', 'label' => 'Product Variants', 'icon' => 'cubes', 'count' => $this->product->variants()->count(), 'livewire' => 'app.product.variant.listing']
                : null,
            ['slug' => 'image', 'label' => 'Product Images', 'icon' => 'image', 'count' => $this->product->images()->count()],
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
        return atom_view('app.product.update');
    }
}
