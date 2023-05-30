<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanSubscription extends Model
{
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
        'discounted_amount' => 'float',
        'extension' => 'integer',
        'data' => 'object',
        'is_trial' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'terminated_at' => 'datetime',
        'provisioned_at' => 'datetime',
        'user_id' => 'integer',
        'price_id' => 'integer',
        'payment_id' => 'integer',
    ];

    protected $appends = ['name', 'description'];

    /**
     * Booted
     */
    protected static function booted(): void
    {
        static::saved(function($subscription) {
            session()->forget('can.plans');
        });
    }

    /**
     * Get user for subscription
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(model('user'));
    }

    /**
     * Get price for subscription
     */
    public function price(): BelongsTo
    {
        return $this->belongsTo(model('plan_price'), 'price_id');
    }

    /**
     * Get payment for subscription
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(model('plan_payment'), 'payment_id');
    }

    /**
     * Attribute for name
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn() => collect([
                $this->price->plan->name,
                $this->is_trial ? __('Trial') : null,
            ])->filter()->join(' '),
        );
    }

    /**
     * Attribute for description
     */
    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->price->description,
        );
    }

    /**
     * Attribute for status
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: function() {
                if (empty($this->provisioned_at)) return 'draft';
                else if ($this->start_at->greaterThan(now())) return 'future';
                else if ($this->terminated_at && $this->terminated_at->lessThan(now())) return 'terminated';
                else if ($this->end_at && $this->end_at->addDays($this->extended ?? 0)->lessThan(now())) return 'ended';
                else return 'active';
            },
        );
    }

    /**
     * Attribute for status color
     */
    protected function statusColor(): Attribute
    {
        return Attribute::make(
            get: fn() => [
                'draft' => 'gray',
                'future' => 'blue',
                'terminated' => 'black',
                'ended' => 'gray',
                'active' => 'green',
            ][$this->status] ?? 'gray',
        );
    }

    /**
     * Attribute for dayrate
     */
    protected function dayrate(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->end_at
                ? $this->grand_total / ($this->start_at->diffInDays($this->end_at))
                : null,
        );
    }

    /**
     * Attribute for is auto renew
     */
    protected function isAutoRenew(): Attribute
    {
        return Attribute::make(
            get: fn() => !empty(data_get($this->data, 'stripe_subscription_id')),
        );
    }

    /**
     * Attribute for grand total
     */
    protected function grandTotal(): Attribute
    {
        return Attribute::make(
            get: function () {
                $total = $this->amount - $this->discounted_amount;
                return $total > 0 ? $total : 0;
            },
        );
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->whereHas('price', fn($q) => $q->search($search))
            ->orWhereHas('user', fn($q) => $q->search($search))
            ->orWhereHas('payment', fn($q) => $q->where('number', $search))
        );
    }

    /**
     * Scope for status
     */
    public function scopeStatus($query, $status): void
    {
        $query->where(function($q) use ($status) {
            foreach ((array)$status as $val) {
                if ($val === 'draft') {
                    $q->orWhereNull('provisioned_at');
                }
                else {
                    $q->orWhere(fn($q) => $q
                        ->whereNotNull('provisioned_at')
                        ->where(function ($q) use ($val) {
                            if ($val === 'terminated') $q->whereNotNull('terminated_at');
                            else {
                                $q->whereNull('terminated_at')->where(function($q) use ($val) {
                                    if ($val === 'future') $q->where('start_at', '>', now());
                                    if ($val === 'ended') $q->whereRaw('end_at + interval (if (extension is not null, extension, 0)) day < ?', [now()]);
                                    if ($val === 'active') {
                                        $q->where(fn($q) => $q
                                            ->whereNull('start_at')
                                            ->orWhere('start_at', '<=', now())
                                        )->where(fn($q) => $q
                                            ->whereNull('end_at')
                                            ->orWhereRaw('end_at + interval (if (extension is not null, extension, 0)) day >= ?', [now()])
                                        );
                                    }
                                });
                            }
                        })
                    );
                }
            }
        });
    }

    /**
     * Scope for enabled auto renew
     */
    public function scopeEnabledAutoRenew($query): void
    {
        $query->whereNotNull('data->stripe_subscription_id');
    }

    /**
     * Scope for plan
     */
    public function scopePlan($query, $plan): void
    {
        $id = is_numeric($plan) ? $plan : (
            is_string($plan) 
                ? optional(model('plan')->where('slug', $plan)->first())->id 
                : optional($plan)->id
        );

        $query->whereHas('price', fn($q) => $q->where('plan_id', $id));
    }

    /**
     * Set validity
     */
    public function setValidity(): void
    {
        $this->setAmount();
        $this->setStartDate();
        $this->setIsTrial();
        $this->setEndDate();
        $this->setProrated();
    }

    /**
     * Set amount
     */
    public function setAmount(): void
    {
        $this->currency = $this->price->plan->currency;
        $this->amount = $this->price->amount;
    }

    /**
     * Set start date
     */
    public function setStartDate(): void
    {
        if (!$this->start_at) {
            if (
                ($sibling = $this->getSiblings()->where('is_trial', false)->status(['active', 'future'])->first())
                && optional($sibling->end_at)->isFuture()
            ) {
                $this->start_at = $sibling->end_at;
            }
            else if (
                ($relative = $this->getRelatives()->status(['active', 'future'])->where('amount', '>', $this->amount)->first())
                && optional($relative->end_at)->isFuture()
            ) {
                $this->start_at = $relative->end_at;
            }
            else $this->start_at = now();
        }
    }

    /**
     * Set end date
     */
    public function setEndDate(): void
    {
        $valid = $this->price->valid;
        $count = data_get($valid, 'count');
        $interval = data_get($valid, 'interval');

        if (in_array($interval, ['forever', 'one-off'])) $this->end_at = null;
        else if ($this->is_trial && ($count = $this->price->plan->trial)) $this->end_at = $this->start_at->addDays($count);
        else if (is_numeric($count)) {
            if (in_array($interval, ['day', 'days'])) $this->end_at = $this->start_at->addDays($count);
            if (in_array($interval, ['month', 'months'])) $this->end_at = $this->start_at->addMonths($count);
            if (in_array($interval, ['year', 'years'])) $this->end_at = $this->start_at->addYears($count);
        }
        else $this->end_at = null;
    }

    /**
     * Set is trial
     */
    public function setIsTrial(): void
    {
        if (!$this->price->plan->trial) {
            $this->is_trial = false;
        }
        else if ($this->price->plan->is_unique_trial) {
            $this->is_trial = $this->getSiblings()
                ->whereNotNull('provisioned_at')
                ->where('is_trial', true)
                ->count() <= 0;
        }
        else {
            $this->is_trial = model('plan_subscription')
                ->whereNotNull('provisioned_at')
                ->where('user_id', $this->user_id)
                ->where('is_trial', true)
                ->count() <= 0;
        }

        if ($this->is_trial) $this->discounted_amount = $this->amount;
    }

    /**
     * Set prorated
     */
    public function setProrated(): void
    {
        if (($terminations = $this->getTerminationQueue()) && $terminations->count()) {
            $prorated = $terminations->map(function($termination) {
                $rate = $termination->dayrate ?? 0;
                $credits = $termination->start_at->lte($this->start_at)
                    ? ($rate * ($this->start_at->diffInDays($termination->end_at)))
                    : ($rate * ($termination->start_at->diffInDays($termination->end_at)));

                if ($credits > $this->amount) {
                    $discount = $this->amount;
                    $extension = round(($credits - $this->amount) / $this->dayrate);
                }
                else {
                    $discount = $credits;
                }

                return [
                    'code' => $termination->price->plan->code,
                    'plan' => $termination->name,
                    'credits' => $credits ?? 0,
                    'discount' => $discount ?? 0,
                    'extension' => $extension ?? 0,
                ];
            });

            $this->discounted_amount = ($this->discounted_amount ?? 0) + $prorated->sum('discount');
            $this->extension = $prorated->sum('extension');
            $this->data = array_merge((array)$this->data, ['prorated' => $prorated->toArray()]);
        }
    }

    /**
     * Get siblings (subscriptions with same plan)
     */
    public function getSiblings(): mixed
    {
        return model('plan_subscription')
            ->where('user_id', $this->user_id)
            ->whereHas('price', fn($q) => $q->where('plan_id', $this->price->plan_id))
            ->latest('id');
    }

    /**
     * Get relatives (subscriptions with upgraded plan)
     */
    public function getRelatives(): mixed
    {
        $prices = model('plan')
            ->whereHas('upgrades', fn($q) => $q->where('plan_upgrades.upgrade_id', $this->price->plan_id))
            ->get()
            ->map(fn($plan) => $plan->prices->pluck('id'))
            ->collapse()
            ->values()
            ->toArray();

        return model('plan_subscription')
            ->where('user_id', $this->user_id)
            ->whereIn('price_id', $prices)
            ->latest('id');
    }

    /**
     * Get termination queue
     */
    public function getTerminationQueue(): mixed
    {
        if ($this->status !== 'draft') return null;

        return $this->getRelatives()
            ->with('price.plan')
            ->status(['active', 'future'])
            ->where('amount', '<=', $this->amount)
            ->get()
            ->concat(
                $this->getSiblings()
                    ->where('is_trial', true)
                    ->status(['active', 'future'])
                    ->get()
            );
    }

    /**
     * Terminate subscription
     */
    public function terminate(): void
    {
        $this->fill(['terminated_at' => now()])->save();
    }
}
