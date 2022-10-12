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

    protected static function boot()
    {
        parent::boot();

        static::creating(function($shareable) {
            $shareable->uuid = $shareable->uuid ?? $shareable->generateUuid();
        });
    }

    /**
     * Get url attribute
     */
    public function getUrlAttribute()
    {
        return route('shareable', [$this->uuid]);
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
