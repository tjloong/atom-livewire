<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\Models\HasUniqueNumber;

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
     * Get items for account order
     */
    public function items()
    {
        return $this->hasMany(get_class(model('account_order_item')));
    }

    /**
     * Get subscriptions for account order
     */
    public function subscriptions()
    {
        return $this->hasMany(get_class(model('account_subscription')));
    }

    /**
     * Get payments for account order
     */
    public function payments()
    {
        return $this->hasMany(get_class(model('account_payment')));
    }

    /**
     * Get description attribute
     */
    public function getDescriptionAttribute()
    {
        return $this->items->pluck('name')->join(', ');
    }
}
