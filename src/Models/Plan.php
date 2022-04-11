<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\HasSlug;
use Jiannius\Atom\Traits\HasTrace;
use Jiannius\Atom\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasSlug;
    use HasTrace;
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'trial' => 'integer',
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get price for plan
     */
    public function prices()
    {
        return $this->hasMany(get_class(model('plan_price')));
    }

    /**
     * Get upgradables for plan
     */
    public function upgradables()
    {
        return $this->belongsToMany(get_class(model('plan')), 'plan_upgradables', 'plan_id', 'upgradable_id');
    }

    /**
     * Get downgradables for plan
     */
    public function downgradables()
    {
        return $this->belongsToMany(get_class(model('plan')), 'plan_downgradables', 'plan_id', 'downgradable_id');
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
