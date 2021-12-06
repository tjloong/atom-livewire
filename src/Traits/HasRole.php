<?php

namespace Jiannius\Atom\Traits;

use App\Models\Role;
use App\Models\Ability;
use Illuminate\Support\Str;

/**
 * This trait is designed to be used for User model
 */
trait HasRole
{
    public $enabledHasRoleTrait = true;

    /**
     * Initialize the trait
     * 
     * @return void
     */
    protected function initializeHasRole()
    {
        $this->fillable[] = 'role_id';
        $this->casts['role_id'] = 'integer';
    }

    /**
     * Get role for user
     */
    public function role()
    {
        return $this->belongsTo(Role::class)->withoutGlobalScopes();
    }

    /**
     * Get abilities for user
     */
    public function abilities()
    {
        return $this->belongsToMany(Ability::class, 'abilities_users')->withPivot('access');
    }

    /**
     * Scope for is role
     * 
     * @param Builder $query
     * @param string $name
     * @return Builder
     */
    public function scopeWhereIsRole($query, $name)
    {
        return $query->whereHas('role', fn($q) => $q->where('slug', $name));
    }

    /**
     * Check user is role
     * 
     * @param mixed $names
     * @return boolean
     */
    public function isRole($names)
    {
        if (!$this->role) return false;

        return collect((array)$names)->filter(function($name) {
            $substr = Str::slug(str_replace('*', '', $name));

            if ($name === 'root') return $this->role->scope === 'root';
            else if ($name === 'admin') return $this->role->slug === 'administrator' && $this->role->is_system;
            else if (Str::startsWith($name, '*')) return Str::endsWith($this->role->slug, $substr);
            else if (Str::endsWith($name, '*')) return Str::startsWith($this->role->slug, $substr);
            else return $this->role->slug === $name;
        })->count() > 0;
    }
}