<?php

namespace Jiannius\Atom\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Password;
use Jiannius\Atom\Notifications\Auth\ActivateNotification;
use Jiannius\Atom\Traits\Models\Footprint;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\Settings;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Footprint;
    use HasApiTokens;
    use HasFactory;
    use HasFilters;
    use Notifiable;
    use Settings;
    use SoftDeletes;

    protected $guarded = [
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'data' => 'array',
        'login_at' => 'datetime',
        'last_active_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    // boot
    protected static function bootUsers() : void
    {
        static::created(function($user) {
            $user->sendActivationNotification();
        });
        
        static::saved(function($user) {
            $user->setAttributes()->saveQuietly();
        });

        static::deleted(function($user) {
            if ($user->exists) $user->setAttributes()->saveQuietly();
        });
    }

    // get role for user
    public function role(): BelongsTo
    {
        return $this->belongsTo(model('role'));
    }

    // get signup for user
    public function signup(): HasOne
    {
        return $this->hasOne(model('signup'));
    }

    // get status attribute
    public function getStatusAttribute($value) : mixed
    {
        return enum('user.status', $value);
    }

    // get blocked by attribute
    public function getBlockedByAttribute($id) : mixed
    {
        return $id ? model('user')->find($id) : null;
    }

    // scope for search
    public function scopeSearch($query, $search) : void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
        );
    }

    // scope for with role
    public function scopeWithRole($query, $roles) : void
    {
        $id = collect($roles)->map(function($role) {
            if (is_numeric($role)) return $role;
            else if (is_string($role)) return optional(model('role')->findBySlug($role))->id;
            else return optional($role)->id;
        })->toArray();
        
        $query->whereIn('role_id', $id);
    }

    // ping
    public function ping($login = false) : void
    {
        if ($login) $this->fill(['login_at' => now()]);

        $this->fill(['last_active_at' => now()]);
        $this->saveQuietly();
    }

    // get user home
    public function home() : string
    {
        return collect([
            route('app.onboarding') => optional($this->signup)->status === enum('signup.status', 'NEW') && !session()->has('onboarding'),
            route('app.dashboard') => true,
        ])->filter()->keys()->first();
    }

    // check user is recently active
    public function isRecentlyActive($duration = '7 days') : bool
    {
        $split = explode(' ', $duration);
        $n = $split[0];
        $unit = $split[1];
        $method = str()->camel('diff in '.$unit);

        return optional($this->last_active_at)->$method(now()) >= $n;
    }

    // check user is authenticated
    public function isAuth() : bool
    {
        return $this->id === user('id');
    }

    // check user is tier
    public function isTier($tiers) : bool
    {
        return collect($tiers)->contains($this->tier);
    }

    // check user is role
    public function isRole($slugs, $strict = false) : bool
    {
        if (tier('root')) return true;
        
        $roles = collect($slugs)->mapWithKeys(function($slug) {
            $substr = str()->slug(str_replace('*', '', $slug));
            $roleslug = optional($this->role)->slug;

            if ($slug === 'admin') return ['admin' => in_array($roleslug, ['admin', 'administrator'])];
            else if (str()->startsWith($slug, '*')) return [$slug => str()->endsWith($roleslug, $substr)];
            else if (str()->endsWith($slug, '*')) return [$slug => str()->startsWith($roleslug, $substr)];
            else return [$slug => $roleslug === $slug];
        });

        if ($strict) return !$roles->some(fn($val) => !$val);
        else return $roles->some(fn($val) => $val);
    }

    // check user is blocked
    public function isBlocked() : bool
    {
        return $this->blocked_at && $this->blocked_at->lessThan(now());
    }

    // block user
    public function block() : void
    {
        $this->fill([
            'blocked_at' => now(),
            'blocked_by' => user('id'),
        ])->save();
    }

    // unblock user
    public function unblock() : void
    {
        $this->fill([
            'blocked_at' => null,
            'blocked_by' => null,
        ])->save();
    }

    // send password reset link
    public function sendPasswordResetLink() : mixed
    {
        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) return $status;
        else return false;
    }

    // send activation notification
    public function sendActivationNotification() : void
    {
        if (!$this->password && $this->email) {
            $this->notify(new ActivateNotification());
        }
    }

    // set attributes
    public function setAttributes() : mixed
    {
        $this->fill([
            'status' => enum('user.status', collect([
                'TRASHED' => $this->trashed(),
                'BLOCKED' => $this->isBlocked(),
                'ACTIVE' => !empty($this->password),
                'INACTIVE' => empty($this->password),
            ])->filter()->keys()->first())->value,
        ]);

        return $this;
    }
}
