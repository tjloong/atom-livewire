<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;

class PlanOrderItem extends Model
{
    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
        'discounted_amount' => 'float',
        'grand_total' => 'float',
        'data' => 'object',
        'plan_order_id' => 'integer',
        'plan_price_id' => 'integer',
    ];

    /**
     * Get order for item
     */
    public function order()
    {
        return $this->belongsTo(model('plan_order'), 'plan_order_id');
    }

    /**
     * Get subscription for items
     */
    public function subscription()
    {
        return $this->hasOne(model('plan_subscription'));
    }

    /**
     * Get price for item
     */
    public function price()
    {
        return $this->belongsTo(model('plan_price'), 'plan_price_id');
    }
}
