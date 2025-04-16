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

    public function role(): BelongsTo
    {
        return $this->belongsTo(model('role'));
    }

    public function signup(): HasOne
    {
        return $this->hasOne(model('signup'));
    }

    public function notifications() : HasMany
    {
        return $this->hasMany(model('notification'), 'receiver_id');
    }

    public function scopeSearch($query, $search) : void
    {
        $query->whereAny(['name', 'email'], 'like', "%$search%");
    }

    public function scopeWithRole($query, $roles) : void
    {
        $id = collect($roles)->map(function($role) {
            if (is_numeric($role)) return $role;
            else if (is_string($role)) return optional(model('role')->findBySlug($role))->id;
            else return optional($role)->id;
        })->toArray();
        
        $query->whereIn('role_id', $id);
    }

    public function scopeLoginable($query) : void
    {
        $query->whereNotNull('password')->whereNull('blocked_at');
    }

    public function ping($login = false, $interval = 5) : void
    {
        if ($login) $this->fill(['login_at' => now()]);

        if ($this->isRecentlyActive($interval.' minutes')) {
            $this->fill(['last_active_at' => now()])->saveQuietly();
        }
    }

    public function home() : string
    {
        return pick([
            route('onboarding') => $this->signup?->status?->is('NEW') && !session()->has('onboarding'),
            route('app.dashboard') => true,
        ]);
    }

    public function isRecentlyActive($duration = '7 days') : bool
    {
        $split = explode(' ', $duration);
        $n = $split[0];
        $unit = $split[1];
        $method = str()->camel('diff in '.$unit);

        return optional($this->last_active_at)->$method(now()) >= $n;
    }

    public function isAuth() : bool
    {
        return $this->id === user('id');
    }

    public function isNotAuth() : bool
    {
        return !$this->isAuth();
    }

    public function isRoot() : bool
    {
        return $this->tier === 'root';
    }

    public function isTier(...$tiers) : bool
    {
        return collect($tiers)->contains($this->tier);
    }

    public function isBlocked() : bool
    {
        return $this->blocked_at && $this->blocked_at->lessThan(now());
    }

    public function block() : void
    {
        $this->fill([
            'blocked_at' => now(),
            'blocked_by' => user('id'),
        ])->save();
    }

    public function unblock() : void
    {
        $this->fill([
            'blocked_at' => null,
            'blocked_by' => null,
        ])->save();
    }

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

    public function fillTier() : mixed
    {
        return $this->fill([
            'tier' => $this->tier ?? 'normal',
        ]);
    }

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
