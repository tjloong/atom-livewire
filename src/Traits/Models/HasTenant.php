<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasTenant
{
    public $usesHasTenant = true;

    // boot
    protected static function bootHasTenant(): void
    {
        static::saving(function ($model) {
            $model->tenant_id = $model->tenant_id ?? tenant('id');
        });
    }

    // initialize the trait
    protected function initializeHasTenant(): void
    {
        $this->casts['tenant_id'] = 'integer';
    }

    // get tenant for model
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(model('tenant'));
    }

    // scope for tenant
    public function scopeForTenant($query, $tenant = null)
    {
        $table = $this->getTable();
        $tenant = $tenant ?? tenant();

        if ($tenant && has_column($table, 'tenant_id')) {
            $query->where($table.'.tenant_id', $tenant->id);
        }

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