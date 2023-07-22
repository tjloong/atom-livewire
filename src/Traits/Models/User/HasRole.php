<?php

namespace Jiannius\Atom\Traits\Models\User;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasRole
{
    // get role for user
    public function role(): BelongsTo
    {
        return $this->belongsTo(model('role'));
    }

    // scope for is role
    public function scopeIsRole($query, $roles): void
    {
        $id = collect($roles)->map(function($role) {
            if (is_numeric($role)) return $role;
            else if (is_string($role)) return optional(model('role')->findBySlug($role))->id;
            else return optional($role)->id;
        })->toArray();
        
        $query->whereIn('role_id', $id);
    }

    // check user is role
    public function isRole($slugs, $strict = false): bool
    {
        $roles = collect($slugs)->mapWithKeys(function($slug) {
            $substr = str()->slug(str_replace('*', '', $slug));

            if ($slug === 'admin') return ['admin' => in_array($this->role->slug, ['admin', 'administrator'])];
            else if (str()->startsWith($slug, '*')) return [$slug => str()->endsWith($this->role->slug, $substr)];
            else if (str()->endsWith($slug, '*')) return [$slug => str()->startsWith($this->role->slug, $substr)];
            else return [$slug => $this->role->slug === $slug];
        });

        if ($strict) return !$roles->some(fn($val) => !$val);
        else return $roles->some(fn($val) => $val);
    }    
}