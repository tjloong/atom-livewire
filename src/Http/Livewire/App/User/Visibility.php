<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Livewire\Component;

class Visibility extends Component
{
    public $user;

    /**
     * Get visibilities property
     */
    public function getVisibilitiesProperty()
    {
        return array_filter([
            ['value' => 'restrict', 'label' => 'Restrict', 'description' => 'Can view data created by ownself.'],
            enabled_module('teams') ? [
                'value' => 'team', 'label' => 'Team', 'description' => 'Can view data created by ownself and team members.'
            ] : null,
            ['value' => 'global', 'label' => 'Global', 'description' => 'Can view all data.'],    
        ]);
    }

    /**
     * Get visibility property
     */
    public function getVisibilityProperty()
    {
        if (tenant()) {
            if ($user = tenant()->users->firstWhere('id', $this->user->id)) {
                return $user->pivot->visibility;
            }
        }
        else return $this->user->visibility;
    }

    /**
     * Toggle
     */
    public function toggle($visibility)
    {
        if (tenant()) {
            if ($user = tenant()->users->firstWhere('id', $this->user->id)) {
                tenant()->users()->updateExistingPivot($user->id, compact('visibility'));
                tenant()->touch();
            }
        }
        else $this->user->fill(compact('visibility'))->save();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.user.visibility');
    }
}