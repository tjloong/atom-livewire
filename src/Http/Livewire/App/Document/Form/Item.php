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
    protected function getListeners(): array
    {
        $id = data_get($this->item, 'id') ?? data_get($this->item, 'ulid');

        return [
            'setProduct:'.$id => 'setProduct',
        ];
    }

    /**
     * Get product property
     */
    public function getProductProperty(): mixed
    {
        if (!enabled_module('products')) return null;
        if (!data_get($this->item, 'product_id')) return null;

        return model('product')->find(data_get($this->item, 'product_id'));
    }

    /**
     * Get variant property
     */
    public function getVariantProperty(): mixed
    {
        if (!$this->product) return null;

        return $this->product->variants()->find(data_get($this->item, 'product_variant_id'));
    }

    /**
     * Get recommended price property
     */
    public function getRecommendedPriceProperty(): mixed
    {
        return in_array($this->document->type, ['purchase-order', 'bill'])
            ? optional($this->variant ?? $this->product)->cost
            : optional($this->variant ?? $this->product)->price;
    }

    /**
     * Get subtotal property
     */
    public function getSubtotalProperty(): float
    {
        return data_get($this->item, 'qty') * data_get($this->item, 'amount');
    }

    /**
     * Get taxes property
     */
    public function getTaxesProperty(): mixed
    {
        if (!enabled_module('taxes')) return null;

        return model('tax')->readable()->status('active')->get();
    }

    /**
     * Updated item
     */
    public function updatedItem(): void
    {
        $this->setTaxes();
        $this->emitEvent();
    }

    /**
     * Open product modal
     */
    public function open()
    {
        $this->emitTo(atom_lw('app.document.form.product-modal'), 'open', $this->item);
    }

    /**
     * Clear product
     */
    public function clearProduct(): void
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
    public function setProduct($data): void
    {
        $this->fill(['item' => $data]);
        $this->emitEvent();
    }

    /**
     * Set taxes
     */
    public function setTaxes(): void
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
    public function addTax($taxId): void
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
    public function removeTax($taxId): void
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
    public function emitEvent(): void
    {
        $this->fill(['item.subtotal' => $this->subtotal]);
        $this->emitUp('setItem', $this->item);
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.document.form.item');
    }
}