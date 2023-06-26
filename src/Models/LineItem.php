<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LineItem extends Model
{
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'qty' => 'float',
        'amount' => 'float',
        'subtotal' => 'float',
        'discount_amount' => 'float',
        'tax_amount' => 'float',
        'grand_total' => 'float',
        'data' => 'array',
        'seq' => 'integer',
        'image_id' => 'integer',
    ];

    // model booted
    protected static function booted(): void
    {
        static::saving(function($item) {
            $item->setAttributes();
        });
    }

    // get image for item
    public function image(): BelongsTo
    {
        return $this->belongsTo(model('file'), 'image_id');
    }

    // get taxes for item
    public function taxes(): BelongsToMany
    {
        return $this->belongsToMany(model('tax'), 'line_item_taxes')->withPivot('amount');
    }

    // set taxes
    public function setTaxes($country, $state = null)
    {
        if ($this->product) {
            $this->product->taxes()
                ->where('country', $country)
                ->where(fn($q) => $q->whereNull('state')->where('state', $state))
                ->each(function ($tax) {
                    $this->taxes()->attach([
                        $tax->id => ['amount' => $tax->calculate($this->subtotal)],
                    ]);
                });
            
            $this->touch();
        }
    }

    // set attributes
    public function setAttributes()
    {
        $product = $this->product() ? $this->product : null;
        $variant = $this->variant() ? $this->variant : null;
        $order = $this->order() ? $this->order : null;
        $taxes = $this->taxes() ? $this->taxes : null;

        $qty = $this->qty;
        $amount = $this->amount ?? optional($variant)->price ?? optional($product)->price ?? 0;
        $subtotal = $qty * $amount;
        $tax = $taxes ? $taxes->map(fn($tax) => $tax->pivot->amount)->sum() : 0;
        $discount = $this->discount;

        if (
            !$this->discount 
            && ($coupon = optional($order)->coupon)
            && $coupon->products->where('id', $this->product_id)->count()
        ) {
            $discount = $coupon->calculate($subtotal);
        }

        $grand = $subtotal + $tax - $discount;

        $data = $this->data ?? (
            $product ? [
                'weight' => $qty * (optional($product)->weight ?? 0),
                'is_required_shipping' => optional($product)->is_required_shipping,
            ] : null
        );

        $image = $this->image_id ?? optional($variant)->image_id ?? (
            $product
                ? optional($product->images()->orderBy('seq')->orderBy('id')->first())->id 
                : null
        );

        foreach ([
            'name' => $this->name ?? optional($product)->name,
            'variant_name' => $this->variant_name ?? optional($variant)->name,
            'description' => $this->description ?? optional($product)->description,
            'amount' => $amount,
            'subtotal' => $subtotal,
            'tax_amount' => $tax,
            'discount_amount' => $discount,
            'grand_total' => $grand > 0 ? $grand : 0,
            'data' => $data,
            'image_id' => $image,
        ] as $col => $val) {
            if (has_column($this->getTable(), $col)) $this->fill([$col => $val]);
        }

        return $this;
    }
}
