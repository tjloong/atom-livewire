<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permission extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public $permissions = [];

    /**
     * Get user for permission
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(model('user'));
    }

    /**
     * Get tenant for permission
     */
    public function tenant(): mixed
    {
        if (!enabled_module('tenants')) return null;

        return $this->belongsTo(model('tenant'));
    }

    /**
     * Scope for granted abiltiy
     */
    public function scopeGranted($query, $permission = null): void
    {
        $query
            ->when($permission, fn($q) => $q->where('permission', $permission))
            ->where('is_granted', true);
    }

    /**
     * Scope for forbidden ability
     */
    public function scopeForbidden($query, $permission = null): void
    {
        $query
            ->when($permission, fn($q) => $q->where('permission', $permission))
            ->where('is_granted', false);
    }

    /**
     * Get permission list
     */
    public function getPermissionList(): array
    {
        return $this->permissions;
    }
}
