<?php

namespace Jiannius\Atom\Http\Livewire\App\Team\Update;

use Jiannius\Atom\Traits\WithPopupNotify;
use Livewire\Component;

class Index extends Component
{
    use WithPopupNotify;

    public $tab = 'info';
    public $team;

    protected $queryString = ['tab'];

    /**
     * Mount
     */
    public function mount($team)
    {
        $this->team = model('team')
            ->when(model('team')->enabledBelongsToAccountTrait, fn($q) => $q->belongsToAccount())
            ->findOrFail($team);

        breadcrumbs()->push($this->team->name);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->team->delete();

        return redirect()->route('app.settings', ['teams'])->with('info', 'Team Deleted.');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.team.update.index');
    }
}