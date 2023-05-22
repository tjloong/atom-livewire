<?php

namespace Jiannius\Atom\Http\Livewire\App\Product;

use Jiannius\Atom\Traits\Livewire\WithFile;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\Component;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Form extends Component
{
    use WithFile;
    use WithForm;
    use WithPopupNotify;

    public $header;
    public $product;
    public $inputs;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'product.name' => ['required' => 'Product name is required.'],
            'product.code' => [
                function ($attr, $value, $fail) {
                    if (
                        $value
                        && model('product')->readable()->where('code', $value)->where('id', '<>', $this->product->id)->count()
                    ) {
                        $fail('Product code is taken.');
                    }
                },
            ],
            'product.type' => ['required' => 'Product type is required.'],
            'product.slug' => ['nullable'],
            'product.description' => ['nullable'],
            'product.price' => ['nullable'],
            'product.stock' => ['nullable'],
            'product.is_active' => ['nullable'],
            'product.is_featured' => ['nullable'],
        ];
    }

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->setInputs();
    }

    /**
     * Get options property
     */
    public function getOptionsProperty(): array
    {
        return [
            'types' => collect(model('product')->getTypes())->map(fn($val) => [
                'value' => data_get($val, 'value'),
                'label' => data_get($val, 'label'),
                'small' => data_get($val, 'description'),
            ])->toArray(),
        ];
    }

    /**
     * Updated inputs images
     */
    public function updatedInputsImages(): void
    {
        $this->product->images()->sync(data_get($this->inputs, 'images'));
        $this->sort(array_keys(data_get($this->inputs, 'images')));
        $this->setInputs();
    }

    /**
     * Set inputs
     */
    public function setInputs(): void
    {
        $this->fill([
            'inputs.taxes' => $this->product->taxes->pluck('id')->toArray(),
            'inputs.categories' => $this->product->categories->pluck('id')->toArray(),
            'inputs.images' => $this->product
                ->images()
                ->orderBy('product_images.seq')
                ->get()
                ->pluck('id')
                ->toArray(),
        ]);
    }

    /**
     * Generate code
     */
    public function generateCode(): void
    {
        $this->product->fill([
            'code' => model('product')->generateCode(),
        ]);
    }

    /**
     * Sort images
     */
    public function sort($data): void
    {
        foreach ($data as $seq => $id) {
            $this->product->images()->updateExistingPivot($id, compact('seq'));
        }

        $this->setInputs();
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();

        $this->persist();

        $this->emit('submitted', $this->product->id);
    }

    /**
     * Persist
     */
    public function persist(): void
    {
        $this->product->save();
        $this->product->categories()->sync(data_get($this->inputs, 'categories'));

        if (enabled_module('taxes')) {
            $this->product->taxes()->sync(data_get($this->inputs, 'taxes'));
        }
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.product.form');
    }
}
