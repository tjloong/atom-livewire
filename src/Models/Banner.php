<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Jiannius\Atom\Traits\Models\HasSlug;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Banner extends Model
{
    use HasSlug;
    use HasTrace;
    use HasFilters;

    protected $guarded = [];

    protected $casts = [
        'placement' => 'array',
        'seq' => 'integer',
        'is_active' => 'boolean',
        'image_id' => 'integer',
        'start_at' => 'date',
        'end_at' => 'date',
    ];

    /**
     * Get image for banner
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(model('file'), 'image_id');
    }

    /**
     * Attribute for status
     */
    public function status(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->is_active) return 'inactive';
                else if ($this->start_at && $this->start_at->isFuture()) return 'upcoming';
                else if ($this->end_at && $this->end_at->isPast()) return 'ended';
                else return 'active';
            },
        );
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
            ->orWhere('url', 'like', "%$search%")
        );
    }

    /**
     * Scope for status
     */
    public function scopeStatus($query, $status): void
    {
        $query->where(function ($q) use ($status) {
            foreach ((array)$status as $val) {
                if ($val === 'inactive') $q->orWhere('is_active', false);
                else {
                    $q->orWhere(function ($q) use ($val) {
                        $q->where('is_active', true);

                        if ($val === 'upcoming') $q->where('start_at', '>', now());
                        if ($val === 'ended') $q->where('end_at', '<', now());
                        if ($val === 'active') {
                            $q->where(fn($q) => $q
                                ->whereRaw('start_at is null and end_at is null')
                                ->orWhereRaw('start_at is not null and start_at < ? and end_at is null', [now()])
                                ->orWhereRaw('start_at is null and end_at is not null and end_at > ?', [now()])
                                ->orWhereRaw('start_at < ? and end_at > ?', [now(), now()])
                            );
                        }
                    });
                }
            }
        });
    }
}
