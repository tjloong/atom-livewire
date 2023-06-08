<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'price' => 'float',
        'min' => 'float',
        'max' => 'float',
        'countries' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where('name', 'like', $search);
    }

    /**
     * Scope for weight condition
     */
    public function scopeWeightCondition($query, $weight): void
    {
        $query->where(function ($q) use ($weight) {
            $q->whereNull('condition')->orWhere(fn($q) => 
                $q->where('condition', 'weight')->where('min', '<=', $weight)->where('max', '>=', $weight)
            );
        });
    }

    /**
     * Scope for amount condition
     */
    public function scopeAmountCondition($query, $amount): void
    {
        $query->where(function ($q) use ($amount) {
            $q->whereNull('condition')->orWhere(fn($q) => 
                $q->where('condition', 'amount')->where('min', '<=', $amount)->where('max', '>=', $amount)
            );
        });
    }

    /**
     * Scope for country
     */
    public function scopeCountry($query, $country): void
    {
        $query->where(function ($q) use ($country) {
            $q->whereNull('countries')->orWhere(function ($q) use ($country) {
                foreach ((array)$country as $val) {
                    $q->orWhere('countries', 'like', '%"'.$val.'"%');
                }
            });
        });
    }
}
