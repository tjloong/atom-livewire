<?php

namespace Jiannius\Atom\Http\Livewire\App\Product\Update;

use Livewire\Component;
use Illuminate\Validation\Rule;

class Overview extends Component
{
    public $product;
    public $categories = [];

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'product.name' => 'required',
            'product.code' => [
                'nullable',
                model('product')->enabledBelongsToAccountTrait
                ? Rule::unique('products', 'code')->ignore($this->product)->where(fn($q) => $q->where('id', $this->product->account_id))
                : Rule::unique('products', 'code')->ignore($this->product),
            ],
            'product.type' => 'required',
            'product.description' => 'nullable',
            'product.price' => 'nullable|numeric',
            'product.stock' => 'nullable|numeric',
            'product.is_active' => 'nullable|boolean',
            'product.tax_id' => 'nullable',
            'product.account_id' => 'nullable',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'product.name.required' => __('Product name is required.'),
            'product.code.unique' => __('This product code is taken.'),
            'product.type.required' => __('Product type is required.'),
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->categories = $this->product->productCategories->pluck('id')->toArray();
    }

    /**
     * Get options property
     */
    public function getOptionsProperty()
    {
        return [
            'types' => model('product')->getTypes()->map(fn($val) => [
                'value' => $val,
                'label' => str()->headline($val),
            ]),

            'taxes' => model('tax')
                ->when(model('tax')->enabledBelongsToAccountTrait, fn($q) => $q->belongsToAccount())
                ->selectRaw('id as value, concat(name, " ", rate, "%") as label')
                ->orderBy('name')
                ->get(),

            'categories' => model('label')
                ->when(model('label')->enabledBelongsToAccountTrait, fn($q) => $q->belongsToAccount())
                ->where('type', 'product-category')
                ->selectRaw('id as value, name as label')
                ->orderBy('name')
                ->get(),
        ];
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->product->save();
        $this->product->productCategories()->sync($this->categories);

        if ($this->product->wasRecentlyCreated) {
            session()->flash('flash', __('Product Created').'::success');        
            return redirect()->route('app.product.update', [
                'product' => $this->product->id,
                'tab' => $this->product->type === 'variant' ? 'variants' : null,
            ]);
        }
        else $this->dispatchBrowserEvent('toast', ['message' => __('Product Updated'), 'type' => 'success']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.product.update.overview');
    }
}
