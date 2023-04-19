<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Jiannius\Atom\Traits\Models\HasTrace;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    public function plan(): BelongsTo
    {
        return $this->belongsTo(model('plan'));
    }
    
    /**
     * Get users for price
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(model('user'), 'plan_subscriptions', 'plan_price_id', 'user_id');
    }

    /**
     * Attribute for name
     */
    public function name(): Attribute
    {
        return new Attribute(
            get: fn() => currency($this->amount, $this->currency).' '.$this->recurring,
        );
    }

    /**
     * Attribute for recurring
     */
    public function recurring(): Attribute
    {
        return new Attribute(
            get: function() {
                if (!$this->expired_after) return __('lifetime');
                if ($this->expired_after === 1) return __('monthly');
                if ($this->expired_after === 12) return __('yearly');

                return __(':total months', ['total' => $this->expired_after]);
            }, 
        );        
    }
}
