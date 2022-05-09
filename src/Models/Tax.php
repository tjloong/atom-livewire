<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'rate' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('country', 'like', "%$search%")
            ->orWhere('region', 'like', "%$search%")
        );
    }
}
