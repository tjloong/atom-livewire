<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Popup extends Model
{
    use HasFactory;
    use HasFilters;

    protected $guarded = [];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    // booted
    protected static function booted() : void
    {
        static::saving(function($popup) {
            if (
                $popup->isDirty('image_id')
                && ($image = model('file')->find($popup->getOriginal('image_id')))
            ) {
                $image->delete();
            }
        });
    }

    // get image for popup
    public function image() : BelongsTo
    {
        return $this->belongsTo(model('file'), 'image_id');
    }

    // attribute for status
    protected function status() : Attribute
    {
        return Attribute::make(
            get: fn() => enum('popup-status', pick([
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
                $val = enum('popup-status', $val);

                if ($val->is('EXPIRED')) {
                    $q->orWhereRaw('(popups.end_at is not null and popups.end_at < now())');
                }
                else if ($val->is('UPCOMING')) {
                    $q->orWhereRaw('(popups.start_at is not null and popups.start_at > now())');
                }
                else if ($val->is('PUBLISHED')) {
                    $q->orWhereRaw('(
                        (popups.start_at is null and popups.end_at is null)
                        or (popups.start_at is not null and popups.end_at is null and popups.start_at <= now())
                        or (popups.start_at is null and popups.end_at is not null and popups.end_at > now())
                        or (popups.start_at is not null and popups.end_at is not null and popups.start_at <= now() and popups.end_at >= now())
                    )');
                }
            }
        });
    }
}
