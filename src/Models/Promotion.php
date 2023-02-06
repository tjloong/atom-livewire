<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasTrace;
    use HasFilters;
    
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
    public function product()
    {
        return $this->belongsTo(get_class(model('product')));
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('code', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
        );
    }

    /**
     * Scope for status
     */
    public function scopeStatus($query, $statuses)
    {
        return $query->where(function($q) use ($statuses) {
            foreach ((array)$statuses as $status) {
                if ($status === 'ended') $q->orWhereDate('end_at', '<=', today());
                if ($status === 'inactive') $q->orWhere(fn($q) => $q->where('is_active', false)->orWhereNull('is_active'));
                if ($status === 'active') {
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

    /**
     * Get status attribute
     */
    public function getStatusAttribute()
    {
        if ($this->end_at && $this->end_at->lte(today())) return 'ended';
        if (!$this->is_active) return 'inactive';

        return 'active';
    }

    /**
     * Get rate display attribute
     */
    public function getRateDisplayAttribute()
    {
        if ($this->type === 'fixed') return currency($this->rate, tenant_settings('currency'));
        if ($this->type === 'percentage') return $this->rate.'%';
    }
}
