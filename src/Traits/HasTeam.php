<?php

namespace Jiannius\Atom\Traits;

use App\Models\Team;

trait HasTeam
{
    public $enabledHasTeamTrait = true;
    
    /**
     * Get teams for user
     */
    public function teams()
    {
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
        $this->teams()->sync(
            $this->teams()->where('teams.id', '<>', $id)->get()
                ->pluck('id')
                ->toArray()
        );
    }
}