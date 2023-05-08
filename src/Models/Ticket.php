<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\HasUniqueNumber;
use Jiannius\Atom\Notifications\TicketCreateNotification;

class Ticket extends Model
{
    use HasFilters;
    use HasTrace;
    use HasUniqueNumber;
    
    protected $guarded = [];

    /**
     * Get comments for ticket
     */
    public function comments(): HasMany
    {
        return $this->hasMany(get_class(model('ticket_comment')));
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('subject', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
        );
    }

    /**
     * Notification
     */
    public function notify(): void
    {
        if ($notifyTo = $this->notifyTo ?? settings('notify_to')) {
            Notification::route('mail', $notifyTo)->notify(new TicketCreateNotification($this));
        }
    }
}
