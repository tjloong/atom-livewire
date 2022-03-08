<?php

namespace Jiannius\Atom\Models;

use App\Models\User;
use Jiannius\Atom\Traits\HasSlug;
use Jiannius\Atom\Traits\HasTrace;
use Jiannius\Atom\Traits\HasFilters;
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

        return $this->hasMany(RolePermission::class);
    }

    /**
     * Get users for role
     */
    public function users()
    {
        return $this->hasMany(User::class);
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

    /**
     * Check role is granted a permission
     * 
     * @param string $permission
     * @return boolean
     */
    public function can($permission)
    {
        if (!enabled_module('permissions')) return true;
        
        return $this->permissions()
            ->where('permission', $permission)
            ->where('is_granted', true)
            ->count() > 0;
    }
}
