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
        'account_order_id' => 'integer',
        'plan_price_id' => 'integer',
    ];

    /**
     * Get account order for account order item
     */
    public function accountOrder()
    {
        return $this->belongsTo(get_class(model('account_order')));
    }

    /**
     * Get account subscription for account order items
     */
    public function accountSubscription()
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
