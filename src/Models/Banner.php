<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jiannius\Atom\Traits\Models\HasSlug;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jiannius\Atom\Traits\Models\Footprint;

class Banner extends Model
{
    use Footprint;
    use HasFactory;
    use HasFilters;
    use HasSlug;

    protected $guarded = [];

    protected $casts = [
        'placement' => 'array',
        'seq' => 'integer',
        'is_active' => 'boolean',
        'start_at' => 'date',
        'end_at' => 'date',
    ];

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
            get: fn() => enum('banner.status', collect([
                'INACTIVE' => !$this->is_active,
                'ENDED' => $this->end_at && $this->end_at->isPast(),
                'UPCOMING' => $this->start_at && $this->start_at->isFuture(),
                'ACTIVE' => true,
            ])->filter()->keys()->first()),
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
        if ($placement) {
            $query->where(fn($q) => $q
                ->whereJsonContains('placement', (array) $placement)
                ->orWhereNull('placement')
                ->orWhere('placement', '[]')
            );
        }
        else {
            $query->where(fn($q) => $q
                ->whereNull('placement')
                ->orWhere('placement', '[]')
            );
        }
    }

    // scope for status
    public function scopeStatus($query, $status) : void
    {
        if ($status) {
            $query->where(function($q) use ($status) {
                foreach ((array) $status as $value) {
                    $value = is_string($value) ? $value : $value->value;

                    if ($value === enum('banner.status', 'INACTIVE')->value) {
                        $q->orWhere('banners.is_active', false);
                    }
                    elseif ($value === enum('banner.status', 'ENDED')->value) {
                        $q->orWhereRaw('banners.is_active = true and banners.end_at is not null and banners.end_at < now()');
                    }
                    elseif ($value === enum('banner.status', 'UPCOMING')->value) {
                        $q->orWhereRaw('banners.is_active = true and banners.start_at is not null and banners.start_at > now()');
                    }
                    elseif ($value === enum('banner.status', 'ACTIVE')->value) {
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
