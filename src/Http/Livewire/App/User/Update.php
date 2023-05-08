<?php

namespace Jiannius\Atom\Http\Livewire\App\User;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithPopupNotify;
    
    public $user;

    /**
     * Mount
     */
    public function mount($userId): void
    {
        $this->user = model('user')->readable()->withTrashed()->findOrFail($userId);

        breadcrumbs()->push($this->user->name);
    }

    /**
     * Remove
     */
    public function remove(): mixed
    {
        if (!enabled_module('tenants')) return null;
        if (!tenant()) return null;

        if ($this->user->id === user('id')) {
            return $this->popup([
                'title' => 'Unable To Remove User',
                'message' => 'You cannot remove youself.',
            ], 'alert', 'error');
        }

        tenant()->users()->detach($this->user->id);

        if (enabled_module('invitations')) {
            tenant()->invitations()->where('email', $this->user->email)->delete();
        }

        return breadcrumbs()->back();
    }

    /**
     * Resend activation email
     */
    public function resend(): void
    {
        $this->user->sendActivation();

        $this->popup('Activation email sent.', 'alert');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.user.update');
    }
}