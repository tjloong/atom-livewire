<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promotion extends Model
{
    use HasFilters;
    use HasTrace;
    
    protected $guarded = [];

    protected $casts = [
        'rate' => 'float',
        'data' => 'object',
        'is_active' => 'boolean',
        'end_at' => 'datetime',
        'product_id' => 'integer',
    ];

    /**
     * Get product for promotion
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(get_class(model('product')));
    }

    /**
     * Attribute for status
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->end_at && $this->end_at->lte(today())) return 'ended';
                if (!$this->is_active) return 'inactive';
        
                return 'active';
            },
        );
    }

    /**
     * Attribute for rate display
     */
    protected function rateDisplay(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->type === 'fixed') return currency($this->rate, tenant('settings.default_currency') ?? settings('default_currency'));
                if ($this->type === 'percentage') return $this->rate.'%';
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
            ->orWhere('code', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
        );
    }

    /**
     * Scope for status
     */
    public function scopeStatus($query, $status): void
    {
        $query->where(function($q) use ($status) {
            foreach ((array)$status as $val) {
                if ($val === 'ended') $q->orWhereDate('end_at', '<=', today());
                if ($val === 'inactive') $q->orWhere(fn($q) => $q->where('is_active', false)->orWhereNull('is_active'));
                if ($val === 'active') {
                    $q->orWhere(fn($q) => $q
                        ->where('is_active', true)
                        ->where(fn($q) => $q
                            ->whereNull('end_at')
                            ->orWhereDate('end_at', '>', today())
                        )
                    );
                }
            }
        });
    }
}
