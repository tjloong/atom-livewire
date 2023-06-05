<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'qty' => 'float',
        'price' => 'float',
        'product_id' => 'integer',
        'product_variant_id' => 'integer',
        'user_id' => 'integer',
    ];

    /**
     * Get product for cart
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(model('product'));
    }

    /**
	 * Get variant for cart
	 */
	public function variant(): BelongsTo
	{
		return $this->belongsTo(model('product_variant'));
	}

    /**
     * Get user for cart
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(model('user'));
    }

    /**
     * Attribute for amount
     */
    public function amount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->qty * $this->price,
        );
    }
}
