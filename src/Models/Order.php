<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jiannius\Atom\Traits\Models\HasRunningNumber;

class Order extends Model
{
    use HasFilters;
    use HasTrace;
    use HasRunningNumber;
    
    protected $guarded = [];

    protected $casts = [
        'subtotal' => 'float',
        'discount_amount' => 'float',
        'shipment_amount' => 'float',
        'grand_total' => 'float',
        'data' => 'object',
        'coupon_id' => 'integer',
        'contact_id' => 'integer',
    ];

    /**
     * Get coupon for order
     */
    public function coupon(): mixed
    {
        if (!enabled_module('coupons')) return null;

        return $this->belongsTo(model('coupon'));
    }

    /**
     * Get contact for order
     */
    public function contact(): mixed
    {
        if (!enabled_module('contacts')) return null;

        return $this->belongsTo(model('contact'));
    }

    /**
     * Get items for order
     */
    public function items(): HasMany
    {
        return $this->hasMany(model('order_item'));
    }

    /**
     * Get shipments for order
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(model('order_shipment'));
    }

    /**
     * Get payments for order
     */
    public function payments(): mixed
    {
        if (!enabled_module('payments')) return null;

        return $this->hasMany(model('payments'));
    }

    /**
     * Attribute for status
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!empty($this->closed_at)) return 'closed';
                else if ($this->shipments->count()) return 'shipped';
                else if (enabled_module('payments') && $this->payments->where('status', 'success')->count()) return 'paid';
                else return 'pending';
            },
        );
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->when(enabled_module('contacts'), fn($q) => $q->orWhereHas('contact', fn($q) => $q->search($search)))
        );
    }

    /**
     * Scope for status
     */
    public function scopeStatus($query, $status): void
    {
        $query->where(function($q) use ($status) {
            foreach ((array)$status as $val) {
                if ($val === 'closed') $q->orWhereNotNull('closed_at');
                else {
                    $q->orWhere(fn($q) => $q->whereNull('closed_at')->where(function($q) use ($val) {
                        if ($val === 'shipped') {
                            $q->has('shipments')->when(enabled_module('payments'), fn($q) => $q
                                ->whereDoesntHave('payments', fn($q) => $q->where('status', 'success'))
                            );
                        }
                        if ($val === 'paid' && enabled_module('payments')) {
                            $q->whereHas('payments', fn($q) => $q->where('status', 'success'));
                        }
                        if ($val === 'pending') {
                            $q->doesntHave('shipments')->when(enabled_module('payments'), fn($q) => $q
                                ->whereDoesntHave('payments', fn($q) => $q->where('status', 'success'))
                            );
                        }
                    }));
                }
            }
        });
    }
}
