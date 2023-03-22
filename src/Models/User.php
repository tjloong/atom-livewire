<?php

namespace Jiannius\Atom\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Notifications\UserActivationNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasFilters;
    use HasTrace;
    use Notifiable;
    use SoftDeletes;

    protected $guarded = ['password'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'data' => 'object',
        'is_root' => 'boolean',
        'signup_at' => 'datetime',
        'onboarded_at' => 'datetime',
        'activated_at' => 'datetime',
        'login_at' => 'datetime',
        'last_active_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Model boot
     */
    protected static function booted(): void
    {
        static::saving(function($user) {
            $user->status = $user->generateStatus();
        });

        static::created(function($user) {
            $user->sendActivation();
        });

        static::updated(function($user) {
            if ($user->isDirty('email') && config('atom.auth.verify')) {
                $user->fill(['email_verified_at' => null])->saveQuietly();
                $user->sendEmailVerificationNotification();
            }
        });
    }

    /**
     * Get role for user
     */
    public function role()
    {
        if (!enabled_module('roles')) return;

        return $this->belongsTo(model('role'));
    }

    /**
     * Get permissions for user
     */
    public function permissions()
    {
        if (!enabled_module('permissions')) return;

        return $this->hasMany(model('permission'));
    }
    
    /**
     * Get teams for user
     */
    public function teams()
    {
        if (!enabled_module('teams')) return;

        return $this->belongsToMany(model('team'), 'team_users');
    }

    /**
     * Get subscriptions for user
     */
    public function subscriptions()
    {
        if (!enabled_module('plans')) return;

        return $this->hasMany(model('plan_subscription'));
    }

    /**
     * Attribute for tier
     */
    public function tier(): Attribute
    {
        return new Attribute(
            get: fn() => $this->is_root ? 'root' : 'signup',
        );
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
            ->orWhere('data->signup->channel', $search)
        );
    }

    /**
     * Scope for is role
     */
    public function scopeIsRole($query, $name): void
    {
        $query->when(
            enabled_module('roles'), 
            fn($q) => $q->whereHas('role', fn($q) => $q->where('slug', $name)) 
        );
    }

    /**
     * Scope for in team
     */
    public function scopeInTeam($query, $id): void
    {
        $query->when(
            enabled_module('teams'),
            fn($q) => $q->whereHas('teams', fn($q) => $q->whereIn('teams.id', (array)$id))
        );
    }

    /**
     * Scope for status
     */
    public function scopeStatus($query, $status): void
    {
        $query->withTrashed()->whereIn('status', (array)$status);
    }

    /**
     * Scope for tier
     */
    public function scopeTier($query, $tier = null): void
    {
        $query->where(function($q) use ($tier) {
            foreach ((array)$tier as $val) {
                if ($val === 'root') {
                    $q->orWhere('is_root', true);
                }
                else if ($val === 'signup') {
                    $q->orWhere('is_root', false);
                }
            }
        });
    }

    /**
     * Get user home
     */
    public function home(): string
    {
        if ($this->isTier('signup')) return '/';
        else return route('app.home');
    }

    /**
     * Check user tier
     */
    public function isTier($tier): bool
    {
        return in_array($this->tier, (array)$tier);
    }

    /**
     * Check user is role
     */
    public function isRole($names): bool
    {
        if (!enabled_module('roles')) return true;
        if (!$this->role) return false;

        return collect((array)$names)->filter(function($name) {
            $substr = str()->slug(str_replace('*', '', $name));

            if ($name === 'admin') return in_array($this->role->slug, ['admin', 'administrator']);
            else if (str()->startsWith($name, '*')) return str()->endsWith($this->role->slug, $substr);
            else if (str()->endsWith($name, '*')) return str()->startsWith($this->role->slug, $substr);
            else return $this->role->slug === $name;
        })->count() > 0;
    }

    /**
     * Check user has plan
     */
    public function hasPlan($plan): bool
    {
        if (!enabled_module('plans')) return false;

        $id = is_numeric($plan) ? $plan : (
            is_string($plan) 
                ? optional(model('plan')->where('slug', $plan)->first())->id
                : optional($plan)->id
        );

        return $this->subscriptions()
            ->status('active')
            ->whereHas('price', fn($q) => $q->where('plan_id', $id))
            ->count() > 0;
    }

    /**
     * Check user has plan price
     */
    public function hasPlanPrice($price): bool
    {
        if (!enabled_module('plans')) return false;

        $id = is_numeric($price) ? $price : optional($price)->id;

        return $this->subscriptions()
            ->status('active')
            ->where('plan_price_id', $id)
            ->count() > 0;
    }

    /**
     * Invite user to activate account
     */
    public function sendActivation(): void
    {
        if ($this->status === 'inactive') {
            $this->notify(new UserActivationNotification());
        }
    }

    /**
     * Send password reset link
     */
    public function sendPasswordResetLink(): mixed
    {
        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) return $status;
        else return false;
    }

    /**
     * Generate status
     */
    public function generateStatus(): string
    {
        if ($this->trashed()) return 'trashed';
        if ($this->blocked()) return 'blocked';

        if ($this->isTier('signup')) {
            if ($this->onboarded_at) return 'onboarded';
            if ($this->signup_at) return 'new';
        }
        else if ($this->activated_at) return 'active';
        
        return 'inactive';
    }

    /**
     * Check user can access portal
     */
    public function canAccessPortal($portal): bool
    {
        if ($portal === 'app') {
            return current_route([
                'app.settings', 
                'app.ticketing.*', 
                'app.plan.*',
                'app.onboarding.*',
            ]) || $this->isTier('root');
        }

        return true;
    }
}
