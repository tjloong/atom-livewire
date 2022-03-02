<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\HasOwner;
use Illuminate\Database\Eloquent\Model;

class PlanPrice extends Model
{
    use HasOwner;
    
    protected $table = 'plans_prices';

    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
        'is_default' => 'boolean',
        'plan_id' => 'integer',
    ];

    /**
     * Get plan for plan price
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get tenants for plan price
     */
    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    /**
     * Get users for plan price
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
