<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;

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
        'is_for_product' => 'boolean',
        'end_at' => 'date',
    ];

    /**
     * Model booted
     */
    protected static function booted(): void
    {
        static::saving(function ($coupon) {
            $coupon->is_for_product = $coupon->products->count() > 0;
        });
    }

    /**
     * Get products for coupon
     */
    public function products(): mixed
    {
        if (!has_table('coupon_products')) return null;

        return $this->belongsToMany(model('product'), 'coupon_products');
    }

    /**
     * Get orders for coupon
     */
    public function orders(): mixed
    {
        if (!has_table('orders')) return null;

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

    /**
     * Calculate discount amount
     */
    public function calculate($amount)
    {
        return $this->type === 'percentage'
            ? ($this->rate/100) * $amount
            : $this->rate;
    }
}
