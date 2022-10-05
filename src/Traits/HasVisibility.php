<?php

namespace Jiannius\Atom\Traits;

use Illuminate\Support\Facades\Schema;

trait HasVisibility
{
    public $enabledHasVisibilityTrait = true;

    /**
     * Scope for visibility
     */
    public function scopeVisible($query, $user = null)
    {
        $user = $user ?? auth()->user();

        if (!config('atom.app.user.data_visibility')) return $query;
        
        if ($user->isAccountType('root') || $user->isRole('admin') || $user->visibility === 'global') {
            if ($this->enabledBelongsToAccountTrait) return $query->where('account_id', $user->account_id);
            else return $query;
        }

        if ($user->visibility === 'restrict') {
            return $query->where(fn($q) => $q
                ->where('created_by', $user->id)
                ->when(
                    Schema::hasColumn($this->getTable(), 'owned_by'), 
                    fn($q) => $q->orWhere('owned_by', $user->id)
                )
            );
        }
        else if ($user->visibility === 'team') {
            if (!enabled_module('teams')) return $query;
            
            $teamId = $user->teams->pluck('id')->toArray();

            return $query->where(fn($q) => $q
                ->whereHas('createdBy', fn($q) => $q->inTeam($teamId))
                ->when(
                    Schema::hasColumn($this->getTable(), 'owned_by'), 
                    fn($q) => $q->orWhereHas('ownedBy', fn($q) => $q->inTeam($teamId))
                )
            );
        }
    }
}