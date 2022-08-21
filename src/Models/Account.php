<?php

namespace Jiannius\Atom\Models;

use App\Models\User;
use Jiannius\Atom\Traits\HasTrace;
use Jiannius\Atom\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasTrace;
    use HasFilters;
    use SoftDeletes;
    
    protected $guarded = [];

    protected $casts = [
        'data' => 'object',
        'agree_tnc' => 'boolean',
        'agree_marketing' => 'boolean',
        'onboarded_at' => 'datetime',
    ];

    /**
     * Get users for account
     */
    public function users()
    {
        return $this->hasMany(get_class(model('user')));
    }

    /**
     * Get account settings for account
     */
    public function accountSettings()
    {
        return $this->hasOne(get_class(model('account_setting')));
    }

    /**
     * Get account subscriptions for account
     */
    public function accountSubscriptions()
    {
        return $this->hasMany(get_class(model('account_subscription')));
    }

    /**
     * Get account orders for account
     */
    public function accountOrders()
    {
        return $this->hasMany(get_class(model('account_order')));
    }

    /**
     * Get account payments for account
     */
    public function accountPayments()
    {
        return $this->hasMany(get_class(model('account_payment')));
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->where('email', 'like', "%$search%")
        );
    }

    /**
     * Get status attribute
     */
    public function getStatusAttribute()
    {
        if ($this->trashed()) return 'trashed';
        if ($this->blocked()) return 'blocked';

        if ($this->type === 'signup') {
            if ($this->onboarded_at) return 'onboarded';
            else return 'new';
        }

        return 'active';
    }

    /**
     * Onboard account
     */
    public function onboard()
    {
        $this->onboarded_at = now();
        $this->saveQuietly();
    }

    /**
     * Check account has plan
     */
    public function hasPlan($id)
    {
        if (!enabled_module('plans')) return false;

        return $this->accountSubscriptions()->status('active')
            ->when(is_numeric($id), 
                fn($q) => $q->whereHas('planPrice', fn($q) => $q->where('plan_prices.plan_id', $id)),
                fn($q) => $q->whereHas('planPrice', fn($q) => $q->whereHas('plan', fn($q) => 
                    $q->where('plans.slug', $id)
                ))
            )
            ->count() > 0;
    }

    /**
     * Check account has plan price
     */
    public function hasPlanPrice($id)
    {
        if (!enabled_module('plans')) return false;
        
        return $this->accountSubscriptions()->status('active')->where('plan_price_id', $id)->count() > 0;
    }
}
