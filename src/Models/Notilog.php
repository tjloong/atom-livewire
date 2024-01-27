<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\Models\Footprint;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\HasUlid;

class Notilog extends Model
{
    use Footprint;
    use HasFactory;
    use HasFilters;
    use HasUlid;

    protected $guarded = [];

    protected $casts = [
        'tags' => 'array',
        'data' => 'array',
    ];

    // attribute for from
    protected function from() : Attribute
    {
        return Attribute::make(
            get: fn() => format($this->getJson('data.from'))->email(),
        );
    }

    // attribute for to
    protected function to() : Attribute
    {
        return Attribute::make(
            get: fn() => format($this->getJson('data.to'))->email(),
        );
    }

    // attribute for reply to
    protected function replyTo() : Attribute
    {
        return Attribute::make(
            get: fn() => format($this->getJson('data.reply_to'))->email(),
        );
    }

    // attribute for cc
    protected function cc() : Attribute
    {
        return Attribute::make(
            get: fn() => format($this->getJson('data.cc'))->email(),
        );
    }

    // attribute for bcc
    protected function bcc() : Attribute
    {
        return Attribute::make(
            get: fn() => format($this->getJson('data.bcc'))->email(),
        );
    }

    // attribute for status
    protected function status() : Attribute
    {
        return Attribute::make(
            get: fn($value) => enum('notilog.status', $value),
            set: fn($value) => is_string($value) ? $value : optional($value)->value,
        );
    }

    // scope for search
    public function scopeSearch($query, $search) : void
    {
        $query->where(fn($q) => $q
            ->where('ulid', 'like', "%$search%")
            ->orWhere('subject', 'like', "%$search%")
            ->orWhere('body', 'like', "%$search%")
        );
    }
}
