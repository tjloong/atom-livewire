<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\Models\HasFilters;

class Tenant extends Model
{
    use HasFilters;
    
    protected $guarded = [];
    protected $casts = [];

    /**
     * Get users for tenant
     */
    public function users()
    {
        return $this->belongsToMany(model('user'), 'tenant_users');
    }

    // /**
    //  * Get subscriptions for account
    //  */
    // public function subscriptions()
    // {
    //     return $this->hasMany(model('account_subscription'));
    // }

    // /**
    //  * Get orders for account
    //  */
    // public function orders()
    // {
    //     return $this->hasMany(get_class(model('account_order')));
    // }

    // /**
    //  * Get payments for account
    //  */
    // public function payments()
    // {
    //     return $this->hasMany(get_class(model('account_payment')));
    // }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->whereHas('users', fn($q) => $q->search($search))
        );
    }

    // /**
    //  * Check account has plan
    //  */
    // public function hasPlan($id)
    // {
    //     if (!enabled_module('plans')) return false;

    //     return $this->subscriptions()
    //         ->status('active')
    //         ->when(is_numeric($id), 
    //             fn($q) => $q->whereHas('planPrice', fn($q) => $q->where('plan_prices.plan_id', $id)),
    //             fn($q) => $q->whereHas('planPrice', fn($q) => $q->whereHas('plan', fn($q) => 
    //                 $q->where('plans.slug', $id)
    //             ))
    //         )
    //         ->count() > 0;
    // }

    // /**
    //  * Check account has plan price
    //  */
    // public function hasPlanPrice($id)
    // {
    //     if (!enabled_module('plans')) return false;

    //     return $this->subscriptions()->status('active')->where('plan_price_id', $id)->count() > 0;
    // }
}
