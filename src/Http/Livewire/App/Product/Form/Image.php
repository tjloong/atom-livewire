<?php

namespace Jiannius\Atom\Http\Livewire\App\Product\Form;

use Jiannius\Atom\Traits\Livewire\WithFile;
use Livewire\Component;

class Image extends Component
{
    use WithFile;

    public $files;
    public $product;
    public $selected = [];

    protected $listeners = ['refresh' => '$refresh'];

    /**
     * Updated files
     */
    public function updatedFiles()
    {
        foreach ($this->files as $id) {
            if ($this->product->images()->where('product_images.id', $id)->count()) continue;
            $this->product->images()->attach($id);
        }

        $this->emitSelf('refresh');
    }

    /**
     * Select image
     */
    public function select($id)
    {
        $selected = collect($this->selected);

        if (is_numeric($selected->search($id))) $selected = $selected->reject($id);
        else $selected->push($id);

        $this->selected = $selected->values()->all();
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->product->images()->detach($this->selected);
        $this->reset('selected');
        $this->emitSelf('refresh');
    }

    /**
     * Sort images
     */
    public function sort($data)
    {
        $this->product->images()->get()->each(function($image) use ($data) {
            $image->pivot->seq = collect($data)->search($image->id);
            $image->pivot->save();
        });
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.product.form.image');
    }
}
