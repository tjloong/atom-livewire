<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanSubscription extends Model
{
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'is_trial' => 'boolean',
        'start_at' => 'datetime',
        'expired_at' => 'datetime',
        'data' => 'object',
        'user_id' => 'integer',
        'plan_order_item_id' => 'integer',
        'plan_price_id' => 'integer',
    ];

    /**
     * Get user for subscription
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(model('user'));
    }

    /**
     * Get item for subscription
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(model('plan_order_item'), 'plan_order_item_id');
    }

    /**
     * Get price for subscription
     */
    public function price(): BelongsTo
    {
        return $this->belongsTo(model('plan_price'), 'plan_price_id');
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->whereHas('price', fn($q) => $q
                ->whereHas('plan', fn($q) => $q->search($search))
            )
            ->orWhereHas('user', fn($q) => $q->search($search))
        );
    }

    /**
     * Scope for status
     */
    public function scopeStatus($query, $status): void
    {
        $query->where(function($q) use ($status) {
            foreach ((array)$status as $val) {
                if ($val === 'pending') $q->orWhere('start_at', '>', now());
                if ($val === 'expired') $q->orWhere('expired_at', '<', now());
                if ($val === 'active') {
                    $q->orWhere(fn($q) => $q
                        ->where(fn($q) => $q
                            ->whereNull('start_at')
                            ->orWhere('start_at', '<=', now())
                        )
                        ->where(fn($q) => $q
                            ->whereNull('expired_at')
                            ->orWhere('expired_at', '>=', now())
                        )
                    );
                }
            }
        });
    }

    /**
     * Scope for plan
     */
    public function scopePlan($query, $plan): void
    {
        $id = is_numeric($plan) ? $plan : (
            is_string($plan) 
                ? optional(model('plan')->where('slug', $plan)->first())->id 
                : optional($plan)->id
        );

        $query->whereHas('price', fn($q) => $q->where('plan_id', $id));
    }

    /**
     * Scope for price
     */
    public function scopePrice($query, $price): void
    {
        $id = is_numeric($price) ? $price : optional($price)->id;

        $query->where('plan_price_id', $id);
    }

    /**
     * Attribute for status
     */
    protected function status(): Attribute
    {
        return new Attribute(
            get: function() {
                if ($this->start_at->greaterThan(now())) return 'pending';
                if ($this->expired_at && $this->expired_at->lessThan(now())) return 'expired';
        
                return 'active';
            },
        );
    }
}
