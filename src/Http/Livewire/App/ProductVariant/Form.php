<?php

namespace Jiannius\Atom\Http\Livewire\App\ProductVariant;

use Livewire\Component;

class Form extends Component
{
    public $variant;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'variant.name' => 'required',
            'variant.price' => 'nullable|numeric',
            'variant.stock' => 'nullable|numeric',
            'variant.is_default' => 'nullable',
            'variant.is_active' => 'nullable',
            'variant.image_id' => 'nullable',
            'variant.product_id' => 'required',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'variant.name.required' => __('Variant name is required.'),
            'variant.price.numeric' => __('Invalid price.'),
            'variant.stock.numeric' => __('Invalid stock.'),
            'variant.product_id.required' => __('Unknown product.'),
        ];
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        if ($this->variant->is_default) {
            $this->variant->product->productVariants()->update(['is_default' => false]);
        }
        
        $this->variant->save();

        return redirect()->route('app.product.update', [
            'product' => $this->variant->product_id, 
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