<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\HasTrace;

class Invitation extends Model
{
    use HasFilters;
    use HasTrace;
    
    protected $guarded = [];

    protected $casts = [
        'data' => 'object',
        'accepted_at' => 'datetime',
        'declined_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public $validfor = 7;

    /**
     * Booted
     */
    protected static function booted(): void
    {
        static::created(function($invitation) {
            $invitation->notify();
        });

        static::saved(function($invitation) {
            $invitation->fill([
                'expired_at' => $invitation->expired_at 
                    ?? $invitation->created_at->addDays($invitation->validfor),
            ])->saveQuietly();
        });
    }

    /**
     * Attribute for status
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: function() {
                if ($this->accepted_at) return 'accepted';
                if ($this->declined_at) return 'declined';
                if ($this->expired_at && $this->expired_at->isPast()) return 'expired';

                return 'pending';
            },
        );
    }

    /**
     * Scope status
     */
    public function scopeStatus($query, $status): void
    {
        $query->where(function($q) use ($status) {
            foreach ((array)$status as $val) {
                if ($val === 'accepted') $q->whereNotNull('accepted_at');
                elseif ($val === 'declined') $q->whereNotNull('declined_at');
                elseif ($val === 'expired') $q->whereNotNull('expired_at')->where('expired_at', '<', now());
                elseif ($val === 'pending') {
                    $q->whereNull('accepted_at')->whereNull('declined_at')->where(fn($q) => $q
                        ->whereNull('expired_at')->orWhere('expired_at', '>=', now())
                    );
                }
            }
        });
    }

    /**
     * Notify
     */
    public function notify()
    {
        if (
            $notification = collect([
                'App\Notifications\InvitationNotification',
                'Jiannius\Atom\Notifications\InvitationNotification',
            ])->first(fn($ns) => file_exists(atom_ns_path($ns)))
        ) {
            Notification::route('mail', $this->email)->notify(
                app()->make($notification, ['invitation' => $this])
            );
        }
    }
}
