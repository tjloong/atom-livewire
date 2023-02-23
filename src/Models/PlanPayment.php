<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Barryvdh\DomPDF\Facade as PDF;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasUniqueNumber;

class PlanPayment extends Model
{
    use HasFilters;
    use HasTrace;
    use HasUniqueNumber;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
        'data' => 'object',
        'plan_order_id' => 'integer',
    ];

    /**
     * Get order for payment
     */
    public function order()
    {
        return $this->belongsTo(model('plan_order'), 'plan_order_id');
    }

    /**
     * Get description attribute
     */
    public function getDescriptionAttribute()
    {
        return $this->order->description;
    }

    /**
     * Get is auto billing attribute
     */
    public function getIsAutoBillingAttribute()
    {
        return data_get($this->data, 'pay_response.data.object.billing_reason') === 'subscription_cycle';
    }
}
