<?php

namespace Jiannius\Atom\Http\Livewire\App\Product\Update;

use Livewire\Component;

class Images extends Component
{
    public $product;
    public $images;
    public $image;

    protected $listeners = ['uploader-completed' => 'attach'];

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
    public function setImages()
    {
        $this->images = $this->product->productImages()
            ->orderBy('seq')
            ->orderBy('id')
            ->get()
            ->map(fn($img) => [
                'id' => $img->id,
                'url' => $img->url,
            ]);
    }

    /**
     * Attach images
     */
    public function attach($files)
    {
        foreach ($files as $file) {
            if ($this->images->where('id', data_get($file, 'id'))->count()) continue;

            $this->product->productImages()->attach(data_get($file, 'id'));
        }
        
        $this->setImages();
    }

    /**
     * Remove image
     */
    public function remove($id)
    {
        $this->product->productImages()->detach($id);
        $this->setImages();
    }

    /**
     * Sort images
     */
    public function sort($data)
    {
        collect($data)->filter()->each(function($id, $index) {
            $image = $this->product->productImages->where('id', $id)->first();
            $image->pivot->seq = $index;
            $image->pivot->save();
        });

        $this->setImages();
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.product.update.images');
    }
}
