<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Notifications\TicketCommentNotification;

class TicketComment extends Model
{
    use HasTrace;

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
            if ($to = site_settings('notify_to')) {
                Notification::route('mail', $to)->notify(new TicketCommentNotification($this));
            }
        }
        // non-creator comment, send notification to creator
        else {
            $this->ticket->created_by_user->notify(new TicketCommentNotification($this));
        }
    }

    /**
     * Get unread count
     */
    public static function getUnreadCount($ticketId = null)
    {
        return model('ticket_comment')
            ->when($ticketId, fn($q) => $q->where('ticket_id', $ticketId))
            ->when(auth()->user(), fn($q) => $q
                ->whereHas('ticket', fn($q) => $q->where('tickets.created_by', auth()->id()))
                ->where('created_by', '<>', auth()->id())
            )
            ->where(fn($q) => $q
                ->whereNull('is_read')
                ->orWhere('is_read', false)
            )
            ->count();
    }
}
