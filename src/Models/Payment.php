<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jiannius\Atom\Traits\Models\HasRunningNumber;
use Jiannius\Atom\Traits\Models\HasUlid;

class Payment extends Model
{
    use HasFilters;
    use HasTrace;
    use HasRunningNumber;
    use HasUlid;
    
    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
        'data' => 'object',
        'order_id' => 'integer',
        'file_id' => 'integer',
    ];

    /**
     * Booted
     */
    protected static function booted(): void
    {
        static::saving(function($payment) {
            $payment->status = $payment->status ?? 'draft';
        });
    }

    /**
     * Get order for payment
     */
    public function order(): mixed
    {
        if (!has_table('orders')) return null;

        return $this->belongsTo(model('order'));
    }

    /**
     * Get file for payment
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(model('file'));
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('number', $search)
            ->when(has_table('orders'), fn($q) => $q->orWhereHas('order', fn($q) => $q->search($search)))
        );
    }
}
