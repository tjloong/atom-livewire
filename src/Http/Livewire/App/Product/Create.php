<?php

namespace Jiannius\Atom\Http\Livewire\App\Product;

use Livewire\Component;

class Create extends Component
{
    public $product;

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->product = model('product')->fill([
            'type' => 'normal',
            'is_active' => true,
        ]);

        breadcrumbs()->push('Create Product');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.product.create');
    }
}
