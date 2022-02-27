<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $table = 'users_permissions';
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

    /**
     * Scope for forbidden ability
     * 
     * @param Builder $query
     * @param string $ability
     */
    public function scopeForbidden($query, $ability)
    {
        return $query->where('permission', $ability)->where('is_granted', false);
    }
}
