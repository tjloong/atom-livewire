<?php

namespace Jiannius\Atom\Http\Livewire\App\Product;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\Component;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Form extends Component
{
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
        $this->fill([
            'inputs.taxes' => $this->product->taxes->pluck('id')->toArray(),
            'inputs.categories' => $this->product->categories->pluck('id')->toArray(),
        ]);
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
     * Generate code
     */
    public function generateCode(): void
    {
        $this->product->fill([
            'code' => model('product')->generateCode(),
        ]);
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();

        $this->persist();

        $this->emit('submitted');
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
