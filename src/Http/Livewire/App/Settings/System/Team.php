<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\System;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;
use Livewire\WithPagination;

class Team extends Component
{
    use WithPagination;
    use WithTable;

    public $sortBy = 'name';
    public $sortOrder = 'asc';
    public $filters = ['search' => null];

    protected $listeners = ['refresh' => '$refresh'];

    /**
     * Get teams property
     */
    public function getTeamsProperty()
    {
        return model('team')
            ->belongsToTenant()
            ->withCount('users')
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->maxRows);
    }

    /**
     * Open
     */
    public function open($action, $data = null)
    {
        $component = [
            'create' => lw('app.settings.system.team-form-modal'),
            'edit' => lw('app.settings.system.team-form-modal'),
            'user' => lw('app.settings.system.user-drawer'),
        ][$action];

        $this->emitTo($component, 'open', $data);
    }

    /**
     * Delete
     */
    public function delete($id)
    {
        $team = model('team')->findOrFail($id);
        $team->delete();

        $this->emitSelf('refresh');
        $this->popup('Team Deleted');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.settings.system.team');
    }
}