<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\HasUniqueNumber;

class Ticket extends Model
{
    use HasFilters;
    use HasTrace;
    use HasUniqueNumber;
    
    protected $guarded = [];

    // get comments for ticket
    public function comments(): MorphMany
    {
        return $this->morphMany(model('comment'), 'parent');
    }

    // attribute for unread_count
    public function unreadCount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->comments()
                ->when(user(), fn($q) => $q->where('comments.created_by', '<>', user('id')))
                ->whereNull('read_at')
                ->count(),
        );
    }

    // scope for search
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('subject', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
        );
    }

    // notify
    public function notify(): void
    {
        if (
            $notifyTo = $this->notifyTo ?? settings('notify_to')
            && ($notification = collect([
                'App\Notifications\Ticket\CreateNotification',
                'Jiannius\Atom\Notifications\Ticket\CreateNotification',
            ])->first(fn($ns) => file_exists(atom_ns_path($ns))))
        ) {
            Notification::route('mail', $notifyTo)->notify(new $notification($this));
        }
    }
}
