<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\Form;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Item extends Component
{
    use WithPopupNotify;

    public $item;
    public $columns;
    public $document;

    protected $rules = [
        'item.name' => 'required',
        'item.qty' => 'required',
        'item.amount' => 'required',
        'item.description' => 'nullable',
        'item.taxes' => 'array',
    ];

    /**
     * Get listeners
     */
    protected function getListeners()
    {
        $id = data_get($this->item, 'id') ?? data_get($this->item, 'ulid');

        return [
            'setProduct:'.$id => 'setProduct',
        ];
    }

    /**
     * Get product property
     */
    public function getProductProperty()
    {
        if (!data_get($this->item, 'product_id')) return;

        return model('product')->find(data_get($this->item, 'product_id'));
    }

    /**
     * Get variant property
     */
    public function getVariantProperty()
    {
        if (!$this->product) return;

        return $this->product->variants()->find(data_get($this->item, 'product_variant_id'));
    }

    /**
     * Get recommended price property
     */
    public function getRecommendedPriceProperty()
    {
        return in_array($this->document->type, ['purchase-order', 'bill'])
            ? optional($this->variant ?? $this->product)->cost
            : optional($this->variant ?? $this->product)->price;
    }

    /**
     * Get subtotal property
     */
    public function getSubtotalProperty()
    {
        return data_get($this->item, 'qty') * data_get($this->item, 'amount');
    }

    /**
     * Get taxes property
     */
    public function getTaxesProperty()
    {
        return model('tax')->readable()->status('active')->get();
    }

    /**
     * Updated item
     */
    public function updatedItem()
    {
        $this->setTaxes();
        $this->emitEvent();
    }

    /**
     * Open product modal
     */
    public function open()
    {
        $this->emitTo(lw('app.document.form.product-modal'), 'open', $this->item);
    }

    /**
     * Clear product
     */
    public function clearProduct()
    {
        $this->fill([
            'item.name' => null,
            'item.product_id' => null,
            'item.taxes' => [],
        ]);

        $this->emitEvent();
    }

    /**
     * Set product
     */
    public function setProduct($data)
    {
        $this->fill(['item' => $data]);
        $this->emitEvent();
    }

    /**
     * Set taxes
     */
    public function setTaxes()
    {
        $taxes = collect(data_get($this->item, 'taxes'))->map(fn($tax) => array_merge($tax, [
            'amount' => optional($this->taxes->firstWhere('id', data_get($tax, 'id')))
                ->calculate($this->subtotal),
        ]));

        $this->fill(['item.taxes' => $taxes]);
    }

    /**
     * Add tax
     */
    public function addTax($taxId)
    {
        $addedTaxes = collect(data_get($this->item, 'taxes'));

        if ($addedTaxes->firstWhere('id', $taxId)) {
            $this->popup('Tax already added.', 'alert');
        }
        else if ($tax = $this->taxes->firstWhere('id', $taxId)) {
            $addedTaxes->push([
                'id' => $tax->id, 
                'label' => $tax->label,
                'amount' => $tax->calculate(data_get($this->item, 'amount')) * data_get($this->item, 'qty'),
            ]);

            $this->fill(['item.taxes' => $addedTaxes->toArray()]);
            $this->emitEvent();
        }
    }

    /**
     * Remove tax
     */
    public function removeTax($taxId)
    {
        $addedTaxes = collect(data_get($this->item, 'taxes'))->reject(
            fn($tax) => data_get($tax, 'id') === $taxId
        );

        $this->fill(['item.taxes' => $addedTaxes->toArray()]);
        $this->emitEvent();
    }

    /**
     * Emit event
     */
    public function emitEvent()
    {
        $this->fill(['item.subtotal' => $this->subtotal]);
        $this->emitUp('setItem', $this->item);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.form.item');
    }
}