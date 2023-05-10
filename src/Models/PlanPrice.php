<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanPrice extends Model
{
    use HasFilters;
    use HasTrace;
    
    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
        'valid' => 'object',
        'is_recurring' => 'boolean',
        'is_active' => 'boolean',
        'plan_id' => 'integer',
    ];

    protected $appends = ['valid_name'];

    /**
     * Booted
     */
    protected static function booted(): void
    {
        static::saving(function($price) {
            if (!data_get($price->valid, 'count') && !data_get($price->valid, 'interval')) {
                $price->valid = null;
            }
        });
    }

    /**
     * Get plan for price
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(model('plan'));
    }

    /**
     * Get subscriptions for price
     */
    public function subscriptions()
    {
        return $this->hasMany(model('plan_subscriptions'), 'price_id');
    }

    /**
     * Attribute for valid name
     */
    protected function validName(): Attribute
    {
        return Attribute::make(
            get: function() {
                if (!$this->valid) return null;

                $count = data_get($this->valid, 'count');
                $interval = data_get($this->valid, 'interval');

                if (!$count || !$interval) return null;

                if ($count === 1 && $interval === 'day') return 'Daily';
                if ($count === 1 && $interval === 'week') return 'Weekly';
                if ($count === 1 && $interval === 'month') return 'Monthly';
                if ($count === 3 && $interval === 'month') return 'Quarterly';
                if ($count === 6 && $interval === 'month') return 'Half-Yearly';
                if ($count === 1 && $interval === 'year') return 'Yearly';
                if ($count === 2 && $interval === 'year') return 'Bi-Yearly';

                return $count.' '.str($interval)->plural($count)->headline()->toString();
            },
        );
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('code', 'like', "%$search%")
            ->orWhereHas('plan', fn($q) => $q->search($search))
        );
    }
}
