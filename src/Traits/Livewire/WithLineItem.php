<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithLineItem
{
    public $lineItems;

    // updated line items
    public function updatedLineItems(): void {
        $this->sumLineItems();
    }

    // sum line items
    public function sumLineItems(): void {
        $this->lineItems = collect($this->lineItems)
            ->map(fn($item) => array_merge($item, [
                'subtotal' => data_get($item, 'qty') * data_get($item, 'amount'),
            ]))
            ->toArray();
    }

    // add line item
    public function addLineItem(): void {
        if (!$this->lineItems) $this->lineItems = [];

        $this->lineItems = collect($this->lineItems)->push([
            'ulid' => (string) str()->ulid(),
            'name' => null,
            'description' => null,
            'qty' => 1,
            'amount' => null,
        ])->toArray();
    }

    // remove line item
    public function removeLineItem($id): void {
        $this->lineItems = collect($this->lineItems)->reject(
            fn($item) => data_get($item, 'id') === $id
                || data_get($item, 'ulid') === $id
        )->values()->toArray();
    }

    // toggle line item tax
    public function toggleLineItemTax($id, $taxId): void {
        $tax = model('tax')->find($taxId);

        $this->lineItems = collect($this->lineItems)->map(function ($item) use ($id, $tax) {
            if (data_get($item, 'id') === $id || data_get($item, 'ulid') === $id) {
                $taxes = collect(data_get($item, 'taxes'));
                $index = $taxes->where('id', $tax->id)->keys()->first();

                if (is_numeric($index)) $taxes = $taxes->splice($index, 1);
                else $taxes->push([
                    'id' => $tax->id,
                    'label' => $tax->label,
                    'amount' => $tax->calculate(data_get($item, 'amount') * data_get($item, 'qty')),
                ]);

                data_set($item, 'taxes', $taxes->values()->all());
            }

            return $item;
        });
    }

    // sort line items
    public function sortLineItems($data): void
    {
        $this->lineItems = collect($data)
            ->map(fn($id) => 
                collect($this->lineItems)->firstWhere('id', $id)
                ?? collect($this->lineItems)->firstWhere('ulid', $id)
            )
            ->values()
            ->toArray();
    }

    // get line items total
    public function getLineItemsTotal()
    {
        if (!$this->lineItems) return null;

        $taxes = collect($this->lineItems)->pluck('taxes')->collapse()->unique('id')
            ->map('collect')
            ->map(fn($tax) => $tax->put(
                'amount', 
                collect($this->lineItems)->pluck('taxes')->collapse()
                    ->where('id', $tax->get('id'))
                    ->sum('amount'),
            ));

        $subtotal = collect($this->lineItems)->sum('subtotal');
        $grandTotal = $subtotal + $taxes->sum('amount');

        return array_merge(
            ['Subtotal' => $subtotal],
            $taxes->mapWithKeys(fn($tax) => [
                $tax->get('label') => $tax->get('amount'),
            ])->toArray(),
            ['Grand Total' => $grandTotal],
        );
    }
}