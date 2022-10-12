<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\HasUniqueNumber;
use Jiannius\Atom\Notifications\TicketCreateNotification;

class Ticket extends Model
{
    use HasTrace;
    use HasFilters;
    use HasUniqueNumber;
    
    protected $guarded = [];

    /**
     * Get comments for ticket
     */
    public function comments()
    {
        return $this->hasMany(get_class(model('ticket_comment')));
    }

    /**
     * Scope for search
     * 
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(fn($q) => $q
            ->where('subject', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
        );
    }

    /**
     * Notification
     */
    public function notify()
    {
        if ($notifyTo = $this->notifyTo ?? site_settings('notify_to')) {
            Notification::route('mail', $notifyTo)->notify(new TicketCreateNotification($this));
        }
    }
}
