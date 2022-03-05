<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\HasOwner;
use Illuminate\Database\Eloquent\Model;

class PlanPrice extends Model
{
    use HasOwner;
    
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
     * Get signups for plan price
     */
    public function signups()
    {
        if (!enabled_module('signups')) return;

        return $this->belongsToMany(Signup::class, 'signups_subscriptions', 'plan_price_id', 'signup_id');
    }

    /**
     * Get recurring attribute
     * 
     * @return string
     */
    public function getRecurringAttribute()
    {
        if ($this->is_lifetime) return 'lifetime';
        if ($this->expired_after === '1 day') return 'day';
        if ($this->expired_after === '1 month') return 'month';
        if ($this->expired_after === '1 year') return 'year';

        [$n, $unit] = explode(' ', $this->expired_after);

        return $n.' '.(str($unit)->plural($n));
    }
}
