<?php

namespace Jiannius\Atom\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Password;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\User\HasSettings;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasFilters;
    use HasSettings;
    use HasTrace;
    use Notifiable;
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
        'signup_at' => 'datetime',
        'onboarded_at' => 'datetime',
        'login_at' => 'datetime',
        'last_active_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    // booted
    protected static function booted(): void
    {
        static::saving(function($user) {
            $user->status = $user->generateStatus();
        });
    }

    // get permissions for user
    public function permissions(): mixed
    {
        if (!has_table('permissions')) return null;

        return $this->hasMany(model('permission'));
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

    // scope for readable
    public function scopeReadable($query): void
    {
        $query->when(tier('root'),
            fn($q) => $q,
            fn($q) => $q->where('tier', user()->tier),
        );
    }

    // scope for status
    public function scopeStatus($query, $status): void
    {
        $query->withTrashed()->whereIn('status', (array)$status);
    }

    // get user home
    public function home(): string
    {
        return route('app.dashboard');
    }

    // check user tier
    public function isTier($tiers): bool
    {
        return collect($tiers)->contains($this->tier);
    }

    // check user is tenant owner
    public function isTenantOwner($tenant = null)
    {
        if (!has_table('tenants')) return false;
        
        if ($tenant = $tenant ?? tenant()) return !empty($tenant->owners->where('id', $this->id)->first());

        return false;
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
