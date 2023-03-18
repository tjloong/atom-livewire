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
            'product.description' => ['nullable'],
            'product.price' => ['nullable'],
            'product.stock' => ['nullable'],
            'product.is_active' => ['nullable'],
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
        return array_merge(
            [
                'types' => collect(model('product')->getTypes())
                    ->when(
                        $this->product->exists,
                        fn($types) => $types->filter(fn($val) => data_get($val, 'value') === $this->product->type)
                    )
                    ->map(fn($val) => [
                        'value' => data_get($val, 'value'),
                        'label' => data_get($val, 'label'),
                        'small' => data_get($val, 'description'),
                    ])
                    ->toArray(),
                
                'categories' => model('label')->readable()
                    ->where('type', 'product-category')
                    ->orderBy('name')
                    ->get()
                    ->transform(fn($label) => [
                        'value' => $label->id,
                        'label' => $label->locale('name'),
                    ])
                    ->toArray(),
            ],

            enabled_module('taxes') ? [
                'taxes' => model('tax')->readable()->orderBy('name')->get()->map(fn($tax) => [
                    'value' => $tax->id, 
                    'label' => $tax->label,
                ])->toArray(),
            ] : [],
        );
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
    public function submit(): mixed
    {
        $this->validateForm();
        $this->persist();

        return $this->product->wasRecentlyCreated
            ? redirect()->route('app.product.update', [$this->product->id])
            : $this->popup('Product Updated.');
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
