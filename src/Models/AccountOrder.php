<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\HasUniqueNumber;

class AccountOrder extends Model
{
    use HasUniqueNumber;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
        'data' => 'object',
        'account_id' => 'integer',
    ];

    /**
     * Get account for account order
     */
    public function account()
    {
        return $this->belongsTo(get_class(model('account')));
    }

    /**
     * Get account order items for account order
     */
    public function accountOrderItems()
    {
        return $this->hasMany(get_class(model('account_order_item')));
    }

    /**
     * Get account subscriptions for account order
     */
    public function accountSubscriptions()
    {
        return $this->hasMany(get_class(model('account_subscription')));
    }

    /**
     * Get account payments for account order
     */
    public function accountPayments()
    {
        return $this->hasMany(get_class(model('account_payment')));
    }

    /**
     * Get description attribute
     */
    public function getDescriptionAttribute()
    {
        return $this->accountOrderItems->pluck('name')->join(', ');
    }
}
