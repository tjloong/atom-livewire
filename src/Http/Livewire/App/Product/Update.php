<?php

namespace Jiannius\Atom\Http\Livewire\App\Product;

use Livewire\Component;

class Update extends Component
{
    public $tab;
    public $product;

    /**
     * Mount
     */
    public function mount($productId): void
    {
        $this->product = model('product')->readable()->findOrFail($productId);
        $this->tab = $this->tab ?? data_get(collect($this->tabs)->first(), 'slug');

        breadcrumbs()->push($this->product->name);
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty(): array
    {
        return [
            ['slug' => 'info', 'label' => 'Product Information', 'icon' => 'circle-info', 'livewire' => 'app.product.form'],
            $this->product->type === 'variant'
                ? ['slug' => 'variant', 'label' => 'Product Variants', 'icon' => 'cubes', 'count' => $this->product->variants()->count(), 'livewire' => 'app.product.variant.listing']
                : null,
            ['slug' => 'image', 'label' => 'Product Images', 'icon' => 'image', 'livewire' => 'app.product.image', 'count' => $this->product->images()->count()],
        ];
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
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.product.update');
    }
}
