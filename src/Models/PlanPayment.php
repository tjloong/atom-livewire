<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\HasRunningNumber;
use Jiannius\Atom\Traits\Models\HasTrace;

class PlanPayment extends Model
{
    use HasFilters;
    use HasRunningNumber;
    use HasTrace;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
        'data' => 'object',
        'price_id' => 'integer',
        'user_id' => 'integer',
        'subscription_id' => 'integer',
    ];

    /**
     * Get price for payment
     */
    public function price(): BelongsTo
    {
        return $this->belongsTo(model('plan_price'), 'price_id');
    }

    /**
     * Get user for payment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(model('user'));
    }

    /**
     * Get subscription for payment
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(model('plan_subscription'), 'subscription_id');
    }

    /**
     * Attribute for is auto billing
     */
    protected function isAutoBilling(): Attribute
    {
        return new Attribute(
            get: fn() => data_get($this->data, 'pay_response.data.object.billing_reason') === 'subscription_cycle',
        );
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('number', 'like', "%$search%")
        );
    }
}
