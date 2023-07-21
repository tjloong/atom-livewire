<?php

namespace Jiannius\Atom\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Password;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Jiannius\Atom\Notifications\Auth\ActivateNotification;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\HasTrace;
use Laravel\Sanctum\HasApiTokens;

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
        'data' => 'array',
        'is_root' => 'boolean',
        'signup_at' => 'datetime',
        'onboarded_at' => 'datetime',
        'login_at' => 'datetime',
        'last_active_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['settings'];

    // booted
    protected static function booted(): void
    {
        static::saving(function($user) {
            $user->status = $user->generateStatus();
        });

        static::created(function($user) {
            model('user_setting')->initialize($user->id);
            $user->sendActivation();
        });

        static::updated(function($user) {
            if ($user->isDirty('email') && config('atom.auth.verify')) {
                $user->fill(['email_verified_at' => null])->saveQuietly();
                $user->sendEmailVerificationNotification();
            }
        });
    }

    // get role for user
    public function role(): mixed
    {
        if (!has_table('roles')) return null;

        return $this->belongsTo(model('role'));
    }

    // get permissions for user
    public function permissions(): mixed
    {
        if (!has_table('permissions')) return null;

        return $this->hasMany(model('permission'));
    }
    
    // get teams for user
    public function teams(): mixed
    {
        if (!has_table('teams')) return null;

        return $this->belongsToMany(model('team'), 'team_users');
    }

    // get subscriptions for user
    public function subscriptions(): mixed
    {
        if (!has_table('plans')) return null;

        return $this->hasMany(model('plan_subscription'));
    }

    // get tenants for user
    public function tenants(): mixed
    {
        if (!has_table('tenants')) return null;

        return $this->belongsToMany(model('tenant'), 'user_tenants')->withPivot([
            'visibility',
            'is_owner',
            'is_preferred',
        ]);
    }

    // attribute for settings
    protected function settings(): Attribute
    {
        return Attribute::make(
            get: function () {
                return model('user_setting')
                    ->where('user_id', $this->id)
                    ->get()
                    ->mapWithKeys(fn($setting) => [$setting->key => $setting->value])
                    ->toArray();
            },
        );
    }

    // attribute for visibility
    protected function visibility(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!has_table('tenants')) return null;
                if (!tenant()) return null;

                return tenant()->users->firstWhere('id', $this->id)->pivot->visibility;
            },
        );
    }

    // scope for fussy search
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
            ->orWhere('data->signup->channel', $search)
        );
    }

    // scope for is role
    public function scopeIsRole($query, $name): void
    {
        $query->when(
            has_table('roles'), 
            fn($q) => $q->whereHas('role', fn($q) => $q->where('roles.slug', $name)) 
        );
    }

    // scope for in team
    public function scopeInTeam($query, $id): void
    {
        $query->when(
            has_table('teams'),
            fn($q) => $q->whereHas('teams', fn($q) => $q->whereIn('teams.id', (array)$id))
        );
    }

    // scope for status
    public function scopeStatus($query, $status): void
    {
        $query->withTrashed()->whereIn('status', (array)$status);
    }

    // scope for tier
    public function scopeTier($query, $tier): void
    {
        $query->where(function($q) use ($tier) {
            foreach ((array)$tier as $val) {
                if ($val === 'root') $q->orWhere('is_root', true);
                elseif ($val === 'tenant' && has_table('tenants')) $q->orWhere(fn($q) => $q->has('tenants')->whereRaw('(is_root = false or is_root is null)'));
                elseif ($val === 'system') $q->orWhere(fn($q) => $q->whereNull('signup_at')->whereRaw('(is_root = false or is_root is null)'));
                elseif ($val === 'signup') $q->orWhere(fn($q) => $q->whereNotNull('signup_at')->whereRaw('(is_root = false or is_root is null)'));
            }
        });
    }

    // get user home
    public function home(): string
    {
        return route('app.dashboard');
    }

    // check user tier
    public function isTier($tier): bool
    {
        $valid = collect([
            'root' => $this->is_root === true,
            'tenant' => has_table('tenants') && $this->tenants->count() > 0,
            'system' => !$this->is_root && empty($this->signup_at),
            'signup' => !$this->is_root && !empty($this->signup_at),
        ])->filter(fn($val, $key) => in_array($key, (array)$tier))->search(true);

        return !empty($valid);
    }

    // check user is role
    public function isRole($names): bool
    {
        if (!has_table('roles')) return true;
        if (!$this->role) return false;

        return collect((array)$names)->filter(function($name) {
            $substr = str()->slug(str_replace('*', '', $name));

            if ($name === 'admin') return in_array($this->role->slug, ['admin', 'administrator']);
            else if (str()->startsWith($name, '*')) return str()->endsWith($this->role->slug, $substr);
            else if (str()->endsWith($name, '*')) return str()->startsWith($this->role->slug, $substr);
            else return $this->role->slug === $name;
        })->count() > 0;
    }

    // check user is tenant owner
    public function isTenantOwner($tenant = null)
    {
        if (!has_table('tenants')) return false;
        
        if ($tenant = $tenant ?? tenant()) return !empty($tenant->owners->where('id', $this->id)->first());

        return false;
    }

    // invite user to activate account
    public function sendActivation(): void
    {
        if ($this->status === 'inactive') {
            $this->notify(new ActivateNotification());
        }
    }

    // send password reset link
    public function sendPasswordResetLink(): mixed
    {
        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) return $status;
        else return false;
    }

    // generate status
    public function generateStatus(): string
    {
        if ($this->trashed()) $status = enum('user.status', 'TRASHED');
        else if ($this->blocked()) $status = enum('user.status', 'BLOCKED');
        else if ($this->password) $status = enum('user.status', 'ACTIVE');
        else $status = enum('user.status', 'INACTIVE');

        return $status->value;
    }
}
