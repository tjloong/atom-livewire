<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Support\Facades\Schema;

trait HasTenant
{
    public $usesHasTenant = true;

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
     * Scope for tenant
     */
    public function scopeForTenant($query, $tenant = null)
    {
        $table = $this->getTable();
        $tenant = $tenant ?? tenant();

        if (!Schema::hasColumn($table, 'tenant_id')) $query;

        if ($tenant && Schema::hasColumn($table, 'tenant_id')) {
            $query->where($table.'.tenant_id', $tenant->id);

            // if (user()) {
            //     $user = $tenant->users->firstWhere('id', user('id'));
            //     $visibility = $user->pivot->visibility ?? 'restrict';

            //     if ($visibility === 'restrict') {
            //         $query->where(fn($q) => $q
            //             ->where('created_by', $user->id)
            //             ->when(Schema::hasColumn($table, 'owned_by'), fn($q) => $q->orWhere('owned_by', $user->id))
            //         );
            //     }
            //     elseif ($visibility === 'team' && enabled_module('teams')) {
            //         $teams = $user->teams->where('tenant_id', $tenant->id)->pluck('id')->toArray();
            //         $query->where(fn($q) => $q
            //             ->whereHas('createdBy', fn($q) => $q->inTeam($teams))
            //             ->when(Schema::hasColumn($table, 'owned_by'), fn($q) => $q->orWhereHas('ownedBy', fn($q) => $q->inTeam($teams)))
            //         );
            //     }
            // }
        }
    }
}