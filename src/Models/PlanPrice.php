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
        'expired_after' => 'integer',
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
        else if ($this->expired_after === 1) return 'monthly';
        else if ($this->expired_after === 12) return 'yearly';
        else return $this->expired_after.' months';
    }
}
