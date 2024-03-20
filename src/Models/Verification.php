<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Notifications\VerificationCode\Send;

class Verification extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    // booted
    protected static function booted() : void
    {
        static::created(function($verification) {
            $verification->fill([
                'code' => mt_rand(100000, 999999),
            ])->saveQuietly();
            
            $verification->notify();
        });
    }

    // notify
    public function notify() : void
    {
        if ($this->email) {
            Notification::route('mail', $this->email)
                ->notify(new Send($this));
        }
        else if ($this->phone) {
            //
        }
    }
}
