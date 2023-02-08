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

    /**
     * Provisioning
     */
    public function provision($metadata = null)
    {
        if ($this->status !== 'success') return;

        // stripe info
        $stripeData = array_filter([
            'stripe_customer_id' => data_get($metadata, 'stripe_customer_id'),
            'stripe_subscription_id' => data_get($metadata, 'stripe_subscription_id'),
        ]);

        foreach ($this->order->items as $item) {
            if ($subscription = model('plan_subscription')->firstWhere('plan_order_item_id', $item->id)) {
                $subscription->fill(['data' => array_merge((array)$subscription->data, $stripeData)])->save();
            }
            else {
                $price = $item->price;
    
                // trial
                if ($price->plan->trial && !$this->order->user->hasPlanPrice($price->id)) {
                    $fields = [
                        'is_trial' => true,
                        'start_at' => $this->created_at,
                        'expired_at' => $this->created_at->addDays($price->plan->trial),
                    ];
                }
                // non-trial
                else {
                    $lastSubscription = $this->order->user->subscriptions()
                        ->where('plan_price_id', $price->id)
                        ->latest()
                        ->first();

                    $start = $lastSubscription 
                        ? $lastSubscription->expired_at->addSecond() 
                        : $this->created_at;

                    $end = $price->is_lifetime
                        ? null
                        : $start->copy()->addMonths($price->expired_after);
    
                    $fields = [
                        'is_trial' => false,
                        'start_at' => $start,
                        'expired_at' => $end,
                    ];
                }
                    
                $this->order->user->subscriptions()->create(array_merge(
                    $fields, 
                    ['data' => $stripeData],
                    [
                        'plan_order_item_id' => $item->id,
                        'plan_price_id' => $price->id,
                    ]
                ));
            }
        }

        $this->fill(['provisioned_at' => now()])->save();
    }
}
