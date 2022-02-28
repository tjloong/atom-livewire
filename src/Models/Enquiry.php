<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\HasFilters;

class Enquiry extends Model
{
    use HasFilters;

    protected $guarded = [];

    protected $casts = [
        'data' => 'object',
    ];

    /**
     * Model boot method
     * 
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($enquiry) {
            $enquiry->status = $enquiry->status ?? 'pending';
        });
    }

    /**
     * Scope for fussy search
     * 
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('phone', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
        );
    }
}
