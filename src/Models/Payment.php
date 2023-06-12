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
    use HasRunningNumber;
    use HasTrace;
    use HasUlid;
    
    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
        'data' => 'object',
        'file_id' => 'integer',
        'order_id' => 'integer',
    ];

    /**
     * Booted
     */
    protected static function booted(): void
    {
        static::saving(function($payment) {
            $payment->setAttributes();
        });
    }

    /**
     * Get file for payment
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(model('file'));
    }

    /**
     * Get order for payment
     */
    public function order(): mixed
    {
        if (!$this->hasColumn('order_id')) return null;

        return $this->belongsTo(model('order'));
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

    /**
     * Set attributes
     */
    public function setAttributes()
    {
        return $this->fill([
            'status' => $this->status ?? 'draft',
        ]);
    }
}
