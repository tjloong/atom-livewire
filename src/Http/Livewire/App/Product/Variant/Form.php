<?php

namespace Jiannius\Atom\Http\Livewire\App\Product\Variant;

use Jiannius\Atom\Traits\Livewire\WithFile;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\Component;

class Form extends Component
{
    use WithFile;
    use WithForm;

    public $variant;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'variant.name' => ['required' => 'Variant name is required.'],

            'variant.code' => [
                function ($attr, $value, $fail) {
                    if ($value) {
                        $count = model('product')->readable()->where('code', $value)->count();

                        if (!$count) {
                            $count = model('product_variant')
                                ->whereHas('product', fn($q) => $q->readable())
                                ->when(
                                    data_get($this->variant, 'id'),
                                    fn($q, $id) => $q->where('id', '<>', $id)
                                )
                                ->where('code', $value)->count();
                        }

                        if ($count) $fail('Variant code is taken.');
                    }
                },
            ],

            'variant.price' => ['nullable'],
            'variant.stock' => ['nullable'],
            'variant.is_default' => ['nullable'],
            'variant.is_active' => ['nullable'],
            'variant.image_id' => ['nullable'],
            'variant.product_id' => ['required' => 'Unknown product.'],
        ];
    }

    /**
     * Generate code
     */
    public function generateCode(): void
    {
        $this->variant->fill([
            'code' => model('product_variant')->generateCode(),
        ]);
    }

    /**
     * Submit
     */
    public function submit(): mixed
    {
        $this->validateForm();
        $this->persist();

        return breadcrumbs()->back();
    }

    /**
     * Persist
     */
    public function persist(): void
    {
        if ($this->variant->is_default) {
            $this->variant->product->variants()->update(['is_default' => false]);
        }
        
        $this->variant->save();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.product.variant.form');
    }
}