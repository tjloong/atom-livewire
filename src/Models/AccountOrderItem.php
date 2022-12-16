<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;

class AccountOrderItem extends Model
{
    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
        'discounted_amount' => 'float',
        'grand_total' => 'float',
        'data' => 'object',
        'account_order_id' => 'integer',
        'plan_price_id' => 'integer',
    ];

    /**
     * Get order for account order item
     */
    public function order()
    {
        return $this->belongsTo(get_class(model('account_order')));
    }

    /**
     * Get subscription for account order items
     */
    public function subscription()
    {
        return $this->hasOne(get_class(model('account_subscription')));
    }

    /**
     * Get plan price for account order item
     */
    public function planPrice()
    {
        return $this->belongsTo(get_class(model('plan_price')));
    }
}
