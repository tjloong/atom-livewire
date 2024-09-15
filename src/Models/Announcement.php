<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jiannius\Atom\Traits\Models\HasSlug;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\Models\Footprint;
use Jiannius\Atom\Traits\Models\Seo;

class Announcement extends Model
{
    use Footprint;
    use HasFactory;
    use HasFilters;
    use HasSlug;
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
        $query->where(function ($q) use ($status) {
            foreach ((array) $status as $val) {
                $val = enum('announcement-status', $val);

                if ($val->is('EXPIRED')) {
                    $q->orWhereRaw('(announcements.end_at is not null and announcements.end_at < now())');
                }
                else if ($val->is('UPCOMING')) {
                    $q->orWhereRaw('(announcements.start_at is not null and announcements.start_at > now())');
                }
                else if ($val->is('PUBLISHED')) {
                    $q->orWhereRaw('(
                        (announcements.start_at is null and announcements.end_at is null)
                        or (announcements.start_at is not null and announcements.end_at is null and announcements.start_at <= now())
                        or (announcements.start_at is null and announcements.end_at is not null and announcements.end_at > now())
                        or (announcements.start_at is not null and announcements.end_at is not null and announcements.start_at <= now() and announcements.end_at >= now())
                    )');
                }
            }
        });
    }
}
