<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\Models\HasTrace;
use Illuminate\Database\Eloquent\Model;

class PlanPrice extends Model
{
    use HasTrace;
    
    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
        'expired_after' => 'integer',
        'is_recurring' => 'boolean',
        'is_default' => 'boolean',
        'plan_id' => 'integer',
    ];

    /**
     * Get plan for price
     */
    public function plan()
    {
        return $this->belongsTo(model('plan'));
    }
    
    /**
     * Get users for price
     */
    public function users()
    {
        return $this->belongsToMany(model('user'), 'plan_subscriptions', 'plan_price_id', 'user_id');
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
        if (!$this->expired_after) return __('lifetime');
        if ($this->expired_after === 1) return __('monthly');
        if ($this->expired_after === 12) return __('yearly');
        
        return __(':total months', ['total' => $this->expired_after]);
    }
}
