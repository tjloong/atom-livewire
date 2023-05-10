<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'user_id' => 'integer',
    ];

    /**
     * Get user for payment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(model('user'));
    }

    /**
     * Get subscriptions for payment
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(model('plan_subscription'), 'payment_id');
    }

    /**
     * Attribute for is auto billing
     */
    protected function isAutoBilling(): Attribute
    {
        return Attribute::make(
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
