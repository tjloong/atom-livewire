<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasSlug;

    protected $guarded = [];

    protected $casts = [
        'seo' => 'object',
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
            ->orWhere('title', 'like', "%$search%")
            ->orWhere('slug', 'like', "%$search%")
        );
    }
}
