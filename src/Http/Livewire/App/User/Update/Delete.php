<?php

namespace Jiannius\Atom\Http\Livewire\App\User\Update;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Delete extends Component
{
    use WithPopupNotify;

    public $user;

    /**
     * Restore
     */
    public function restore()
    {
        $this->user->restore();
        
        return breadcrumbs()->back();
    }

    /**
     * Delete
     */
    public function delete()
    {
        if ($this->user->trashed()) $this->user->forceDelete();
        else $this->user->delete();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.user.update.delete');
    }
}