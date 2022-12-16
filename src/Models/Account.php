<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
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
     * Get settings for account
     */
    public function settings()
    {
        return $this->hasOne(get_class(model('account_setting')));
    }

    /**
     * Get subscriptions for account
     */
    public function subscriptions()
    {
        return $this->hasMany(get_class(model('account_subscription')));
    }

    /**
     * Get orders for account
     */
    public function orders()
    {
        return $this->hasMany(get_class(model('account_order')));
    }

    /**
     * Get payments for account
     */
    public function payments()
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

        return $this->subscriptions()
            ->status('active')
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

        return $this->subscriptions()->status('active')->where('plan_price_id', $id)->count() > 0;
    }
}
