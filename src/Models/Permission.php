<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $guarded = [];

    public $timestamps = false;
    public $actions = [];

    /**
     * Get role for permission
     */
    public function role()
    {
        return $this->belongsTo(model('role'));
    }

    /**
     * Get user for permission
     */
    public function user()
    {
        return $this->belongsTo(model('user'));
    }

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

    /**
     * Get actions
     */
    public function getActions()
    {
        return $this->actions;
    }
}
