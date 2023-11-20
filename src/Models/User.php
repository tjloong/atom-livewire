<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Password;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\HasTrace;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasFilters;
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
        'login_at' => 'datetime',
        'last_active_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    // booted
    protected static function booted(): void
    {
        static::created(function($user) {
            model('user_setting')->initialize($user);
        });
        
        static::saved(function($user) {
            $user->setAttributes()->saveQuietly();
        });

        static::deleted(function($user) {
            if ($user->exists) $user->setAttributes()->saveQuietly();
        });
    }

    // get signup for user
    public function signup(): HasOne
    {
        return $this->hasOne(model('signup'));
    }

    // attribute for status
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn($value) => enum('user.status', $value),
        );
    }

    // scope for fussy search
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
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
        if ($status) {
            $query->withTrashed()->whereIn('status', (array) $status);
        }
    }

    // get user home
    public function home(): string
    {
        if (session('onboarding') !== 'onhold' && optional($this->signup)->status === enum('signup.status', 'NEW')) return route('app.onboarding');

        return route('app.dashboard');
    }

    // get settings
    public function settings($key = null, $default = null)
    {
        if (is_array($key)) {
            foreach ($key as $name => $value) {
                model('user_setting')
                    ->firstOrNew(['user_id' => $this->id, 'name' => $name])
                    ->fill(compact('value'))
                    ->save();
            }
        }
        else {
            if (!session('user_settings')) {
                session(['user_settings' => model('user_setting')->where('user_id', $this->id)
                    ->get()
                    ->mapWithKeys(fn($val) => [$val->name => $val->value])]);
            }

            return $key ? data_get(session('user_settings'), $key, $default) : session('user_settings');
        }
    }

    // check user tier
    public function isTier($tiers): bool
    {
        return collect($tiers)->contains($this->tier);
    }

    // send password reset link
    public function sendPasswordResetLink(): mixed
    {
        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) return $status;
        else return false;
    }

    // set attributes
    public function setAttributes(): mixed
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
