<?php

namespace App\Http\Livewire\App\Ability;

use App\Models\Ability;
use Livewire\Component;
use Illuminate\Support\Str;

class Listing extends Component
{
    public $role;
    public $user;
    public $groups;

    /**
     * Mount
     * 
     * @return void
     */
    public function mount()
    {
        $this->groups = $this->getGroups();
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.ability.listing');
    }

    /**
     * Save abilities
     * 
     * @param array $inputs
     * @return void
     */
    public function save($ability)
    {
        if ($this->role) {
            if ($ability['enabled']) $this->role->abilities()->detach($ability['id']);
            else $this->role->abilities()->attach($ability['id']);
        }
        else if ($this->user) {
            $this->user->abilities()->detach($ability['id']);
            $this->user->abilities()->attach([
                $ability['id'] => [
                    'access' => $ability['enabled'] ? 'forbid' : 'grant',
                ],
            ]);
        }

        $this->groups = $this->getGroups();

        $this->dispatchBrowserEvent('toast', ['message' => 'Permissions Updated', 'type' => 'success']);
    }

    /**
     * Reset the overwrote abilities
     * 
     * @return void
     */
    public function resetOverwrote($id)
    {
        $this->user->abilities()->detach($id);
        $this->groups = $this->getGroups();
    }

    /**
     * Get groups
     * 
     * @return collection
     */
    private function getGroups()
    {
        $names = [
            'user' => 'Settings → Users',
            'role' => 'Settings → Roles',
            'team' => 'Settings → Teams',
        ];

        return $this->getAbilities()->groupBy('module')
            ->map(fn($value, $key) => ['name' => $key, 'abilities' => $value])
            ->values()
            ->map(function($value) use ($names) {
                $value['name'] = $names[$value['name']] ?? Str::title($value['name']);
                $value['is_overwrote'] = $value['abilities']->where('overwrote', true)->count() > 0;

                return $value;
            })
            ->toArray();
    }

    /**
     * Get abilities
     * 
     * @return Ability
     */
    private function getAbilities()
    {
        return Ability::all()->map(function($ability) {
            $ability->enabled = $this->isEnabled($ability);
            $ability->overwrote = $this->isOverwrote($ability);
            return $ability;
        });
    }

    /**
     * Check is ability enabled
     * 
     * @return boolean
     */
    private function isEnabled($ability)
    {
        if ($this->role) {
            return $this->role->abilities()->where('abilities.id', $ability->id)->count() > 0
                || ($this->role->slug === 'administrator' && $this->role->is_system);
        }
        else if ($this->user) {
            return $this->user->can($ability->module . '.' . $ability->name);
        }

        return false;
    }

    /**
     * Check is ability overwrote
     * 
     * @return boolean
     */
    private function isOverwrote($ability)
    {
        if ($this->user) {
            return $this->user->abilities()->where('abilities.id', $ability->id)->count() > 0;
        }

        return false;
    }
}