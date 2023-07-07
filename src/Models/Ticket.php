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

    // scope for search
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('subject', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
        );
    }

    // notify to
    public function notifyTo(): mixed
    {
        return settings('notify_to');
    }

    // notify
    public function notify($comment = null): void
    {
        if (
            ($to = $this->notifyTo()) 
            && ($notification = collect([
                'App\Notifications\Ticket\CreateNotification',
                'Jiannius\Atom\Notifications\Ticket\CreateNotification',
            ])->first(fn($ns) => file_exists(atom_ns_path($ns))))
        ) {
            Notification::route('mail', $to)->notify(new $notification($this));
        }
    }
}
