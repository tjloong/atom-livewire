<?php

namespace Jiannius\Atom\Models;

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
     * Get permissions for role
     */
    public function permissions()
    {
        if (!enabled_module('permissions')) return;

        return $this->hasMany(model('permission'));
    }

    /**
     * Get users for role
     */
    public function users()
    {
        return $this->hasMany(model('user'));
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%$search%");
    }

    /**
     * Scope for is admin
     */
    public function scopeIsAdmin($query)
    {
        return $query->whereIn('slug', ['admin', 'administrator']);
    }

    /**
     * Get is admin attribute
     */
    public function getIsAdminAttribute()
    {
        return in_array($this->slug, ['admin', 'administrator']);
    }

    /**
     * Check role is granted a permission
     */
    public function can($permission)
    {
        if (!enabled_module('permissions')) return true;

        return $this->is_admin || $this->permissions()->granted($permission)->count() > 0;
    }
}
