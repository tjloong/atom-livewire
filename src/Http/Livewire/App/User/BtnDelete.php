<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class BtnDelete extends Component
{
    use WithPopupNotify;

    public $user;

    /**
     * Restore
     */
    public function restore(): mixed
    {
        $this->user->restore();
        
        return breadcrumbs()->back();
    }

    /**
     * Delete
     */
    public function delete(): mixed
    {
        if ($this->user->id === user('id')) {
            return $this->popup([
                'title' => 'Unable To Delete User',
                'message' => 'You cannot delete yourself.',
            ], 'alert', 'error');
        }

        if ($this->user->trashed()) $this->user->forceDelete();
        else $this->user->delete();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.user.btn-delete');
    }
}