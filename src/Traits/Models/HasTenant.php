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
            $model->tenant_id = $model->tenant_id ?? tenant('id');
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
    public function scopeBelongsToTenant($query, $tenant = null)
    {
        $table = $this->getTable();
        $id = tier('root')
            ? (is_numeric($tenant) ? $tenant : optional($tenant)->id)
            : tenant('id');

        if (!Schema::hasColumn($table, 'tenant_id')) return $query;

        if ($id) return $query->where($table.'.tenant_id', $id);
        else if (tier('root')) return $query;
        else return $query->whereNull($table.'.id');
    }
}