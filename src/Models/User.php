<?php

namespace Jiannius\Atom\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Jiannius\Atom\Traits\HasOwner;
use Jiannius\Atom\Notifications\ActivateAccountNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasOwner;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'email_verified_at',
    ];

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
}
