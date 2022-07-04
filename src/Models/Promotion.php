<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\HasTrace;
use Jiannius\Atom\Traits\HasFilters;
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
     * 
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('code', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
        );
    }
}
