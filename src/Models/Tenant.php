<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\Models\HasFilters;

class Tenant extends Model
{
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'avatar_id' => 'integer',
    ];

    /**
     * Model booted
     */
    protected static function booted()
    {
        static::saved(function($tenant) {
            if (($sess = session('tenant')) && $sess->id === $tenant->id) {
                session()->forget('tenant');
            }
        });
    }

    /**
     * Get avatar for tenant
     */
    public function avatar()
    {
        return $this->belongsTo(model('file'), 'avatar_id');
    }

    /**
     * Get users for tenant
     */
    public function users()
    {
        return $this->belongsToMany(model('user'), 'tenant_users');
    }

    /**
     * Get settings for tenant
     */
    public function settings()
    {
        return $this->hasMany(model('tenant_setting'));
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->whereHas('users', fn($q) => $q->search($search))
        );
    }

    /**
     * Get owner attribute
     */
    public function getOwnerAttribute()
    {
        return $this->users()->wherePivot('is_owner', true)->first();
    }
}
