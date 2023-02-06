<?php

namespace Jiannius\Atom\Http\Livewire\App\Product\Variant;

use Jiannius\Atom\Traits\Livewire\WithFile;
use Livewire\Component;

class Form extends Component
{
    use WithFile;

    public $variant;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'variant.name' => 'required',

            'variant.code' => [
                function ($attr, $value, $fail) {
                    if ($value) {
                        $count = model('product')->when(
                            model('product')->enabledHasTenantTrait,
                            fn($q) => $q->belongsToTenant()
                        )->where('code', $value)->count();

                        if (!$count) {
                            $count = model('product_variant')
                                ->whereHas('product', fn($q) => $q->when(
                                    model('product')->enabledHasTenantTrait,
                                    fn($q) => $q->belongsToTenant()        
                                ))
                                ->when(
                                    data_get($this->variant, 'id'),
                                    fn($q, $id) => $q->where('id', '<>', $id)
                                )
                                ->where('code', $value)->count();
                        }

                        if ($count) $fail(__('Variant code is taken.'));
                    }
                },
            ],

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
     * Generate code
     */
    public function generateCode()
    {
        $this->variant->fill([
            'code' => model('product_variant')->generateCode(),
        ]);
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();
        $this->persist();

        return $this->submitted();
    }

    /**
     * Persist
     */
    public function persist()
    {
        if ($this->variant->is_default) {
            $this->variant->product->variants()->update(['is_default' => false]);
        }
        
        $this->variant->save();
    }

    /**
     * Submitted
     */
    public function submitted()
    {
        return redirect()->route('app.product.update', [
            'productId' => $this->variant->product_id, 
            'tab' => 'variant',
        ]);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.product.variant.form');
    }
}