<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Traits\HasOwner;
use Jiannius\Atom\Notifications\TicketCommentNotification;

class TicketComment extends Model
{
    use HasOwner;

    protected $table = 'tickets_comments';
    protected $guarded = [];

    /**
     * Get ticket for ticket comment
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get is self comment attribute
     * 
     * @return boolean
     */
    public function getIsSelfCommentAttribute()
    {
        return $this->created_by === $this->ticket->created_by;
    }

    /**
     * Notify
     */
    public function notify()
    {
        // creator comment, send notification to admin
        if ($this->is_self_comment) {
            if ($to = SiteSetting::getSetting('notify_to')) {
                Notification::route('mail', $to)->notify(new TicketCommentNotification($this));
            }
        }
        // non-creator comment, send notification to creator
        else {
            $this->creator->notify(new TicketCommentNotification($this));
        }
    }
}
