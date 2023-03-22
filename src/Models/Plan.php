<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\Models\HasSlug;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasSlug;
    use HasTrace;
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'trial' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get prices for plan
     */
    public function prices(): HasMany
    {
        return $this->hasMany(model('plan_price'));
    }

    /**
     * Get upgradables for plan
     */
    public function upgradables(): BelongsToMany
    {
        return $this->belongsToMany(model('plan'), 'plan_upgradables', 'plan_id', 'upgradable_id');
    }

    /**
     * Get downgradables for plan
     */
    public function downgradables(): BelongsToMany
    {
        return $this->belongsToMany(model('plan'), 'plan_downgradables', 'plan_id', 'downgradable_id');
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where('name', 'like', "%$search%");
    }

    /**
     * Get features list attributes
     */
    public function getFeaturesListAttribute(): array
    {
        return collect(explode("\n", $this->features))->filter()->map(fn($val) => trim($val))->values()->all();
    }

    /**
     * Route guard
     */
    public function routeGuard(): array
    {
        return [];
    }
}
