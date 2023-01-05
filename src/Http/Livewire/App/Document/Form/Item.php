<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\Form;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Item extends Component
{
    use WithPopupNotify;

    public $items;
    public $columns;
    public $document;

    protected $listeners = ['addItem'];
    protected $rules = ['document.type' => 'required'];
    
    /**
     * Mount
     */
    public function mount()
    {
        $this->init();
    }

    /**
     * Get taxes property
     */
    public function getTaxesProperty()
    {
        return model('tax')
            ->when(
                model('tax')->enabledBelongsToAccountTrait,
                fn($q) => $q->belongsToAccount(),
            )
            ->status('active')
            ->get();
    }

    /**
     * Updated items
     */
    public function updatedItems()
    {
        $this->syncItems();
    }

    /**
     * Init
     * For initialize items on first load, subsequent update will call syncItems
     */
    public function init()
    {
        $this->columns = $this->document->getColumns();

        $items = $this->document->items()->with('taxes')->get()->toArray();

        $this->items = collect($items)->map('collect')
            ->map(fn($item) => $item->merge($this->getItemLatestProduct($item)))
            ->map(fn($item) => $item->merge($this->getItemRecommendedPrice($item)))
            ->map(fn($item) => $item->merge($this->getItemInfo($item)))
            ->each(function($item) {
                $taxes = collect($item->get('taxes'))->map(fn($tax) => [
                    'id' => data_get($tax, 'id'),
                    'label' => data_get($tax, 'label'),
                    'amount' => data_get($tax, 'pivot.amount'),
                ]);

                $item->put('taxes', $taxes);
            })
            ->toArray();
    }

    /**
     * Sync items
     * Will be called whenever items changed, except on first load it will call initItems
     */
    public function syncItems()
    {
        $this->items = collect($this->items)->map('collect')
            ->map(fn($item) => $item->merge($this->getItemLatestProduct($item)))
            ->map(fn($item) => $item->merge($this->getItemRecommendedPrice($item)))
            ->map(fn($item) => $item->merge($this->getItemInfo($item)))
            ->map(fn($item) => $item->merge($this->getItemTaxes($item)))
            ->toArray();

        $this->emitUp('setItems', $this->items);
    }

    /**
     * Open product modal
     */
    public function openProductModal()
    {
        $this->emitTo(lw('app.document.form.product-modal'), 'open', $this->document->currency);
    }

    /**
     * Get item latest product
     */
    public function getItemLatestProduct($item)
    {
        $metadata = collect($item->get('metadata')) ?? collect();
        if ($metadata->get('product')) return $item;

        $product = model('product')->find($item->get('product_id'));

        if ($product) {
            $taxes = $product->taxes->map(fn($tax) => ['id' => $tax->id, 'label' => $tax->label]);
            $variant = $product->variants()->find($item->get('product_variant_id'));
            $metadata->put('product', collect(array_merge($product->toArray(), ['taxes' => $taxes])));
            $metadata->put('variant', $variant ? collect($variant->toArray()) : null);
        }
        else {
            $metadata->put('product', null);
        }

        return ['metadata' => $metadata->toArray()];
    }

    /**
     * Get item recommended price
     */
    public function getItemRecommendedPrice($item)
    {
        $metadata = collect($item->get('metadata')) ?? collect();
        $product = $metadata->get('product');
        $variant = $metadata->get('variant');
        $price = in_array($this->document->type, ['purchase-order', 'bill'])
            ? data_get(optional($variant ?? $product), 'cost')
            : data_get(optional($variant ?? $product), 'price');

        $metadata->put('recommended_price', [
            'amount' => $price,
        ]);

        return ['metadata' => $metadata];
    }

    /**
     * Get item info
     */
    public function getItemInfo($item)
    {
        $info = collect([
            'name' => $item->get('name', collect([
                data_get($item, 'metadata.product.name'),
                data_get($item, 'metadata.variant.name'),
            ])->filter()->whenNotEmpty(fn($name) => $name->join(' - '))),

            'description' => $item->get('description', data_get($item, 'metadata.product.description')),
            'qty' => $item->get('qty', 1),
        ]);

        if ($this->columns->get('price')) {
            $amount = $item->get('amount') ?: data_get($item, 'metadata.recommended_price.amount', 0);

            $info->put('amount', $amount);
            $info->put('subtotal', data_get($info, 'qty') * data_get($info, 'amount'));
        }

        return $info->toArray();
    }

    /**
     * Get item taxes
     */
    public function getItemTaxes($item)
    {
        $taxes = $item->has('taxes')
            ? collect($item->get('taxes'))
            : collect(data_get($item, 'metadata.product.taxes'))->map(fn($tax) => [
                'id' => data_get($tax, 'id'),
                'label' => data_get($tax, 'label'),
            ]);

        $taxes = $taxes->map(function($tax) use ($item) {
            $qty = $item->get('qty', 1);
            $sel = $this->taxes->firstWhere('id', data_get($tax, 'id'));
            $amount = $sel ? ($qty * $sel->calculate($item->get('amount', 0))) : false;

            return array_merge($tax, ['amount' => $amount]);
        });


        return ['taxes' => $taxes->toArray()];
    }

    /**
     * Add item
     */
    public function addItem($data)
    {
        $this->items = collect($this->items)->push($data);
        $this->syncItems();
    }

    /**
     * Remove item
     */
    public function removeItem($i)
    {
        $this->items = collect($this->items)
            ->reject(fn($item, $key) => $key === $i)
            ->values()
            ->toArray();

        $this->syncItems();
    }

    /**
     * Sort items
     */
    public function sortItems($data)
    {
        $this->items = collect($data)
            ->map(fn($i) => $this->items[$i])
            ->values()
            ->toArray();
    }

    /**
     * Add tax
     */
    public function addTax($index, $taxId)
    {
        $addedTaxes = collect(data_get($this->items, $index.'.taxes'));

        if ($addedTaxes->firstWhere('id', $taxId)) {
            $this->popup('Tax already added.', 'alert');
        }
        else if ($tax = $this->taxes->firstWhere('id', $taxId)) {
            $addedTaxes->push(['id' => $tax->id, 'label' => $tax->label]);

            $this->fill(['items.'.$index.'.taxes' => $addedTaxes->toArray()]);
            $this->syncItems();
        }
    }

    /**
     * Remove tax
     */
    public function removeTax($index, $taxId)
    {
        $addedTaxes = collect(data_get($this->items, $index.'.taxes'))->reject(
            fn($tax) => data_get($tax, 'id') === $taxId
        );

        $this->fill(['items.'.$index.'.taxes' => $addedTaxes->toArray()]);
        $this->syncItems();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.form.item');
    }
}