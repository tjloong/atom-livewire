<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jiannius\Atom\Traits\Models\Slugify;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\Models\Footprint;
use Jiannius\Atom\Traits\Models\Seo;

class Announcement extends Model
{
    use Footprint;
    use HasFactory;
    use HasFilters;
    use Slugify;
    use HasUlids;
    use Seo;

    protected $guarded = [];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    // attribute for status
    protected function status() : Attribute
    {
        return Attribute::make(
            get: fn() => enum('announcement-status', pick([
                'EXPIRED' => $this->end_at && $this->end_at->isPast(),
                'UPCOMING' => $this->start_at && $this->start_at->isFuture(),
                'PUBLISHED' => true,
            ])),
        );
    }

    // scope for fussy search
    public function scopeSearch($query, $search) : void
    {
        $query->where('name', 'like', "%$search%");
    }

    // scope for status
    public function scopeStatus($query, $status) : void
    {
        if (!$status) return;

        $status = is_array($status)
            ? collect($status)->map(fn($val) => enum('announcement-status', $val))->toArray()
            : enum('announcement-status', $status);

        if (is_array($status)) {
            $query->where(fn($q) => collect($status)->each(fn($val, $i) => 
                $i === 0 ? $query->status($val) : $query->orWhere(fn($q) => $q->status($val))
            ));
        }
        elseif ($status->is('EXPIRED')) {
            $query->whereRaw('(announcements.end_at is not null and announcements.end_at < now())');
        }
        else if ($status->is('UPCOMING')) {
            $query->whereRaw('(announcements.start_at is not null and announcements.start_at > now())');
        }
        else if ($status->is('PUBLISHED')) {
            $query->whereRaw('(
                (announcements.start_at is null and announcements.end_at is null)
                or (announcements.start_at is not null and announcements.end_at is null and announcements.start_at <= now())
                or (announcements.start_at is null and announcements.end_at is not null and announcements.end_at > now())
                or (announcements.start_at is not null and announcements.end_at is not null and announcements.start_at <= now() and announcements.end_at >= now())
            )');
        }
    }
}
