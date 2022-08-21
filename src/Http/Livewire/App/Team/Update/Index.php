<?php

namespace Jiannius\Atom\Http\Livewire\App\Team\Update;

use Livewire\Component;

class Index extends Component
{
    public $tab = 'info';
    public $team;

    protected $queryString = ['tab'];

    /**
     * Mount
     */
    public function mount($team)
    {
        $this->team = model('team')
            ->belongsToAccount()
            ->findOrFail($team);

        breadcrumbs()->push($this->team->name);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->team->delete();
        
        session()->flash('flash', 'Team Deleted');

        return redirect()->route('app.team.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.team.update.index');
    }
}