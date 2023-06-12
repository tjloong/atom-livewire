<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'rate' => 'float',
        'is_active' => 'boolean',
    ];

    protected $appends = ['label'];

    /**
     * Attribute for label
     */
    protected function label(): Attribute
    {
        return Attribute::make(
            get: fn() => collect([$this->name, str($this->rate)->finish('%')->toString()])->join(' '),
        );
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('country', $search)
            ->orWhere('state', $search)
        );
    }

    /**
     * Calculate tax amount
     */
    public function calculate($amount = 0)
    {
        $tax = $amount * ($this->rate/100);

        return round($tax * 2, 1)/2;
    }
}
