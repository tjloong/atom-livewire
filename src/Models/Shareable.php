<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\Models\HasFilters;

class Shareable extends Model
{
    use HasFilters;

    protected $guarded = [];

    protected $casts = [
        'data' => 'object',
        'valid_for' => 'integer',
        'expired_at' => 'datetime',
    ];

    protected $appends = ['url'];

    /**
     * Model boot
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function($shareable) {
            $shareable->uuid = $shareable->uuid ?? $shareable->generateUuid();
        });
    }

    /**
     * Scope for status
     */
    public function scopeStatus($query, $status)
    {
        if ($status === 'expired') return $query->where('expired_at', '<=', now());
        if ($status === 'active') {
            return $query->where(fn($q) => $q
                ->where('expired_at', '>', now())
                ->orWhereNull('expired_at')
            );
        }
    }

    /**
     * Get url attribute
     */
    public function getUrlAttribute()
    {
        return route('shareable', [$this->uuid]);
    }

    /**
     * Get status attribute
     */
    public function getStatusAttribute()
    {
        if ($this->expired_at && ($this->expired_at->isPast() || $this->expired_at->isToday())) return 'expired';

        return 'active';
    }

    /**
     * Generate uuid
     */
    public function generateUuid()
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
