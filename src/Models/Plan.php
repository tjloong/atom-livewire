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
     * Get upgradables for plan
     */
    public function upgradables()
    {
        return $this->belongsToMany(Plan::class, 'plans_upgradables', 'plan_id', 'upgradable_id');
    }

    /**
     * Get downgradables for plan
     */
    public function downgradables()
    {
        return $this->belongsToMany(Plan::class, 'plans_downgradables', 'plan_id', 'downgradable_id');
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
