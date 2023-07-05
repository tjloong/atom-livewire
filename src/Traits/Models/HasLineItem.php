<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasLineItem
{
    // get line items for model
    public function line_items(): HasMany {
        return $this->hasMany(model('line_item'));
    }

    // set contact info
    // public function setContactInfo(
    //     $columns = ['name', 'address', 'person', 'payterm']
    // ): mixed {
    //     $values = collect($columns)
    //         ->filter(fn($col) => has_column($this->getTable(), $col))
    //         ->mapWithKeys(fn($col) => [
    //             $col => [
    //                 'name' => optional($this->contact)->name,

    //                 'address' => $this->contact ? (
    //                     format_address($this->contact)
    //                     ?? format_address($this->contact->billing)
    //                     ?? format_address($this->contact->shipping)
    //                 ) : null,
                    
    //                 'person' => $this->contact &&  optional($this->contact->persons)->count() === 1
    //                     ? $this->contact->persons->first()->name
    //                     : null,

    //                 'payterm' => optional($this->contact)->payterm,
    //             ][$col] ?? null,
    //         ])
    //         ->toArray();

    //     $this->fill($values);

    //     return $this;
    // }

    // save line items
    public function saveLineItems($lineItems): void 
    {
        if ($id = collect($lineItems)->pluck('id')->values()->all()) {
            $this->line_items()->whereNotIn('id', $id)->delete();
        }

        foreach ($lineItems as $item) {
            $this->updateOrCreateLineItem($item);
        }

        $this->fresh()
            ->sumLineItemsTotal()
            ->setLineItemsSummary()
            ->saveQuietly();
    }

    // update or create line item
    public function updateOrCreateLineItem($input): void 
    {
        $input = collect($input)->except('ulid');

        if ($item = $this->line_items()->find($input->get('id'))) $item->fill($input->toArray())->save();
        else $item = $this->line_items()->create($input->toArray());

        if ($taxes = data_get($input, 'taxes')) {
            $item->taxes()->sync(
                collect($taxes)->mapWithKeys(fn($tax) => [
                    data_get($tax, 'id') => ['amount' => data_get($tax, 'amount')],
                ])
            );
        }
    }

    // sum line items total
    public function sumLineItemsTotal(): mixed 
    {
        $subtotal = $this->line_items->sum('subtotal');
        $tax = $this->line_items->sum('tax_amount');
        $grandTotal = $this->line_items->sum('grand_total');

        foreach ([
            'subtotal' => $subtotal,
            'tax_amount' => $tax,
            'grand_total' => $grandTotal,
        ] as $col => $val) {
            if (has_column($this->getTable(), $col)) $this->fill([$col => $val]);
        }

        return $this;
    }

    // set line items summary
    public function setLineItemsSummary(): mixed 
    {
        if (has_column($this->getTable(), 'summary')) {
            $summary = str()->limit($this->line_items->pluck('name')->join(', '), 200);
            $this->fill(compact('summary'));
        }

        return $this;
    }
}