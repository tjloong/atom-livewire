<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\Models\Footprint;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\HasUlid;

class Notification extends Model
{
    use Footprint;
    use HasFactory;
    use HasFilters;
    use HasUlid;

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
    ];

    // attribute for status
    protected function status() : Attribute
    {
        return Attribute::make(
            get: fn($value) => enum('notification.status', $value),
            set: fn($value) => is_string($value) ? $value : optional($value)->value,
        );
    }
}
