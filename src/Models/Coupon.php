<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFilters;
    use HasTrace;
    
    protected $guarded = [];

    protected $casts = [
        'rate' => 'float',
        'min_amount' => 'float',
        'limit' => 'integer',
        'is_active' => 'boolean',
        'end_at' => 'date',
    ];

    /**
     * Get products for coupon
     */
    public function products(): BelongsToMany
    {
        if (!enabled_module('products')) return null;

        return $this->belongsToMany(model('product'), 'coupon_products');
    }

    /**
     * Get orders for coupon
     */
    public function orders(): HasMany
    {
        if (!enabled_module('orders')) return null;

        return $this->hasMany(model('order'));
    }

    /**
     * Attribute for status
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->end_at && $this->end_at->lte(today())) return 'ended';
                else if (!$this->is_active) return 'inactive';
                else return 'active';
            },
        );
    }

    /**
     * Attribute for rate display
     */
    protected function rateDisplay(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->type === 'fixed') return currency($this->rate, tenant('settings.default_currency') ?? settings('default_currency'));
                if ($this->type === 'percentage') return str($this->rate)->finish('%')->toString();
            },
        );
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('code', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
        );
    }

    /**
     * Scope for status
     */
    public function scopeStatus($query, $status): void
    {
        $query->where(function($q) use ($status) {
            foreach ((array)$status as $val) {
                if ($val === 'ended') $q->orWhereDate('end_at', '<=', today());
                if ($val === 'inactive') $q->orWhere(fn($q) => $q->where('is_active', false)->orWhereNull('is_active'));
                if ($val === 'active') {
                    $q->orWhere(fn($q) => $q
                        ->where('is_active', true)
                        ->where(fn($q) => $q
                            ->whereNull('end_at')
                            ->orWhereDate('end_at', '>', today())
                        )
                    );
                }
            }
        });
    }
}
