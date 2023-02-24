<?php

namespace Jiannius\Atom\Http\Livewire\App\Product;

use Livewire\Component;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

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
                function ($attr, $value, $fail) {
                    if (model('product')->readable()->where('code', $value)->where('id', $this->product->id)->count()) {
                        $fail('Product code is taken.');
                    }
                },
            ],
            'product.type' => 'required',
            'product.description' => 'nullable',
            'product.price' => 'nullable|numeric',
            'product.stock' => 'nullable|numeric',
            'product.is_active' => 'nullable|boolean',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'product.name.required' => 'Product name is required.',
            'product.code.unique' => 'This product code is taken.',
            'product.type.required' => 'Product type is required.',
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
                ->readable()
                ->orderBy('name')
                ->get()
                ->map(fn($tax) => ['value' => $tax->id, 'label' => $tax->label]),

            'categories' => model('label')
                ->readable()
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

        return redirect()->route('app.product.update', [
            'productId' => $this->product->id,
            'tab' => $this->product->type === 'variant' && $this->product->wasRecentlyCreated
                ? 'variant'
                : null,
        ]);
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
     * Render
     */
    public function render()
    {
        return atom_view('app.product.form');
    }
}
