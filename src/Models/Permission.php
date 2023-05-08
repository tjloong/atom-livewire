<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jiannius\Atom\Traits\Models\HasFilters;

class Permission extends Model
{
    use HasFilters;
    
    protected $guarded = [];

    public $timestamps = false;

    public $permissions = [];

    /**
     * Booted
     */
    protected static function booted(): void
    {
        static::saved(function($permission) {
            session()->forget('can.permissions');
        });
    }

    /**
     * Get user for permission
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(model('user'));
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
