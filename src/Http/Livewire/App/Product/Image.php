<?php

namespace Jiannius\Atom\Http\Livewire\App\Product;

use Jiannius\Atom\Traits\Livewire\WithFile;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Image extends Component
{
    use WithFile;
    use WithPopupNotify;

    public $files;
    public $product;
    public $checkboxes = [];

    protected $listeners = ['refresh' => '$refresh'];

    /**
     * Updated files
     */
    public function updatedFiles(): void
    {
        foreach ($this->files as $id) {
            if (!$this->product->images()->find($id)) {
                $this->product->images()->attach($id);
            }
        }

        $this->files = [];
        $this->emit('refresh');
    }

    /**
     * Checkbox
     */
    public function checkbox($id): void
    {
        $checkboxes = collect($this->checkboxes);

        if ($checkboxes->contains($id)) $checkboxes = $checkboxes->reject($id);
        else $checkboxes->push($id);

        $this->checkboxes = $checkboxes->values()->all();
    }

    /**
     * Delete
     */
    public function delete(): void
    {
        $this->product->images()->detach($this->checkboxes);
        $this->reset('checkboxes');
        $this->emit('refresh');
    }

    /**
     * Sort images
     */
    public function sort($data): void
    {
        foreach ($data as $seq => $id) {
            if ($image = $this->product->images()->find($id)) {
                $image->pivot->seq = $seq;
                $image->pivot->save();
            }
        }

        $this->popup('Product Images Sorted.');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.product.image');
    }
}
