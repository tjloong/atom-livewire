<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Traits\Models\HasTrace;

class Comment extends Model
{
    use HasTrace;

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

    // attribute for is self
    protected function isSelf(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_by === $this->ticket->created_by,
        );
    }

    // notify
    public function notify($notification = null): void
    {
        $notification = $notification ?? collect([
            'App\Notifications\Comment\SendNotification',
            'Jiannius\Atom\Notifications\Comment\SendNotification',
        ])->first(fn($ns) => file_exists(atom_ns_path($ns)));

        // creator comment, send notification to admin
        if ($this->is_self) {
            if ($to = settings('notify_to')) {
                Notification::route('mail', $to)->notify(new $notification($this));
            }
        }
        // non-creator comment, send notification to creator
        else {
            optional($this->ticket->createdBy)->notify(new $notification($this));
        }
    }
}
