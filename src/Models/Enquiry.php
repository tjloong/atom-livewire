<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Enums\EnquiryStatus;
use Jiannius\Atom\Traits\Models\HasFilters;

class Enquiry extends Model
{
    use HasFactory;
    use HasFilters;
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
        'status' => EnquiryStatus::class,
    ];

    protected static function booted() : void
    {
        static::saving(function ($enquiry) {
            $enquiry->status = $enquiry->status ?? enum('enquiry-status', 'PENDING');
        });
    }

    public function scopeSearch($query, $search) : void
    {
        $query->where(fn($q) => $q
            ->whereAny(['name', 'phone', 'email'], 'like', "%$search")
            ->orWhere('ref', $search)
            ->orWhere('utm', $search)
        );
    }
}
