<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;

class SignupOrder extends Model
{
    use HasFilters;

    protected $table = 'signups_orders';
    
    protected $guarded = [];

    protected $casts = [
        'grand_total' => 'float',
        'data' => 'object',
        'signup_id' => 'integer',
    ];

    /**
     * Get signup for order
     */
    public function signup()
    {
        return $this->belongsTo(Signup::class);
    }

    /**
     * Get subscriptions for order
     */
    public function subscriptions()
    {
        return $this->hasMany(SignupSubscription::class, 'signup_order_id');
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
            ->whereHas('signup', fn($q) => $q->search($search))
        );
    }
}
