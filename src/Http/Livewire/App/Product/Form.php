<?php

namespace Jiannius\Atom\Http\Livewire\App\Product;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Jiannius\Atom\Traits\WithPopupNotify;

class Form extends Component
{
    use WithPopupNotify;

    public $header;
    public $product;
    public $selected = [
        'taxes' => [],
        'categories' => [],
    ];

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'product.name' => 'required',
            'product.code' => [
                'nullable',
                Rule::unique('products', 'code')
                    ->ignore($this->product)
                    ->where(fn($q) => $q
                        ->when(
                            model('product')->enabledBelongsToAccountTrait,
                            fn($q) => $q->where('account_id', auth()->user()->account_id)
                        )
                    ),
            ],
            'product.type' => 'required',
            'product.description' => 'nullable',
            'product.price' => 'nullable|numeric',
            'product.stock' => 'nullable|numeric',
            'product.is_active' => 'nullable|boolean',
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
        $this->fill([
            'selected.taxes' => $this->product->taxes->pluck('id')->toArray(),
            'selected.categories' => $this->product->categories->pluck('id')->toArray(),
        ]);
    }

    /**
     * Get options property
     */
    public function getOptionsProperty()
    {
        return [
            'types' => collect(model('product')->getTypes())
                ->when(
                    $this->product->exists,
                    fn($types) => $types->filter(fn($val) => data_get($val, 'value') === $this->product->type)
                ),

            'taxes' => model('tax')
                ->when(
                    model('tax')->enabledBelongsToAccountTrait, 
                    fn($q) => $q->belongsToAccount()
                )
                ->orderBy('name')
                ->get()
                ->map(fn($tax) => ['value' => $tax->id, 'label' => $tax->label]),

            'categories' => model('label')
                ->when(
                    model('label')->enabledBelongsToAccountTrait, 
                    fn($q) => $q->belongsToAccount()
                )
                ->where('type', 'product-category')
                ->select('id as value', 'name->'.app()->currentLocale().' as label')
                ->orderBy('name')
                ->get(),
        ];
    }

    /**
     * Generate code
     */
    public function generateCode()
    {
        $this->product->fill([
            'code' => model('product')->generateCode(),
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
        $this->product->save();
        $this->product->taxes()->sync(data_get($this->selected, 'taxes'));
        $this->product->categories()->sync(data_get($this->selected, 'categories'));
    }

    /**
     * Submitted
     */
    public function submitted()
    {
        if ($this->product->wasRecentlyCreated) {
            return redirect()->route('app.product.update', [
                'productId' => $this->product->id,
                'tab' => $this->product->type === 'variant' ? 'variants' : null,
            ])->with('success', 'Product Created');
        }
        else $this->popup('Product Updated');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.product.form');
    }
}
