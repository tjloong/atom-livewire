<?php

namespace Jiannius\Atom\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Jiannius\Atom\Traits\HasRole;
use Jiannius\Atom\Traits\HasTeam;
use Jiannius\Atom\Traits\HasOwner;
use Jiannius\Atom\Notifications\ActivateAccountNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasRole;
    use HasTeam;
    use HasOwner;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $guarded = ['password'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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
