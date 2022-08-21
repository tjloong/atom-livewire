<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    /**
     * Scope for granted abiltiy
     */
    public function scopeGranted($query, $permission = null)
    {
        return $query
            ->when($permission, fn($q) => $q->where('permission', $permission))
            ->where('is_granted', true);
    }

    /**
     * Scope for forbidden ability
     */
    public function scopeForbidden($query, $permission = null)
    {
        return $query
            ->when($permission, fn($q) => $q->where('permission', $permission))
            ->where('is_granted', false);
    }
}
