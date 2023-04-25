<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PlanSubscription extends Model
{
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'data' => 'object',
        'is_trial' => 'boolean',
        'user_id' => 'integer',
        'price_id' => 'integer',
    ];

    protected $appends = ['name', 'description'];

    /**
     * Booted
     */
    protected static function booted(): void
    {
        static::creating(function($subscription) {
            $subscription->setValidity();
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
    public function payment(): HasOne
    {
        return $this->hasOne(model('plan_payment'), 'subscription_id');
    }

    /**
     * Attribute for name
     */
    protected function name(): Attribute
    {
        return new Attribute(
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
        return new Attribute(
            get: fn() => $this->price->description,
        );
    }

    /**
     * Attribute for status
     */
    protected function status(): Attribute
    {
        return new Attribute(
            get: function() {
                if ($this->start_at->greaterThan(now())) return 'future';
                if ($this->end_at && $this->end_at->lessThan(now())) return 'ended';
        
                return 'active';
            },
        );
    }

    /**
     * Attribute for is auto renew
     */
    protected function isAutoRenew(): Attribute
    {
        return new Attribute(
            get: fn() => !empty(data_get($this->data, 'stripe_subscription_id')),
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
        );
    }

    /**
     * Scope for status
     */
    public function scopeStatus($query, $status): void
    {
        $query->where(function($q) use ($status) {
            foreach ((array)$status as $val) {
                if ($val === 'future') $q->orWhere('start_at', '>', now());
                if ($val === 'ended') $q->orWhere('end_at', '<', now());
                if ($val === 'active') {
                    $q->orWhere(fn($q) => $q
                        ->where(fn($q) => $q
                            ->whereNull('start_at')
                            ->orWhere('start_at', '<=', now())
                        )
                        ->where(fn($q) => $q
                            ->whereNull('end_at')
                            ->orWhere('end_at', '>=', now())
                        )
                    );
                }
            }
        });
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
        $existing = model('plan_subscription')
            ->where('user_id', $this->user_id)
            ->whereHas('price', fn($q) => $q->where('plan_id', $this->price->plan_id))
            ->latest('id');

        if (!$this->start_at) {
            if (
                ($last = $existing->first())
                && $last->end_at
                && $last->end_at->isFuture()    
            ) {
                $this->start_at = $last->end_at->addHour();
            }
            else $this->start_at = now();
        }

        $this->is_trial = $this->price->plan->trial > 0 
            && !$existing->where('is_trial', true)->count();

        $this->end_at = $this->end_at ?? $this->getEndDate();
    }

    /**
     * Get end date
     */
    public function getEndDate(): mixed
    {
        $valid = $this->price->valid;
        $count = data_get($valid, 'count');
        $interval = data_get($valid, 'interval');

        if (in_array($interval, ['forever', 'one-off'])) return null;

        if ($this->is_trial) {
            $count = $this->price->plan->trial;
            $interval = 'day';
        }

        if (is_numeric($count)) {
            if (in_array($interval, ['day', 'days'])) return $this->start_at->addDays($count);
            if (in_array($interval, ['month', 'months'])) return $this->start_at->addMonths($count);
            if (in_array($interval, ['year', 'years'])) return $this->start_at->addYears($count);
        }

        return null;
    }
}
