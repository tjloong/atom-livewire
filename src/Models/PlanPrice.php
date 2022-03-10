<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\HasTrace;
use Illuminate\Database\Eloquent\Model;

class PlanPrice extends Model
{
    use HasTrace;
    
    protected $table = 'plans_prices';

    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
        'is_lifetime' => 'boolean',
        'is_default' => 'boolean',
        'plan_id' => 'integer',
    ];

    protected $appends = [
        'recurring',
    ];

    /**
     * Get plan for plan price
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get tenants for plan price
     */
    public function tenants()
    {
        if (!enabled_module('tenants')) return;
        
        return $this->belongsToMany(Tenant::class, 'tenants_subscriptions', 'plan_price_id', 'tenant_id');
    }
    
    /**
     * Get accounts for plan price
     */
    public function accounts()
    {
        if (!enabled_module('signups')) return;

        return $this->belongsToMany(Account::class, 'accounts_subscriptions', 'plan_price_id', 'account_id');
    }

    /**
     * Get recurring attribute
     * 
     * @return string
     */
    public function getRecurringAttribute()
    {
        if ($this->is_lifetime) return 'lifetime';
        if ($this->expired_after === '1 day') return 'daily';
        if ($this->expired_after === '1 month') return 'monthly';
        if ($this->expired_after === '1 year') return 'yearly';

        [$n, $unit] = explode(' ', $this->expired_after);

        return $n.' '.(str($unit)->plural($n));
    }
}
