<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Support\Facades\Schema;

trait HasTenant
{
    public $enabledHasTenantTrait = true;

    /**
     * Boot the trait
     *
     * @return void
     */
    protected static function bootHasTenant()
    {
        static::saving(function ($model) {
            $model->tenant_id = $model->tenant_id ?? auth()->user()->tenant_id;
        });
    }

    /**
     * Initialize the trait
     * 
     * @return void
     */
    protected function initializeHasTenant()
    {
        $this->casts['tenant_id'] = 'integer';
    }

    /**
     * Get tenant for model
     */
    public function tenant()
    {
        return $this->belongsTo(model('tenant'));
    }

    /**
     * Scope for belongsToTenant
     */
    public function scopeBelongsToTenant($query, $tenantId = null)
    {
        $table = $this->getTable();

        if (!Schema::hasColumn($table, 'tenant_id')) return $query;

        if (auth()->user()->is_root) {
            if ($tenantId) return $query->where($table.'.tenant_id', $tenantId);
            else return $query;
        }
        elseif ($tenantId = auth()->user()->tenant_id) return $query->where($table.'.tenant_id', $tenantId);
        else return $query->whereNull($table.'.id');
    }
}