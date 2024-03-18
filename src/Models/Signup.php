<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jiannius\Atom\Traits\Models\HasFilters;

class Signup extends Model
{
    use HasFactory;
    use HasFilters;

    protected $guarded = [];

    protected $casts = [
        'geo' => 'array',
        'agree_tnc' => 'boolean',
        'agree_promo' => 'boolean',
        'onboarded_at' => 'datetime',
    ];

    // booted
    protected static function booted() : void
    {
        static::saved(function($signup) {
            $signup->setAttributes()->saveQuietly();
        });
    }

    // get user for signup
    public function user() : BelongsTo
    {
        return $this->belongsTo(model('user'));
    }

    // attribute for status
    protected function status() : Attribute
    {
        return Attribute::make(
            get: fn($value) => enum('signup.status', $value),
            set: fn($status) => is_string($status) ? $status : optional($status)->value,
        );
    }

    // scope for search
    public function scopeSearch($query, $search) : void
    {
        $query->whereHas('user', fn($q) => $q->search($search));
    }

    // scope for status
    public function scopeStatus($query, $status) : void
    {
        if ($status) {
            $query->whereIn('status', (array) $status);
        }
    }

    // set attributes
    public function setAttributes() : mixed
    {
        $this->fill([
            'status' => enum('signup.status', collect([
                'TRASHED' => optional($this->user)->trashed(),
                'BLOCKED' => optional($this->user)->isBlocked(),
                'ONBOARDED' => !empty($this->onboarded_at),
                'NEW' => empty($this->onboarded_at),
            ])->filter()->keys()->first())->value,
        ]);

        return $this;
    }
}