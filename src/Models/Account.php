<?php

namespace Jiannius\Atom\Models;

use App\Models\User;
use Jiannius\Atom\Traits\HasTrace;
use Jiannius\Atom\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasTrace;
    use HasFilters;
    use SoftDeletes;
    
    protected $guarded = [];

    protected $casts = [
        'dob' => 'date',
        'data' => 'object',
        'agree_tnc' => 'boolean',
        'agree_marketing' => 'boolean',
        'onboarded_at' => 'datetime',
    ];

    /**
     * Get users for account
     */
    public function users()
    {
        return $this->hasMany(User::class)->withTrashed();
    }

    /**
     * Get orders for account
     */
    public function orders()
    {
        return $this->hasMany(AccountOrder::class);
    }

    /**
     * Get subscriptions for account
     */
    public function subscriptions()
    {
        return $this->hasMany(AccountSubscription::class);
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
            ->where('phone', 'like', "%$search%")
            ->where('email', 'like', "%$search%")
        );
    }

    /**
     * Get status attribute
     * 
     * @return string
     */
    public function getStatusAttribute()
    {
        if ($this->blocked()) return 'blocked';

        return 'active';
    }

    /**
     * Onboard account
     * 
     * @return void
     */
    public function onboard()
    {
        $this->onboarded_at = now();
        $this->saveQuietly();
    }

    /**
     * Check account is onboarded
     * 
     * @return boolean
     */
    public function onboarded()
    {
        return !empty($this->onboarded_at);
    }
}
