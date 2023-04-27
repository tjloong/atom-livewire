<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Jiannius\Atom\Traits\Models\HasSlug;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasSlug;
    use HasTrace;
    use HasFilters;
    
    protected $guarded = [];

    /**
     * Get users for role
     */
    public function users()
    {
        return $this->hasMany(model('user'));
    }

    /**
     * Attribute for is admin
     */
    protected function isAdmin(): Attribute
    {
        return new Attribute(
            get: fn() => in_array($this->slug, ['admin', 'administrator']),
        );
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where('name', 'like', "%$search%");
    }

    /**
     * Scope for is admin
     */
    public function scopeIsAdmin($query): void
    {
        $query->whereIn('slug', ['admin', 'administrator']);
    }
}
