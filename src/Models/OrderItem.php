<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'qty' => 'float',
        'unit_amount' => 'float',
        'subtotal' => 'float',
        'discount_amount' => 'float',
        'tax_amount' => 'float',
        'grand_total' => 'float',
        'weight' => 'float',
        'data' => 'array',
        'is_required_shipping' => 'boolean',
        'order_id' => 'integer',
        'product_id' => 'integer',
        'product_variant_id' => 'integer',
        'image_id' => 'integer',
        'tax_id' => 'integer',
    ];

    /**
     * Model booted
     */
    protected static function booted(): void
    {
        static::saving(function($item) {
            $item->setAttributes();
        });
    }

    /**
     * Get order for item
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(model('order'));
    }

    /**
     * Get product for item
     */
    public function product(): mixed
    {
        if (!has_table('products')) return null;

        return $this->belongsTo(model('product'));
    }

    /**
     * Get product variant for item
     */
    public function variant(): mixed
    {
        if (!has_table('product_variants')) return null;

        return $this->belongsTo(model('product_variant'), 'product_variant_id');
    }

    /**
     * Get tax for item
     */
    public function tax(): mixed
    {
        if (!has_table('taxes')) return null;

        return $this->belongsTo(model('tax'));
    }

    /**
     * Get image for item
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(model('file'), 'image_id');
    }

    /**
     * Set attributes
     */
    public function setAttributes()
    {
        $qty = $this->qty;
        $amount = $this->unit_amount ?? optional($this->variant)->price ?? optional($this->product)->price ?? 0;
        $subtotal = $qty * $amount;
        $tax = optional($this->tax)->calculate($subtotal);

        $coupon = optional($this->order)->coupon;
        $discount = optional($coupon)->is_for_product
            ? optional($coupon)->calculate($subtotal)
            : 0;

        $grand = $subtotal + $tax - $discount;

        $this->fill([
            'name' => $this->name ?? optional($this->product)->name,
            'description' => $this->description ?? optional($this->variant)->name,
            'unit_amount' => $amount,
            'subtotal' => $subtotal,
            'tax_amount' => $tax,
            'discount_amount' => $discount,
            'grand_total' => $grand > 0 ? $grand : 0,
            'is_required_shipping' => $this->product->is_required_shipping ?? false,
            'image_id' => optional($this->variant)->image_id
                ?? optional($this->product->images()->orderBy('seq')->orderBy('id')->first())->id,
        ]);
    }
}
