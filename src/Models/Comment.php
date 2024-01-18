<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Traits\Models\Footprint;

class Comment extends Model
{
    use Footprint;

    protected $guarded = [];

    protected $casts = [
        'is_read' => 'boolean',
        'parent_id' => 'integer',
        'read_at' => 'datetime',
    ];

    // get parent for comment
    public function parent(): MorphTo
    {
        return $this->morphTo();
    }

    // scope for unread
    public function scopeUnread($query, $user = null): void
    {
        $user = $user ?? user();

        $query
            ->when($user, fn($q) => $q->where('created_by', '<>', $user->id))
            ->whereNull('read_at');
    }

    // notify to
    public function notifyTo(): mixed
    {
        return settings('notify_to');
    }

    // notify
    public function notify(): void
    {
        if (
            ($to = $this->notifyTo()) 
            && ($notification = $notification ?? collect([
                'App\Notifications\Comment\SendNotification',
                'Jiannius\Atom\Notifications\Comment\SendNotification',
            ])->first(fn($ns) => file_exists(atom_ns_path($ns))))
        ) {
            Notification::route('mail', $to)->notify(new $notification($this));
        }
    }
}
