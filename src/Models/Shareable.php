<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\HasUlid;

class Shareable extends Model
{
    use HasFilters;
    use HasUlid;

    protected $guarded = [];

    protected $casts = [
        'data' => 'object',
        'valid_for' => 'integer',
        'is_enabled' => 'boolean',
        'parent_id' => 'integer',
        'expired_at' => 'datetime',
    ];

    protected $appends = ['url'];

    // booted
    protected static function booted(): void
    {
        static::saved(function($shareable) {
            $shareable->fill([
                'expired_at' => $shareable->valid_for > 0 
                    ? $shareable->updated_at->copy()->addDays($shareable->valid_for) 
                    : null,
            ])->saveQuietly();
        });
    }

    // get parent for shareable
    public function parent(): MorphTo
    {
        return $this->morphTo();
    }

    // attribute for url
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn() => route('shareable', [(string) $this->ulid]),
        );
    }

    // attribute for status
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn() => ($this->expired_at && ($this->expired_at->isPast() || $this->expired_at->isToday()))
                ? 'expired'
                : 'active',
        );
    }

    // scope for status
    public function scopeStatus($query, $status): void
    {
        $query->where(function($q) use ($status) {
            foreach ((array)$status as $val) {
                if ($val === 'expired') $q->orWhere('expired_at', '<=', now());
                if ($val === 'disabled') $q->orWhere('is_enabled', false);
                if ($val === 'enabled') $q->orWhere('is_enabled', true);
                if ($val === 'active') {
                    $q->orWhere(fn($q) => $q
                        ->where('is_enabled', true)
                        ->where(fn($q) => $q
                            ->where('expired_at', '>', now())
                            ->orWhereNull('expired_at')
                        )
                    );
                }
            }
        });
    }
}
