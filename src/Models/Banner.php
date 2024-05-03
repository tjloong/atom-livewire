<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jiannius\Atom\Traits\Models\HasSlug;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jiannius\Atom\Traits\Models\Footprint;
use Jiannius\Atom\Traits\Models\HasSequence;

class Banner extends Model
{
    use Footprint;
    use HasFactory;
    use HasFilters;
    use HasSlug;
    use HasSequence;

    protected $guarded = [];

    protected $casts = [
        'seq' => 'integer',
        'is_active' => 'boolean',
        'start_at' => 'date',
        'end_at' => 'date',
    ];

    // booted
    protected static function booted() : void
    {
        static::updating(function($banner) {
            if ($banner->isDirty('image_id')) {
                optional(model('file')->find($banner->getOriginal('image_id')))->delete();
            }

            if ($banner->isDirty('mob_image_id')) {
                optional(model('file')->find($banner->getOriginal('mob_image_id')))->delete();
            }
        });

        static::deleting(function($banner) {
            optional($banner->image)->delete();
            optional($banner->mobile_image)->delete();
        });
    }

    // get image for banner
    public function image(): BelongsTo
    {
        return $this->belongsTo(model('file'), 'image_id');
    }

    // get mobile image for banner
    public function mobile_image(): BelongsTo
    {
        return $this->belongsTo(model('file'), 'mob_image_id');
    }

    // attribute for status
    protected function status() : Attribute
    {
        return Attribute::make(
            get: fn() => enum('banner.status', pick([
                'INACTIVE' => !$this->is_active,
                'ENDED' => $this->end_at && $this->end_at->isPast(),
                'UPCOMING' => $this->start_at && $this->start_at->isFuture(),
                'ACTIVE' => true,
            ])),
        );
    }

    // attribute for type
    protected function type() : Attribute
    {
        return Attribute::make(
            get: fn($value) => enum('banner.type', $value),
            set: fn($value) => is_string($value) ? $value : optional($value)->value,
        );
    }

    // attribute for placement
    protected function placement() : Attribute
    {
        return Attribute::make(
            get: fn($value) => collect(json_decode($value))->map(fn($val) => enum('banner.placement', $val))->toArray(),
            set: fn($value) => collect($value)->map(fn($val) => is_string($val) ? $val : optional($val)->value),
        );
    }

    // scope for fussy search
    public function scopeSearch($query, $search) : void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
            ->orWhere('url', 'like', "%$search%")
        );
    }

    // scope for placement
    public function scopePlacement($query, $placement = null) : void
    {
        $query->where(fn($q) => $q
            ->whereRaw('(
                `placement` is null
                or (json_contains(`placement`, json_array()) and json_length(`placement`) = 0)
            )')
            ->when($placement, fn($q) => $q->orWhereJsonContains('placement', (array) $placement))
        );
    }

    // scope for status
    public function scopeStatus($query, $status) : void
    {
        if ($status) {
            $query->where(function($q) use ($status) {
                foreach ((array) $status as $value) {
                    $value = enum('banner.status', $value);

                    if ($value->is('INACTIVE')) {
                        $q->orWhere('banners.is_active', false);
                    }
                    elseif ($value->is('ENDED')) {
                        $q->orWhereRaw('banners.is_active = true and banners.end_at is not null and banners.end_at < now()');
                    }
                    elseif ($value->is('UPCOMING')) {
                        $q->orWhereRaw('banners.is_active = true and banners.start_at is not null and banners.start_at > now()');
                    }
                    elseif ($value->is('ACTIVE')) {
                        $q->orWhereRaw('(
                            banners.is_active = true and (
                                (banners.start_at is null and banners.end_at is null)
                                or (banners.start_at <= now() and banners.end_at is null)
                                or (banners.start_at is null and banners.end_at >= now())
                                or (banners.start_at <= now() and banners.end_at >= now())
                            )
                        )');
                    }
                }
            });
        }
    }
}
