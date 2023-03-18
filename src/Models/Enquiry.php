<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\Models\HasFilters;

class Enquiry extends Model
{
    use HasFilters;

    protected $guarded = [];

    protected $casts = [
        'data' => 'object',
    ];

    /**
     * Model booted method
     */
    protected static function booted(): void
    {
        static::saving(function ($enquiry) {
            $enquiry->status = $enquiry->status ?? 'pending';
        });
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('phone', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
        );
    }
}
