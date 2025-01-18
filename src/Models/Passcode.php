<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Atom;

class Passcode extends Model
{
    use HasFactory;
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    protected static function booted() : void
    {
        static::created(function($passcode) {
            $passcode->notify();
        });
    }

    public function notify() : void
    {
        if ($this->email) {
            $this->fill([
                'code' => mt_rand(100000, 999999),
                'expired_at' => now()->addDay(),
            ])->saveQuietly();

            $greeting = t('hi');
            $content = t('here-is-your-verification-code');

            Atom::mail(
                to: [$this->email],
                subject: t('your-verification-code'),
                content: <<<EOL
                ## {$greeting}
                    
                {$content}

                # {$this->code}
                EOL,
            );
        }
        else if ($this->phone) {
            //
        }
    }

    public static function resend($email = null, $phone = null)
    {
        if (!$email && !$phone) return;

        optional(
            self::query()
                ->when($email, fn ($q) => $q->where('email', $email))
                ->when($phone, fn ($q) => $q->where('phone', $phone))
                ->first()
        )->notify();
    }

    public static function verify($email = null, $phone = null, $code = null) : bool
    {
        $method = config('atom.auth.verify_method');

        if (!$method) return true;
        if (!$code) return false;
        if (!$email && !$phone) return false;
        if ($method === 'email' && !$email) return false;
        if ($method === 'otp' && !$phone) return false;

        $query = self::query()
            ->when($method === 'email', fn ($q) => $q->where('email', $email))
            ->when($method === 'otp', fn ($q) => $q->where('phone', $phone));
                
        $verified = (clone $query)
            ->where('code', $code)
            ->where(fn($q) => $q->whereNull('expired_at')->orWhere('expired_at', '>', now()))
            ->count() > 0;

        if ($verified) {
            $query->delete();
            return true;
        }

        return false;
    }
}
