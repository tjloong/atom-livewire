<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Shareable extends Model
{
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
        'valid_for' => 'integer',
        'expired_at' => 'datetime',
    ];

    protected $appends = ['url'];

    protected static function booted(): void
    {
        static::saving(function ($shareable) {
            $shareable->fill([
                'expired_at' => $shareable->valid_for > 0 
                    ? $shareable->updated_at->copy()->addDays($shareable->valid_for) 
                    : null,
            ]);
        });
    }

    public function parent(): MorphTo
    {
        return $this->morphTo()->withoutGlobalScopes();
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn() => url('share/'.$this->id),
        );
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn() => enum('shareable-status', pick([
                'EXPIRED' => $this->expired_at && ($this->expired_at->isPast() || $this->expired_at->isToday()),
                'ACTIVE' => true,
            ])),
        );
    }

    public function scopeStatus($query, $status): void
    {
        if (!$status) return;

        $status = is_array($status)
            ? collect($status)->map(fn($val) => enum('shareable-status', $val))->toArray()
            : enum('shareable-status', $status);

        if (is_array($status)) {
            $query->where(fn($q) => collect($status)->each(fn($val, $i) => 
                $i === 0 ? $query->status($val) : $query->orWhere(fn($q) => $q->status($val))
            ));
        }
        elseif ($status->is('EXPIRED')) $query->whereRaw('(shareables.expired_at <= now())');
        else $query->whereRaw('(shareables.expired_at is null or shareables.expired_at > now())');
    }
}
