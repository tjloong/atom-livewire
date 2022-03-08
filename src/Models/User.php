<?php

namespace Jiannius\Atom\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Password;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Jiannius\Atom\Traits\HasTrace;
use Jiannius\Atom\Traits\HasFilters;
use Jiannius\Atom\Notifications\ActivateAccountNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use SoftDeletes;
    use HasFactory;
    use HasApiTokens;
    use HasTrace;
    use HasFilters;

    protected $guarded = ['password'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_root' => 'boolean',
        'is_pending' => 'boolean',
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    const ROOT_EMAIL = 'root@jiannius.com';

    /**
     * Get signup for user
     */
    public function signup()
    {
        if (!enabled_module('signups')) return;

        return $this->hasOne(Signup::class);
    }

    /**
     * Get role for user
     */
    public function role()
    {
        if (!enabled_module('roles')) return;

        return $this->belongsTo(Role::class);
    }

    /**
     * Get permissions for user
     */
    public function permissions()
    {
        if (!enabled_module('permissions')) return;

        return $this->hasMany(UserPermission::class);
    }
    
    /**
     * Get teams for user
     */
    public function teams()
    {
        if (!enabled_module('teams')) return;

        return $this->belongsToMany(Team::class, 'teams_users');
    }

    /**
     * Get plan price for user
     */
    public function plan_price()
    {
        if (!enabled_module('plans')) return;

        return $this->belongsTo(PlanPrice::class, 'plan_price_id');
    }

    /**
     * Get plan for user
     */
    public function plan()
    {
        if (!enabled_module('plans')) return;

        return $this->hasOneThrough(Plan::class, PlanPrice::class);
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
        return $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
            ->when(enabled_module('signups'), fn($q) => $q->whereHas('signup', fn($q) => $q->search($search)))
            ->when(enabled_module('tenants'), fn($q) => $q->whereHas('tenant', fn($q) => $q->where('tenants.name', 'like', "%$search%")))
        );
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
        if (!enabled_module('roles')) abort_module('roles');

        return $query->whereHas('role', fn($q) => $q->where('slug', $name));
    }

    /**
     * Scope for team id
     * 
     * @param Builder $query
     * @param integer $id
     * @return Builder
     */
    public function scopeTeamId($query, $id)
    {
        if (!enabled_module('teams')) abort_module('teams');

        return $query->whereHas('teams', fn($q) => $q->whereIn('teams.id', (array)$id));
    }

    /**
     * Get status attribute
     * 
     * @return string
     */
    public function getStatusAttribute()
    {
        if (!$this->is_active) return 'inactive';
        if ($this->is_pending) return 'pending';

        return 'active';
    }

    /**
     * Check user is role
     * 
     * @param mixed $names
     * @return boolean
     */
    public function isRole($names)
    {
        if (!enabled_module('roles')) abort_module('roles');
        if (!$this->role) return false;

        return collect((array)$names)->filter(function($name) {
            $substr = str()->slug(str_replace('*', '', $name));

            if (str()->startsWith($name, '*')) return str()->endsWith($this->role->slug, $substr);
            else if (str()->endsWith($name, '*')) return str()->startsWith($this->role->slug, $substr);
            else return $this->role->slug === $name;
        })->count() > 0;
    }

    /**
     * Check user can access app portal
     * 
     * @return boolean
     */
    public function canAccessApp()
    {
        if ($this->is_root) return true;

        if (enabled_module('signups')) return empty($this->signup);
        if (enabled_module('tenants')) return !empty($this->tenant);

        return false;
    }

    /**
     * Invite user to activate account
     * 
     * @return void
     */
    public function sendAccountActivation()
    {
        if ($this->status === 'pending') {
            $this->notify(new ActivateAccountNotification());
        }
    }

    /**
     * Send password reset link
     * 
     * @return void
     */
    public function sendPasswordResetLink()
    {
        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) return $status;
        else return false;
    }
}
