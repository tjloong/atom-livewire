<?php

namespace Jiannius\Atom\Models;

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
        'utm' => 'array',
        'geo' => 'array',
        'agree_tnc' => 'boolean',
        'agree_promo' => 'boolean',
        'onboarded_at' => 'datetime',
        'status' => \Jiannius\Atom\Enums\SignupStatus::class,
    ];

    // booted
    protected static function booted() : void
    {
        static::saving(function($signup) {
            $signup->fillStatus();
        });
    }

    // get user for signup
    public function user() : BelongsTo
    {
        return $this->belongsTo(model('user'));
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

    // fill status
    public function fillStatus() : mixed
    {
        return $this->fill([
            'status' => enum('signup-status', pick([
                'TRASHED' => optional($this->user)->trashed(),
                'BLOCKED' => optional($this->user)->isBlocked(),
                'ONBOARDED' => !empty($this->onboarded_at),
                'NEW' => empty($this->onboarded_at),
            ])),
        ]);
    }
}