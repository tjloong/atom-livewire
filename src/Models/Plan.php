<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\HasSlug;
use Jiannius\Atom\Traits\HasOwner;
use Jiannius\Atom\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasSlug;
    use HasOwner;
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'features' => 'array',
        'data' => 'object',
        'is_active' => 'boolean',
    ];

    /**
     * Get price for plan
     */
    public function prices()
    {
        return $this->hasMany(PlanPrice::class);
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
        return $query->where('name', 'like', "%$search%");
    }
}
