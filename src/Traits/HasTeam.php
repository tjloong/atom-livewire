<?php

namespace Jiannius\Atom\Traits;

use Jiannius\Atom\Models\Team;

trait HasTeam
{
    public $enabledHasTeamTrait = true;
    
    /**
     * Get teams for user
     */
    public function teams()
    {
        if (!enabled_feature('teams')) return;

        return $this->belongsToMany(Team::class, 'teams_users');
    }

    /**
     * Scope for team id
     * 
     * @param Builder $query
     * @param integer $id
     * @return Builder
     */
    public function scopeTeamId($query, $id)
    {
        if (!enabled_feature('teams')) return $query;

        return $query->whereHas('teams', function($q) use ($id) {
            $q->whereIn('teams.id', (array)$id);
        });
    }

    /**
     * Join team
     * 
     * @return void
     */
    public function joinTeam($id)
    {
        if (!enabled_feature('teams')) return;

        $this->teams()->sync(
            $this->teams->pluck('id')->push($id)->toArray()
        );
    }

    /**
     * Leave team
     * 
     * @return void
     */
    public function leaveTeam($id)
    {
        if (!enabled_feature('teams')) return;

        $this->teams()->sync(
            $this->teams()->where('teams.id', '<>', $id)->get()
                ->pluck('id')
                ->toArray()
        );
    }
}