<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Jiannius\Atom\Traits\Models\HasSlug;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jiannius\Atom\Traits\Models\Footprint;

class Role extends Model
{
    use Footprint;
    use HasFilters;
    use HasSlug;
    
    protected $guarded = [];

    // get users for role
    public function users(): HasMany
    {
        return $this->hasMany(model('user'));
    }

    // attribute for is admin
    protected function isAdmin(): Attribute
    {
        return Attribute::make(
            get: fn() => in_array($this->slug, ['admin', 'administrator']),
        );
    }

    // scope for fussy search
    public function scopeSearch($query, $search): void
    {
        $query->where('name', 'like', "%$search%");
    }

    // scope for is admin
    public function scopeIsAdmin($query): void
    {
        $query->whereIn('slug', ['admin', 'administrator']);
    }
}
