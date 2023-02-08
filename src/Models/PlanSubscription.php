<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;

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
    public function user()
    {
        return $this->belongsTo(model('user'));
    }

    /**
     * Get item for subscription
     */
    public function item()
    {
        return $this->belongsTo(model('plan_order_item'), 'plan_order_item_id');
    }

    /**
     * Get price for subscription
     */
    public function price()
    {
        return $this->belongsTo(model('plan_price'), 'plan_price_id');
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(fn($q) => $q
            ->whereHas('price', fn($q) => $q
                ->whereHas('plan', fn($q) => $q->search($search))
            )
            ->whereHas('user', fn($q) => $q->search($search))
        );
    }

    /**
     * Scope for status
     */
    public function scopeStatus($query, $statuses)
    {
        return $query->where(function($q) use ($statuses) {
            foreach ((array)$statuses as $status) {
                if ($status === 'pending') $q->orWhere('start_at', '>', now());
                if ($status === 'expired') $q->orWhere('expired_at', '<', now());
                if ($status === 'active') {
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
     * Get status attribute
     */
    public function getStatusAttribute()
    {
        if ($this->start_at->greaterThan(now())) return 'pending';
        if ($this->expired_at && $this->expired_at->lessThan(now())) return 'expired';

        return 'active';
    }
}
