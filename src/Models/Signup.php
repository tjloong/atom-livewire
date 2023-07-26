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
        'geo' => 'array',
        'agree_tnc' => 'boolean',
        'agree_promo' => 'boolean',
    ];

    // get user for signup
    public function user(): BelongsTo
    {
        return $this->belongsTo(model('user'));
    }

    // scope for search
    public function scopeSearch($query, $search): void
    {
        $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$search%"));
    }
}
