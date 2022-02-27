<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'roles_permissions';
    protected $guarded = [];

    public $timestamps = false;

    /**
     * Scope for granted abiltiy
     * 
     * @param Builder $query
     * @param string $ability
     */
    public function scopeGranted($query, $ability)
    {
        return $query->where('permission', $ability)->where('is_granted', true);
    }
}
