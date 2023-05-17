<?php

namespace Jiannius\Atom\Http\Livewire\App\Product;

use Livewire\Component;

class Create extends Component
{
    public $product;

    protected $listeners = ['submitted'];

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
     * Submitted
     */
    public function submitted($id): mixed
    {
        return redirect()->route('app.product.update', [$id]);
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.product.create');
    }
}
