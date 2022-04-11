<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;

class AccountSubscription extends Model
{
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'is_trial' => 'boolean',
        'start_at' => 'datetime',
        'expired_at' => 'datetime',
        'account_id' => 'integer',
        'account_order_item_id' => 'integer',
        'plan_price_id' => 'integer',
    ];

    /**
     * Get account for account subscription
     */
    public function account()
    {
        return $this->belongsTo(get_class(model('account')));
    }

    /**
     * Get account order item for account subscription
     */
    public function accountOrderItem()
    {
        return $this->belongsTo(get_class(model('account_order_item')));
    }

    /**
     * Get plan price for account subscription
     */
    public function planPrice()
    {
        return $this->belongsTo(get_class(model('plan_price')));
    }

    /**
     * Scope for fussy search
     * 
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(fn($q) => $q
            ->whereHas('plan_price', fn($q) => $q
                ->whereHas('plan', fn($q) => $q->search($search))
            )
            ->whereHas('account', fn($q) => $q->search($search))
        );
    }

    /**
     * Scope for status
     * 
     * @param Builder $query
     * @param mixed $statuses
     * @return Builder
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
     * 
     * @return string
     */
    public function getStatusAttribute()
    {
        if ($this->start_at->greaterThan(now())) return 'pending';
        if ($this->expired_at && $this->expired_at->lessThan(now())) return 'expired';

        return 'active';
    }
}
