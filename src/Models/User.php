<?php

namespace Jiannius\Atom\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
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
        'blocked_at' => 'datetime',
        'last_active_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'status' => \Jiannius\Atom\Enums\UserStatus::class,
    ];

    // boot
    protected static function booted() : void
    {
        static::saving(function($user) {
            $user->fillTier();
            $user->fillStatus();
        });

        static::deleting(function($user) {
            $user->fillStatus();
        });

        static::created(function($user) {
            $user->resetSettings();
            $user->sendActivationMail();
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

    // get notifications for user
    public function notifications() : HasMany
    {
        return $this->hasMany(model('notification'), 'receiver_id');
    }

    // scope for search
    public function scopeSearch($query, $search) : void
    {
        $query->whereAny(['name', 'email'], 'like', "%$search%");
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
    public function ping($login = false, $interval = 5) : void
    {
        if ($login) $this->fill(['login_at' => now()]);

        if ($this->isRecentlyActive($interval.' minutes')) {
            $this->fill(['last_active_at' => now()])->saveQuietly();
        }
    }

    // get user home
    public function home() : string
    {
        return pick([
            route('onboarding') => $this->signup?->status?->is('NEW') && !session()->has('onboarding'),
            route('app.dashboard') => true,
        ]);
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

    // check user is not authenticated
    public function isNotAuth() : bool
    {
        return !$this->isAuth();
    }

    // check user is tier
    public function isTier(...$tiers) : bool
    {
        return collect($tiers)->contains($this->tier);
    }

    // check user is role
    public function isRole(...$slugs) : bool
    {
        if ($this->isTier('root')) return true;

        $roles = collect($slugs)->mapWithKeys(function($slug) {
            $substr = str()->slug(str_replace('*', '', $slug));
            $roleslug = optional($this->role)->slug;

            if ($slug === 'admin') return ['admin' => in_array($roleslug, ['admin', 'administrator'])];
            else if (str()->startsWith($slug, '*')) return [$slug => str()->endsWith($roleslug, $substr)];
            else if (str()->endsWith($slug, '*')) return [$slug => str()->startsWith($roleslug, $substr)];
            else return [$slug => $roleslug === $slug];
        });

        return $roles->some(fn($val) => $val);
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

    // send activation mail
    public function sendActivationMail() : void
    {
        if ($this->status->isNot('INACTIVE')) return;
        if (!$this->email) return;

        $mail = collect([
            'App\Mail\UserActivation',
            'Jiannius\Atom\Mail\UserActivation',
        ])->first(fn($ns) => file_exists(atom_ns_path($ns)));

        Mail::to($this->email)->send(new $mail($this));
    }

    // fill tier
    public function fillTier() : mixed
    {
        return $this->fill([
            'tier' => $this->tier ?? 'normal',
        ]);
    }

    // fill status
    public function fillStatus() : mixed
    {
        return $this->fill([
            'status' => enum('user-status', pick([
                'TRASHED' => $this->trashed(),
                'BLOCKED' => $this->isBlocked(),
                'ACTIVE' => !empty($this->password),
                'INACTIVE' => empty($this->password),
            ]))->value,
        ]);
    }
}
