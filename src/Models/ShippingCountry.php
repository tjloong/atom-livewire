<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingCountry extends Model
{
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'rate_id' => 'integer',
    ];

    /**
     * Get rate for country
     */
    public function rate(): BelongsTo
    {
        return $this->belongsTo(model('shipping_rate'), 'rate_id');
    }
}
