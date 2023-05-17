<?php

namespace Jiannius\Atom\Http\Livewire\App\Product;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithPopupNotify;
    
    public $tab;
    public $product;

    protected $listeners = ['submitted'];

    /**
     * Mount
     */
    public function mount($productId): mixed
    {
        $this->product = model('product')->readable()->findOrFail($productId);

        if (!$this->tab) {
            return redirect()->route('app.product.update', [$this->product->id, 'tab' => data_get(tabs($this->tabs), '0.slug')]);
        }

        return breadcrumbs()->push($this->product->name);
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty(): array
    {
        return [
            ['slug' => 'info', 'label' => 'Product Information', 'livewire' => [
                'name' => 'app.product.form',
                'data' => ['header' => 'Product Information'],
            ]],
            $this->product->type === 'variant'
                ? ['slug' => 'variant', 'label' => 'Product Variants', 'count' => $this->product->variants()->count(), 'livewire' => 'app.product.variant.listing']
                : null,
            ['slug' => 'image', 'label' => 'Product Images', 'livewire' => 'app.product.image', 'count' => $this->product->images()->count()],
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
