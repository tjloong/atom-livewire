<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'type',
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
