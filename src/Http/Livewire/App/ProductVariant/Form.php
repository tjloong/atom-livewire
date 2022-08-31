<?php

namespace Jiannius\Atom\Http\Livewire\App\ProductVariant;

use Livewire\Component;

class Form extends Component
{
    public $productVariant;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'productVariant.name' => 'required',

            'productVariant.code' => [
                function ($attr, $value, $fail) {
                    if ($value) {
                        $dup = model('product')->belongsToAccount()->where('code', $value)->count() > 0
                            || model('product_variant')
                                ->whereHas('product', fn($q) => $q->belongsToAccount())
                                ->where('code', $value)->count() > 0;

                        if ($dup) $fail(__('Variant code is taken.'));
                    }
                },
            ],

            'productVariant.price' => 'nullable|numeric',
            'productVariant.stock' => 'nullable|numeric',
            'productVariant.is_default' => 'nullable',
            'productVariant.is_active' => 'nullable',
            'productVariant.image_id' => 'nullable',
            'productVariant.product_id' => 'required',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'productVariant.name.required' => __('Variant name is required.'),
            'productVariant.price.numeric' => __('Invalid price.'),
            'productVariant.stock.numeric' => __('Invalid stock.'),
            'productVariant.product_id.required' => __('Unknown product.'),
        ];
    }

    /**
     * Generate code
     */
    public function generateCode()
    {
        $code = null;
        $dup = true;

        while ($dup) {
            $code = str()->upper(str()->random(6));
            $dup = model('product')->belongsToAccount()->where('code', $code)->count() > 0
                || model('product_variant')
                    ->whereHas('product', fn($q) => $q->belongsToAccount())
                    ->where('code', $code)->count() > 0;
        }

        $this->productVariant->fill(['code' => $code]);
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        if ($this->productVariant->is_default) {
            $this->productVariant->product->productVariants()->update(['is_default' => false]);
        }
        
        $this->productVariant->save();

        return redirect()->route('app.product.update', [
            'productId' => $this->productVariant->product_id, 
            'tab' => 'variants',
        ]);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.product-variant.form');
    }
}