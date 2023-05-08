<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\Models\HasFilters;

class Shareable extends Model
{
    use HasFilters;

    protected $guarded = [];

    protected $casts = [
        'data' => 'object',
        'valid_for' => 'integer',
        'is_enabled' => 'boolean',
        'expired_at' => 'datetime',
    ];

    protected $appends = ['url'];

    /**
     * Booted
     */
    protected static function booted(): void
    {
        static::saving(function($shareable) {
            $shareable->uuid = $shareable->uuid ?? $shareable->generateUuid();
        });
        
        static::saved(function($shareable) {
            $shareable->fill([
                'expired_at' => $shareable->valid_for > 0 ? $shareable->updated_at->copy()->addDays($shareable->valid_for) : null,
            ])->saveQuietly();
        });
    }

    /**
     * Attribute for url
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn() => route('shareable', [$this->uuid]),
        );
    }

    /**
     * Attribute for status
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn() => ($this->expired_at && ($this->expired_at->isPast() || $this->expired_at->isToday()))
                ? 'expired'
                : 'active',
        );
    }

    /**
     * Scope for status
     */
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

    /**
     * Generate uuid
     */
    public function generateUuid(): string
    {
        $dup = true;
        $uuid = null;

        while ($dup) {
            $uuid = str()->uuid();
            $dup = model('shareable')->where('uuid', $uuid)->count() > 0;
        }

        return $uuid;
    }
}
