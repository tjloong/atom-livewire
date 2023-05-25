<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingRate extends Model
{
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'price' => 'float',
        'min' => 'float',
        'max' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Get countries for shipment
     */
    public function countries(): HasMany
    {
        return $this->hasMany(model('shipping_country'), 'rate_id');
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where('name', 'like', $search);
    }
}
