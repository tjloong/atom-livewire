<?php

namespace Jiannius\Atom\Models;

use App\Models\User;
use Jiannius\Atom\Traits\HasTrace;
use Jiannius\Atom\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;

class Signup extends Model
{
    use HasTrace;
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'dob' => 'date',
        'agree_tnc' => 'boolean',
        'agree_marketing' => 'boolean',
        'user_id' => 'integer',
        'onboarded_at' => 'datetime',
    ];

    /**
     * Get user for signup
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get orders for signup
     */
    public function orders()
    {
        return $this->hasMany(SignupOrder::class);
    }

    /**
     * Get subscriptions for signup
     */
    public function subscriptions()
    {
        return $this->hasMany(SignupSubscription::class);
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
        if ($this->user->trashed()) return 'trashed';

        return 'active';
    }

    /**
     * Onboard signup
     * 
     * @return void
     */
    public function onboard()
    {
        $this->onboarded_at = now();
        $this->saveQuietly();
    }

    /**
     * Check signup is onboarded
     * 
     * @return boolean
     */
    public function onboarded()
    {
        return !empty($this->onboarded_at);
    }
}
