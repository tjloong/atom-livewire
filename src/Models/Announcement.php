<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jiannius\Atom\Traits\Models\HasSlug;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;
    use HasFilters;
    use HasSlug;

    protected $guarded = [];

    protected $casts = [
        'seo' => 'array',
        'data' => 'array',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    // attribute for seo
    protected function seo() : Attribute
    {
        return Attribute::make(
            get: fn($seo) => [
                'title' => data_get($seo, 'title') ?? $this->name,
                'description' => data_get($seo, 'description')
                    ?? html_excerpt($this->content)
                    ?? data_get($seo, 'title')
                    ?? $this->name,
                'image' => data_get($seo, 'image'),
            ],
        );
    }

    // attribute for status
    protected function status() : Attribute
    {
        return Attribute::make(
            get: fn() => enum('announcement.status', collect([
                'EXPIRED' => $this->end_at && $this->end_at->isPast(),
                'UPCOMING' => $this->start_at && $this->start_at->isFuture(),
                'PUBLISHED' => true,
                // 'PUBLISHED' => (!$this->start_at && !$this->end_at)
                //     || ($this->start_at && !$this->end_at && $this->start_at->lte(now()))
                //     || (!$this->start_at && $this->end_at && $this->end_at->gt(now()))
                //     || ($this->start_at && $this->end_at && now()->betweenIncluded($this->start_at, $this->end_at)),
            ])->filter()->keys()->first()),
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
                if (is_string($val)) $val = enum('announcement.status', $val);

                if ($val === enum('announcement.status', 'EXPIRED')) {
                    $q->orWhereRaw('(announcements.end_at is not null and announcements.end_at < now())');
                }
                else if ($val === enum('announcement.status', 'UPCOMING')) {
                    $q->orWhereRaw('(announcements.start_at is not null and announcements.start_at > now())');
                }
                else if ($val === enum('announcement.status', 'PUBLISHED')) {
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
