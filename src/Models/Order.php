<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jiannius\Atom\Traits\Models\HasRunningNumber;
use Jiannius\Atom\Traits\Models\HasUlid;

class Order extends Model
{
    use HasFilters;
    use HasTrace;
    use HasRunningNumber;
    use HasUlid;
    
    protected $guarded = [];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'subtotal' => 'float',
        'discount_amount' => 'float',
        'shipping_amount' => 'float',
        'grand_total' => 'float',
        'data' => 'array',
        'coupon_id' => 'integer',
        'user_id' => 'integer',
        'shipping_rate_id' => 'integer',
    ];

    /**
     * Model booted
     */
    protected static function booted(): void
    {
        static::saving(function ($order) {
            $order->setAttributes();
        });
    }

    /**
     * Get coupon for order
     */
    public function coupon(): mixed
    {
        if (!has_table('coupons')) return null;

        return $this->belongsTo(model('coupon'));
    }

    /**
     * Get user for order
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(model('user'));
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
    public function payments()
    {
        return $this->hasMany(model('payment'));
    }

    /**
     * Get shipping rate for order
     */
    public function shippingRate(): mixed
    {
        if (!has_table('shipping_rates')) return null;

        return $this->belongsTo(model('shipping_rate'));
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->when(fn($q) => $q->orWhereHas('user', fn($q) => $q->search($search)))
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

    /**
     * Set attributes
     */
    public function setAttributes()
    {
        $items = $this->items()->get();
        $subtotal = $items->sum('subtotal');
        $shipping = optional(model('shipping_rate')->find($this->shipping_rate_id))->price;
        $tax = $items->sum('tax_amount');
        $coupon = model('coupon')->find($this->coupon_id);
        $discount = optional($coupon)->is_for_product
            ? $items->sum('discount_amount')
            : optional($coupon)->calculate($subtotal);

        $grand = $subtotal + $shipping + $tax - $discount;

        if (!empty($this->closed_at)) $status = 'closed';
        else if ($this->shipments->count()) $status = 'shipped';
        else if ($this->payments->count()) {
            if ($this->payments->where('status', 'success')->count()) $status = 'paid';
            else $status = 'failed';
        }
        else $status = 'pending';

        return $this->fill([
            'subtotal' => $subtotal,
            'shipping_amount' => $shipping,
            'tax_amount' => $tax,
            'discount_amount' => $discount,
            'grand_total' => $grand > 0 ? $grand : 0,
            'status' => $status,
        ]);
    }
}
