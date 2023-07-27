<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\Models\HasFilters;

class Enquiry extends Model
{
    use HasFactory;
    use HasFilters;

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
    ];

    // booted
    protected static function booted(): void
    {
        static::saving(function ($enquiry) {
            $enquiry->status = $enquiry->status ?? enum('enquiry.status', 'PENDING');
        });
    }

    // attribute for status
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value
                ? enum('enquiry.status', $value)
                : enum('enquiry.status', 'PENDING'),
            set: fn($status) => is_string($status)
                ? $status
                : $status->value,
        );
    }

    // scope for search
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('phone', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
        );
    }
}
