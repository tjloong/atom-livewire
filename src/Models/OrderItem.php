<?php

namespace Jiannius\Atom\Models;

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
        'grand_total' => 'float',
        'data' => 'object',
        'order_id' => 'integer',
        'product_id' => 'integer',
        'product_variant_id' => 'integer',
    ];

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
        if (!enabled_module('products')) return null;

        return $this->belongsTo(model('product'));
    }

    /**
     * Get product variant for item
     */
    public function productVariant(): mixed
    {
        if (!enabled_module('products')) return null;

        return $this->belongsTo(model('product_variant'));
    }
}
