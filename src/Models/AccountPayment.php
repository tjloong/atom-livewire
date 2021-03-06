<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Barryvdh\DomPDF\Facade as PDF;
use Jiannius\Atom\Traits\HasFilters;
use Jiannius\Atom\Traits\HasUniqueNumber;

class AccountPayment extends Model
{
    use HasFilters;
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
     * Get account payment description attribute
     */
    public function getDescriptionAttribute()
    {
        return $this->accountOrder->description;
    }

    /**
     * Generate pdf
     */
    public function pdf($request)
    {
        $filename = 'billing-payment-'.$this->number.'.pdf';
        $path = 'pdf.account-payment';
        $view = view()->exists($path) ? $path : 'atom::'.$path;
        $instance = PDF::loadView($view, [
            'accountPayment' => $this,
            'title' => $filename,
            'doc' => data_get($request, 'doc'),
        ]);

        return (object)compact('filename', 'instance');
    }

    /**
     * Provisioning
     */
    public function provision()
    {
        if ($this->status !== 'success') return;
        if ($this->provisioned_at) return;

        foreach ($this->accountOrder->accountOrderItems as $item) {
            $planPrice = $item->planPrice;

            // trial
            if ($planPrice->plan->trial && !$this->account->hasPlanPrice($planPrice->id)) {
                $data = [
                    'is_trial' => true,
                    'start_at' => $this->created_at,
                    'expired_at' => $this->created_at->addDays($planPrice->plan->trial),
                ];
            }
            // non-trial
            else {
                $lastSubscription = $this->account->accountSubscriptions()->where('plan_price_id', $planPrice->id)->latest()->first();
                $start = $lastSubscription ? $lastSubscription->expired_at->addSecond() : $this->created_at;
                $end = $planPrice->is_lifetime
                    ? null
                    : $start->copy()->addMonths($planPrice->expired_after);

                $data = [
                    'is_trial' => false,
                    'start_at' => $start,
                    'expired_at' => $end,
                ];
            }
                
            $this->account->accountSubscriptions()->create(array_merge($data, [
                'account_order_item_id' => $item->id,
                'plan_price_id' => $planPrice->id,
            ]));
        }

        $this->fill(['provisioned_at' => now()])->save();
    }
}
