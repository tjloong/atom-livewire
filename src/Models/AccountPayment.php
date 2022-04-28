<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\HasUniqueNumber;

class AccountPayment extends Model
{
    use HasUniqueNumber;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
        'data' => 'object',
        'account_id' => 'integer',
        'account_order_id' => 'integer',
    ];

    /**
     * Get account for account payment
     */
    public function account()
    {
        return $this->belongsTo(get_class(model('account')));
    }

    /**
     * Get account order for account payment
     */
    public function accountOrder()
    {
        return $this->belongsTo(get_class(model('account_order')));
    }

    /**
     * Provisioning
     */
    public function provision()
    {
        if ($this->status !== 'success') return;
        if ($this->provisioned_at) return;

        foreach ($this->accountOrder->accountOrderItems as $item) {
            if (!$item->accountSubscription) {
                $price = $item->planPrice;
    
                // trial
                if ($price->plan->trial && !$this->account->hasPlanPrice($price->id)) {
                    $subscription = [
                        'is_trial' => true,
                        'start_at' => $this->created_at,
                        'expired_at' => $this->created_at->addDays($price->plan->trial),
                    ];
                }
                // non-trial
                else {
                    $start = $this->created_at;
    
                    if (!$price->is_lifetime) {
                        [$n, $unit] = explode(' ', $price->expired_after);
    
                        if ($unit === 'day') $end = $start->copy()->addDays($n);
                        if ($unit === 'month') $end = $start->copy()->addMonths($n);
                        if ($unit === 'year') $end = $start->copy()->addYears($n);
                    }
    
                    $subscription = [
                        'is_trial' => false,
                        'start_at' => $start,
                        'expired_at' => $end,
                    ];
                }
                
                $this->account->accountSubscriptions()->create(array_merge($subscription, [
                    'account_order_item_id' => $item->id,
                    'plan_price_id' => $price->id,                
                ]));
            }
        }

        $this->fill(['provisioned_at' => now()])->save();
    }
}
