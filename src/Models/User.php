<?php

namespace Jiannius\Atom\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Jiannius\Atom\Traits\HasOwner;
use Jiannius\Atom\Traits\HasFilters;
use Jiannius\Atom\Notifications\ActivateAccountNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasOwner;
    use HasFilters;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $guarded = ['password'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'root' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    const ROOT_EMAIL = 'root@jiannius.com';

    /**
     * Model boot method
     * 
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            $user->status = $user->status ?? 'pending';
        });
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
        return $query->where(fn($q) => 
            $q->where('name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")        
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
