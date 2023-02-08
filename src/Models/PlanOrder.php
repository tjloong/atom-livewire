<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\Models\HasUniqueNumber;

class PlanOrder extends Model
{
    use HasUniqueNumber;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
        'data' => 'object',
        'user_id' => 'integer',
    ];

    /**
     * Get user for order
     */
    public function user()
    {
        return $this->belongsTo(model('user'));
    }

    /**
     * Get items for order
     */
    public function items()
    {
        return $this->hasMany(model('plan_order_item'));
    }

    /**
     * Get subscriptions for order
     */
    public function subscriptions()
    {
        return $this->hasMany(model('plan_subscription'));
    }

    /**
     * Get payments for order
     */
    public function payments()
    {
        return $this->hasMany(model('plan_payment'));
    }

    /**
     * Get description attribute
     */
    public function getDescriptionAttribute()
    {
        return $this->items->pluck('name')->join(', ');
    }
}
