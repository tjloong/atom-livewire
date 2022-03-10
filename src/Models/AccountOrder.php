<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;

class AccountOrder extends Model
{
    use HasFilters;

    protected $table = 'accounts_orders';
    
    protected $guarded = [];

    protected $casts = [
        'grand_total' => 'float',
        'data' => 'object',
        'account_id' => 'integer',
    ];

    /**
     * Get account for order
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get subscriptions for order
     */
    public function subscriptions()
    {
        return $this->hasMany(AccountSubscription::class, 'account_order_id');
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
            ->whereHas('account', fn($q) => $q->search($search))
        );
    }
}
