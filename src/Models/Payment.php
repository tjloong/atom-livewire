<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\Models\HasRunningNumber;

class Payment extends Model
{
    use HasFilters;
    use HasTrace;
    use HasRunningNumber;
    
    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
        'data' => 'object',
        'order_id' => 'integer',
    ];

    /**
     * Get order for payment
     */
    public function order(): mixed
    {
        if (!enabled_module('orders')) return null;

        return $this->belongsTo(model('order'));
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('number', $search)
            ->when(enabled_module('orders'), fn($q) => $q->orWhereHas('order', fn($q) => $q->search($search)))
        );
    }
}
