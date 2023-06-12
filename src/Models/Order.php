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
        'customer' => 'array',
        'shipping' => 'array',
        'billing' => 'array',
        'subtotal' => 'float',
        'discount_amount' => 'float',
        'shipping_amount' => 'float',
        'grand_total' => 'float',
        'data' => 'array',
        'coupon_id' => 'integer',
        'user_id' => 'integer',
        'shipping_rate_id' => 'integer',
        'closed_at' => 'datetime',
        'shipped_at' => 'datetime',
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
        return $this->hasMany(model('line_item'));
    }

    /**
     * Get payments for order
     */
    public function payments(): HasMany
    {
        return $this->hasMany(model('payment'));
    }

    /**
     * Get coupon for order
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(model('coupon'));
    }

    /**
     * Get shipping for order
     */
    public function shipping_rate(): BelongsTo
    {
        return $this->belongsTo(model('shipping_rate'), 'shipping_rate_id');
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
                            $q->whereNotNull('shipped_at')
                                ->whereDoesntHave('payments', fn($q) => $q->where('status', 'success'));
                        }
                        if ($val === 'paid') {
                            $q->whereHas('payments', fn($q) => $q->where('status', 'success'));
                        }
                        if ($val === 'pending') {
                            $q->whereNull('shipped_at')
                                ->whereDoesntHave('payments', fn($q) => $q->where('status', 'success'));
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
        $tax = $items->sum('tax_amount');
        $shipping = optional(model('shipping_rate')->find($this->shipping_rate_id))->price;
        $discount = $this->discount_amount;

        if ($coupon = model('coupon')->find($this->coupon_id)) {
            $discount = $coupon->is_for_product
                ? $items->sum('discount_amount')
                : $coupon->calculate($subtotal);
        }

        $grand = $subtotal + $shipping + $tax - $discount;

        return $this->fill([
            'currency' => $this->currency ?? tenant('settings.currency') ?? settings('currency'),
            'subtotal' => $subtotal,
            'shipping_amount' => $shipping,
            'tax_amount' => $tax,
            'discount_amount' => $discount,
            'grand_total' => $grand > 0 ? $grand : 0,
            'status' => collect([
                'closed' => !empty($this->closed_at),
                'shipped' => !empty($this->shipped_at),
                'paid' => $this->payments->where('status', 'success')->count() > 0,
                'failed' => $this->payments->count() > 0 
                    && $this->payments->where('status', 'success')->count() <= 0,
            ])->filter()->keys()->first() ?? 'pending',
        ]);
    }
}
