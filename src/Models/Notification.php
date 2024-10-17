<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jiannius\Atom\Traits\Models\Footprint;

class Notification extends Model
{
    use Footprint;
    use HasFactory;
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'read_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    // get sender for notification
    public function sender(): BelongsTo
    {
        return $this->belongsTo(model('user'), 'sender_id');
    }

    // get receiver for notification
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(model('user'), 'receiver_id');
    }

    // attribute for status
    protected function status() : Attribute
    {
        return Attribute::make(
            get: fn() => enum('notification-status', pick([
                'ARCHIVED' => !empty($this->archived_at),
                'READ' => !empty($this->read_at),
                'UNREAD' => true,
            ])),
        );
    }

    // scope for status
    public function scopeStatus($query, $status) : void
    {
        if (!$status) return;

        $status = is_array($status)
            ? collect($status)->map(fn($val) => enum('notification-status', $val))->toArray()
            : enum('notification-status', $status);

        if (is_array($status)) {
            $query->where(fn($q) => collect($status)->each(fn($val, $i) => 
                $i === 0 ? $query->status($val) : $query->orWhere(fn($q) => $q->status($val))
            ));
        }
        elseif ($status->is('ARCHIVED')) $query->whereRaw('notifications.archived_at is not null');
        else {
            $query->whereRaw('notifications.archived_at is null');

            if ($status->is('READ')) $query->whereRaw('notifications.read_at is not null');
            else if ($status->is('UNREAD')) $query->whereRaw('notifications.read_at is null');
        }
    }

    // mark as read
    public function read($bool = true) : void
    {
        if ($bool && !$this->read_at) $this->fill(['read_at' => now()])->save();
        if (!$bool && $this->read_at) $this->fill(['read_at' => null])->save();
    }

    // mark as archived
    public function archive($bool = true) : void
    {
        if ($bool && !$this->archived_at) $this->fill(['archived_at' => now()])->save();
        if (!$bool && $this->archived_at) $this->fill(['archived_at' => null])->save();
    }
}
