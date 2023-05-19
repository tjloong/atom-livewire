<?php

namespace Jiannius\Atom\Http\Livewire\App\Product;

use Jiannius\Atom\Traits\Livewire\WithFile;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Image extends Component
{
    use WithFile;
    use WithPopupNotify;

    public $images;
    public $product;

    /**
     * Mount
     */
    public function mount()
    {
        $this->setImages();
    }

    /**
     * Set images
     */
    public function setImages(): void
    {
        $this->fill(['images' => $this->product
            ->images()
            ->orderBy('product_images.seq')
            ->get()
            ->pluck('id')
            ->toArray(),
        ]);
    }

    /**
     * Updated images
     */
    public function updatedImages(): void
    {
        $this->product->images()->sync($this->images);
        $this->sort(array_keys($this->images));
    }

    /**
     * Sort images
     */
    public function sort($data): void
    {
        foreach ($data as $seq => $id) {
            $this->product->images()->updateExistingPivot($id, compact('seq'));
        }

        $this->setImages();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.product.image');
    }
}
