<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\HasFilters;

class Label extends Model
{
    use HasSlug;
    use HasFilters;

    protected $guarded = [];

    protected $casts = [
        'seq' => 'integer',
        'data' => 'object',
    ];

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
            ->orWhere('slug', 'like', "%$search%")
        );
    }
}
