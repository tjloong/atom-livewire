<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Share extends Model
{
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
        'valid_for' => 'integer',
        'is_enabled' => 'boolean',
        'expired_at' => 'datetime',
    ];

    protected $appends = ['url'];

    // booted
    protected static function booted(): void
    {
        static::saving(function ($share) {
            $share->fill([
                'expired_at' => $this->valid_for > 0 
                    ? $this->updated_at->copy()->addDays($this->valid_for) 
                    : null,
            ]);
        });
    }

    // get parent for shareable
    public function parent(): MorphTo
    {
        return $this->morphTo()->withoutGlobalScopes();
    }

    // attribute for url
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn() => app('route')->has('web.share')
                ? route('web.share', (string) $this->ulid)
                : url('share/'.((string) $this->ulid)),
        );
    }

    // attribute for status
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn() => enum('share-status', pick([
                'DISABLED' => !$this->is_enabled,
                'EXPIRED' => $this->expired_at && ($this->expired_at->isPast() || $this->expired_at->isToday()),
                'ACTIVE' => true,
            ])),
        );
    }

    // scope for status
    public function scopeStatus($query, $status): void
    {
        if (!$status) return;

        $status = is_array($status)
            ? collect($status)->map(fn($val) => enum('share-status', $val))->toArray()
            : enum('share-status', $status);

        if (is_array($status)) {
            $query->where(fn($q) => collect($status)->each(fn($val, $i) => 
                $i === 0 ? $query->status($val) : $query->orWhere(fn($q) => $q->status($val))
            ));
        }
        elseif ($status->is('EXPIRED')) {
            $query->whereRaw('(shares.expired_at <= now())');
        }
        else {
            $query->whereRaw('(shares.expired_at is null or shares.expired_at > now())');

            if ($status->is('DISABLED')) $query->whereRaw('(shares.is_enabled = false)');
            else if ($status->is('ACTIVE')) $query->whereRaw('(shares.is_enabled = true)');
        }
    }
}
