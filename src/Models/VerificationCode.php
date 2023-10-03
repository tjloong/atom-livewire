<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Notifications\VerificationCode\Send;

class VerificationCode extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    // booted
    protected static function booted() : void
    {
        static::created(function($model) {
            $model->fill([
                'code' => fake()->randomNumber(6, true),
            ])->saveQuietly();
            
            $model->notify();
        });
    }

    // notify
    public function notify() : void
    {
        if ($this->email) {
            Notification::route('mail', $this->email)->notify(new Send($this));
        }
        else if ($this->phone) {
            //
        }
    }
}
