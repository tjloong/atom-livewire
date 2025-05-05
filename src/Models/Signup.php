<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Signup extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'utm' => 'array',
        'geo' => 'array',
        'data' => 'array',
        'agree_tnc' => 'boolean',
        'agree_promo' => 'boolean',
        'onboarded_at' => 'datetime',
        'method' => \Jiannius\Atom\Enums\SignupMethod::class,
        'status' => \Jiannius\Atom\Enums\SignupStatus::class,
    ];

    protected static function booted() : void
    {
        static::created(fn ($signup) => $signup->fillMethod()->saveQuietly());
        static::saving(fn ($signup) => $signup->fillStatus());
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    protected function ip() : Attribute
    {
        return new Attribute(
            get: fn () => data_get($this->geo, 'ip'),
        );
    }

    protected function location() : Attribute
    {
        return new Attribute(
            get: fn () => collect([
                data_get($this->geo, 'city'),
                data_get($this->geo, 'country'),
            ])->filter()->join(', '),
        );
    }

    public function scopeSearch($query, $search) : void
    {
        $query->whereHas('user', fn($q) => $q->search($search));
    }

    public function scopeStatus($query, $status) : void
    {
        if ($status) {
            $query->whereIn('status', (array) $status);
        }
    }

    public function scopeSource($query, $source) : void
    {
        if ($source) {
            $query->whereIn('utm->source', (array) $source);
        }
    }

    public function fillMethod() : self
    {
        return $this->fill([
            'method' => data_get($this->user->data, 'oauth')
                ? \Jiannius\Atom\Enums\SignupMethod::OAUTH
                : \Jiannius\Atom\Enums\SignupMethod::WEB,
        ]);
    }

    public function fillStatus() : self
    {
        return $this->fill([
            'status' => \Jiannius\Atom\Enums\SignupStatus::get(pick([
                'TRASHED' => optional($this->user)->trashed(),
                'BLOCKED' => optional($this->user)->isBlocked(),
                'ONBOARDED' => !empty($this->onboarded_at),
                'NEW' => empty($this->onboarded_at),
            ])),
        ]);
    }
}