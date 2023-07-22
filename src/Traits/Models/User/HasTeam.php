<?php

namespace Jiannius\Atom\Traits\Models\User;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasTeam
{
    // get teams for user
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(model('team'), 'team_users');
    }

    // scope for in team
    public function scopeInTeam($query, $teams): void
    {
        $id = collect($teams)->map(function($team) {
            if (is_numeric($team)) return $team;
            else if (is_string($team)) return optional(model('team')->firstWhere('name', $team))->id;
            else return optional($team)->id;
        })->toArray();

        $query->whereHas('teams', fn($q) => $q->wherePivotIn('team_id', $id));
    }

    // check user is in team
    public function inTeam($names, $strict = false): bool
    {
        $teams = collect($names)->mapWithKeys(fn($name) => [
            $name => !empty($this->teams->firstWhere('name', $name))
        ]);

        if ($strict) return !$teams->some(fn($val) => !$val);
        else return $teams->some(fn($val) => $val);
    }
}