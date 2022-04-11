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
        foreach ($this->accountOrder->accountOrderItems as $item) {
            $price = $item->planPrice;

            if ($price->plan->trial && !$this->account->hasPlanPrice($price->id)) {
                $this->account->accountSubscriptions()->create([
                    'is_trial' => true,
                    'start_at' => $this->created_at,
                    'expired_at' => $this->created_at->addDays($price->plan->trial),
                    'account_order_item_id' => $item->id,
                    'plan_price_id' => $price->id,
                ]);
            }
        }
    }
}
