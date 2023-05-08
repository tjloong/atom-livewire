<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(model('ticket'));
    }

    /**
     * Get is self comment attribute
     */
    protected function isSelfComment(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_by === $this->ticket->created_by,
        );
    }

    /**
     * Notify
     */
    public function notify(): void
    {
        // creator comment, send notification to admin
        if ($this->is_self_comment) {
            if ($to = settings('notify_to')) {
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
    public function getUnreadCount($ticketId = null): int
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
