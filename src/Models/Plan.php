<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Jiannius\Atom\Traits\Models\HasSlug;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFilters;
    use HasSlug;
    use HasTrace;
    
    protected $guarded = [];

    protected $casts = [
        'trial' => 'integer',
        'is_unique_trial' => 'boolean',
        'is_active' => 'boolean',
    ];

    public $slugify = ['name' => 'code'];

    /**
     * Get prices for plan
     */
    public function prices(): HasMany
    {
        return $this->hasMany(model('plan_price'));
    }

    /**
     * Get upgrades for plan
     */
    public function upgrades(): BelongsToMany
    {
        return $this->belongsToMany(model('plan'), 'plan_upgrades', 'plan_id', 'upgrade_id');
    }

    /**
     * Attribute for features
     */
    protected function features(): Attribute
    {
        return Attribute::make(
            get: fn($value) => collect(explode("\n", $value))->filter()->map(fn($val) => trim($val))->values()->all(),
            set: fn($value) => is_string($value) ? $value : collect($value)->join("\n"),
        );
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('code', 'like', "%$search%")
        );
    }

    /**
     * Scope for country
     */
    public function scopeCountry($query, $country = null): void
    {
        $country = $country ?? geoip()->getLocation()->iso_code;

        $query->where('country', $country);
    }

    /**
     * Scope for subscribeable
     */
    public function scopeSubscribeable($query): void
    {
        $query->country()->status('active')->has('prices');
    }
}
