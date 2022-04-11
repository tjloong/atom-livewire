<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\HasTrace;
use Illuminate\Database\Eloquent\Model;

class PlanPrice extends Model
{
    use HasTrace;
    
    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
        'is_lifetime' => 'boolean',
        'is_default' => 'boolean',
        'plan_id' => 'integer',
    ];

    /**
     * Get plan for plan price
     */
    public function plan()
    {
        return $this->belongsTo(get_class(model('plan')));
    }
    
    /**
     * Get accounts for plan price
     */
    public function accounts()
    {
        return $this->belongsToMany(get_class(model('account')), 'account_subscriptions', 'plan_price_id', 'account_id');
    }

    /**
     * Get name attribute
     */
    public function getNameAttribute()
    {
        return currency($this->amount, $this->currency).' '.$this->recurring;
    }

    /**
     * Get recurring attribute
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
