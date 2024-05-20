<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\HasUlid;

class Share extends Model
{
    use HasFilters;
    use HasUlid;

    protected $guarded = [];

    protected $casts = [
        'data' => 'object',
        'valid_for' => 'integer',
        'is_enabled' => 'boolean',
        'expired_at' => 'datetime',
    ];

    protected $appends = ['url'];

    // booted
    protected static function booted(): void
    {
        static::saved(function($share) {
            $share->setAttributes()->saveQuietly();
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
            get: fn() => route('share', (string) $this->ulid),
        );
    }

    // attribute for status
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn() => enum('share.status', collect([
                'DISABLED' => !$this->is_enabled,
                'EXPIRED' => $this->expired_at && ($this->expired_at->isPast() || $this->expired_at->isToday()),
                'ACTIVE' => true,
            ])->filter()->keys()->first()),
        );
    }

    // scope for status
    public function scopeStatus($query, $status): void
    {
        $query->where(function($q) use ($status) {
            foreach ((array) $status as $val) {
                $val = is_string($val) ? $val : $val->value;

                if ($val === enum('share.status', 'EXPIRED')->value) {
                    $q->orWhereRaw('(expired_at <= curdate()');
                }
                else if ($val === enum('share.status', 'DISABLED')->value) {
                    $q->orWhere('is_enabled', false);
                }
                else if ($val === enum('share.status', 'ACTIVE')->value) {
                    $q->orWhereRaw('is_enabled = true and (expired_at is null or expired_at > curdate())');
                }
            }
        });
    }

    // set attributes
    public function setAttributes() : mixed
    {
        $this->fill([
            'expired_at' => $this->valid_for > 0 
                ? $this->updated_at->copy()->addDays($this->valid_for) 
                : null,
        ]);

        return $this;
    }
}
